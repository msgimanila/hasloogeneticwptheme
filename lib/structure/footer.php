<?php
/**
 * Outputs the structural markup for the footer
 *
 * @since 1.2
 */
add_action('hasloo_footer', 'hasloo_footer_markup_open', 5);
function hasloo_footer_markup_open() {
	
	echo '<div id="footer"><div class="wrap">' . "\n";
	
}
add_action('hasloo_footer', 'hasloo_footer_markup_close', 15);
function hasloo_footer_markup_close() {
	
	echo '</div><!-- end .wrap --></div><!-- end #footer -->' . "\n";
	
}


/**
 * Output the contents of the footer
 * Execute any shortcodes that might be present
 *
 * @since 1.0.1
 */
add_filter('hasloo_footer_output', 'do_shortcode', 20);
add_action('hasloo_footer', 'hasloo_do_footer');
function hasloo_do_footer() {
	
	// Build the filterable text strings. Includes shortcodes.
	$backtotop_text = apply_filters('hasloo_footer_backtotop_text', '[footer_backtotop]');
	$creds_text = apply_filters('hasloo_footer_creds_text', __('Copyright', 'hasloo') . ' [footer_copyright] [footer_childtheme_link] &middot; [footer_hasloo_link] [footer_studiopress_link] &middot; [footer_wordpress_link] &middot; [footer_loginout]');
	
	// For backward compatibility (pre-1.1 filter)
	if( apply_filters('hasloo_footer_credits', FALSE) ) {
		$filtered = apply_filters('hasloo_footer_credits', '[footer_childtheme_link] &middot; [footer_hasloo_link] &middot; [footer_wordpress_link]');
		$creds_text = __('Copyright', 'hasloo') . ' [footer_copyright] '. $filtered .' &middot; [footer_loginout]';
	}
	
	$output = '<div class="gototop"><p>' . $backtotop_text . '</p></div>' . '<div class="creds"><p>' . $creds_text . '</p></div>';
	
	echo apply_filters('hasloo_footer_output', $output, $backtotop_text, $creds_text);
	
}

/**
 * Output the footer scripts, defined in Theme Settings
 */
add_filter('hasloo_footer_scripts', 'do_shortcode');
add_action('wp_footer', 'hasloo_footer_scripts');
function hasloo_footer_scripts() {
	
	echo apply_filters('hasloo_footer_scripts', hasloo_option('footer_scripts'));

}