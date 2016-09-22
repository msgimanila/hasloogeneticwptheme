<?php
/**
 * WARNING: This file is part of the core Genetic framework. DO NOT edit
 * this file under any circumstances. Please do all modifications
 * in the form of a child theme.
 *
 * This file initializes the framework by doing some
 * basic things like defining constants, and loading
 * framework components from the /lib directory.
 *
 * @package Genetic
 *
 **/

//	Run the hasloo_pre Hook
do_action('hasloo_pre');

/**
 * Activate Theme features
 */
add_theme_support('menus');
add_theme_support('post-thumbnails');
add_theme_support('automatic-feed-links');
add_theme_support('hasloo-inpost-layouts');
add_theme_support('hasloo-archive-layouts');
add_theme_support('hasloo-admin-menu');
add_theme_support('hasloo-seo-settings-menu');
add_theme_support('hasloo-import-export-menu');
add_theme_support('hasloo-readme-menu');
add_theme_support('hasloo-auto-updates');

/**
 * Define Theme Name/Version Constants
 * 
 **/
define('PARENT_THEME_NAME', 'Genetic');
define('PARENT_THEME_VERSION', '1.3');
define('PARENT_THEME_RELEASE_DATE', date_i18n('F j, Y', '1291960800'));

/**
 * Define Directory Location Constants
 */
define('PARENT_DIR', TEMPLATEPATH);
define('CHILD_DIR', STYLESHEETPATH);
define('HASLOO_IMAGES_DIR', PARENT_DIR.'/images');
define('HASLOO_LIB_DIR', PARENT_DIR.'/lib');
define('HASLOO_ADMIN_DIR', HASLOO_LIB_DIR.'/admin');
define('HASLOO_ADMIN_IMAGES_DIR', HASLOO_LIB_DIR.'/admin/images');
define('HASLOO_JS_DIR', HASLOO_LIB_DIR.'/js');
define('HASLOO_CSS_DIR', HASLOO_LIB_DIR.'/css');
define('HASLOO_FUNCTIONS_DIR', HASLOO_LIB_DIR.'/functions');
define('HASLOO_SHORTCODES_DIR', HASLOO_LIB_DIR.'/shortcodes');
define('HASLOO_STRUCTURE_DIR', HASLOO_LIB_DIR.'/structure');
if( !defined('HASLOO_LANGUAGES_DIR') ) // So we can define with a child theme
	define('HASLOO_LANGUAGES_DIR', HASLOO_LIB_DIR.'/languages');
define('HASLOO_TOOLS_DIR', HASLOO_LIB_DIR.'/tools');
define('HASLOO_WIDGETS_DIR', HASLOO_LIB_DIR.'/widgets');

/**
 * Define URL Location Constants
 */
define('PARENT_URL', get_bloginfo('template_directory'));
define('CHILD_URL', get_bloginfo('stylesheet_directory'));
define('HASLOO_IMAGES_URL', PARENT_URL.'/images');
define('HASLOO_LIB_URL', PARENT_URL.'/lib');
define('HASLOO_ADMIN_URL', HASLOO_LIB_URL.'/admin');
define('HASLOO_ADMIN_IMAGES_URL', HASLOO_LIB_URL.'/admin/images');
define('HASLOO_JS_URL', HASLOO_LIB_URL.'/js');
define('HASLOO_CSS_URL', HASLOO_LIB_URL.'/css');
define('HASLOO_FUNCTIONS_URL', HASLOO_LIB_URL.'/functions');
define('HASLOO_SHORTCODES_URL', HASLOO_LIB_URL.'/shortcodes');
define('HASLOO_STRUCTURE_URL', HASLOO_LIB_URL.'/structure');
if( !defined('HASLOO_LANGUAGES_URL') ) // So we can predefine to child theme
	define('HASLOO_LANGUAGES_URL', HASLOO_LIB_URL.'/languages');
define('HASLOO_TOOLS_URL', HASLOO_LIB_URL.'/tools');
define('HASLOO_WIDGETS_URL', HASLOO_LIB_URL.'/widgets');

/**
 * Define Settings Field Constants (for DB storage)
 */
define('HASLOO_SETTINGS_FIELD', apply_filters('hasloo_settings_field', 'hasloo-settings'));
define('HASLOO_SEO_SETTINGS_FIELD', apply_filters('hasloo_seo_settings_field', 'hasloo-seo-settings'));

//	Run the hasloo_pre_framework Hook
do_action('hasloo_pre_framework');

/**
 * Load Framework Components, unless a child theme says not to
 *
 **/
if ( !defined('HASLOO_LOAD_FRAMEWORK') || HASLOO_LOAD_FRAMEWORK !== false ) :

