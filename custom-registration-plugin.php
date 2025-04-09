<?php
/*
Plugin Name: Custom Registration Plugin
Plugin URI: https://viveksaini.in/
Description: Adds custom fields to the registration form and stores data in a custom DB table.
Version: 1.1
Author: Vivek Saini
*/

defined('ABSPATH') || exit;
define('CRP_PLUGIN_PATH', plugin_dir_path(__FILE__));

// Include custom fields
require_once CRP_PLUGIN_PATH . 'inc/custom-registration-fields.php';
require_once CRP_PLUGIN_PATH . 'inc/custom-registration-handler.php';
require_once CRP_PLUGIN_PATH . 'admin/admin-page.php';


register_activation_hook(__FILE__, 'custom_registration_plugin_activate');

function custom_registration_plugin_activate() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_user_data'; // example: wp_custom_user_data
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        name varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        degree varchar(255) NOT NULL,
        passing_year varchar(10) NOT NULL,
        percentage varchar(10) NOT NULL,
        file_url varchar(255) DEFAULT '' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
