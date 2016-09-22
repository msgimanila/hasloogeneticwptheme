<?php
/**
 * Creates the initial layouts when the 'init' action is fired
 *
 * @since 1.4
 */
add_action('hasloo_init', 'hasloo_create_initial_layouts', 0);
function hasloo_create_initial_layouts() {
	
	hasloo_register_layout( 'content-sidebar', array(
		'label' => __('Content/Sidebar', 'hasloo'),
		'img' => HASLOO_ADMIN_IMAGES_URL . '/layouts/cs.gif',
		'default' => true
	) );
	
	hasloo_register_layout( 'sidebar-content', array(
		'label' => __('Sidebar/Content', 'hasloo'),
		'img' => HASLOO_ADMIN_IMAGES_URL . '/layouts/sc.gif'
	) );
	
	hasloo_register_layout( 'content-sidebar-sidebar', array(
		'label' => __('Content/Sidebar/Sidebar', 'hasloo'),
		'img' => HASLOO_ADMIN_IMAGES_URL . '/layouts/css.gif'
	) );
	
	hasloo_register_layout( 'sidebar-sidebar-content', array(
		'label' => __('Sidebar/Sidebar/Content', 'hasloo'),
		'img' => HASLOO_ADMIN_IMAGES_URL . '/layouts/ssc.gif'
	) );
	
	hasloo_register_layout( 'sidebar-content-sidebar', array(
		'label' => __('Sidebar/Content/Sidebar', 'hasloo'),
		'img' => HASLOO_ADMIN_IMAGES_URL . '/layouts/scs.gif'
	) );
	
	hasloo_register_layout( 'full-width-content', array(
		'label' => __('Full Width Content', 'hasloo'),
		'img' => HASLOO_ADMIN_IMAGES_URL . '/layouts/c.gif',
	) );
	
}

/**
 * This function registers new layouts by modifying the global
 * $_hasloo_layouts variable.
 * 
 * @since 1.4
 */
function hasloo_register_layout( $id = '', $args = array() ) {
	
	global $_hasloo_layouts;
	
	if ( !is_array( $_hasloo_layouts ) )
		$_hasloo_layouts = array();
		
	// Don't allow empty $id, or double registrations
	if ( !$id || isset( $_hasloo_layouts[$id] ) )
		return false;
		
	$defaults = array(
		'label' => 'No Label Selected',
		'img' => HASLOO_ADMIN_IMAGES_URL . '/layouts/none.gif',
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	$_hasloo_layouts[$id] = $args;
	
	return $args;
	
}

/**
 * This function allows a user to identify a layout as being the default
 * layout on a new install, as well as serve as the fallback layout.
 *
 * @since 1.4
 */
function hasloo_set_default_layout( $id = '' ) {
	
	global $_hasloo_layouts;
	
	if ( !is_array( $_hasloo_layouts ) )
		$_hasloo_layouts = array();
		
	// Don't allow empty $id, or double registrations
	if ( !$id || !isset( $_hasloo_layouts[$id] ) )
		return false;
		
	// remove default flag for all other layouts
	foreach ( (array)$_hasloo_layouts as $key => $value ) {
		if ( isset( $_hasloo_layouts[$key]['default'] ) ) {
			unset( $_hasloo_layouts[$key]['default'] );
		}
	}
		
	$_hasloo_layouts[$id]['default'] = true;
	
	return $id;
	
}

/**
 * This function unregisters layouts by modifying the global
 * $_hasloo_layouts variable.
 * 
 * @since 1.4
 */
function hasloo_unregister_layout( $id = '' ) {
	
	global $_hasloo_layouts;
	
	if ( !$id || !isset( $_hasloo_layouts[$id] ) )
		return false;
		
	unset( $_hasloo_layouts[$id] );
	
	return true;
	
}

/**
 * This function returns all registered Genetic Layouts
 *
 * @since 1.4
 */
function hasloo_get_layouts() {
	
	global $_hasloo_layouts;
	
	if ( !is_array( $_hasloo_layouts ) )
		$_hasloo_layouts = array();
		
	return $_hasloo_layouts;
	
}

/**
 * This function returns the data from a single layout,
 * specified by the $id passed to it.
 *
 * @since 1.4
 */
function hasloo_get_layout( $id ) {
	
	$layouts = hasloo_get_layouts();
	
	if ( !$id || !isset( $layouts[$id] ) )
		return;
		
	return $layouts[$id];
	
}

/**
 * This function returns the layout that is set to default.
 *
 * @since 1.4
 */
function hasloo_get_default_layout() {
	
	global $_hasloo_layouts;
	
	$default = '';
	
	foreach ( (array)$_hasloo_layouts as $key => $value ) {
		if ( isset( $value['default'] ) && $value['default'] ) {
			$default = $key; break;
		}
	}
	
	// return default layout, if exists
	if ( $default ) {
		return $default;
	}
	
	return 'nolayout';
	
}

/**
 * This function checks both the custom field and
 * the theme option to find the user-selected site
 * layout, and returns it.
 *
 * @since 0.2.2
 */
function hasloo_site_layout() {
	
	// If viewing a singular page/post
	if ( is_singular() ) {
		
		$custom_field = hasloo_get_custom_field( '_hasloo_layout' );
		$site_layout = $custom_field ? $custom_field : hasloo_get_option( 'site_layout' );
		
	}
	
	// If viewing a taxonomy archive
	elseif ( is_category() || is_tag() || is_tax() ) {
		global $wp_query;
		
		$term = $wp_query->get_queried_object();
		
		$site_layout = $term && isset( $term->meta['layout'] ) && $term->meta['layout'] ? $term->meta['layout'] : hasloo_get_option( 'site_layout' );
		
	}
	
	// If viewing an author archive
	elseif( is_author() ) {
		
		$site_layout = get_the_author_meta( 'layout', (int)get_query_var('author') ) ? get_the_author_meta( 'layout', (int)get_query_var('author') ) : hasloo_get_option('site_layout');
		
	}

	// else pull the theme option
	else {
		
		$site_layout = hasloo_get_option( 'site_layout' );
		
	}
	
	// Use default layout as a fallback, if necessary
	if ( !hasloo_get_layout( $site_layout ) ) {
		$site_layout = hasloo_get_default_layout();
	}
	
	return esc_attr( apply_filters('hasloo_site_layout', $site_layout ) );
	
}