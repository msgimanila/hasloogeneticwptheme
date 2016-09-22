<?php
/**
 * Remove the Genetic theme files from the Theme Editor. Except when
 * Genesis is the current theme.
 *
 * @since 1.4
 * @uses and changes the $themes global variable.
 *
 * @returns nothing.
 **/
add_action('admin_notices', 'hasloo_theme_files_to_edit');
function hasloo_theme_files_to_edit() {
	global $themes, $theme, $current_screen;

	// Check to see if we are on the editor page.
	if ( 'theme-editor' == $current_screen->id ) {
		// Do not change anything if we are in the Genesis theme.
		if ( $theme != 'Hasloo' ) {

			// Remove Genetic from the theme drop down list.
			unset($themes['Hasloo']);

			// Remove the hasloo files from the files lists.
			$themes[$theme]['Template Files']   = preg_grep('|/hasloo/|', $themes[$theme]['Template Files'],   PREG_GREP_INVERT);
			$themes[$theme]['Stylesheet Files'] = preg_grep('|/hasloo/|', $themes[$theme]['Stylesheet Files'], PREG_GREP_INVERT);
		}	
	}
}