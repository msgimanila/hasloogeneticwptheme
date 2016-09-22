<?php
/**
 * This function expedites the widget area registration process by taking
 * common things, before/after_widget, before/after_title, and doing them automatically.
 *
 * @uses wp_parse_args, register_sidebar
 * @since 1.0.1
 * @author Genetic
 * @author Genetic
 */
function hasloo_register_sidebar($args) {
	$defaults = array(
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-wrap">',
		'after_widget'  => "</div></div>\n",
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => "</h4>\n"
	);

	$args = wp_parse_args($args, $defaults);

	return register_sidebar($args);
}


// defines the sidebars that are displayed in the WordPress widget screen

if ( hasloo_get_option('header_right') ) {
hasloo_register_sidebar(array(
	'name' => __('Header Right', 'hasloo'),
	'description' => __('This is the right side of the header', 'hasloo'),
	'id' => 'header-right'
));
}

hasloo_register_sidebar(array(
	'name' => __('Primary Sidebar', 'hasloo'),
	'description' => __('This is the primary sidebar if you are using a 2 or 3 column site layout option', 'hasloo'),
	'id' => 'sidebar'
));

hasloo_register_sidebar(array(
	'name' => __('Secondary Sidebar', 'hasloo'),
	'description' => __('This is the secondary sidebar if you are using a 3 column site layout option', 'hasloo'),
	'id' => 'sidebar-alt'
));