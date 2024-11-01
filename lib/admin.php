<?php

// Hook into Admin Menu
function vippy_admin_menu() {
    add_menu_page(__('Vippy', VIPPYTEXTDOMAIN), __('Vippy', VIPPYTEXTDOMAIN), 'manage_options', 'vippy-settings', 'vippy_settings_page', VIPPYURL . '/images/vippy.png' );
}
add_action('admin_menu', 'vippy_admin_menu');

// WordPress Settings API
function vippy_register_settings(){
	register_setting('vippy_settings_group', 'vippy_settings', 'vippy_settings_validate');
}
add_action('admin_init', 'vippy_register_settings');

// Vippy Head Script
/*function vippy_admin_head_script() { ?>
	<script type="text/javascript" language="javascript" src="http://cdn2.vippy.co/files/js/vippy.js"></script>
	<link rel="stylesheet" href="http://cdn2.vippy.co/files/css/default.css" type="text/css" media="screen" />
<?php }
add_action('admin_head', 'vippy_admin_head_script');*/

// Styles
function vippy_admin_styles() {
	wp_register_style('vippy', VIPPYURL . 'css/style.css');
	wp_register_style('fancybox', VIPPYURL . 'tools/fancybox/jquery.fancybox-1.3.4.css');
	wp_enqueue_style('vippy');
	wp_enqueue_style('fancybox');
}
add_action('admin_print_styles-post.php', 'vippy_admin_styles');
add_action('admin_print_styles-post-new.php', 'vippy_admin_styles');
add_action('admin_print_styles-media-upload-popup', 'vippy_admin_styles');

// Scripts
function vippy_admin_scripts() {
	wp_register_script('vippy', VIPPYURL . 'js/global.js');
	wp_register_script('fancybox', VIPPYURL . 'tools/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'));
	wp_register_script('vippy-wp', get_bloginfo('url') . '/?vippy-jquery=1', array('jquery'));
	wp_enqueue_script('jquery');
	wp_enqueue_script('vippy');
	wp_enqueue_script('fancybox');
	wp_enqueue_script('vippy-wp');
}
add_action('admin_print_scripts-post.php', 'vippy_admin_scripts');
add_action('admin_print_scripts-post-new.php', 'vippy_admin_scripts');
add_action('admin_print_scripts-media-upload-popup', 'vippy_admin_scripts');

// Settings Page
function vippy_settings_page() { ?>
	<?php vippy_nag(); ?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2><?php _e('Vippy Settings', VIPPYTEXTDOMAIN); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields('vippy_settings_group'); ?>
			<?php $options = get_option('vippy_settings'); ?>
<?php
	/*if(!isset($options['vippy_default_width'])){
		$options['vippy_default_width'] = 640;
	}
	if(!isset($options['vippy_default_height'])){
		$options['vippy_default_height'] = 360;
	}*/
?>			
			<table class="form-table">
				<tr valign="top"><th scope="row"><?php _e('Vippy API Key', VIPPYTEXTDOMAIN); ?></th>
					<td><input name="vippy_settings[vippy_api_key]" type="text" value="<?php echo $options['vippy_api_key']; ?>" style="width: 300px;" /></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Vippy API Secret Key', VIPPYTEXTDOMAIN); ?></th>
					<td><input name="vippy_settings[vippy_secret_key]" type="text" value="<?php echo $options['vippy_secret_key']; ?>" style="width: 300px;" /></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Default video width', VIPPYTEXTDOMAIN); ?></th>
					<td><input name="vippy_settings[vippy_default_width]" type="text" value="<?php echo $options['vippy_default_width']; ?>" style="width: 50px;" /></td>
				</tr>
				<tr valign="top"><th scope="row"><?php _e('Default video height', VIPPYTEXTDOMAIN); ?></th>
					<td><input name="vippy_settings[vippy_default_height]" type="text" value="<?php echo $options['vippy_default_height']; ?>" style="width: 50px;" /></td>
				</tr>								
			</table>
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save') ?>" />
			</p>
		</form>
	</div>
<?php }

// Validation
function vippy_settings_validate($input) {
	return $input;
}

// Hook for Messages
function vippy_nag() { 
	if (vippy_wp_authenticate()) :
		$message = __('Successfully connected to the <a title="Vippy" href="http://vippy.co/" target="_blank">Vippy</a> API', VIPPYTEXTDOMAIN);
	else :
		$message = __('<span style="color: #BC0B0B">Error connecting to the <a title="Vippy" href="http://vippy.co/" target="_blank">Vippy</a> API. Please confirm your credentials are correct.</span>', VIPPYTEXTDOMAIN);
	endif;
	
	echo '<div class="update-nag">'.$message.'</div>';
}

function vippy_media_menu($tabs) {
	$newtab = array('vippy' => __('Vippy', VIPPYTEXTDOMAIN));
	return array_merge($tabs, $newtab);
}
add_filter('media_upload_tabs', 'vippy_media_menu');

function media_vippy_process() {
	media_upload_header();
	do_vippy_wp_upload_page();
}

function vippy_media_menu_handle() {
    return wp_iframe( 'media_vippy_process');
}

add_action('media_upload_vippy', 'vippy_media_menu_handle');