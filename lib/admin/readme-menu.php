<?php
/**
 * Returns contents of the README.txt Child theme file, if it exists.
 *
 * @since 1.3
 */
function hasloo_readme_menu_admin() {

	// Assume we cannot find the file.
	$file = false;

	// Get the file contents
	$file = @file_get_contents(CHILD_DIR . '/README.txt');

	if ( !$file || empty($file) ) {
		$file = '<b>README.txt file not found.</b>';
	}
	
?>
	<div id="hasloo-readme-file" class="wrap">
		<?php screen_icon('edit-pages'); ?>
		<h2><?php _e('HASLOO - README.txt Theme File', 'hasloo'); ?></h2>
		<?php echo wpautop( $file ); ?>
	</div>
<?php
}