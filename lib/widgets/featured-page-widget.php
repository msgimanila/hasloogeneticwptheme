<?php
/**
 * undocumented 
 *
 * @package Hasloo
 */

add_action('widgets_init', create_function('', "register_widget('Hasloo_Featured_Page');"));
class Hasloo_Featured_Page extends WP_Widget {

	function Hasloo_Featured_Page() {
		$widget_ops = array( 'classname' => 'featuredpage', 'description' => __('Displays featured page with thumbnails', 'hasloo') );
		$control_ops = array( 'width' => 200, 'height' => 250, 'id_base' => 'featured-page' );
		$this->WP_Widget( 'featured-page', __('Hasloo - Featured Page', 'hasloo'), $widget_ops, $control_ops );
	}

	function widget($args, $instance) {
		extract($args);
		
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'page_id' => '',
			'show_image' => 0,
			'image_alignment' => '',
			'image_size' => '',
			'show_title' => 0,
			'show_byline' => 0,
			'show_content' => 0,
			'content_limit' => '',
			'more_text' => ''
		) );
		
		echo $before_widget;
		
			// Set up the author bio
			if (!empty($instance['title']))
				echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;

			$featured_page = new WP_Query(array('page_id' => $instance['page_id']));
			if($featured_page->have_posts()) : while($featured_page->have_posts()) : $featured_page->the_post();
				
				echo '<div '; post_class(); echo '>';

				if(!empty($instance['show_image'])) :
					printf( '<a href="%s" title="%s" class="%s">%s</a>', get_permalink(), the_title_attribute('echo=0'), esc_attr( $instance['image_alignment'] ), hasloo_get_image( array( 'format' => 'html', 'size' => $instance['image_size'] ) ) );
				endif;
				
				if(!empty($instance['show_title'])) :
					printf( '<h2><a href="%s" title="%s">%s</a></h2>', get_permalink(), the_title_attribute('echo=0'), the_title_attribute('echo=0') );
				endif;
				
				if(!empty($instance['show_byline'])) :
					echo '<p class="byline">';
					the_time('F j, Y');
					echo ' '.__('by', 'hasloo').' ';
					the_author_posts_link();
					echo ' &middot; ';
					comments_popup_link(__('Leave a Comment', 'hasloo'), __('1 Comment', 'hasloo'), __('% Comments', 'hasloo'));
					echo ' ';
					edit_post_link(__('(Edit)', 'hasloo'), '', '');
					echo '</p>';
				endif;
				
				if(!empty($instance['show_content'])) :
				
					if(empty($instance['content_limit'])) :
						the_content($instance['more_text']);
					else :
						the_content_limit( (int)$instance['content_limit'], esc_html( $instance['more_text'] ) );
					endif;
					
				endif;
				
				echo '</div><!--end post_class()-->'."\n\n";
					
			endwhile; endif;
		
		echo $after_widget;
		wp_reset_query();
	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) { 
		
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'page_id' => '',
			'show_image' => 0,
			'image_alignment' => '',
			'image_size' => '',
			'show_title' => 0,
			'show_byline' => 0,
			'show_content' => 0,
			'content_limit' => '',
			'more_text' => __('[Read More...]', 'hasloo')
		) );
		
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'hasloo'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" /></p>
		
		<p><label for="<?php echo $this->get_field_id('page_id'); ?>"><?php _e('Page', 'hasloo'); ?>:</label>
		<?php wp_dropdown_pages(array('name' => $this->get_field_name('page_id'), 'selected' => $instance['page_id'])); ?></p>
		
		<hr class="div" />
		
		<p><input id="<?php echo $this->get_field_id('show_image'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_image'); ?>" value="1" <?php checked(1, $instance['show_image']); ?>/> <label for="<?php echo $this->get_field_id('show_image'); ?>"><?php _e('Show Featured Image', 'hasloo'); ?></label></p>

		<p><label for="<?php echo $this->get_field_id('image_size'); ?>"><?php _e('Image Size', 'hasloo'); ?>:</label>
		<?php $sizes = hasloo_get_additional_image_sizes(); ?>
		<select id="<?php echo $this->get_field_id('image_size'); ?>" name="<?php echo $this->get_field_name('image_size'); ?>">
			<option style="padding-right:10px;" value="thumbnail">thumbnail (<?php echo get_option('thumbnail_size_w'); ?>x<?php echo get_option('thumbnail_size_h'); ?>)</option>
			<?php
			foreach((array)$sizes as $name => $size) :
			echo '<option style="padding-right: 10px;" value="'.$name.'" '.selected($name, $instance['image_size'], FALSE).'>'.$name.' ('.$size['width'].'x'.$size['height'].')</option>';
			endforeach;
			?>
		</select></p>	
		
		<p><label for="<?php echo $this->get_field_id('image_alignment'); ?>"><?php _e('Image Alignment', 'hasloo'); ?>:</label>
		<select id="<?php echo $this->get_field_id('image_alignment'); ?>" name="<?php echo $this->get_field_name('image_alignment'); ?>">
			<option style="padding-right:10px;" value="">- <?php _e('None', 'hasloo'); ?> -</option>
			<option style="padding-right:10px;" value="alignleft" <?php selected('alignleft', $instance['image_alignment']); ?>><?php _e('Left', 'hasloo'); ?></option>
			<option style="padding-right:10px;" value="alignright" <?php selected('alignright', $instance['image_alignment']); ?>><?php _e('Right', 'hasloo'); ?></option>
		</select></p>
		
		<hr class="div" />
		
		<p><input id="<?php echo $this->get_field_id('show_title'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_title'); ?>" value="1" <?php checked(1, $instance['show_title']); ?>/> <label for="<?php echo $this->get_field_id('show_title'); ?>"><?php _e('Show Page Title', 'hasloo'); ?></label></p>
		
		<p><input id="<?php echo $this->get_field_id('show_byline'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_byline'); ?>" value="1" <?php checked(1, $instance['show_byline']); ?>/> <label for="<?php echo $this->get_field_id('show_byline'); ?>"><?php _e('Show Page Byline', 'hasloo'); ?></label></p>
		
		<p><input id="<?php echo $this->get_field_id('show_content'); ?>" type="checkbox" name="<?php echo $this->get_field_name('show_content'); ?>" value="1" <?php checked(1, $instance['show_content']); ?>/> <label for="<?php echo $this->get_field_id('show_content'); ?>"><?php _e('Show Page Content', 'hasloo'); ?></label></p>
		
		<p><label for="<?php echo $this->get_field_id('content_limit'); ?>"><?php _e('Content Character Limit', 'hasloo'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('content_limit'); ?>" name="<?php echo $this->get_field_name('content_limit'); ?>" value="<?php echo esc_attr( $instance['content_limit'] ); ?>" size="3" /></p>
		
		<p><label for="<?php echo $this->get_field_id('more_text'); ?>"><?php _e('More Text', 'hasloo'); ?>:</label>
		<input type="text" id="<?php echo $this->get_field_id('more_text'); ?>" name="<?php echo $this->get_field_name('more_text'); ?>" value="<?php echo esc_attr( $instance['more_text'] ); ?>" /></p>
			
	<?php 
	}
}