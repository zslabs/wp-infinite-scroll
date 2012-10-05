<?php

/**
 * Register Settings
 *
*/


/**
 * Register Settings
 *
 * Registers the required settings.
 *
*/

function wpis_register_settings() {

	// setup some default option sets
	$pages = get_pages();
	$pages_options = array();
	array_unshift($pages_options, ''); // blank option
	if($pages) {
		foreach ( $pages as $page ) {
		  	$pages_options[$page->ID] = $page->post_title;
		}
	}
	// get list of post types
	$args = array(
		'public'   => true
	);
	$output = 'names'; // names or objects, note names is the default
	$operator = 'and'; // 'and' or 'or'
	$post_types = get_post_types($args,$output,$operator);

	/* white list our settings, each in their respective section
	   filters can be used to add more options to each section */
	$wpis_settings = array(
		'general' => apply_filters('wpis_settings_general',
			array(
				array(
					'id' => 'img',
					'name' => __('Load image', 'wpis'),
					'desc' => __('Normally a gif spinner. Clear to use default', 'wpis'),
					'type' => 'upload',
					'std' => WPIS_BASE_URL . 'img/ajax-loader.gif'
				),
				array(
					'id' => 'msgText',
					'name' => __('Message Text', 'wpis'),
					'desc' => __('Message shown before more posts are loaded', 'wpis'),
					'type' => 'text'
				),
				array(
					'id' => 'finishedMsg',
					'name' => __('Finished Message', 'wpis'),
					'desc' => __('Message shown when there are no other posts to be loaded', 'wpis'),
					'type' => 'text'
				),
				array(
					'id' => 'navSelector',
					'name' => __('Nav Selector', 'wpis'),
					'desc' => __('CSS selector that relates to the paged navigation (it will be hidden)', 'wpis'),
					'type' => 'text'
				),
				array(
					'id' => 'nextSelector',
					'name' => __('Next Selector', 'wpis'),
					'desc' => __('CSS selector that relates to the \'next posts\' link', 'wpis'),
					'type' => 'text'
				),
				array(
					'id' => 'itemSelector',
					'name' => __('Item Selector', 'wpis'),
					'desc' => __('CSS selector that relates to an individual item within the search results', 'wpis'),
					'type' => 'text'
				),
				array(
					'id' => 'contentSelector',
					'name' => __('Content Selector', 'wpis'),
					'desc' => __('CSS selector that relates to the container around the search results', 'wpis'),
					'type' => 'text'
				)
			)
		)
	);

	if( false == get_option( 'wpis_settings_general' ) ) {
        add_option( 'wpis_settings_general' );
   }


	add_settings_section(
		'wpis_settings_general',
		__('General Settings', 'wpis'),
		'wpis_settings_general_description_callback',
		'wpis_settings_general'
	);

	foreach($wpis_settings['general'] as $option) {
		add_settings_field(
			'wpis_settings_general[' . $option['id'] . ']',
			$option['name'],
			'wpis_' . $option['type'] . '_callback',
			'wpis_settings_general',
			'wpis_settings_general',
			array(
				'id' => $option['id'],
				'desc' => isset($option['desc']) ? $option['desc'] : '',
				'name' => $option['name'],
				'section' => 'general',
				'size' => isset($option['size']) ? $option['size'] : null,
				'options' => isset($option['options']) ? $option['options'] : '',
				'inputclass' => isset($option['inputclass']) ? $option['inputclass'] : '',
				'std' => isset($option['std']) ? $option['std'] : ''
	    	)
		);
	}

	// creates our settings in the options table
	register_setting('wpis_settings_general', 'wpis_settings_general', 'wpis_settings_sanitize');
}
add_action('admin_init', 'wpis_register_settings');


/**
 * Settings General Description Callback
 *
 * Renders the general section description.
 *
*/

function wpis_settings_general_description_callback() {
	//echo __('Configure the settings below', 'wpis');
}


/**
 * Header Callback
 *
 * Renders the header.
 *
*/

function wpis_header_callback($args) {
    echo '';
}


/**
 * Checkbox Callback
 *
 * Renders checkboxes.
 *
*/

function wpis_checkbox_callback($args) {

	global $wpis_options;

	$checked = !empty($wpis_options[$args['id']]) ? checked(1, $wpis_options[$args['id']], false) : '';
	$html = '<input type="hidden" id="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" name="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" value="0" />';
    $html .= '<input type="checkbox" id="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" name="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" value="1" ' . $checked . '/>';
    $html .= '<label for="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;

}


