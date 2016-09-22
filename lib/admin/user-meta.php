<?php
/**
 * This file handles the insertion of Hasloo-specific user meta
 * information, including what features a user has access to,
 * and the SEO information for that user's post archive.
 *
 * @since 1.4
 */

add_action( 'show_user_profile', 'hasloo_user_options_fields' );
add_action( 'edit_user_profile', 'hasloo_user_options_fields' );
/**
 * This function adds new form elements to the user edit screen.
 * 
 * @since 1.4
 */
function hasloo_user_options_fields( $user ) {
	
	if ( !current_user_can( 'edit_users', $user->ID ) )
		return false;
	
	?>
	
	<h3><?php _e('Hasloo User Settings', 'hasloo'); ?></h3>
	<table class="form-table">
		
		<tr>
			<th scope="row" valign="top"><label><?php _e('Hasloo Admin Menus', 'hasloo'); ?></label></th>
			<td>
				<label><input name="meta[hasloo_admin_menu]" type="checkbox" value="1" <?php checked(1, get_the_author_meta('hasloo_admin_menu', $user->ID)); ?> /> <?php _e('Enable Hasloo Admin Menu?', 'hasloo'); ?></label><br />
				<label><input name="meta[hasloo_seo_settings_menu]" type="checkbox" value="1" <?php checked(1, get_the_author_meta('hasloo_seo_settings_menu', $user->ID)); ?> /> <?php _e('Enable SEO Settings Submenu?', 'hasloo'); ?></label><br />
				<label><input name="meta[hasloo_import_export_menu]" type="checkbox" value="1" <?php checked(1, get_the_author_meta('hasloo_import_export_menu', $user->ID)); ?> /> <?php _e('Enable Import/Export Submenu?', 'hasloo'); ?></label>
			</td>
		</tr>
		
		<tr>
			<th scope="row" valign="top"><label><?php _e('Author Box', 'hasloo'); ?></label></th>
			<td>
				<label><input name="meta[hasloo_author_box_single]" type="checkbox" value="1" <?php checked(1, get_the_author_meta('hasloo_author_box_single', $user->ID)); ?> /> <?php _e('Enable Author Box on this User\'s Posts?', 'hasloo'); ?></label><br />
				<label><input name="meta[hasloo_author_box_archive]" type="checkbox" value="1" <?php checked(1, get_the_author_meta('hasloo_author_box_archive', $user->ID)); ?> /> <?php _e('Enable Author Box on this User\'s Archives?', 'hasloo'); ?></label>
			</td>
		</tr>
	
		</table>
	
<?php }


add_action( 'show_user_profile', 'hasloo_user_seo_fields' );
add_action( 'edit_user_profile', 'hasloo_user_seo_fields' );
/**
 * This function adds new form elements to the user edit screen.
 * 
 * @since 1.4
 */
function hasloo_user_seo_fields( $user ) {
	
	if ( !current_user_can( 'edit_users', $user->ID ) )
		return false;
	
	?>
	
		<h3><?php _e('Hasloo SEO Options and Settings', 'hasloo'); ?></h3>
		<p><span class="description"><?php _e('These settings apply to this author\'s archive pages.', 'hasloo'); ?></span></p>
		<table class="form-table">

		<tr class="form-field">
			<th scope="row" valign="top"><label for="headline"><?php _e( 'Custom Archive Headline', 'hasloo' ); ?></label></th>
			<td><input name="meta[headline]" id="headline" type="text" value="<?php echo esc_attr( get_the_author_meta('headline', $user->ID) ); ?>" size="40" /><br />
			<span class="description"><?php printf( __('Will display in the %s tag at the top of the first page', 'hasloo'), '<code>&lt;h1&gt;&lt;/h1&gt;</code>' ); ?></span></td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top"><label for="intro_text"><?php _e( 'Custom Description Text', 'hasloo' ); ?></label></th>
			<td><textarea name="meta[intro_text]" id="intro_text" rows="3" cols="50" style="width: 97%;"><?php echo esc_html( get_the_author_meta('intro_text', $user->ID) ); ?></textarea><br />
			<span class="description"><?php _e('This text will be the first paragraph, and display on the first page', 'hasloo'); ?></span></td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top"><label for="doctitle"><?php printf( __('Custom Document %s', 'hasloo'), '<code>&lt;title&gt;</code>' ); ?></label></th>
			<td><input name="meta[doctitle]" id="doctitle" type="text" value="<?php echo esc_attr( get_the_author_meta('doctitle', $user->ID) ); ?>" size="40" /></td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top"><label for="meta-description"><?php printf( __('%s Description', 'hasloo'), '<code>META</code>' ); ?></label></th>
			<td><textarea name="meta[meta_description]" id="meta-description" rows="3" cols="50" style="width: 97%;"><?php echo esc_html( get_the_author_meta('meta_description', $user->ID) ); ?></textarea></td>
		</tr>

		<tr class="form-field">
			<th scope="row" valign="top"><label for="meta-keywords"><?php printf( __('%s Keywords', 'hasloo'), '<code>META</code>' ); ?></label></th>
			<td><input name="meta[meta_keywords]" id="meta-keywords" type="text" value="<?php echo esc_attr( get_the_author_meta('meta_keywords', $user->ID) ); ?>" size="40" /><br />
			<span class="description"><?php _e('Comma separated list', 'hasloo'); ?></span></td>
		</tr>

		<tr>
			<th scope="row" valign="top"><label><?php _e('Robots Meta', 'hasloo'); ?></label></th>
			<td>
				<label><input name="meta[noindex]" id="noindex" type="checkbox" value="1" <?php checked(1, get_the_author_meta('noindex', $user->ID)); ?> /> <?php printf( __('Apply %s to this archive?', 'hasloo'), '<code>noindex</code>' ); ?></label><br />
				<label><input name="meta[nofollow]" id="nofollow" type="checkbox" value="1" <?php checked(1, get_the_author_meta('nofollow', $user->ID)); ?> /> <?php printf( __('Apply %s to this archive?', 'hasloo'), '<code>nofollow</code>' ); ?></label><br />
				<label><input name="meta[noarchive]" id="noarchive" type="checkbox" value="1" <?php checked(1, get_the_author_meta('noarchive', $user->ID)); ?> /> <?php printf( __('Apply %s to this archive?', 'hasloo'), '<code>noarchive</code>' ); ?></label>
			</td>
		</tr>

		</table>

<?php }


