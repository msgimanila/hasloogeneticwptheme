<?php
/**
 * This function registers the default values for Genesis SEO Settings
 */
function hasloo_seo_settings_defaults() {
	$defaults = array( // define our defaults
		'append_description_home' => 1,
		'append_site_title' => 0,
		'doctitle_sep' => '—',
		'doctitle_seplocation' => 'right',
		
		'home_h1_on' => 'title',
		'home_doctitle' => '',
		'home_description' => '',
		'home_keywords' => '',
		'home_noindex' => 0,
		'home_nofollow' => 0,
		'home_noarchive' => 0,
		
		'canonical_archives' => 1,
		
		'head_index_rel_link' => 0,
		'head_parent_post_rel_link' => 0,
		'head_start_post_rel_link' => 0,
		'head_adjacent_posts_rel_link' => 0,
		'head_wlwmanifest_link' => 0,
		'head_shortlink' => 0,
		
		'noindex_cat_archive' => 1,
		'noindex_tag_archive' => 1,
		'noindex_author_archive' => 1,
		'noindex_date_archive' => 1,
		'noindex_search_archive' => 1,
		'noarchive_cat_archive' => 0,
		'noarchive_tag_archive' => 0,
		'noarchive_author_archive' => 0,
		'noarchive_date_archive' => 0,
		'noarchive_search_archive' => 0,
		'noarchive' => 0,
		'noodp' => 1,
		'noydir' => 1 // <-- no comma after last option
	);
	
	return apply_filters('hasloo_seo_settings_defaults', $defaults);
}

/**
 * This registers the settings field and adds defaults to the options table
 */
add_action('admin_init', 'hasloo_register_seo_settings');
function hasloo_register_seo_settings() {
	register_setting( HASLOO_SEO_SETTINGS_FIELD, HASLOO_SEO_SETTINGS_FIELD );
	add_option( HASLOO_SEO_SETTINGS_FIELD, hasloo_seo_settings_defaults() );
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'seo-settings' )
		return;
		
	if ( hasloo_get_seo_option('reset') ) {
		update_option(HASLOO_SEO_SETTINGS_FIELD, hasloo_seo_settings_defaults());
		wp_redirect( admin_url( 'admin.php?page=seo-settings&reset=true' ) );
		exit();
	}
}

/**
 * This is the notice that displays when you successfully save or reset
 * the SEO settings.
 */
add_action('admin_notices', 'hasloo_seo_settings_notice');
function hasloo_seo_settings_notice() {
	
	if ( !isset($_REQUEST['page']) || $_REQUEST['page'] != 'seo-settings' )
		return;
	
	if ( isset( $_REQUEST['reset'] ) && $_REQUEST['reset'] == 'true' ) {
		echo '<div id="message" class="updated" id="message"><p><strong>'.__('SEO Settings Reset', 'hasloo').'</strong></p></div>';
	}
	elseif ( isset($_REQUEST['updated']) && $_REQUEST['updated'] == 'true') {  
		echo '<div id="message" class="updated" id="message"><p><strong>'.__('SEO Settings Saved', 'hasloo').'</strong></p></div>';
	}
	
}

/**
 * This is a necessary go-between to get our scripts and boxes loaded
 * on the theme settings page only, and not the rest of the admin
 */
add_action('admin_menu', 'hasloo_seo_settings_init');
function hasloo_seo_settings_init() {
	global $_hasloo_seo_settings_pagehook;
	
	add_action('load-'.$_hasloo_seo_settings_pagehook, 'hasloo_seo_settings_scripts');
	add_action('load-'.$_hasloo_seo_settings_pagehook, 'hasloo_seo_settings_boxes');
}

function hasloo_seo_settings_scripts() {	
	wp_enqueue_script('common');
	wp_enqueue_script('wp-lists');
	wp_enqueue_script('postbox');
}

