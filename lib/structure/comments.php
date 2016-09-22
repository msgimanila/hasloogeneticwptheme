<?php
/**
 * @todo document this file
 */

/**
 * Output the comments at the end of posts/pages.
 * The checks are for 3rd party comment systems.
 *
 * @since 1.1
 */
add_action('hasloo_after_post', 'hasloo_get_comments_template');
function hasloo_get_comments_template() {
	
	// Load comments only if we are on a page or post and only if comments or trackbacks are chosen 
	if ( is_single() && ( hasloo_get_option('trackbacks_posts') || hasloo_get_option('comments_posts') ) )
		comments_template('', true); 
	else if ( is_page() && ( hasloo_get_option('trackbacks_pages') || hasloo_get_option('comments_pages') ) )
		comments_template('', true); 

	return;
	
}

/**
 * 
 * @since 1.1.2
 */
add_action('hasloo_comments', 'hasloo_do_comments');
function hasloo_do_comments() { 
	global $post, $wp_query;
	
	//print_r( $wp_query->comments_by_type );
	
	// Check
	if ( ( is_page() && !hasloo_get_option('comments_pages') ) || ( is_single() && !hasloo_get_option('comments_posts') ) )
		return;
	
	if ( have_comments() && !empty( $wp_query->comments_by_type['comment'] ) ) : ?>
		
	<div id="comments">
		<?php echo apply_filters('hasloo_title_comments', __('<h3>Comments</h3>', 'hasloo')); ?>
		<ol class="comment-list">
			<?php hasloo_list_comments(); ?>
		</ol>
		<div class="navigation">
			<div class="alignleft"><?php previous_comments_link() ?></div>
			<div class="alignright"><?php next_comments_link() ?></div>
		</div>
	</div><!--end #comments-->
	
	<?php else : // this is displayed if there are no comments so far ?>
		
	<div id="comments">
		<?php if ('open' == $post->comment_status) : ?>
		<!-- If comments are open, but there are no comments. -->
		<?php echo apply_filters('hasloo_no_comments_text', ''); ?>

		<?php else : // comments are closed ?>
		<!-- If comments are closed. -->
		<?php echo apply_filters('hasloo_comments_closed_text', ''); ?>

		<?php endif; // endif comments are open, but there are no comments ?>
	</div><!--end #comments-->
	
	<?php endif; // endif have comments ?>
	
<?php
}

/**
 * 
 * @since 1.1.2
 */
add_action('hasloo_pings', 'hasloo_do_pings');
function hasloo_do_pings() {
	global $post, $wp_query;
		
	// Check
	if ( ( is_page() && !hasloo_get_option('trackbacks_pages') ) || ( is_single() && !hasloo_get_option('trackbacks_posts') ) )
		return;

	if ( have_comments() && !empty( $wp_query->comments_by_type['pings'] ) ) : // if have pings ?>
	
	<div id="pings">
		<?php echo apply_filters('hasloo_title_pings', __('<h3>Trackbacks</h3>', 'hasloo')); ?>

		<ol class="ping-list">
			<?php hasloo_list_pings(); ?>
		</ol>
	</div><!-- end #pings -->

	<?php else : // this is displayed if there are no pings ?>

		<?php echo apply_filters('hasloo_no_pings_text', ''); ?>

	<?php endif; // endif have pings ?>

<?php	
}

/**
 * This function outputs the comment list to the <code>hasloo_comment_list()</code> hook
 *
 * @since 1.0
 */
add_action('hasloo_list_comments', 'hasloo_default_list_comments');
function hasloo_default_list_comments() {
	
	$args = array(
		'type' => 'comment',
		'avatar_size' => 48,
		'callback' => 'hasloo_comment_callback'
	);
	
	$args = apply_filters('hasloo_comment_list_args', $args);
	
	wp_list_comments($args);
}

/**
 * This function outputs the ping list to the <code>hasloo_ping_list()</code> hook
 * 
 * @since 1.0
 */
add_action('hasloo_list_pings', 'hasloo_default_list_pings');
function hasloo_default_list_pings() {
	$args = array(
		'type' => 'pings'
	);
	
	$args = apply_filters('hasloo_ping_list_args', $args);
	
	wp_list_comments($args);
}

/**
 * This function is the comment callback for <code>hasloo_default_comment_list()</code>
 * 
 * @since 1.0
 */
function hasloo_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	
	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">
	
		<?php hasloo_before_comment(); ?>
	
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, $size = $args['avatar_size'] ); ?>
			<?php printf( __('<cite class="fn">%s</cite> <span class="says">%s:</span>', 'hasloo'), get_comment_author_link(), apply_filters('comment_author_says_text', __('says', 'hasloo')) ); ?>
	 	</div><!-- end .comment-author -->

		<div class="comment-meta commentmetadata">
			<a href="<?php echo esc_attr( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf(__('%1$s at %2$s', 'hasloo'), get_comment_date(),  get_comment_time()); ?></a>
			<?php edit_comment_link(__('Edit', 'hasloo'), '&bull; ', ''); ?>
		</div><!-- end .comment-meta -->

		<div class="comment-content">
			<?php if ($comment->comment_approved == '0') : ?>
				<p class="alert"><?php _e('Your comment is awaiting moderation.', 'hasloo'); ?></p>
			<?php endif; ?>
			
			<?php comment_text(); ?>
		</div><!-- end .comment-content -->

		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
		</div>
		
		<?php hasloo_after_comment(); ?>
		
	<?php // no ending </li> tag because of comment threading
}

/**
 * This function defines the comment form, hooked to <code>hasloo_comments_form()</code>
 *
 * @since 1.0
 */
add_action('hasloo_comment_form', 'hasloo_do_comment_form');
function hasloo_do_comment_form() {
	global $user_identity, $id;
	
	// Check
	if ( ( is_page() && !hasloo_get_option('comments_pages') ) || ( is_single() && !hasloo_get_option('comments_posts') ) )
		return;
	
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? ' aria-required="true"' : '' );
	
	$args = array(
		'fields' => array(
			'author' =>	'<p class="comment-form-author">' .
						'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" tabindex="1"' . $aria_req . ' />' .
						'<label for="author">' . __( 'Name', 'hasloo' ) . '</label> ' .
						( $req ? '<span class="required">*</span>' : '' ) .
						'</p><!-- #form-section-author .form-section -->',
		
			'email' =>	'<p class="comment-form-email">' .
						'<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" tabindex="2"' . $aria_req . ' />' .
						'<label for="email">' . __( 'Email', 'hasloo' ) . '</label> ' .
						( $req ? '<span class="required">*</span>' : '' ) .
						'</p><!-- #form-section-email .form-section -->',
		
			'url' =>	'<p class="comment-form-url">' .
						'<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" tabindex="3" />' .
						'<label for="url">' . __( 'Website', 'hasloo' ) . '</label>' .
						'</p><!-- #form-section-url .form-section -->'
		),
				
		'comment_field' =>	'<p class="comment-form-comment">' .
							'<textarea id="comment" name="comment" cols="45" rows="8" tabindex="4" aria-required="true"></textarea>' .
							'</p><!-- #form-section-comment .form-section -->',
							
		'title_reply' => __( 'Speak Your Mind', 'hasloo' ),
		
		'comment_notes_before' => '',
		
		'comment_notes_after' => '',
	);
	
	comment_form( apply_filters('hasloo_comment_form_args', $args, $user_identity, $id, $commenter, $req, $aria_req), $id );
}