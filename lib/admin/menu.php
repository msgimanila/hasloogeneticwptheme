<?php
/*
 * @todo Document this file
 */

//	This function adds the top-level menu
add_action('admin_menu', 'hasloo_add_admin_menu');
function hasloo_add_admin_menu() {
	
	global $menu;
	
	// Disable if programatically disabled
	if ( !current_theme_supports('hasloo-admin-menu') ) return;
	
	// Disable if disabled for current user
	$user = wp_get_current_user();
	if ( !get_the_author_meta( 'hasloo_admin_menu', $user->ID ) ) return;
	
	// Create the new separator
	$menu['58.995'] = array( '', 'manage_options', 'separator-hasloo', '', 'wp-menu-separator' );
	
	// Create the new top-level Menu
	add_menu_page('Hasloo', 'Hasloo', 'manage_options', 'hasloo', 'hasloo_theme_settings_admin', PARENT_URL.'/images/hasloo.gif', '58.996');
}

// This function adds the submenus
add_action('admin_menu', 'hasloo_add_admin_submenus');
function hasloo_add_admin_submenus() {
	
	global	$_hasloo_theme_settings_pagehook,
			$_hasloo_seo_settings_pagehook;
			
	if( !current_theme_supports('hasloo-admin-menu') ) return;
	
	$user = wp_get_current_user();
	
	// Add "Theme Settings" submenu
	$_hasloo_theme_settings_pagehook = add_submenu_page('hasloo', __('Theme Settings','hasloo'), __('Theme Settings','hasloo'), 'manage_options', 'hasloo', 'hasloo_theme_settings_admin');
	
	// Add "SEO Settings" submenu
	if ( current_theme_supports('hasloo-seo-settings-menu') && get_the_author_meta( 'hasloo_seo_settings_menu', $user->ID ) ) {
		$_hasloo_seo_settings_pagehook = add_submenu_page('hasloo', __('SEO Settings','hasloo'), __('SEO Settings','hasloo'), 'manage_options', 'seo-settings', 'hasloo_seo_settings_admin');
	}
	
	// Add "Import/Export" submenu
	if ( current_theme_supports('hasloo-import-export-menu') && get_the_author_meta( 'hasloo_import_export_menu', $user->ID ) ) {
		add_submenu_page('hasloo', __('Import/Export','hasloo'), __('Import/Export','hasloo'), 'manage_options', 'hasloo-import-export', 'hasloo_import_export_admin');
	}
	
	// Add README.txt file submenu, if it exists
	if ( current_theme_supports('hasloo-readme-menu') ) {
		$_hasloo_readme_menu_pagehook = file_exists( CHILD_DIR . '/README.txt' ) ? add_submenu_page('hasloo', __('README', 'hasloo'), __('README', 'hasloo'), 'manage_options', 'readme', 'hasloo_readme_menu_admin') : null;
	}
	
}