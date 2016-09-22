<?php
/**
 * This file outputs the Hasloo-specific inpost option boxes.
 * It also handles saving the user input from those boxes, when a
 * post or page gets published or updated.
 *
 */

/**
 * This code adds a new box to the post/page edit screen,
 * so that the user can set SEO options on a per-post/page basis.
 *
 * hasloo_add_inpost_seo_box() is used to register the boxes.
 * @uses add_meta_box
 * hasloo_inpost_seo_box() generates the content in the boxes.
 * @uses wp_create_nonce, checked, hasloo_get_custom_field
 *
 * @since 0.1.3
 */
add_action('admin_menu', 'hasloo_add_inpost_seo_box');
function hasloo_add_inpost_seo_box() {
	
	foreach ( (array)get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'hasloo-seo' ) || $type == 'post' || $type = 'page' ) {
			add_meta_box('hasloo_inpost_seo_box', __('Hasloo SEO Options and Settings', 'hasloo'), 'hasloo_inpost_seo_box', $type, 'normal', 'high');
		}	
	}
	
}

function hasloo_inpost_seo_box() { ?>
	
	<input type="hidden" name="hasloo_inpost_seo_nonce" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />
	
	<p><label for="hasloo_title"><b><?php _e('Custom Document Title', 'hasloo'); ?></b> <abbr title="&lt;title&gt; Tag">[?]</abbr> <span class="hide-if-no-js"><?php printf( __('Characters Used: %s', 'hasloo'), '<span id="hasloo_title_chars">'. strlen( hasloo_get_custom_field('_hasloo_title') ) .'</span>' ); ?></span></label></p>
	<p><input style="width: 99%;" type="text" name="hasloo_seo[_hasloo_title]" id="hasloo_title" value="<?php echo esc_attr( hasloo_get_custom_field('_hasloo_title')); ?>" /></p>
	
	<p><label for="hasloo_description"><b><?php _e('Custom Post/Page Meta Description', 'hasloo'); ?></b> <abbr title="&lt;meta name=&quot;description&quot; /&gt;">[?]</abbr> <span class="hide-if-no-js"><?php printf( __('Characters Used: %s', 'hasloo'), '<span id="hasloo_description_chars">'. strlen( hasloo_get_custom_field('_hasloo_description') ) .'</span>' ); ?></span></label></p>
	<p><textarea style="width: 99%;" name="hasloo_seo[_hasloo_description]" id="hasloo_description" rows="4" cols="4"><?php echo htmlspecialchars(hasloo_get_custom_field('_hasloo_description')); ?></textarea></p>
	
	<p><label for="hasloo_keywords"><b><?php _e('Custom Post/Page Meta Keywords, comma separated', 'hasloo'); ?></b> <abbr title="&lt;meta name=&quot;keywords&quot; /&gt;">[?]</abbr></label></p>
	<p><input style="width: 99%;" type="text" name="hasloo_seo[_hasloo_keywords]" id="hasloo_keywords" value="<?php echo esc_attr(hasloo_get_custom_field('_hasloo_keywords')); ?>" /></p>
	
	<p><label for="hasloo_canonical"><b><?php _e('Custom Canonical URI', 'hasloo'); ?></b> <a href="http://www.mattcutts.com/blog/canonical-link-tag/" target="_blank" title="&lt;link rel=&quot;canonical&quot; /&gt;">[?]</a></label></p>
	<p><input style="width: 99%;" type="text" name="hasloo_seo[_hasloo_canonical_uri]" id="hasloo_canonical" value="<?php echo esc_url( hasloo_get_custom_field('_hasloo_canonical_uri') ); ?>" /></p>
	
	<p><label for="hasloo_redirect"><b><?php _e('Custom Redirect URI', 'hasloo'); ?></b> <a href="http://www.google.com/support/webmasters/bin/answer.py?hl=en&amp;answer=93633" target="_blank" title="301 Redirect">[?]</a></label></p>
	<p><input style="width: 99%;" type="text" name="hasloo_seo[redirect]" id="hasloo_redirect" value="<?php echo esc_url( hasloo_get_custom_field('redirect') ); ?>" /></p>
	
	<br />
	
	<p><b><?php _e('Robots Meta Settings', 'hasloo'); ?></b></p>
	
	<p>
		<input type="checkbox" name="hasloo_seo[_hasloo_noindex]" id="hasloo_noindex" value="1" <?php checked(1, hasloo_get_custom_field('_hasloo_noindex')); ?> /> 
		<label for="hasloo_noindex"><?php printf( __('Apply %s to this post/page', 'hasloo'), '<code>noindex</code>' ); ?> <a href="http://www.robotstxt.org/meta.html" target="_blank">[?]</a></label><br />
		
		<input type="checkbox" name="hasloo_seo[_hasloo_nofollow]" id="hasloo_nofollow" value="1" <?php checked(1, hasloo_get_custom_field('_hasloo_nofollow')); ?> /> 
		<label for="hasloo_nofollow"><?php printf( __('Apply %s to this post/page', 'hasloo'), '<code>nofollow</code>' ); ?> <a href="http://www.robotstxt.org/meta.html" target="_blank">[?]</a></label><br />
	
		<input type="checkbox" name="hasloo_seo[_hasloo_noarchive]" id="hasloo_noarchive" value="1" <?php checked(1, hasloo_get_custom_field('_hasloo_noarchive')); ?> /> 
		<label for="hasloo_nofollow"><?php printf( __('Apply %s to this post/page', 'hasloo'), '<code>noarchive</code>' ); ?> <a href="http://www.ezau.com/latest/articles/no-archive.shtml" target="_blank">[?]</a></label>
	</p>
	
	<br />
	
	<p><label for="hasloo_scripts"><b><?php _e('Custom Tracking/Conversion Code', 'hasloo'); ?></b></label></p>
	<p><textarea style="width: 99%;" rows="4" cols="4" name="hasloo_seo[_hasloo_scripts]" id="hasloo_scripts"><?php echo htmlspecialchars(hasloo_get_custom_field('_hasloo_scripts')); ?></textarea></p>

<?php
}