add_action( 'show_user_profile', 'hasloo_user_layout_fields' );
add_action( 'edit_user_profile', 'hasloo_user_layout_fields' );
/**
 * This function adds new layout form elements to the user edit screen.
 * 
 * @since 1.4
 */
function hasloo_user_layout_fields( $user ) {
	
	if ( !current_user_can( 'edit_users', $user->ID ) )
		return false;
		
	$layout = get_the_author_meta( 'layout', $user->ID );
	$layout = $layout ? $layout : '';
	
	?>
	
	<h3><?php _e('Hasloo Layout Options', 'hasloo'); ?></h3>
	<table class="form-table">
	
	<tr>
		<th scope="row" valign="top"><label><?php _e('Choose Layout', 'hasloo'); ?></label></th>
		<td>
		<input type="radio" name="meta[layout]" id="default-layout" value="" <?php checked('', $layout); ?> /> <label class="default" for="default-layout"><?php printf( __('Default Layout set in <a href="%s">Theme Settings</a>', 'hasloo'), admin_url('admin.php?page=hasloo') ); ?></label>
	
		<br style="clear: both;" /><br />
	
		<?php
		foreach ( hasloo_get_layouts() as $id => $data ) {
		
			printf( '<label class="box"><input type="radio" name="meta[layout]" id="%s" value="%s" %s /> <img src="%s" alt="%s" /></label>', esc_attr( $id ), esc_attr( $id ), checked($id, $layout, false), esc_url( $data['img'] ), esc_attr( $data['label'] ) );
		
		}
		?>
	
		<br style="clear: both;" />
		</td>
	</tr>
	
	</table>

<?php }


add_action( 'personal_options_update', 'hasloo_user_meta_save' );
add_action( 'edit_user_profile_update', 'hasloo_user_meta_save' );
/**
 * This function stores/updates user meta when page is saved.
 * 
 * @since 1.4
 */
function hasloo_user_meta_save( $user_id ) {
	
	if ( !current_user_can( 'edit_users', $user_id ) )
		return;
		
	if ( !isset( $_POST['meta'] ) || !is_array( $_POST['meta'] ) )
		return;
		
	$meta = wp_parse_args( $_POST['meta'], array(
		'hasloo_admin_menu' => '',
		'hasloo_seo_settings_menu' => '',
		'hasloo_import_export_menu' => '',
		'hasloo_author_box_single' => '',
		'hasloo_author_box_archive' => '',
		'headline' => '',
		'intro_text' => '',
		'doctitle' => '',
		'meta_description' => '',
		'meta_keywords' => '',
		'noindex' => '',
		'nofollow' => '',
		'noarchive' => '',
		'layout' => ''
	) );
		
	foreach ( $meta as $key => $value ) {
		update_user_meta( $user_id, $key, $value );
	}
	
}


/**
 * This filter function checks to see if user data has actually been saved,
 * or if defaults need to be forced. This filter is useful for user options
 * that need to be "on" by default, but keeps us from having to push defaults
 * into the database, which would be a very expensive task.
 *
 * Yes, this function is hacky. I did the best I could.
 *
 * @since 1.4
 * @author Genetic
 */
function hasloo_user_meta_default_on( $value, $user_id ) {
	
	$field = str_replace( 'get_the_author_', '', current_filter() );
	
	// if a real value exists, simply return it.
	if ( $value ) return $value;
	
	// setup user data
	if ( !$user_id )
		global $authordata;
	else
		$authordata = get_userdata( $user_id );
		
	// just in case
	$user_field = "user_$field";
	if ( isset( $authordata->$user_field ) )
		return $authordata->user_field;
		
	// if an empty or false value exists, return it
	if ( isset( $authordata->$field ) )
		return $value;
	
	// if all that fails, default to true
	return 1;
	
}

add_filter( 'get_the_author_hasloo_admin_menu', 'hasloo_user_meta_default_on', 10, 2 );
add_filter( 'get_the_author_hasloo_seo_settings_menu', 'hasloo_user_meta_default_on', 10, 2 );
add_filter( 'get_the_author_hasloo_import_export_menu', 'hasloo_user_meta_default_on', 10, 2 );

add_filter( 'get_the_author_hasloo_author_box_single', 'hasloo_author_box_single_default_on', 10, 2 );
/**
 * This is a special filter function to be used to conditionally force
 * a default 1 value for each users' author box setting.
 *
 * @since 1.4
 */
function hasloo_author_box_single_default_on( $value, $user_id ) {
	
	if ( hasloo_get_option('author_box_single') )
		return hasloo_user_meta_default_on( $value, $user_id );
	else
		return $value;
	
}