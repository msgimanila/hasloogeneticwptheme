<?php
/**
 * This file controls the Import/Export functions of the Genesis Framework
 *
 * @since 1.4
 */

/**
 * This function controls the admin page for the Genesis Import/Export functionality.
 *
 * @since 1.4
 */
function hasloo_import_export_admin() { ?>
	
	<div class="wrap">
		<?php screen_icon('tools'); ?>	
		<h2><?php _e('Hasloo - Import/Export', 'hasloo'); ?></h2>
			
			<table class="form-table"><tbody>
				
				<tr>
					<th scope="row"><p><b><?php _e('Import Hasloo Settings File', 'hasloo'); ?></b></p></th>
					<td>
						<p><?php _e('Upload the data file from your computer (.json) and we\'ll import your settings.', 'hasloo'); ?></p>
						<p><?php _e('Choose the file from your computer and click "Upload and Import"', 'hasloo'); ?></p>
						<p>
							<form enctype="multipart/form-data" method="post" action="<?php echo admin_url('admin.php?page=hasloo-import-export'); ?>">
								<?php wp_nonce_field('hasloo-import'); ?>
								<input type="hidden" name="hasloo-import" value="1" />
								<label for="hasloo-import-upload"><?php sprintf( __('Upload File: (Maximum Size: %s)', 'hasloo'), ini_get('post_max_size') ); ?></label>
								<input type="file" id="hasloo-import-upload" name="hasloo-import-upload" size="25" />
								<input type="submit" class="button" value="<?php _e('Upload file and import', 'hasloo'); ?>" />
							</form>
						</p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><p><b><?php _e('Export Hasloo Settings File', 'hasloo'); ?></b></p></th>
					<td>
						<p><?php _e('When you click the button below, Hasloo will generate a JSON file for you to save to your computer.', 'hasloo'); ?></p>
						<p><?php _e('Once you have saved the download file, you can use the import function on another site to import this data.', 'hasloo'); ?></p>
						<p>
							<form method="post" action="<?php echo admin_url('admin.php?page=hasloo-import-export'); ?>">
								<?php wp_nonce_field('hasloo-export'); ?>
								<select name="hasloo-export">
									<option value="theme">Theme Settings</option>
									<option value="seo">SEO Settings</option>
									<option value="all">Theme and SEO Settings</option>
								</select>
								<input type="submit" class="button" value="<?php _e('Download Export File', 'hasloo'); ?>" />
							</form>
						</p>
					</td>
				</tr>
				
				<?php hasloo_import_export_form(); // hook ?>
				
			</tbody></table>
		
	</div>
	
<?php }

add_action('admin_notices', 'hasloo_import_export_notices');
/**
 * This is the notice that displays when you successfully save or reset
 * the theme settings.
 */
function hasloo_import_export_notices() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'hasloo-import-export' )
		return;
	
	if ( isset( $_REQUEST['imported'] ) && $_REQUEST['imported'] == 'true' ) {
		echo '<div id="message" class="updated"><p><strong>'.__('Settings successfully imported!', 'hasloo').'</strong></p></div>';
	}
	elseif ( isset($_REQUEST['error']) && $_REQUEST['error'] == 'true') {  
		echo '<div id="message" class="error"><p><strong>'.__('There was a problem importing your settings. Please Try again.', 'hasloo').'</strong></p></div>';
	}
	
}

add_action( 'admin_init', 'hasloo_export' );
/**
 * This function generates the export file, if requested, in JSON format
 *
 * @since 1.4
 */
function hasloo_export() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'hasloo-import-export' )
		return;
		
	if ( empty( $_REQUEST['hasloo-export'] ) )
		return;
		
	check_admin_referer('hasloo-export'); // Verify nonce
	
	// hookable
	do_action('hasloo_export', $_REQUEST['hasloo-export']);
	
	$settings = array();
	
	if ( $_REQUEST['hasloo-export'] === 'all' ) {		
		$settings = array(
			HASLOO_SETTINGS_FIELD => get_option( HASLOO_SETTINGS_FIELD ),
			HASLOO_SEO_SETTINGS_FIELD => get_option( HASLOO_SEO_SETTINGS_FIELD )
		);
		$prefix = 'hasloo-settings';
	}
	
	if ( $_REQUEST['hasloo-export'] === 'theme' ) {		
		$settings = array(
			HASLOO_SETTINGS_FIELD => get_option( HASLOO_SETTINGS_FIELD )
		);
		$prefix = 'hasloo-theme-settings';
	}
	
	if ( $_REQUEST['hasloo-export'] === 'seo' ) {		
		$settings = array(
			HASLOO_SEO_SETTINGS_FIELD => get_option( HASLOO_SEO_SETTINGS_FIELD )
		);
		$prefix = 'hasloo-seo-settings';
	}
	
	if ( !$settings ) return;
	
    $output = json_encode( (array)$settings );

    header( 'Content-Description: File Transfer' );
    header( 'Cache-Control: public, must-revalidate' );
    header( 'Pragma: hack' );
    header( 'Content-Type: text/plain' );
    header( 'Content-Disposition: attachment; filename="' . $prefix . '-' . date("Ymd-His") . '.json"' );
    header( 'Content-Length: ' . strlen($output) );
    echo $output;
    exit;
	
}

add_action( 'admin_init', 'hasloo_import' );
/**
 * This function handles the import.
 *
 * @since 1.4
 */
function hasloo_import() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'hasloo-import-export' )
		return;
		
	if ( empty( $_REQUEST['hasloo-import'] ) )
		return;
		
	check_admin_referer('hasloo-import'); // Verify nonce
	
	// hookable
	do_action('hasloo_import', $_REQUEST['hasloo-import'], $_FILES['hasloo-import-upload']);
	
	// Extract file contents
	$upload = file_get_contents($_FILES['hasloo-import-upload']['tmp_name']);
	
	// Decode the JSON
	$options = json_decode( $upload, true );
	
	// Check for errors
	if ( !$options || $_FILES['hasloo-import-upload']['error'] ) {
		wp_redirect( admin_url( 'admin.php?page=hasloo-import-export&error=true' ) );
		exit;
	}
	
	// Cycle through data, import settings
	foreach ( (array)$options as $key => $settings ) {
		update_option( $key, $settings );
	}
	
	// Redirect, add success flag to the URI
	wp_redirect( admin_url( 'admin.php?page=hasloo-import-export&imported=true' ) );
	exit;
	
}