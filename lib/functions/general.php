<?php
/**
 * This is where we put all the functions that that are
 * difficult or impossible to categorize anywhere else.
 *
 **/

/**
 * Helper function to enable the author box for ALL users.
 *
 * @Genetic
 */
function hasloo_enable_author_box( $args = array() ) {
	
	$args = wp_parse_args( $args, array( 'type' => 'single' ) );
	
	if ( $args['type'] === 'single' ) {
		add_filter( 'get_the_author_hasloo_author_box_single', '__return_true' );
	}
	elseif ( $args['type'] === 'archive' ) {
		add_filter( 'get_the_author_hasloo_author_box_archive', '__return_true' );
	}
	
}