<?php
/**
 * This file defines return functions to be used as shortcodes
 * in the site footer.
 * 
 * @example <code>[footer_something]</code>
 * @example <code>[footer_something before="<b>" after="</b>" foo="bar"]</code>
 */

/**
 * This function produces the "Return to Top" link
 * 
 */
add_shortcode('footer_backtotop', 'hasloo_footer_backtotop_shortcode');
function hasloo_footer_backtotop_shortcode($atts) {
	
	$defaults = array( 
		'text' => __('Return to top of page', 'hasloo'),
		'href' => '#wrap',
		'nofollow' => true,
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$nofollow = $atts['nofollow'] ? 'rel="nofollow"' : '';
	
	$output = sprintf( '%s<a href="%s" %s>%s</a>%s', $atts['before'], esc_url( $atts['href'] ), $nofollow, $atts['text'], $atts['after'] );
	
	return apply_filters('hasloo_footer_backtotop_shortcode', $output, $atts);
	
}

add_shortcode('footer_copyright', 'hasloo_footer_copyright_shortcode');
function hasloo_footer_copyright_shortcode($atts) {
	
	$defaults = array( 
		'copyright' => '&#xa9;',
		'first' => '',
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$output = $atts['before'] . $atts['copyright'] . ' ';
	if ( '' != $atts['first'] && date('Y') != $atts['first'])
		$output .= $atts['first'] . '&#x2013;';
	$output .= date('Y') . $atts['after'];
	
	return apply_filters('hasloo_footer_copyright_shortcode', $output, $atts);
	
}

add_shortcode('footer_childtheme_link', 'hasloo_footer_childtheme_link_shortcode');
function hasloo_footer_childtheme_link_shortcode($atts) {
	
	$defaults = array( 
		'before' => '&middot; ',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	if ( (CHILD_DIR == PARENT_DIR) || !defined('CHILD_THEME_NAME') || !defined('CHILD_THEME_URL') )
		return;
	
	$output = sprintf( '%s<a href="%s" title="%s">%s</a>%s', $atts['before'], esc_url( CHILD_THEME_URL ), esc_attr( CHILD_THEME_NAME ), esc_html( CHILD_THEME_NAME ), $atts['after'] );
	
	return apply_filters('hasloo_footer_childtheme_link_shortcode', $output, $atts);
	
}

add_shortcode('footer_hasloo_link', 'hasloo_footer_hasloo_link_shortcode');
function hasloo_footer_hasloo_link_shortcode($atts) {
	
	$defaults = array( 
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$output = $atts['before'] . '<a href="http://www.hasloo.com" title="Hasloo Framework">HASLOO Framework</a>' . $atts['after'];
	
	return apply_filters('hasloo_footer_hasloo_link_shortcode', $output, $atts);
	
}

add_shortcode('footer_studiopress_link', 'hasloo_footer_studiopress_link_shortcode');
function hasloo_footer_studiopress_link_shortcode($atts) {
	
	$defaults = array( 
		'before' => __('by ', 'hasloo'),
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$output = $atts['before'] . '<a href="http://www.hasloo.com/">HASLOO.COM</a>' . $atts['after'];
	
	return apply_filters('hasloo_footer_studiopress_link_shortcode', $output, $atts);
	
}

add_shortcode('footer_wordpress_link', 'hasloo_footer_wordpress_link_shortcode');
function hasloo_footer_wordpress_link_shortcode($atts) {
	
	$defaults = array( 
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	$output = sprintf( '%s<a href="%s" title="%s">%s</a>%s', $atts['before'], 'http://wordpress.org/', 'WordPress', 'WordPress', $atts['after'] );
	
	return apply_filters('hasloo_footer_wordpress_link_shortcode', $output, $atts);
	
}

add_shortcode('footer_loginout', 'hasloo_footer_loginout_shortcode');
function hasloo_footer_loginout_shortcode($atts) {
	
	$defaults = array(
		'redirect' => '',
		'before' => '',
		'after' => ''
	);
	$atts = shortcode_atts( $defaults, $atts );
	
	if ( ! is_user_logged_in() )
		$link = '<a href="' . esc_url( wp_login_url($atts['redirect']) ) . '">' . __('Log in', 'hasloo') . '</a>';
	else
		$link = '<a href="' . esc_url( wp_logout_url($atts['redirect']) ) . '">' . __('Log out', 'hasloo') . '</a>';
	
	
	$output = $atts['before'] . apply_filters('loginout', $link) . $atts['after'];
	
	return apply_filters('hasloo_footer_loginout_shortcode', $output, $atts);
	
}