<?php


/**
 * Admin Messages
 *
*/

function wpis_admin_messages() {
	settings_errors( 'wpis-notices' );
}
add_action('admin_notices', 'wpis_admin_messages');