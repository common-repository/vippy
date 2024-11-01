<?php

// Add Triggers
function vippy_add_trigger($vars) {
    $vars[] = 'vippy-controller';
    $vars[] = 'vippy-jquery';
    return $vars;
}
add_filter('query_vars','vippy_add_trigger');

// Controllers
function vippy_trigger_check() {
	if ( intval(get_query_var('vippy-controller')) == 1 ) :
		exit;
	endif;
	
	if ( intval(get_query_var('vippy-jquery')) == 1 ) : ?>
		jQuery(function() {
			return false;
		});
	<?php exit;
	endif;
}
add_action('template_redirect', 'vippy_trigger_check');