function hasloo_seo_settings_boxes() {
	global $_hasloo_seo_settings_pagehook;
	
	add_meta_box('hasloo-seo-settings-doctitle', __('Doctitle Settings', 'hasloo'), 'hasloo_seo_settings_doctitle_box', $_hasloo_seo_settings_pagehook, 'column1');
	add_meta_box('hasloo-seo-settings-homepage', __('Homepage Settings', 'hasloo'), 'hasloo_seo_settings_homepage_box', $_hasloo_seo_settings_pagehook, 'column1');
	add_meta_box('hasloo-seo-settings-archives', __('Archives Settings', 'hasloo'), 'hasloo_seo_settings_archives_box', $_hasloo_seo_settings_pagehook, 'column1');
	add_meta_box('hasloo-seo-settings-dochead', __('Document Head Settings', 'hasloo'), 'hasloo_seo_settings_document_head_box', $_hasloo_seo_settings_pagehook, 'column2');
	add_meta_box('hasloo-seo-settings-robots', __('Robots Meta Settings', 'hasloo'), 'hasloo_seo_settings_robots_meta_box', $_hasloo_seo_settings_pagehook, 'column2');
	add_meta_box('hasloo-seo-settings-nofollow', __('Link nofollow Settings', 'hasloo'), 'hasloo_seo_settings_nofollow_box', $_hasloo_seo_settings_pagehook, 'column2');
}

/**
 * Tell WordPress that we want only 2 columns available for our meta-boxes
 */
add_filter('screen_layout_columns', 'hasloo_seo_settings_layout_columns', 10, 2);
function hasloo_seo_settings_layout_columns($columns, $screen) {
	global $_hasloo_seo_settings_pagehook;
	if ($screen == $_hasloo_seo_settings_pagehook) {
		// This page should only have 2 column options
		$columns[$_hasloo_seo_settings_pagehook] = 2;
	}
	return $columns;
}

/**
 * This function is what actually gets output to the page. It handles the markup,
 * builds the form, outputs necessary JS stuff, and fires <code>do_meta_boxes()</code>
 */
function hasloo_seo_settings_admin() { 
global $_hasloo_seo_settings_pagehook, $screen_layout_columns;
if( $screen_layout_columns == 3 ) {
	$width = 'width: 32.67%';
	$hide2 = $hide3 = ' display: block;';
}
elseif( $screen_layout_columns == 2 ) {
	$width = 'width: 49%;';
	$hide2 = ' display: block;';
	$hide3 = ' display: none;';
}
else {
	$width = 'width: 99%;';
	$hide2 = $hide3 = ' display: none;';
}
?>	
	<div id="hasloo-seo-settings" class="wrap hasloo-metaboxes">
	<form method="post" action="options.php">
		
		<?php wp_nonce_field('closedpostboxes', 'closedpostboxesnonce', false ); ?>
		<?php wp_nonce_field('meta-box-order', 'meta-box-order-nonce', false ); ?>
		<?php settings_fields(HASLOO_SEO_SETTINGS_FIELD); // important! ?>
		
		<?php screen_icon('options-general'); ?>
		<h2>
			<?php _e('Hasloo - SEO Settings', 'hasloo'); ?>
			<input type="submit" class="button-primary add-new-h2" value="<?php _e('Save Settings', 'hasloo') ?>" />
			<input type="submit" class="button-highlighted add-new-h2" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[reset]" value="<?php _e('Reset Settings', 'hasloo'); ?>" onclick="return hasloo_confirm('<?php echo esc_js( __('Are you sure you want to reset?', 'hasloo') ); ?>');" />
		</h2>
		
		<div class="metabox-holder">
			<div class="postbox-container" style="<?php echo $width; ?>">
				<?php do_meta_boxes($_hasloo_seo_settings_pagehook, 'column1', null); ?>
			</div>
			<div class="postbox-container" style="<?php echo $width; echo $hide2; ?>">
				<?php do_meta_boxes($_hasloo_seo_settings_pagehook, 'column2', null); ?>
			</div>
		</div>
		
		<div class="bottom-buttons">
			<input type="submit" class="button-primary" value="<?php _e('Save Settings', 'hasloo') ?>" />
			<?php $reset_onclick = 'onclick="if ( confirm(\'' . esc_js( __('Are you sure you want to reset?', 'hasloo') ) . '\') ) {return true;}return false;"'; ?>
			<input type="submit" <?php echo $reset_onclick; ?> class="button-highlighted" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[reset]" value="<?php _e('Reset Settings', 'hasloo'); ?>" />
		</div>
	</form>
	</div>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('<?php echo $_hasloo_seo_settings_pagehook; ?>');
		});
		//]]>
	</script>

<?php
}

