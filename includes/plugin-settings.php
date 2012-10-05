<?php

function wpis_options_page() {

	global $wpis_options;

	$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general'; 

	ob_start(); ?>
	<div class="wrap">
		
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo add_query_arg('tab', 'general', remove_query_arg('settings-updated')); ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'wpis'); ?></a>
			<a href="<?php echo add_query_arg('tab', 'documentation', remove_query_arg('settings-updated')); ?>" class="nav-tab <?php echo $active_tab == 'documentation' ? 'nav-tab-active' : ''; ?>"><?php _e('Documentation', 'wpis'); ?></a>
		</h2>
			
		<div id="tab_container">
						
			<form method="post" action="options.php" id="wpis_options">
	
				<?php
				
				if($active_tab == 'general') {
					settings_fields('wpis_settings_general'); 
					do_settings_sections('wpis_settings_general');
				} elseif($active_tab == 'documentation') {
					wpis_docs();
				}
				
				if($active_tab != 'documentation')
					submit_button(); 
				
				?>
				 
			</form>
		</div><!--end #tab_container-->
		
	</div>
	<?php
	echo ob_get_clean();
}


function wpis_plugin_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('wpis-admin-settings-scripts',  WPIS_BASE_URL . 'js/admin-settings.js', array('jquery'), '', true);
	wp_localize_script('wpis-admin-settings-scripts', 'wpis_vars', array(
        'post_id' 			=> isset($post->ID) ? $post->ID : 0,
        'add_new_file' 	=> __('Add New File', 'wpis'), // thickbox title
        'use_this_file' 	=> __('Use This File','wpis') // "use this file" button
    ));
    wp_enqueue_style('thickbox');
}

// WP Infinite Scroll Options link
function wpis_add_options_link() {
	$wpis_options_page = add_options_page(__('WP Infinite Scroll Options','roots'),__('WP Infinite Scroll','roots'),'manage_options','wpis-options','wpis_options_page');
	add_action( 'admin_print_scripts-' . $wpis_options_page, 'wpis_plugin_admin_scripts' );
}
add_action('admin_menu','wpis_add_options_link');