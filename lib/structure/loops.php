<?php
/**
 * Hook loops to the hasloo_loop output hook so we can get
 * some front-end output. Pretty basic stuff.
 *
 * @since 1.1
 */
add_action('hasloo_loop', 'hasloo_do_loop');
function hasloo_do_loop() {
	
	if ( is_page_template('page_blog.php') ) {
		$include = hasloo_get_option('blog_cat');
		$exclude = hasloo_get_option('blog_cat_exclude') ? explode(',', str_replace(' ', '', hasloo_get_option('blog_cat_exclude'))) : '';
		$paged = get_query_var('paged') ? get_query_var('paged') : 1;
		
		$cf = hasloo_get_custom_field('query_args'); // Easter Egg
		$args = array('cat' => $include, 'category__not_in' => $exclude, 'showposts' => hasloo_get_option('blog_cat_num'), 'paged' => $paged);
		$query_args = wp_parse_args($cf, $args);
		
		hasloo_custom_loop( $query_args );
	} 
	else {
		hasloo_standard_loop();
	}
	
}

/**
 * This is a standard loop, and is meant to be executed, without
 * modification, in most circumstances where content needs to be displayed.
 * 
 * It outputs basic wrapping HTML, but uses hooks to do most of its
 * content output like Title, Content, Post information, and Comments.
 *
 * @since 1.1
 */
function hasloo_standard_loop() {
	global $loop_counter;
	$loop_counter = 0;

	if (have_posts()) : while (have_posts()) : the_post(); // the loop

	hasloo_before_post();
?>
	<div <?php post_class(); ?>>
                    
		<?php hasloo_before_post_title(); ?>
		<?php hasloo_post_title(); ?>
		<?php hasloo_after_post_title(); ?>

		<?php hasloo_before_post_content(); ?>
		<div class="entry-content">
			<?php hasloo_post_content(); ?>
		</div><!-- end .entry-content -->
		<?php hasloo_after_post_content(); ?> 

	</div><!-- end .postclass -->
<?php
	
	hasloo_after_post();
	$loop_counter++;

	endwhile; // end of one post
	hasloo_after_endwhile();

	else : // if no posts exist
	hasloo_loop_else();
	endif; // end loop 
}

/**
 * This is a custom loop function, and is meant to be executed when a
 * custom query is needed. It accepts arguments in query_posts style
 * format to modify the custom WP_Query object.
 * 
 * It outputs basic wrapping HTML, but uses hooks to do most of its
 * content output like Title, Content, Post information, and Comments.
 *
 * @since 1.1
 */
function hasloo_custom_loop( $args = array() ) {
	global $wp_query, $more, $loop_counter;
	$loop_counter = 0;
	
	$defaults = array(); // For forward compatibility
	$args = apply_filters('hasloo_custom_loop_args', wp_parse_args($args, $defaults), $args, $defaults);
	
	// save the original query
	$orig_query = $wp_query;
	
	$wp_query = new WP_Query($args);
	if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post();
	$more = 0;
?>

	<?php hasloo_before_post(); ?>
	<div <?php post_class(); ?>>

		<?php hasloo_before_post_title() ; ?>
		<?php hasloo_post_title(); ?>
		<?php hasloo_after_post_title(); ?>

		<?php hasloo_before_post_content(); ?>
		<div class="entry-content">
			<?php hasloo_post_content(); ?>
		</div><!-- end .entry-content -->
		<?php hasloo_after_post_content(); ?>

	</div><!-- end .postclass -->
	<?php hasloo_after_post(); ?>
	<?php $loop_counter++; ?>

<?php endwhile; // end of one post ?>
<?php hasloo_after_endwhile(); ?>

<?php else : // if no posts exist ?>
<?php hasloo_loop_else(); ?>
<?php endif; // end loop ?>

<?php
	// restore original query
	$wp_query = $orig_query; wp_reset_query();
?>

<?php
}