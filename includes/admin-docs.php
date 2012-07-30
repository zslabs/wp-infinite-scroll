<?php

function wpis_docs() {
	ob_start();

	echo '<h3>'.__('Documentation','wpis').'</h3>'; ?>

	<?php

	echo ob_get_clean();
}