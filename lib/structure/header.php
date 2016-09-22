<?php
/**
 * This function handles the doctype. If you are going to replace the
 * doctype with a custom one, you must remember to include the opening
 * <html> and <head> elements too, along with the proper properties.
 *
 * It would be beneficial to also include the <meta> tag for Content Type.
 *
 * The default doctype is xHTML v1.0 Transitional.
 *
 * @since 1.3
 */
add_action('hasloo_doctype', 'hasloo_do_doctype');
function hasloo_do_doctype() { 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes('xhtml'); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<?php
}

/**
 * Remove unnecessary code that WordPress puts in the <head>
 *
 * @since 1.3
 * @uses remove_action(), hasloo_get_seo_option()
 */
add_action('get_header', 'hasloo_doc_head_control');
function hasloo_doc_head_control() {
	
	remove_action( 'wp_head', 'wp_generator' );
		
	if ( !hasloo_get_seo_option('head_index_rel_link') )
		remove_action( 'wp_head', 'index_rel_link' );
	
	if ( !hasloo_get_seo_option('head_parent_post_rel_link') )
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	
	if ( !hasloo_get_seo_option('head_start_post_rel_link') )
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	
	if ( !hasloo_get_seo_option('head_adjacent_posts_rel_link') )
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
		
	if ( !hasloo_get_seo_option('head_wlwmanifest_link') )
		remove_action( 'wp_head', 'wlwmanifest_link' );
	
	if ( !hasloo_get_seo_option('head_shortlink') )
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

	if ( is_single() && !hasloo_get_option('comments_posts') )
		remove_action( 'wp_head', 'feed_links_extra', 3 );
		
	if ( is_page() && !hasloo_get_option('comments_pages') )
		remove_action( 'wp_head', 'feed_links_extra', 3 );

}

/**
 * This function outputs our site title in the #header.
 * Depending on the SEO option set by the user, this will
 * either be wrapped in <h1> or <p> tags.
 */
add_action('hasloo_site_title', 'hasloo_seo_site_title');
function hasloo_seo_site_title() {
	// Set what goes inside the wrapping tags
	$inside = sprintf( '<a href="%s" title="%s">%s</a>', trailingslashit( get_bloginfo('url') ), esc_attr( get_bloginfo('name') ), get_bloginfo('name') );
	
	// Determine which wrapping tags to use
	$wrap = is_home() && hasloo_get_seo_option('home_h1_on') == 'title' ? 'h1' : 'p';
	
	// A little fallback, in case an SEO plugin is active
	$wrap = is_home() && !hasloo_get_seo_option('home_h1_on') ? 'h1' : $wrap;

	// Build the Title
	$title = sprintf('<%s id="title">%s</%s>', $wrap, $inside, $wrap);
	
	// Return (filtered)
	echo apply_filters('hasloo_seo_title', $title, $inside, $wrap);
}

/**
 * This function outputs our site description in the #header.
 * Depending on the SEO option set by the user, this will
 * either be wrapped in <h1> or <p> tags.
 */
add_action('hasloo_site_description', 'hasloo_seo_site_description');
function hasloo_seo_site_description() {
	// Set what goes inside the wrapping tags
	$inside = esc_html ( get_bloginfo( 'description' ) );
	
	// Determine which wrapping tags to use
	$wrap = is_home() && hasloo_get_seo_option('home_h1_on') == 'description' ? 'h1' : 'p';

	// Build the Description
	$description = sprintf('<%s id="description">%s</%s>', $wrap, $inside, $wrap);
	
	// Return (filtered)
	echo apply_filters('hasloo_seo_description', $description, $inside, $wrap);
}

/**
 * This function wraps the doctitle in <title></title> tags
 */
add_filter('wp_title', 'hasloo_doctitle_wrap', 20);
function hasloo_doctitle_wrap( $title ) {
	return is_feed() ? $title : sprintf( "<title>%s</title>\n", $title );
}

/**
 * This function does 3 things:
 * 1. Pulls the values for $sep and $seplocation, uses defaults if necessary
 * 2. Determines if the site title should be appended
 * 3. Allows the user to set a custom title on a per-page/post basis
 *
 * @since 0.1.3
 */
