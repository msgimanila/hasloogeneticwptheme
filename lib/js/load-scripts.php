<?php
/**
 * @todo Document this file
 *
 **/

/**
 * This function loads front-end JS files
 *
 */
add_action('get_header', 'hasloo_load_scripts');
function hasloo_load_scripts() {
	if (is_singular() && get_option('thread_comments') && comments_open())
		wp_enqueue_script('comment-reply');
		
	// Load superfish and our common JS (in the footer, and only if necessary)
	if( hasloo_get_option('nav_superfish') || hasloo_get_option('subnav_superfish') || 
		is_active_widget(0,0, 'menu-categories') || is_active_widget(0,0, 'menu-pages') ) {
			
			wp_enqueue_script('superfish', HASLOO_JS_URL.'/menu/superfish.js', array('jquery'), '1.4.8', TRUE);
			wp_enqueue_script('superfish-args', HASLOO_JS_URL.'/menu/superfish.args.js', array('superfish'), PARENT_THEME_VERSION, TRUE);
			
	}
}

/**
 * Hook this function to wp_head() and you'll be able to use many of
 * the new IE8 functionality. Not loaded by default.
 *
 * @link http://ie7-js.googlecode.com/svn/test/index.html
 */
function hasloo_ie8_js() {
	$output = '
<!--[if lt IE 8]>
<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
<![endif]-->
	';
	
	echo "\n".$output."\n";
}

/**
 * This function loads the admin JS files
 *
 */
add_action('admin_init', 'hasloo_load_admin_scripts');
function hasloo_load_admin_scripts() {
	add_thickbox();
	wp_enqueue_script('theme-preview');
	wp_enqueue_script('hasloo_admin_js', HASLOO_JS_URL.'/admin.js');	
}