<?php
/**
 * This file is home to the code that has
 * been deprecated/replaced by other code.
 *
 * It serves as a compatibility mechanism.
 *
 * @package Genetic
 **/


/**
 * @deprecated in 1.1.2
 */
function hasloo_before_respond() {
	_deprecated_function( __FUNCTION__, '1.1.2', 'hasloo_before_comment_form()' );
	
	hasloo_before_comment_form();
}
function hasloo_after_respond() {
	_deprecated_function( __FUNCTION__, '1.1.2', 'hasloo_after_comment_form()' );
	
	hasloo_after_comment_form();
}

/**
 * @deprecated in 1.2
 */
function hasloo_add_image_size($name, $width = 0, $height = 0, $crop = FALSE) {
	_deprecated_function( __FUNCTION__, '1.2', 'add_image_size()' );
	
	add_image_size($name, $width, $height, $crop);
}

/**
 * @deprecated in 1.2
 */
function hasloo_add_intermediate_sizes($deprecated = '') {
	_deprecated_function( __FUNCTION__, '1.2' );
	
	return array();
}

/**
 * @deprecated in 1.2
 */
function hasloo_comment() {
	_deprecated_function( __FUNCTION__, '1.2', 'hasloo_after_comment()' );
	
	do_action('hasloo_after_comment');
}