<?php

function wpis_activate() {
	global $wpdb, $wpis_options;
	if( false == get_option( 'wpis_settings_general' ) ) {
		$wpis_settings_general = array(
			'img'             => WPIS_BASE_URL . 'img/ajax-loader.gif',
			'msgText' => __('Loading the next set of posts','wpis'),
			'finishedMsg' => __('All posts loaded','wpis'),
			'nextSelector' => '#nav-below .nav-previous a',
			'navSelector' => '#nav-below',
			'itemSelector' => 'article',
			'contentSelector' => '#content'
		);
		update_option( 'wpis_settings_general', $wpis_settings_general );
	}
}
register_activation_hook( WPIS_PLUGIN_FILE, 'wpis_activate' );