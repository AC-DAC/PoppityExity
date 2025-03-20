<?php
// If uninstall.php is not called by WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Delete the plugin option from the database
delete_option('poppity_exity_content');