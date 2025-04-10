<?php
// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Access the database
global $wpdb;

// Your custom table name
$table_name = $wpdb->prefix . 'custom_user_data';

// Drop the custom table
$wpdb->query("DROP TABLE IF EXISTS {$table_name}");

// Optionally, delete custom user meta (if you want)
$meta_keys = ['degree', 'passing_year', 'percentage', 'file_url'];
foreach ($meta_keys as $key) {
    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM $wpdb->usermeta WHERE meta_key = %s",
            $key
        )
    );
}