/**
 * This function saves the SEO settings when we save a post/page.
 * It does so by grabbing the array passed in $_POST, looping through
 * it, and saving each key/value pair as a custom field.
 *
 * @uses wp_verify_nonce, plugin_basename, current_user_can
 * @uses add_post_meta, delete_post_meta, get_custom_field
 *
 * @since 0.1.3
 */
add_action('save_post', 'hasloo_inpost_seo_save', 1, 2);
function hasloo_inpost_seo_save($post_id, $post) {
	
	//	verify the nonce
	if ( !isset($_POST['hasloo_inpost_seo_nonce']) || !wp_verify_nonce( $_POST['hasloo_inpost_seo_nonce'], plugin_basename(__FILE__) ) )
		return $post->ID;
		
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;

	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post->ID)) || !current_user_can('edit_post', $post->ID ) )
		return $post->ID;
		
	// Define all as false, to be trumped by user submission
	$seo_post_defaults = array(
		'_hasloo_title' => '',
		'_hasloo_description' => '',
		'_hasloo_keywords' => '',
		'_hasloo_canonical_uri' => '',
		'redirect' => '',
		'_hasloo_noindex' => 0,
		'_hasloo_nofollow' => 0,
		'_hasloo_noarchive' => 0,
		'_hasloo_scripts' => ''
	); 

	$hasloo_seo = wp_parse_args($_POST['hasloo_seo'], $seo_post_defaults);
	
	//	store the custom fields
	foreach ( (array)$hasloo_seo as $key => $value ) {
		
		if ( $post->post_type == 'revision' ) return; // don't try to store data during revision save
		
		//	sanitize the title, description, and tags before storage
		if ( $key == '_hasloo_title' || $key == '_hasloo_description' || $key == '_hasloo_keywords' )
			$value = esc_html( strip_tags( $value ) );
		
		if ( $value ) {
			//	save/update
			update_post_meta($post->ID, $key, $value);
		} else {
			//	delete if blank
			delete_post_meta($post->ID, $key);
		}

	}
}

/**
 * This code adds a new box to the post/page edit screen,
 * so that the user can set layout options on a per-post/page basis.
 *
 * hasloo_add_inpost_layout_box() is used to register the boxes.
 * @uses add_meta_box
 * hasloo_inpost_layout_box() generates the content in the boxes.
 * @uses wp_create_nonce, checked, hasloo_get_custom_field
 *
 * @since 0.2.2
 */