add_action('hasloo_title', 'wp_title');
add_filter('wp_title', 'hasloo_default_title', 10, 3);
function hasloo_default_title($title, $sep, $seplocation) {
	global $wp_query;
	
	if ( is_feed() ) return trim( $title );
	
	$sep = hasloo_get_seo_option('doctitle_sep') ? hasloo_get_seo_option('doctitle_sep') : '–';
	$seplocation = hasloo_get_seo_option('doctitle_seplocation') ? hasloo_get_seo_option('doctitle_seplocation') : 'right';
	
	//	if viewing the homepage
	if ( is_front_page() ) {
		// determine the doctitle
		$title = hasloo_get_seo_option('home_doctitle') ? hasloo_get_seo_option('home_doctitle') : get_bloginfo('name');
		
		// append site description, if necessary
		$title = hasloo_get_seo_option('append_description_home') ? $title." $sep ".get_bloginfo('description') : $title;
	}
	
	//	if viewing a post/page/attachment
	if ( is_singular() ) {
		//	The User Defined Title (Genesis)
		if ( hasloo_get_custom_field('_hasloo_title') ) {
			$title = hasloo_get_custom_field('_hasloo_title');
		}
		//	All-in-One SEO Pack Title (latest, vestigial)
		elseif ( hasloo_get_custom_field('_aioseop_title') ) {
			$title = hasloo_get_custom_field('_aioseop_title');
		}
		//	Headspace Title (vestigial)	
		elseif ( hasloo_get_custom_field('_headspace_page_title') ) {
			$title = hasloo_get_custom_field('_headspace_page_title');
		}
		//	Thesis Title (vestigial)	
		elseif ( hasloo_get_custom_field('thesis_title') ) {
			$title = hasloo_get_custom_field('thesis_title');
		}
		//	SEO Title Tag (vestigial)
		elseif ( hasloo_get_custom_field('title_tag') ) {
			$title = hasloo_get_custom_field('title_tag');
		}
		//	All-in-One SEO Pack Title (old, vestigial)
		elseif ( hasloo_get_custom_field('title') ) {
			$title = hasloo_get_custom_field('title');
		}
	}
	
	if ( is_category() ) {
		//$term = get_term( get_query_var('cat'), 'category' );
		$term = $wp_query->get_queried_object();
		
		$title = !empty( $term->meta['doctitle'] ) ? $term->meta['doctitle'] : $title;
	}
	
	if ( is_tag() ) {
		//$term = get_term( get_query_var('tag_id'), 'post_tag' );
		$term = $wp_query->get_queried_object();
		
		$title = !empty( $term->meta['doctitle'] ) ? $term->meta['doctitle'] : $title;
	}
	
	if ( is_tax() ) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$title = !empty( $term->meta['doctitle'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['doctitle'] ) ) : $title;
	}
	
	if ( is_author() ) {
		$user_title = get_the_author_meta( 'doctitle', (int)get_query_var('author') );
		
		$title = $user_title ? $user_title : $title;
	}
	
	//	if we don't want site name appended, or if we're on the homepage
	if ( !hasloo_get_seo_option('append_site_title') || is_front_page() )
		return esc_html ( trim( $title ) );
	
	// else
	$title = $seplocation == 'right' ? $title." $sep ".get_bloginfo('name') : get_bloginfo('name')." $sep ".$title;
		return esc_html( trim( $title ) );
}

/**
 * This function generates the <code>META</code> Description based
 * on contextual criteria. Outputs nothing if description isn't there.
 *
 * @since 1.2
 * @todo Add vestigial Thesis support (in 1.3)
 */
