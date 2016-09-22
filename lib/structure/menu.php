<?php
/**
 * @todo document this file
 */

/**
 * The following registers the Nav Menu Locations.
 * These locations are used as places to where Nav
 * Menus can be placed/associated
 */
register_nav_menus( array(
	'primary' => __('Primary Navigation Menu', 'hasloo'),
	'secondary' => __('Secondary Navigation Menu', 'hasloo')
) );


add_action('hasloo_after_header', 'hasloo_do_nav');
/**
 * This function is responsible for displaying the "Primary Navigation" bar.
 *
 * @uses hasloo_nav(), hasloo_get_option(), wp_nav_menu()
 * @since 1.0
 */
function hasloo_do_nav() {
	if ( hasloo_get_option('nav') ) {
		
		if ( hasloo_get_option('nav_type') == 'nav-menu' && function_exists('wp_nav_menu') ) {
			
			$nav = wp_nav_menu(array(
				'theme_location' => 'primary',
				'container' => '',
				'menu_class' => hasloo_get_option('nav_superfish') ? 'nav superfish' : 'nav',
				'echo' => 0
			));
			
		} else {
			
			$nav = hasloo_nav(array(
				'theme_location' => 'primary',
				'menu_class' => hasloo_get_option('nav_superfish') ? 'nav superfish' : 'nav',
				'show_home' => hasloo_get_option('nav_home'),
				'type' => hasloo_get_option('nav_type'),
				'sort_column' => hasloo_get_option('nav_pages_sort'),
				'orderby' => hasloo_get_option('nav_categories_sort'),
				'depth' => hasloo_get_option('nav_depth'),
				'exclude' => hasloo_get_option('nav_exclude'),
				'include' => hasloo_get_option('nav_include'),
				'echo' => false
			));
			
		}
		
		echo '<div id="nav"><div class="wrap">' . $nav . '</div></div>';
		
	}
}


add_action('hasloo_after_header', 'hasloo_do_subnav');
/**
 * This function  is responsible for displaying the "Secondary Navigation" bar.
 *
 * @uses hasloo_nav(), hasloo_get_option(), wp_nav_menu
 * @since 1.0.1
 *
 */
function hasloo_do_subnav() {
	if ( hasloo_get_option('subnav') ) {
		
		if ( hasloo_get_option('subnav_type') == 'nav-menu' && function_exists('wp_nav_menu') ) {
			
			$subnav = wp_nav_menu(array(
				'theme_location' => 'secondary',
				'container' => '',
				'menu_class' => hasloo_get_option('subnav_superfish') ? 'nav superfish' : 'nav',
				'echo' => 0
			));
			
		} else {
			
			$subnav = hasloo_nav(array(
				'theme_location' => 'secondary',
				'menu_class' => hasloo_get_option('subnav_superfish') ? 'nav superfish' : 'nav',
				'show_home' => hasloo_get_option('subnav_home'),
				'type' => hasloo_get_option('subnav_type'),
				'sort_column' => hasloo_get_option('subnav_pages_sort'),
				'orderby' => hasloo_get_option('subnav_categories_sort'),
				'depth' => hasloo_get_option('subnav_depth'),
				'exclude' => hasloo_get_option('subnav_exclude'),
				'include' => hasloo_get_option('subnav_include'),
				'echo' => false
			));
		
		}
		
		echo '<div id="subnav"><div class="wrap">' . $subnav . '</div></div>';
	}
}


add_filter('hasloo_nav_items', 'hasloo_nav_right', 10, 2);
add_filter('wp_nav_menu_items', 'hasloo_nav_right', 10, 2);
/**
 * This function filters the Primary Navigation menu items, appending
 * either RSS links, search form, twitter link, or today's date.
 *
 * @uses hasloo_get_option(), get_bloginfo(), get_search_form(),
 * @since 1.0
 */
function hasloo_nav_right($menu, $args) {
	
	$args = (array)$args;
	
	if ( !hasloo_get_option('nav_extras_enable') || $args['theme_location'] != 'primary' )
		return $menu;
	
	if ( hasloo_get_option('nav_extras') == 'rss' ) {
		$rss = '<a rel="nofollow" href="'.get_bloginfo('rss_url').'">'.__('Posts', 'hasloo').'</a>';
		$rss .= '<a rel="nofollow" href="'.get_bloginfo('comments_rss2_url').'">'.__('Comments', 'hasloo').'</a>';
		
		$menu .= '<li class="right rss">'.$rss.'</li>';
	}
	elseif ( hasloo_get_option('nav_extras') == 'search' ) {
		//output buffering
		ob_start();
		get_search_form();
		$search = ob_get_clean();
		
		$menu .= '<li class="right search">'.$search.'</li>';
	}
	elseif ( hasloo_get_option('nav_extras') == 'twitter' ) {
		
		$menu .= sprintf( '<li class="right twitter"><a href="%s">%s</a></li>', esc_url( 'http://twitter.com/' . hasloo_get_option('nav_extras_twitter_id') ), esc_html( hasloo_get_option('nav_extras_twitter_text') ) );
	
	}
	elseif ( hasloo_get_option('nav_extras') == 'date' ) {
		
		$menu .= '<li class="right date">'.date_i18n(get_option('date_format')).'</li>';
		
	}
	
	return $menu;
	
}