/**
 * Multicheck Callback
 *
 * Renders multiple checkboxes.
 *
*/

function wpis_multicheck_callback($args) {

	global $wpis_options;

	foreach($args['options'] as $key => $option) :
		if(!empty($wpis_options[$args['id']][$key])) { $enabled = $option; } else { $enabled = NULL; }
		echo '<input name="wpis_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']"" id="wpis_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="hidden" value="0" />';
		echo '<input name="wpis_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']"" id="wpis_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']" type="checkbox" value="' . $option . '" ' . checked($option, $enabled, false) . '/>&nbsp;';
		echo '<label for="wpis_settings_' . $args['section'] . '[' . $args['id'] . '][' . $key . ']">' . $option . '</label><br/>';
	endforeach;
	echo '<p class="description">' . $args['desc'] . '</p>';

}


/**
 * Text Callback
 *
 * Renders text fields.
 *
*/

function wpis_text_callback($args) {

	global $wpis_options;

	if(isset($wpis_options[$args['id']])) { $value = $wpis_options[$args['id']]; } else { $value = isset($args['std']) ? $args['std'] : ''; }
	$size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
    $html = '<input type="text" class="'. $args['inputclass'] . ' ' .  $args['size'] . '-text" id="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" name="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . $value . '"/>';
    $html .= '<label for="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;

}


/**
 * Select Callback
 *
 * Renders select fields.
 *
*/

function wpis_select_callback($args) {

	global $wpis_options;

    $html = '<select id="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" name="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']"/>';
    foreach($args['options'] as $option => $name) {
		$selected = isset($wpis_options[$args['id']]) ? selected($option, $wpis_options[$args['id']], false) : '';
		$html .= '<option value="' . $option . '" ' . $selected . '>' . $name . '</option>';
	}
	$html .= '</select>';
	$html .= '<label for="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;

}


/**
 * Rich Editor Callback
 *
 * Renders rich editor fields.
 *
*/

function wpis_rich_editor_callback($args) {

	global $wpis_options, $wp_version;

	if(isset($wpis_options[$args['id']])) { $value = $wpis_options[$args['id']]; } else { $value = isset($args['std']) ? $args['std'] : ''; }
    if($wp_version >= 3.3 && function_exists('wp_editor')) {
		$html = wp_editor($value, 'wpis_settings_' . $args['section'] . '[' . $args['id'] . ']', array('textarea_name' => 'wpis_settings_' . $args['section'] . '[' . $args['id'] . ']'));
    } else {
		$html = '<textarea class="large-text" rows="10" id="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" name="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']">' . $value . '</textarea>';
	}
	$html .= '<br/><label for="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';

    echo $html;

}


/**
 * Upload Callback
 *
 * Renders upload fields.
 *
*/

function wpis_upload_callback($args) {

	global $wpis_options;

	if(isset($wpis_options[$args['id']])) { $value = $wpis_options[$args['id']]; } else { $value = isset($args['std']) ? $args['std'] : ''; }
	$size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
    $html = '<input type="text" class="' . $args['size'] . '-text wpis_upload_field" id="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" name="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '"/>';
    $html .= '<span>&nbsp;<input type="button" class="wpis_upload_image_button button-secondary" value="' . __('Upload File', 'wpis') . '"/></span>';
    $html .= '<label for="wpis_settings_' . $args['section'] . '[' . $args['id'] . ']"> '  . $args['desc'] . '</label>';
    $html .= '<div class="preview-image" style="padding-top:10px;">';
    $html .= '<img src="';
    $html .= $value ? $value : $args['std'];
    $html .= '">';
    $html .= '</div>';

    echo $html;

}


/**
 * Hook Callback
 *
 * Adds a do_action() hook in place of the field
 *
*/

function wpis_hook_callback($args) {

	do_action('wpis_' . $args['id']);

}

/**
 * Settings Sanitization
 *
 * Adds a settings error (for the updated message)
 * At some point this will validate input
 *
*/

function wpis_settings_sanitize( $input ) {
	add_settings_error('wpis-notices', '', __('Settings Updated', 'wpis'), 'updated');
	return $input;
}


/**
 * Get Settings
 *
 * Retrieves all plugin settings and returns them
 * as a combined array.
 *
*/

function wpis_get_settings() {
	$general_settings = is_array(get_option('wpis_settings_general')) ? get_option('wpis_settings_general') : array();

	return array_merge($general_settings);
}