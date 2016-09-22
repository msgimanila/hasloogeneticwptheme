<?php
/**

 *
 * Template Name: Archive
 */
get_header(); ?>

<?php hasloo_before_content_sidebar_wrap(); ?>
<div id="content-sidebar-wrap">
	
	<?php hasloo_before_content(); ?>
	<div id="content">
	
		<?php hasloo_before_loop(); ?>
		<div class="post hentry">

			<h1 class="entry-title"><?php _e("Site Archives", 'hasloo'); ?></h1>

			<div class="entry-content">
				<div class="archive-page">

					<h4><?php _e("Pages:", 'hasloo'); ?></h4>
					<ul>
						<?php wp_list_pages('title_li='); ?>
					</ul>

					<h4><?php _e("Categories:", 'hasloo'); ?></h4>
					<ul>
						<?php wp_list_categories('sort_column=name&title_li='); ?>
					</ul>

				</div><!-- end .archive-page-->

				<div class="archive-page">

					<h4><?php _e("Authors:", 'hasloo'); ?></h4>
					<ul>
						<?php wp_list_authors('exclude_admin=0&optioncount=1'); ?>   
					</ul>

					<h4><?php _e("Monthly:", 'hasloo'); ?></h4>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>

					<h4><?php _e("Recent Posts:", 'hasloo'); ?></h4>
					<ul>
						<?php wp_get_archives('type=postbypost&limit=100'); ?> 
					</ul>    

				</div><!-- end .archive-page-->
			</div><!-- end .entry-content -->

		</div><!-- end .post -->
		<?php hasloo_after_loop(); ?>
	
	</div><!-- end #content -->
	<?php hasloo_after_content(); ?>

</div><!-- end #content-sidebar-wrap -->
<?php hasloo_after_content_sidebar_wrap(); ?>

<?php get_footer(); ?>