add_action('admin_menu', 'hasloo_add_inpost_layout_box');
function hasloo_add_inpost_layout_box() {
	
	if ( !current_theme_supports('hasloo-inpost-layouts') )
		return;
	
	foreach ( (array)get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'hasloo-layouts' ) || $type == 'post' || $type = 'page' ) {
			add_meta_box('hasloo_inpost_layout_box', __('Genesis Layout Options', 'hasloo'), 'hasloo_inpost_layout_box', $type, 'normal', 'high');
		}	
	}

}
function hasloo_inpost_layout_box() { ?>

	<?php wp_nonce_field( plugin_basename(__FILE__), 'hasloo_inpost_layout_nonce' ); ?>
	
	<?php $layout = hasloo_get_custom_field('_hasloo_layout'); ?>
	
	<input type="radio" name="_hasloo_layout" id="default-layout" value="" <?php checked('', $layout); ?> /> <label class="default" for="default-layout"><?php printf( __('Default Layout set in <a href="%s">Theme Settings</a>', 'hasloo'), admin_url('admin.php?page=hasloo') ); ?></label>
	
	<br style="clear: both;" /><br />
	
	<?php
	foreach ( hasloo_get_layouts() as $id => $data ) {
		
		printf( '<label class="box"><input type="radio" name="_hasloo_layout" id="%s" value="%s" %s /> <img src="%s" alt="%s" /></label>', esc_attr( $id ), esc_attr( $id ), checked($id, $layout, false), esc_url( $data['img'] ), esc_attr( $data['label'] ) );
		
	}
	?>
	
	<br style="clear: both;" />
	
	<p><label for="hasloo_custom_body_class"><b><?php _e('Custom Body Class', 'hasloo'); ?></b></label></p>
	<p><input style="width: 99%;" type="text" name="_hasloo_custom_body_class" id="hasloo_custom_body_class" value="<?php echo esc_attr( sanitize_html_class( hasloo_get_custom_field('_hasloo_custom_body_class') ) ); ?>" /></p>
	
	<p><label for="hasloo_custom_post_class"><b><?php _e('Custom Post Class', 'hasloo'); ?></b></label></p>
	<p><input style="width: 99%;" type="text" name="_hasloo_custom_post_class" id="hasloo_custom_post_class" value="<?php echo esc_attr( sanitize_html_class( hasloo_get_custom_field('_hasloo_custom_post_class') ) ); ?>" /></p>
	
<?php
}

/**
 * This function saves the layout options when we save a post/page.
 * It does so by grabbing the array passed in $_POST, looping through
 * it, and saving each key/value pair as a custom field.
 *
 * @uses wp_verify_nonce, plugin_basename, current_user_can
 * @uses add_post_meta, delete_post_meta, get_custom_field
 *
 * @since 0.2.2
 */
add_action('save_post', 'hasloo_inpost_layout_save', 1, 2);
function hasloo_inpost_layout_save($post_id, $post) {
	
	//	verify the nonce
	if ( !isset($_POST['hasloo_inpost_layout_nonce']) || !wp_verify_nonce( $_POST['hasloo_inpost_layout_nonce'], plugin_basename(__FILE__) ) )
		return $post_id;
		
	//	don't try to save the data under autosave, ajax, or future post.
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;
	if ( defined('DOING_AJAX') && DOING_AJAX ) return;
	if ( defined('DOING_CRON') && DOING_CRON ) return;

	//	is the user allowed to edit the post or page?
	if ( ('page' == $_POST['post_type'] && !current_user_can('edit_page', $post_id)) || !current_user_can('edit_post', $post_id ) )
		return $post_id;
		
	/**
	 * Save all the layout/class data
	 *
	 */
	$hasloo_layout = $_POST['_hasloo_layout'];
	
	if ( $hasloo_layout ) {
		//	save/update
		update_post_meta($post_id, '_hasloo_layout', $hasloo_layout);
	} else {
		//	delete if blank
		delete_post_meta($post_id, '_hasloo_layout');
	}
	
	$hasloo_custom_body_class = $_POST['_hasloo_custom_body_class'];
	
	if ( $hasloo_custom_body_class ) {
		//	save/update
		update_post_meta($post_id, '_hasloo_custom_body_class', $hasloo_custom_body_class);
	} else {
		//	delete if blank
		delete_post_meta($post_id, '_hasloo_custom_body_class');
	}
	
	$hasloo_custom_post_class = $_POST['_hasloo_custom_post_class'];
	
	if ( $hasloo_custom_post_class ) {
		//	save/update
		update_post_meta($post_id, '_hasloo_custom_post_class', $hasloo_custom_post_class);
	} else {
		//	delete if blank
		delete_post_meta($post_id, '_hasloo_custom_post_class');
	}
	
}