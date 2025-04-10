<?php
// Exit if accessed directly
defined('ABSPATH') || exit;

// Create a custom menu in the admin dashboard
function crp_add_admin_menu() {
    add_menu_page(
        'Custom User Data',
        'User Submissions',
        'manage_options',
        'crp-custom-page',
        'crp_display_custom_table',
        'dashicons-welcome-widgets-menus',
        26
    );
}
add_action('admin_menu', 'crp_add_admin_menu');

function crp_display_custom_table() {
    global $wpdb;
    $table = $wpdb->prefix . 'custom_user_data';

    // Handle Delete
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
        $wpdb->delete($table, ['id' => intval($_GET['id'])]);
        echo '<div class="notice notice-success is-dismissible"><p>Record deleted successfully!</p></div>';
    }

    // Handle Update
    if (isset($_POST['update_crp_user'])) {
        $id = intval($_POST['record_id']);

        if (wp_verify_nonce($_POST['_wpnonce'], 'crp_update_user_' . $id)) {
            $degree = sanitize_text_field($_POST['degree']);
            $year = sanitize_text_field($_POST['passing_year']);
            $percentage = sanitize_text_field($_POST['percentage']);

            $wpdb->update(
                $table,
                [
                    'degree' => $degree,
                    'passing_year' => $year,
                    'percentage' => $percentage,
                ],
                ['id' => $id]
            );

            echo '<div class="notice notice-success is-dismissible"><p>Record updated successfully!</p></div>';
        }
    }

    // Handle Edit Form
    if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $record = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));

        if ($record) {
            ?>
            <div class="wrap">
                <h2>Edit Record</h2>
                <form method="post">
                    <input type="hidden" name="record_id" value="<?php echo esc_attr($record->id); ?>">
                    <table class="form-table">
                        <tr>
                            <th>Degree</th>
                            <td><input type="text" name="degree" value="<?php echo esc_attr($record->degree); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th>Passing Year</th>
                            <td><input type="text" name="passing_year" value="<?php echo esc_attr($record->passing_year); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th>Percentage</th>
                            <td><input type="text" name="percentage" value="<?php echo esc_attr($record->percentage); ?>" class="regular-text"></td>
                        </tr>
                    </table>
                    <?php wp_nonce_field('crp_update_user_' . $record->id); ?>
                    <p><input type="submit" name="update_crp_user" value="Update" class="button button-primary"></p>
                </form>
            </div>
            <?php
            return; // Don't show the table when editing
        }
    }

    // Display Table
    $results = $wpdb->get_results("SELECT * FROM $table");

    ?>
    <div class="wrap">
        <h1>Custom User Submissions</h1>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Degree</th>
                    <th>Passing Year</th>
                    <th>Percentage</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?php echo esc_html($row->id); ?></td>
                        <td><?php echo esc_html($row->user_id); ?></td>
                        <td><?php echo esc_html($row->name); ?></td>
                        <td><?php echo esc_html($row->email); ?></td>
                        <td><?php echo esc_html($row->degree); ?></td>
                        <td><?php echo esc_html($row->passing_year); ?></td>
                        <td><?php echo esc_html($row->percentage); ?></td>
                        <td>
                            <?php if (!empty($row->file_url)) {
                                echo '<a href="' . esc_url($row->file_url) . '" target="_blank">View File</a>';
                            } ?>
                        </td>
                        <td>
                            <a href="<?php echo admin_url('admin.php?page=crp-custom-page&action=edit&id=' . $row->id); ?>" class="button">Edit</a>
                            <a href="<?php echo admin_url('admin.php?page=crp-custom-page&action=delete&id=' . $row->id); ?>" class="button delete" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}
add_action('edit_user_profile', 'crp_show_uploaded_file_link_admin');

function crp_show_uploaded_file_link_admin($user) {
    $file_url = get_user_meta($user->ID, 'file_url', true);
    
    if ($file_url) {
        echo '<h3>Uploaded File</h3>';
        echo '<table class="form-table"><tr><th>CV / PDF</th><td>';
        echo '<a href="' . esc_url($file_url) . '" target="_blank" class="button button-primary">View Uploaded PDF</a>';
        echo '</td></tr></table>';
    }
}
