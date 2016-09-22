<?php
/**
 * undocumented 
 *
 */

/**
 * This function/filter adds custom body class(es) to the
 * body class array. It accepts values from a per-post/page
 * custom field, and only outputs when viewing singular page.
 *
 * @since 1.4
 */
add_filter('body_class', 'hasloo_custom_body_class', 15);
function hasloo_custom_body_class( $classes ) {
	
	$new_class = is_singular() ? hasloo_get_custom_field( '_hasloo_custom_body_class' ) : null;
	
	if ( $new_class ) $classes[] = esc_attr( sanitize_html_class( $new_class ) );
	
	return $classes;
	
}

/**
 * This function loads the stylesheet.
 * If a child theme is active, it loads the child theme's stylesheet,
 * otherwise, it loads the Genesis stylesheet.
 *
 */
add_action('hasloo_meta', 'hasloo_load_stylesheet');
function hasloo_load_stylesheet() {
	
	echo '<link rel="stylesheet" href="'.get_bloginfo('stylesheet_url').'" type="text/css" media="screen" />'."\n";
	
}

/**
 * This function/filter adds new classes to the <body>
 * so that we can use psuedo-variables in our CSS file,
 * which helps us achieve multiple header layouts with minimal code
 *
 * @since 0.2.2
 */
add_filter('body_class', 'hasloo_header_body_classes');
function hasloo_header_body_classes($classes) {
	
	// add header classes to $classes array
	if ( !hasloo_get_option('header_right') )
		$classes[] = 'header-full-width';
		
	if ( hasloo_get_option('blog_title') == 'image' )
		$classes[] = 'header-image';
	
	// return filtered $classes
	return $classes;
	
}

/**
 * This function/filter adds new classes to the <body>
 * so that we can use psuedo-variables in our CSS file,
 * which helps us achieve multiple site layouts with minimal code
 *
 * @since 0.2.2
 */
add_filter('body_class', 'hasloo_layout_body_classes');
function hasloo_layout_body_classes($classes) {
	
	// get the layout
	$site_layout = hasloo_site_layout();
	
	// add new class to $classes array
	if ( $site_layout ) $classes[] = $site_layout;
	
	// return filtered $classes
	return $classes;
}

/**
 * This function outputs the sidebar.php file
 * if specified in theme options or in-post options
 *
 * @since 0.2
 */
add_action('hasloo_after_content', 'hasloo_get_sidebar');
function hasloo_get_sidebar() {
	
	// get the layout
	$site_layout = hasloo_site_layout();

	// don't load sidebar on pages that don't need it
	if ( $site_layout == 'full-width-content' ) return;
	
	// output the primary sidebar
	get_sidebar();
}

/**
 * This function outputs the sidebar_alt.php file
 * if specified in theme options or in-post options
 *
 * @since 0.2
 */
add_action('hasloo_after_content_sidebar_wrap', 'hasloo_get_sidebar_alt');
function hasloo_get_sidebar_alt() {
	
	// get the layout
	$site_layout = hasloo_site_layout();
	
	// don't load sidebar-alt on pages that don't need it
	if ( $site_layout == 'content-sidebar' || 
		$site_layout == 'sidebar-content' || 
		$site_layout == 'full-width-content' ) return;
	
	// output the alternate sidebar
	get_sidebar('alt');

}