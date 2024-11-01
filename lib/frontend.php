<?php

// Frontend Styles
function vippy_styles() {
	wp_register_style('fancybox', VIPPYURL . 'tools/fancybox/jquery.fancybox-1.3.4.css');
	wp_enqueue_style('fancybox');
}
add_action('wp_print_styles', 'vippy_styles');

// Frontend Scripts
function vippy_scripts() {
	wp_register_script('fancybox', VIPPYURL . 'tools/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'));
	wp_register_script('vippy-frontend', VIPPYURL . 'js/frontend.js', array('jquery'));
	wp_enqueue_script('jquery');
	wp_enqueue_script('fancybox');
	wp_enqueue_script('vippy-frontend');
}
add_action('wp_print_scripts', 'vippy_scripts');