add_action('hasloo_meta','hasloo_seo_meta_description');
function hasloo_seo_meta_description() {
	global $wp_query, $post;
	
	$description = '';
	
	// if we're on the homepage
	if ( is_front_page() ) {
		$description = hasloo_get_seo_option('home_description') ? hasloo_get_seo_option('home_description') : get_bloginfo('description');
	}
	
	// if we're on a single post/page/attachment
	if ( is_singular() ) {
		// else if description is set via custom field
		if ( hasloo_get_custom_field('_hasloo_description') ) {
			$description = hasloo_get_custom_field('_hasloo_description');
		}
		// else if the user used All-in-One SEO Pack (latest, vestigial)
		elseif ( hasloo_get_custom_field('_aioseop_description') ) {
			$description = hasloo_get_custom_field('_aioseop_description');
		}
		// else if the user used Headspace2 (vestigial)
		elseif ( hasloo_get_custom_field('_headspace_description') ) {
			$description = hasloo_get_custom_field('_headspace_description');
		}
		// else if the user used Thesis (vestigial)
		elseif ( hasloo_get_custom_field('thesis_description') ) {
			$description = hasloo_get_custom_field('thesis_description');
		}
		// else if the user used All-in-One SEO Pack (old, vestigial)
		elseif ( hasloo_get_custom_field('description') ) {
			$description = hasloo_get_custom_field('description');
		}
	}
	
	// if we're on a category archive
	if ( is_category() ) {
		//$term = get_term( get_query_var('cat'), 'category' );
		$term = $wp_query->get_queried_object();
		
		$description = !empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}
	
	// if we're on a tag archive
	if ( is_tag() ) {
		//$term = get_term( get_query_var('tag_id'), 'post_tag' );
		$term = $wp_query->get_queried_object();
		
		$description = !empty( $term->meta['description'] ) ? $term->meta['description'] : '';
	}
	
	// if we're on a taxonomy archive
	if ( is_tax() ) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$description = !empty( $term->meta['description'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['description'] ) ) : '';
	}
	
	// if we're on an author archive
	if ( is_author() ) {
		$user_description = get_the_author_meta( 'meta_description', (int)get_query_var('author') );
		
		$description = $user_description ? $user_description : '';
	}
	
	// Add the description, but only if one exists
	if ( !empty($description) ) {
		echo '<meta name="description" content="'.esc_attr( $description ).'" />'."\n";
	}

}

/**
 * This function generates the <code>META</code> Keywords based
 * on contextual criteria. Outputs nothing if keywords aren't there.
 * 
 * @since 1.2
 * @todo Add vestigial Thesis support (in 1.3)
 */
add_action('hasloo_meta', 'hasloo_seo_meta_keywords');
function hasloo_seo_meta_keywords() {
	global $wp_query, $post;
	
	$keywords = '';
	
	// if we're on the homepage
	if( is_front_page() ) {
		
		$keywords = hasloo_get_seo_option('home_keywords');
		
	}
	
	// if we're on a single post/page/attachment
	if ( is_singular() ) {
		
		// if keywords are set via custom field
		if ( hasloo_get_custom_field('_hasloo_keywords') ) {
			$keywords = hasloo_get_custom_field('_hasloo_keywords');
		}
		// else if keywords are set via All-in-One SEO Pack (latest, vestigial)
		elseif ( hasloo_get_custom_field('_aioseop_keywords') ) {
			$keywords = hasloo_get_custom_field('_aioseop_keywords');
		}
		// else if keywords are set via Thesis (vestigial)
		elseif ( hasloo_get_custom_field('thesis_keywords') ) {
			$keywords = hasloo_get_custom_field('thesis_keywords');
		}
		// else if keywords are set via All-in-One SEO Pack (old, vestigial)
		elseif ( hasloo_get_custom_field('keywords') ) {
			$keywords = hasloo_get_custom_field('keywords');
		}

	}
	
	// if we're on a category archive
	if ( is_category() ) {
		//$term = get_term( get_query_var('cat'), 'category' );
		$term = $wp_query->get_queried_object();
		
		$keywords = !empty( $term->meta['keywords'] ) ? $term->meta['keywords'] : '';
	}
	
	// if we're on a tag archive
	if ( is_tag() ) {
		//$term = get_term( get_query_var('tag_id'), 'post_tag' );
		$term = $wp_query->get_queried_object();
		
		$keywords = !empty( $term->meta['keywords'] ) ? $term->meta['keywords'] : '';
	}
	
	// if we're on a taxonomy archive
	if ( is_tax() ) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$keywords = !empty( $term->meta['keywords'] ) ? wp_kses_stripslashes( wp_kses_decode_entities( $term->meta['keywords'] ) ) : '';
	}
	
	// if we're on an author archive
	if ( is_author() ) {
		$user_keywords = get_the_author_meta( 'meta_keywords', (int)get_query_var('author') );
		
		$keywords = $user_keywords ? $user_keywords : '';
	}
	
	// return nothing, if no keywords set
	if ( empty( $keywords ) )
		return;
	
	// Add the keywords, but only if they exist	
	echo '<meta name="keywords" content="'.esc_attr( $keywords ).'" />'."\n";
	
}