/**
 * This next section defines functions that contain the content of the "boxes" that will be
 * output by default on the "SEO Settings" page. There's a bunch of them.
 *
 */
function hasloo_seo_settings_doctitle_box() { ?>
	
	<p><span class="description"><?php _e('The Document Title is the single most important SEO tag in your document source. It succinctly informs search engines of what information is contained in the document. The doctitle changes from page to page, but these options will help you control what it looks by default.', 'hasloo'); ?></span></p>
	
	<p><span class="description"><?php _e('<b>By default</b>, the homepage doctitle will contain the site title, the single post and page doctitle will contain the post/page title, archive pages will contain the archive type, etc.', 'hasloo'); ?></span></p>
	
	<p><label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[append_description_home]" value="1" <?php checked(1, hasloo_get_seo_option('append_description_home')); ?> /> <?php _e('Append Site Description to Doctitle on homepage?', 'hasloo'); ?></label></p>
	
	<p><label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[append_site_title]" value="1" <?php checked(1, hasloo_get_seo_option('append_site_title')); ?> /> <?php _e('Append Site Name to Doctitle on inner pages?', 'hasloo'); ?> </label></p>
	
	<p><?php _e('Doctitle (<code>&lt;title&gt;</code>) Append Location', 'hasloo'); ?>:<br />
	<span class="description"><?php _e('Determines what side the appended doctitle text will go on', 'hasloo'); ?></span></p>
	
	<p><label><input type="radio" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[doctitle_seplocation]" value="left" <?php checked('left', hasloo_get_seo_option('doctitle_seplocation')); ?> />
	<?php _e('Left', 'hasloo'); ?></label>
	<label><input type="radio" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[doctitle_seplocation]" value="right" <?php checked('right', hasloo_get_seo_option('doctitle_seplocation')); ?> />
	<?php _e('Right', 'hasloo'); ?></label></p>
	
	<p><?php _e('Doctitle (<code>&lt;title&gt;</code>) Separator', 'hasloo'); ?>:
	<input type="text" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[doctitle_sep]" value="<?php echo esc_attr( hasloo_get_seo_option('doctitle_sep') ); ?>" size="15" /></p>
	
	<p><span class="description"><?php _e('<b>NOTE:</b> If the doctitle consists of two parts (Title &amp; Appended Text), then the Doctitle Separator will go between them.', 'hasloo'); ?></span></p>
	
<?php
}

function hasloo_seo_settings_homepage_box() { ?>
	
	<p><?php printf(__('Which text would you like to be wrapped in %s tags?', 'hasloo'), '<code>&lt;h1&gt;</code>'); ?><br />
	<span class="description"><?php printf(__('The %s tag is, arguably, the second most important SEO tag in the document source. Choose wisely.', 'hasloo'), '<code>&lt;h1&gt;</code>'); ?></span><br /></p>
	
	<p><label><input type="radio" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_h1_on]" value="title" <?php checked('title', hasloo_get_seo_option('home_h1_on')); ?> />
	<?php _e('Site Title', 'hasloo'); ?></label><br />
	<label><input type="radio" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_h1_on]" value="description" <?php checked('description', hasloo_get_seo_option('home_h1_on')); ?> />
	<?php _e('Site Description', 'hasloo'); ?></label><br />
	<label><input type="radio" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_h1_on]" value="neither" <?php checked('neither', hasloo_get_seo_option('home_h1_on')); ?> />
	<?php _e('Neither. I\'ll manually wrap my own text on the homepage', 'hasloo'); ?></label></p>
	
	<p><?php _e('Home Doctitle', 'hasloo'); ?>:<br />
	<input type="text" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_doctitle]" value="<?php echo esc_attr( hasloo_get_seo_option('home_doctitle') ); ?>" size="40" /></p>
	
	<p><span class="description"><?php _e('<b>NOTE:</b> If you leave the doctitle field blank, your site&rsquo;s title will be used instead.', 'hasloo'); ?></span></p>
	
	<p><?php _e('Home META Description', 'hasloo'); ?>:<br />
	<textarea name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_description]" rows="3" cols="34"><?php echo htmlspecialchars( hasloo_get_seo_option('home_description') ); ?></textarea></p>
	
	<p><span class="description"><?php _e('<b>NOTE:</b> The META Description can be used to determine the text used under the title on search engine results pages.', 'hasloo'); ?></span></p>
	
	<p><?php _e('Home META Keywords (comma separated)', 'hasloo'); ?>:<br />
	<input type="text" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_keywords]" value="<?php echo esc_attr( hasloo_get_seo_option('home_keywords') ); ?>" size="40" /></p>
	
	<p><span class="description"><?php _e('<b>NOTE:</b> Keywords are generally ignored by Search Engines.', 'hasloo'); ?></span></p>
	
	<p><?php _e('Homepage Robots Meta Tags:', 'hasloo'); ?><p>
	
	<p>
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_noindex]" value="1" <?php checked(1, hasloo_get_seo_option('home_noindex')); ?> /> <?php printf( __('Apply %s to the homepage?', 'hasloo'), '<code>noindex</code>' ); ?> </label><br />
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_nofollow]" value="1" <?php checked(1, hasloo_get_seo_option('home_nofollow')); ?> /> <?php printf( __('Apply %s to the homepage?', 'hasloo'), '<code>nofollow</code>' ); ?> </label><br />
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[home_noarchive]" value="1" <?php checked(1, hasloo_get_seo_option('home_noarchive')); ?> /> <?php printf( __('Apply %s to the homepage?', 'hasloo'), '<code>noarchive</code>' ); ?> </label>
	</p>
	
