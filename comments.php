<?php
/**

 */
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
	die ('Please do not load this page directly. Thanks!');

if ( post_password_required() ) { ?>
	<p class="alert"><?php _e('This post is password protected. Enter the password to view comments.', 'hasloo'); ?></p>
<?php
	return;
}

hasloo_before_comments();
hasloo_comments();
hasloo_after_comments();

hasloo_before_pings();
hasloo_pings();
hasloo_after_pings();

hasloo_before_comment_form();
hasloo_comment_form();
hasloo_after_comment_form();