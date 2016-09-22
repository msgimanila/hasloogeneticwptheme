<?php
/**
 * The functions in this file act as shortcuts for
 * accessing Genetic-specific Settings that have been
 * stored in the options table and as post meta data.
 */

/**
 * These functions pull options/settings
 * from the options database.
 *
 * @since 0.1.3
 **/
function hasloo_get_option($key, $setting = null) {

	// get setting
	$setting = $setting ? $setting : HASLOO_SETTINGS_FIELD;

	// setup caches
	static $settings_cache = array();
	static $options_cache = array();

	// allow child theme to short-circuit this function
	$pre = apply_filters('hasloo_pre_get_option_'.$key, false, $setting);
	if ( false !== $pre )
		return $pre;

	// Check options cache
	if ( isset($options_cache[$setting][$key]) ) {

		// option has been cached
		return $options_cache[$setting][$key];

	}

	// check settings cache
	if ( isset($settings_cache[$setting]) ) {

		// setting has been cached
		$options = apply_filters('hasloo_options', $settings_cache[$setting], $setting);

	} else {

		// set value and cache setting
		$options = $settings_cache[$setting] = apply_filters('hasloo_options', get_option($setting), $setting);
	
	}

	// check for non-existent option
	if ( !is_array( $options ) || !array_key_exists($key, (array) $options) ) {

		// cache non-existent option
		$options_cache[$setting][$key] = '';

		return '';
	}

	// option has been cached, cache option
	$options_cache[$setting][$key] = wp_kses_stripslashes( wp_kses_decode_entities( $options[$key] ) );

	return $options_cache[$setting][$key];

}
function hasloo_option($key, $setting = null) {
	echo hasloo_get_option($key, $setting);
}
function hasloo_get_seo_option($key) {
	return hasloo_get_option($key, HASLOO_SEO_SETTINGS_FIELD);
}
function hasloo_seo_option($key) {
	hasloo_option($key, HASLOO_SEO_SETTINGS_FIELD);
}

/**
 * These functions can be used to easily and efficiently pull data from a
 * post/page custom field. Returns FALSE if field is blank or not set.
 *
 * @param string $field used to indicate the custom field key
 *
 * @since 0.1.3
 */
function hasloo_custom_field($field) {
	echo hasloo_get_custom_field($field);
}
function hasloo_get_custom_field($field) {
	global $post;
	
	if ( null === $post ) return FALSE;
	
	$custom_field = get_post_meta($post->ID, $field, true);
	
	if ( $custom_field ) {
		// sanitize and return the value of the custom field
		return wp_kses_stripslashes( wp_kses_decode_entities( $custom_field ) );
	}
	else {
		// return FALSE if custom field is empty
		return FALSE;
	}
}

/**
 * Genetic is forced to create its own term-meta data structure in
 * the options table. Therefore, the following function merges that
 * data into the term data structure, via a filter.
 */
add_filter('get_term', 'hasloo_get_term_filter', 10, 2);
function hasloo_get_term_filter($term, $taxonomy) {
	
	$db = get_option('hasloo-term-meta');
	$term_meta = isset( $db[$term->term_id] ) ? $db[$term->term_id] : array();
	
	$term->meta = wp_parse_args( $term_meta, array(
			'display_title' => 0,
			'display_description' => 0,
			'doctitle' => '',
			'description' => '',
			'keywords' => '',
			'noindex' => 0,
			'nofollow' => 0,
			'noarchive' => 0,
			'layout' => ''
	) );
	
	// Sanitize term meta
	foreach ( $term->meta as $field => $value ) {
		$term->meta[$field] = wp_kses_stripslashes( wp_kses_decode_entities( $value ) );
	}
	
	return $term;
	
}