<?php
/**
 * undocumented 
 *
 * @package Hasloo
 */

add_action('widgets_init', 'register_hasloo_menu_categories_widget');
function register_hasloo_menu_categories_widget() {
	//unregister_widget('WP_Widget_Categories');
	register_widget('Hasloo_Widget_Menu_Categories');
}

class Hasloo_Widget_Menu_Categories extends WP_Widget {

	function Hasloo_Widget_Menu_Categories() {
		$widget_ops = array('classname' => 'menu-categories', 'description' => __('Display category navigation for your header', 'hasloo') );
		$this->WP_Widget('menu-categories', __('Hasloo - Category Navigation Menu', 'hasloo'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'include' => array(),
			'order' => ''
		) );
		
		echo $before_widget;
		
		if ($instance['title']) echo $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
			
		echo '<ul class="nav">'."\n";
		
		// Empty fallback (default)
		if(empty($instance['include'])) :
			$cats = get_categories('hide_empty=0');
			foreach((array)$cats as $cat) {
				$instance['include'][] = $cat->ID;
			}
		endif;
			
		// Show Home Link?
		if(in_array('home', (array)$instance['include'])) {
			$active = (is_front_page()) ? 'class="current_page_item"' : '';
			echo '<li '.$active.'><a href="'. trailingslashit( get_bloginfo('url') ) .'">'.__('Home', 'hasloo').'</a></li>';
		}
		// Show Category Links?
		wp_list_categories(array('title_li' => '', 'include' => implode(',', (array)$instance['include']), 'orderby' => $instance['order'], 'hide_empty' => FALSE));
		
		echo '</ul>'."\n";
		
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) { 
		
		$instance = wp_parse_args( (array)$instance, array(
			'title' => '',
			'include' => array(),
			'order' => ''
		) );
		
		// Empty fallback (default)
		if(empty($instance['include'])) :
			$cats = get_categories('hide_empty=0');
			foreach((array)$cats as $cat) {
				$instance['include'][] = $cat->ID;
			}
		endif;
		?>
		
		<p><?php _e('NOTE: Leave title blank if using this widget in the header', 'hasloo'); ?></p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
			<?php _e('Title', 'hasloo'); ?>:
			</label>
			<input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" style="width:95%;" />
		</p>
		
		<p><?php _e('Choose the order by which you would like to display your categories', 'hasloo'); ?>:</p>
		
		<p><select name="<?php echo $this->get_field_name('order'); ?>">
			<option style="padding-right:10px;" value="ID" <?php selected('id', $instance['order']); ?>>ID</option>
			<option style="padding-right:10px;" value="name" <?php selected('name', $instance['order']); ?>>Name</option>
			<option style="padding-right:10px;" value="slug" <?php selected('slug', $instance['order']); ?>>Slug</option>
			<option style="padding-right:10px;" value="count" <?php selected('count', $instance['order']); ?>>Count</option>
			<option style="padding-right:10px;" value="term_group" <?php selected('term_group', $instance['order']); ?>>Term Group</option>
		</select></p>

		<p><?php _e('Use the checklist below to choose which categories (and subcategories) you want to include in your Navigation Menu', 'hasloo'); ?></p>
		
		<div id="categorydiv">
		<ul class="categorychecklist">
		<li>
		<?php hasloo_category_checklist($this->get_field_name('include'), $instance['include']); ?>
		</ul>
		</div>
		
	<?php 
	}
}
?>