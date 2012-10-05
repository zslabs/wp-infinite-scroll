<?php

function wpis_docs() {
	ob_start();

	echo '<h3>'.__('Documentation','wpis').'</h3>'; ?>

	<?php

	do_action('wpis_docs');

	echo ob_get_clean();
}