<?php
}

function hasloo_seo_settings_archives_box() { ?>
	
	<p><label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[canonical_archives]" value="1" <?php checked(1, hasloo_get_seo_option('canonical_archives')); ?> /> <?php printf( __('Canonical Paginated Archives', 'hasloo') ); ?> </label></p>
	
	<p><span class="description"><?php _e('This option points search engines to the first page of an archive, if viewing a paginated page. If you do not know what this means, leave it on.', 'hasloo'); ?></span></p>
	
<?php
}

function hasloo_seo_settings_document_head_box() { ?>

	<p><span class="description"><?php printf( __('By default, WordPress places several tags in your document %1$s. Most of these tags are completely unnecessary, and provide no SEO value whatsoever. They just make your site slower to load. Choose which tags you would like included in your document %1$s. If you do not know what something is, leave it unchecked.', 'hasloo'), '<code>&lt;head&gt;</code>' ); ?></span></p>
	
	<p><b><?php _e('Relationship Link Tags:', 'hasloo'); ?></b></p>
	
	<p>
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[head_index_rel_link]" value="1" <?php checked(1, hasloo_get_seo_option('head_index_rel_link')); ?> /> <?php printf( __('Index %s link tag', 'hasloo'), '<code>rel</code>' ); ?></label><br />
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[head_parent_post_rel_link]" value="1" <?php checked(1, hasloo_get_seo_option('head_parent_post_rel_link')); ?> /> <?php printf( __('Parent Post %s link tag', 'hasloo'), '<code>rel</code>' ); ?></label><br />
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[head_start_post_rel_link]" value="1" <?php checked(1, hasloo_get_seo_option('head_start_post_rel_link')); ?> /> <?php printf( __('Start Post %s link tag', 'hasloo'), '<code>rel</code>' ); ?></label><br />
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[head_adjacent_posts_rel_link]" value="1" <?php checked(1, hasloo_get_seo_option('head_adjacent_posts_rel_link')); ?> /> <?php printf( __('Adjacent Posts %s link tag', 'hasloo'), '<code>rel</code>' ); ?></label>
	</p>
	
	<p><b><?php _e('Windows Live Writer Support:', 'hasloo'); ?></b></p>
	
	<p><label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[head_wlwmanifest_link]" value="1" <?php checked(1, hasloo_get_seo_option('head_wlwmanifest_link')); ?> /> <?php printf( __('Include Windows Live Writer Support Tag?', 'hasloo') ); ?></label></p>
	
	<p><b><?php _e('Shortlink Tag:', 'hasloo'); ?></b></p>
	
	<p><label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[head_shortlink]" value="1" <?php checked(1, hasloo_get_seo_option('head_shortlink')); ?> /> <?php printf( __('Include Shortlink tag?', 'hasloo') ); ?></label></p>
	
	<p><span class="description"><?php _e('<b>NOTE:</b> The shortlink tag might have some use for 3rd party service discoverability, but it has no SEO value whatsoever.', 'hasloo'); ?></span></p>
	
<?php
}

