<?php

/*
Plugin Name: WP Infinite Scroll
Plugin URI: http://yellowhousedesign.com
Description: WP Infinite Scroll Buttons
Version: 0.1
Author: Yellow House Design
Author URI: http://yellowhousedesign.com
License: GPL2
*/

// Global Variables
$wpis_prefix = 'wpis_';
$wpis_name = 'WP Infinite Scroll';

// plugin folder url
if(!defined('WPIS_BASE_URL')) {
	define('WPIS_BASE_URL', plugin_dir_url(__FILE__));
}
// plugin folder path
if(!defined('WPIS_BASE_DIR')) {
	define('WPIS_BASE_DIR', plugin_dir_path( __FILE__ ));
}
// plugin root file
if(!defined('WPIS_PLUGIN_FILE')) {
	define('WPIS_PLUGIN_FILE', __FILE__);
}

// Includes
include_once(WPIS_BASE_DIR . 'includes/register-settings.php'); // Register

// Retrieve settings from the options page
global $wpis_options;
$wpis_options = wpis_get_settings();

include_once(WPIS_BASE_DIR . 'includes/install.php'); // Sets default plugin settings on activation
include_once(WPIS_BASE_DIR . 'includes/plugin-settings.php'); // Plugin options page HTML/Save functions
include_once(WPIS_BASE_DIR . 'includes/admin-notices.php'); // Notices shown throughout admin
include_once(WPIS_BASE_DIR . 'includes/admin-docs.php'); // Documentation tab in plugin settings
include_once(WPIS_BASE_DIR . 'includes/scripts.php'); // Conrols JS/CSS