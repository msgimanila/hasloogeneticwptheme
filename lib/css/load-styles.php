<?php
/**
 * @todo Document this file
 *
 **/

add_action('get_header', 'hasloo_load_styles');
function hasloo_load_styles() {

}

add_action('admin_init', 'hasloo_load_admin_styles');
function hasloo_load_admin_styles() {
	wp_enqueue_style('hasloo_admin_css', GENESIS_CSS_URL.'/admin.css');
}