//	Load Framework
require_once(HASLOO_LIB_DIR . '/framework.php');

//	Load Functions
require_once(HASLOO_FUNCTIONS_DIR . '/hooks.php');
require_once(HASLOO_FUNCTIONS_DIR . '/upgrade.php');
require_once(HASLOO_FUNCTIONS_DIR . '/general.php');
require_once(HASLOO_FUNCTIONS_DIR . '/options.php');
require_once(HASLOO_FUNCTIONS_DIR . '/image.php');
require_once(HASLOO_FUNCTIONS_DIR . '/admin.php');
require_once(HASLOO_FUNCTIONS_DIR . '/menu.php');
require_once(HASLOO_FUNCTIONS_DIR . '/layout.php');
require_once(HASLOO_FUNCTIONS_DIR . '/formatting.php');
require_once(HASLOO_FUNCTIONS_DIR . '/seo.php');
require_once(HASLOO_FUNCTIONS_DIR . '/widgetize.php');
require_once(HASLOO_FUNCTIONS_DIR . '/feed.php');
require_once(HASLOO_FUNCTIONS_DIR . '/i18n.php');
require_once(HASLOO_FUNCTIONS_DIR . '/deprecated.php');

//	Load Shortcodes
require_once(HASLOO_SHORTCODES_DIR . '/post.php');
require_once(HASLOO_SHORTCODES_DIR . '/footer.php');

//	Load Structure
require_once(HASLOO_STRUCTURE_DIR . '/header.php');
require_once(HASLOO_STRUCTURE_DIR . '/footer.php');
require_once(HASLOO_STRUCTURE_DIR . '/menu.php');
require_once(HASLOO_STRUCTURE_DIR . '/layout.php');
require_once(HASLOO_STRUCTURE_DIR . '/post.php');
require_once(HASLOO_STRUCTURE_DIR . '/loops.php');
require_once(HASLOO_STRUCTURE_DIR . '/comments.php');
require_once(HASLOO_STRUCTURE_DIR . '/sidebar.php');
require_once(HASLOO_STRUCTURE_DIR . '/archive.php');
require_once(HASLOO_STRUCTURE_DIR . '/search.php');

//	Load Admin
require_once(HASLOO_ADMIN_DIR . '/menu.php');
require_once(HASLOO_ADMIN_DIR . '/theme-settings.php');
require_once(HASLOO_ADMIN_DIR . '/seo-settings.php');
require_once(HASLOO_ADMIN_DIR . '/import-export.php');
require_once(HASLOO_ADMIN_DIR . '/readme-menu.php');
require_once(HASLOO_ADMIN_DIR . '/inpost-metaboxes.php');
require_once(HASLOO_ADMIN_DIR . '/term-meta.php');
require_once(HASLOO_ADMIN_DIR . '/user-meta.php');
require_once(HASLOO_ADMIN_DIR . '/editor.php');

//	Load Javascript
require_once(HASLOO_JS_DIR . '/load-scripts.php');

//	Load CSS
require_once(HASLOO_CSS_DIR . '/load-styles.php');
 
//	Load Widgets

//	Load Tools
require_once(HASLOO_TOOLS_DIR . '/custom-field-redirect.php');
require_once(HASLOO_TOOLS_DIR . '/breadcrumb.php');
if( current_theme_supports('post-templates') ) {
	require_once(HASLOO_TOOLS_DIR . '/post-templates.php');
}

endif; // end conditional loading of framework components

/**
 * Allowed formatting tags, used by wp_kses().
 * Filterable.
 */
$_hasloo_formatting_allowedtags = apply_filters('hasloo_formatting_allowedtags', array(
	//	<p>, <span>, <div>
	'p' => array( 'align' => array(), 'class' => array(), 'style' => array() ),
	'span' => array( 'align' => array(), 'class' => array(), 'style' => array() ),
	'div' => array( 'align' => array(), 'class' => array(), 'style' => array() ),
	
	// <img src="" class="" alt="" title="" width="" height="" />
	//'img' => array( 'src' => array(), 'class' => array(), 'alt' => array(), 'title' => array(), 'width' => array(), 'height' => array() ),
	
	//	<a href="" title="">Text</a>
	'a' => array( 'href' => array(), 'title' => array() ),
	
	//	<b>, </i>, <em>, <strong>
	'b' => array(), 'strong' => array(),
	'i' => array(), 'em' => array(),
	
	//	<blockquote>, <br />
	'blockquote' => array(),
	'br' => array()
) );

/**
 * Run the hasloo_init() action hook
 *
 **/
hasloo_init();