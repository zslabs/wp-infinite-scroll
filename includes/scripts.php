<?php

// Script conrol
function wpis_load_scripts() {
	global $wpis_options;
	if(!is_singular()) {
		wp_enqueue_style('wpis-style',  WPIS_BASE_URL . 'css/style.css');
		wp_enqueue_script('jquery-infinite-scroll', WPIS_BASE_URL . 'js/jquery.infinitescroll.min.js', array('jquery'), '', true);
		wp_enqueue_script('wpis-script', WPIS_BASE_URL . 'js/script.js', array('jquery','jquery-infinite-scroll'), '', true);

		$wpis_vars = array(
			'img'             => $wpis_options['img'] ? $wpis_options['img'] : WPIS_BASE_URL . 'img/ajax-loader.gif',
			'msgText'         => $wpis_options['msgText'],
			'finishedMsg'     => $wpis_options['finishedMsg'],
			'nextSelector'    => $wpis_options['nextSelector'],
			'navSelector'     => $wpis_options['navSelector'],
			'itemSelector'    => $wpis_options['itemSelector'],
			'contentSelector' => $wpis_options['contentSelector']
		);
		wp_localize_script('wpis-script', 'wpis_vars', $wpis_vars);
	}
	
}
add_action('wp_enqueue_scripts','wpis_load_scripts');