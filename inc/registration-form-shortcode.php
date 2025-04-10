<?php
function crp_registration_form_shortcode() {
    ob_start();

    // Show error or success messages
    if (!empty($_GET['crp_error'])) {
        echo '<div class="error" style="color:red;">' . esc_html($_GET['crp_error']) . '</div>';
    }

    if (!empty($_GET['crp_success'])) {
        echo '<div class="success" style="color:green;">' . esc_html($_GET['crp_success']) . '</div>';
    }
    ?>
    <form method="post" enctype="multipart/form-data">
        <p><label>Name<br><input type="text" name="crp_name" required></label></p>
        <p><label>Email<br><input type="email" name="crp_email" required></label></p>
        <p><label>Degree<br><input type="text" name="crp_degree" required></label></p>
        <p><label>Passing Year<br><input type="text" name="crp_year" required></label></p>
        <p><label>Percentage<br><input type="text" name="crp_percentage" required></label></p>
        <p><label>Upload PDF<br><input type="file" name="crp_pdf" required></label></p>
        <p><?php wp_nonce_field('crp_form_action', 'crp_form_nonce'); ?></p>
        <p><input type="submit" name="crp_submit" value="Submit"></p>
    </form>
    <?php

    return ob_get_clean();
}
add_shortcode('custom_registration_form', 'crp_registration_form_shortcode');