/**
 * This function generates the index/follow/noodp/noydir/noarchive code in the document <head>
 *
 * @uses hasloo_get_seo_option, hasloo_get_custom_field
 *
 * @since 0.1.3
 */
add_action('hasloo_meta','hasloo_robots_meta');
function hasloo_robots_meta() {
	global $wp_query, $post;
	
	// if the user wants the blog private, then follow logic
	// is unnecessary. WP will insert noindex and nofollow
	if ( get_option('blog_public') == 0 ) return;
	
	// defaults
	$meta = array(
		'noindex' => '',
		'nofollow' => '',
		'noarchive' => hasloo_get_seo_option('noarchive') ? 'noarchive' : '',
		'noodp' => hasloo_get_seo_option('noodp') ? 'noodp' : '',
		'noydir' => hasloo_get_seo_option('noydir') ? 'noydir' : ''
	);
	
	// Check homepage SEO settings, set noindex/nofollow/noarchive
	if ( is_front_page() ) {
		
		$meta['noindex'] = hasloo_get_seo_option('home_noindex') ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = hasloo_get_seo_option('home_nofollow') ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = hasloo_get_seo_option('home_noarchive') ? 'noarchive' : $meta['noarchive'];
		
	}

	// Check category META, set noindex/nofollow/noarchive
	if ( is_category() ) {
		//$term = get_term( get_query_var('cat'), 'category' );
		$term = $wp_query->get_queried_object();
		
		$meta['noindex'] = $term->meta['noindex'] ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = $term->meta['nofollow'] ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = $term->meta['noarchive'] ? 'noarchive' : $meta['noarchive'];
		
		$meta['noindex'] = hasloo_get_seo_option('noindex_cat_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = hasloo_get_seo_option('noarchive_cat_archive') ? 'noarchive' : $meta['noarchive'];
		
		//	noindex paged archives, if canonical archives is off
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$meta['noindex'] = $paged > 1 && !hasloo_get_seo_option('canonical_archives') ? 'noindex' : $meta['noindex'];

	}
	
	// Check tag META, set noindex/nofollow/noarchive
	if ( is_tag() ) {
		//$term = get_term( get_query_var('tag_id'), 'post_tag' );
		$term = $wp_query->get_queried_object();
		
		$meta['noindex'] = $term->meta['noindex'] ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = $term->meta['nofollow'] ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = $term->meta['noarchive'] ? 'noarchive' : $meta['noarchive'];
		
		$meta['noindex'] = hasloo_get_seo_option('noindex_tag_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = hasloo_get_seo_option('noarchive_tag_archive') ? 'noarchive' : $meta['noarchive'];
		
		//	noindex paged archives, if canonical archives is off
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$meta['noindex'] = $paged > 1 && !hasloo_get_seo_option('canonical_archives') ? 'noindex' : $meta['noindex'];
		
	}
	
	// Check term META, set noindex/nofollow/noarchive
	if ( is_tax() ) {
		$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		
		$meta['noindex'] = $term->meta['noindex'] ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = $term->meta['nofollow'] ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = $term->meta['noarchive'] ? 'noarchive' : $meta['noarchive'];
		
		//	noindex paged archives, if canonical archives is off
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$meta['noindex'] = $paged > 1 && !hasloo_get_seo_option('canonical_archives') ? 'noindex' : $meta['noindex'];
		
	}
	
	// Check author META, set noindex/nofollow/noarchive
	if ( is_author() ) {
		
		$meta['noindex'] = get_the_author_meta( 'noindex', (int)get_query_var('author') ) ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = get_the_author_meta( 'nofollow', (int)get_query_var('author') ) ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = get_the_author_meta( 'noarchive', (int)get_query_var('author') ) ? 'noarchive' : $meta['noarchive'];
		
		$meta['noindex'] = hasloo_get_seo_option('noindex_author_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = hasloo_get_seo_option('noarchive_author_archive') ? 'noarchive' : $meta['noarchive'];
		
		//	noindex paged archives, if canonical archives is off
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		$meta['noindex'] = $paged > 1 && !hasloo_get_seo_option('canonical_archives') ? 'noindex' : $meta['noindex'];
		
	}
	
	if ( is_date() ) {
		$meta['noindex'] = hasloo_get_seo_option('noindex_date_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = hasloo_get_seo_option('noarchive_date_archive') ? 'noarchive' : $meta['noarchive'];
	}
	if ( is_search() ) {
		$meta['noindex'] = hasloo_get_seo_option('noindex_search_archive') ? 'noindex' : $meta['noindex'];
		$meta['noarchive'] = hasloo_get_seo_option('noarchive_search_archive') ? 'noarchive' : $meta['noarchive'];
	}

	// Check post/page META, set noindex/nofollow/noarchive
	if ( is_singular() ) {
		
		$meta['noindex'] = hasloo_get_custom_field('_hasloo_noindex') ? 'noindex' : $meta['noindex'];
		$meta['nofollow'] = hasloo_get_custom_field('_hasloo_nofollow') ? 'nofollow' : $meta['nofollow'];
		$meta['noarchive'] = hasloo_get_custom_field('_hasloo_noarchive') ? 'noarchive' : $meta['noarchive'];
		
	}
		
	// return nothing, unless we're supposed to noindex OR nofollow
	if ( !$meta['noindex'] && !$meta['nofollow'] && !$meta['noodp'] && !$meta['noydir'] && !$meta['noarchive'] )
		return;

	printf( '<meta name="robots" content="%s" />' . "\n", implode( ",", array_filter( $meta ) ) );
}

/**
 * Show Parent and Child information in the document head if specified by the user.
 * This can be helpful for diagnosing problems with the theme, because you can
 * easily determine if anything is out of date, needs to be updated.
 *
 * @since 1.0
 */
add_action('hasloo_meta', 'hasloo_show_theme_info_in_head');
function hasloo_show_theme_info_in_head() {
	if ( !hasloo_get_option( 'show_info' ) ) return;
	
	// Show Parent Info
	echo "\n".'<!-- Theme Information -->'."\n";
	echo '<meta name="wp_template" content="'. esc_attr( PARENT_THEME_NAME ) .' '. esc_attr( PARENT_THEME_VERSION ) .'" />'."\n";
	
	// If there is no child theme, don't continue
	if ( CHILD_DIR == PARENT_DIR ) return;
	
	// Show Child Info
	$child_info = get_theme_data(CHILD_DIR.'/style.css');
	echo '<meta name="wp_theme" content="'. esc_attr( $child_info['Name'] ) .' '. esc_attr( $child_info['Version'] ) .'" />'."\n";
}

/**
 * This function adds the pingback meta tag to the <head> so that other
 * sites can know how to send a pingback to our site.
 * 
 * @since 1.3
 */
add_action('wp_head', 'hasloo_do_meta_pingback');
function hasloo_do_meta_pingback() {
	
	if ( get_option('default_ping_status') == 'open' ) {
		echo '<link rel="pingback" href="' . get_bloginfo('pingback_url') . '" />' . "\n";
	}
	
}

/**
 * Remove the default WordPress canonical tag, and use our custom 
 * one. Gives us more flexibility and effectiveness.
 *
 * @uses hasloo_get_seo_option, hasloo_get_custom_field
 *
 * @since 0.1.3
 */
remove_action('wp_head', 'rel_canonical');
add_action('wp_head','hasloo_canonical');
function hasloo_canonical() {
	global $wp_query;
	
	$canonical = '';
	
	if ( is_front_page() ) {
		$canonical = trailingslashit( get_bloginfo('url') );
	}
		
	if ( is_singular() ) {
		
		if ( !$id = $wp_query->get_queried_object_id() )
			return;
		
		$cf = hasloo_get_custom_field('_hasloo_canonical_uri');
		
		$canonical = $cf ? $cf : get_permalink( $id );
		
	}
	
	if ( is_category() || is_tag() || is_tax() ) {
		
		if ( !$id = $wp_query->get_queried_object_id() )
			return;
			
		$taxonomy = $wp_query->queried_object->taxonomy;
		
		$canonical = hasloo_get_seo_option('canonical_archives') ? get_term_link( (int)$id, $taxonomy ) : 0;
		
	}
	
	if ( is_author() ) {
		
		if ( !$id = $wp_query->get_queried_object_id() )
			return;
		
		$canonical = hasloo_get_seo_option('canonical_archives') ? get_author_posts_url( $id ) : 0;
		
	}
	
	if ( !$canonical ) return;
		
	printf('<link rel="canonical" href="%s" />'."\n", esc_url( $canonical ) );
	
}

/**
 * This function looks for a favicon. If it finds
 * one, it will output the proper code in the <head>
 *
 * @since 0.2.2
 */
add_action('hasloo_meta', 'hasloo_load_favicon');
function hasloo_load_favicon() {
	
	// Allow child theme to short-circuit this function
	$pre = apply_filters('hasloo_pre_load_favicon', false);
	
	if ( $pre !== false )
		$favicon = $pre;
	elseif ( file_exists(CHILD_DIR.'/images/favicon.ico') )
		$favicon = CHILD_URL.'/images/favicon.ico';
	elseif ( file_exists(CHILD_DIR.'/images/favicon.gif') )
		$favicon = CHILD_URL.'/images/favicon.gif';
	elseif ( file_exists(CHILD_DIR.'/images/favicon.png') )
		$favicon = CHILD_URL.'/images/favicon.png';
	elseif ( file_exists(CHILD_DIR.'/images/favicon.jpg') )
		$favicon = CHILD_URL.'/images/favicon.jpg';
	else
		$favicon = PARENT_URL.'/images/favicon.ico';

	$favicon = apply_filters('hasloo_favicon_url', $favicon);

	if ( $favicon )
	echo '<link rel="Shortcut Icon" href="'. esc_url( $favicon ). '" type="image/x-icon" />'."\n";
}

/**
 * Output header scripts in to <code>wp_head()</code>
 * Allow shortcodes
 *
 * @since 0.2.3
 */
add_filter('hasloo_header_scripts', 'do_shortcode');
add_action('wp_head', 'hasloo_header_scripts');
function hasloo_header_scripts() {
	
	echo apply_filters('hasloo_header_scripts', hasloo_get_option('header_scripts'));
	
	// If singular, echo scripts from custom field
	if ( is_singular() ) {
		hasloo_custom_field('_hasloo_scripts');
	}
	
}

/**
 * Outputs the structural markup for the header
 *
 * @since 1.2
 */
add_action('hasloo_header', 'hasloo_header_markup_open', 5);
function hasloo_header_markup_open() {
	
	echo '<div id="header"><div class="wrap">';
	
}
add_action('hasloo_header', 'hasloo_header_markup_close', 15);
function hasloo_header_markup_close() {

	echo '</div><!-- end .wrap --></div><!--end #header-->' . "\n";

}

/**
 * This function outputs the default header, including the #title-area div,
 * along with #title and #description, as well as the .widget-area.
 *
 * @since 1.0.2
 */
add_action('hasloo_header', 'hasloo_do_header');
function hasloo_do_header() {
		
	echo '<div id="title-area">';
		hasloo_site_title();
		hasloo_site_description();
	echo '</div><!-- end #title-area -->';
	
	if ( hasloo_get_option('header_right') ) {
		echo '<div class="widget-area">';
			dynamic_sidebar('Header Right');
		echo '</div><!-- end .widget_area -->';
	}
}