function hasloo_seo_settings_robots_meta_box() { ?>
	
	<p><span class="description"><?php _e('Depending on your situation, you may or may not want the following archive pages to be indexed by search engines. Only you can make that determination.', 'hasloo'); ?></span></p>
	
	<p><label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noindex_cat_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noindex_cat_archive')); ?> /> <?php printf( __('Apply %s to Category Archives?', 'hasloo'), '<code>noindex</code>' ); ?></label><br />
	<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noindex_tag_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noindex_tag_archive')); ?> /> <?php printf( __('Apply %s to Tag Archives?', 'hasloo'), '<code>noindex</code>' ); ?></label><br />
	<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noindex_author_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noindex_author_archive')); ?> /> <?php printf( __('Apply %s to Author Archives?', 'hasloo'), '<code>noindex</code>' ); ?></label><br />
	<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noindex_date_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noindex_date_archive')); ?> /> <?php printf( __('Apply %s to Date Archives?', 'hasloo'), '<code>noindex</code>' ); ?></label><br />
	<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noindex_search_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noindex_search_archive')); ?> /> <?php printf( __('Apply %s to Search Archives?', 'hasloo'), '<code>noindex</code>' ); ?></label></p>
	
	<p><span class="description"><?php printf( __('Some search engines will cache pages in your site (e.g Google Cache). The %1$s tag will prevent them from doing so. Choose what archives you want to %1$s.', 'hasloo'), '<code>noarchive</code>' ); ?></span></p>
	
	<p><label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noarchive]" value="1" <?php checked(1, hasloo_get_seo_option('noarchive')); ?> /> <?php printf( __('Apply %s to Entire Site?', 'hasloo'), '<code>noarchive</code>' ); ?></label></p>
	
	<p><label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noarchive_cat_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noarchive_cat_archive')); ?> /> <?php printf( __('Apply %s to Category Archives?', 'hasloo'), '<code>noarchive</code>' ); ?></label><br />
	<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noarchive_tag_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noarchive_tag_archive')); ?> /> <?php printf( __('Apply %s to Tag Archives?', 'hasloo'), '<code>noarchive</code>' ); ?></label><br />
	<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noarchive_author_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noarchive_author_archive')); ?> /> <?php printf( __('Apply %s to Author Archives?', 'hasloo'), '<code>noarchive</code>' ); ?></label><br />
	<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noarchive_date_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noarchive_date_archive')); ?> /> <?php printf( __('Apply %s to Date Archives?', 'hasloo'), '<code>noarchive</code>' ); ?></label><br />
	<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noarchive_search_archive]" value="1" <?php checked(1, hasloo_get_seo_option('noarchive_search_archive')); ?> /> <?php printf( __('Apply %s to Search Archives?', 'hasloo'), '<code>noarchive</code>' ); ?></label></p>
	
	<p><span class="description"><?php printf( __('Occasionally, search engines use resources like the Open Directory Project and the Yahoo! Directory to find titles and descriptions for your content. Generally, you will not want them to do this. The %s and %s tags prevent them from doing so.', 'hasloo'), '<code>noodp</code>', '<code>noydir</code>' ); ?></span></p>
	
	<p>
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noodp]" value="1" <?php checked(1, hasloo_get_seo_option('noodp')); ?> /> <?php printf( __('Apply %s to your site?', 'hasloo'), '<code>noodp</code>' ) ?></label><br />
		<label><input type="checkbox" name="<?php echo HASLOO_SEO_SETTINGS_FIELD; ?>[noydir]" value="1" <?php checked(1, hasloo_get_seo_option('noydir')); ?> /> <?php printf( __('Apply %s to your site?', 'hasloo'), '<code>noydir</code>' ) ?></label>
	<p>
	
<?php
}

function hasloo_seo_settings_nofollow_box() { ?>
	
	<p><span class="description"><?php printf( __('<b>NOTE:</b> Don&apos;t be alarmed. We have deprecated these settings, because according to the <a href="%s" target="_blank">latest information available</a>, applying %s to internal links provides no SEO value to your site.', 'hasloo'), 'http://www.mattcutts.com/blog/pagerank-sculpting/', '<code>nofollow</code>' ); ?></span></p>
	
<?php
}