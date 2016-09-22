<?php

/**
 * Primary Sidebar Content
 */
add_action('hasloo_sidebar', 'hasloo_do_sidebar');
function hasloo_do_sidebar() {

	if ( !dynamic_sidebar('sidebar') ) {
	
		echo '<div class="widget widget_text"><div class="widget-wrap">';
			echo '<h4 class="widgettitle">';
				_e('Primary Sidebar Widget Area', 'hasloo');
			echo '</h4>';
			echo '<div class="textwidget"><p>';
				printf(__('This is the Primary Sidebar Widget Area. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', 'hasloo'), admin_url('widgets.php'));
			echo '</p></div>';
		echo '</div></div>';
		
	}

}

/**
 * Alternate Sidebar Content
 */
add_action('hasloo_sidebar_alt', 'hasloo_do_sidebar_alt');
function hasloo_do_sidebar_alt() {
	
	if ( !dynamic_sidebar('sidebar-alt') ) {
	
		echo '<div class="widget widget_text"><div class="widget-wrap">';
			echo '<h4 class="widgettitle">';
				_e('Secondary Sidebar Widget Area', 'hasloo');
			echo '</h4>';
			echo '<div class="textwidget"><p>';
				printf(__('This is the Secondary Sidebar Widget Area. You can add content to this area by visiting your <a href="%s">Widgets Panel</a> and adding new widgets to this area.', 'hasloo'), admin_url('widgets.php'));
			echo '</p></div>';
		echo '</div></div>';
		
	}
	
}