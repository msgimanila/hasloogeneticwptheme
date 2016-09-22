<?php
/**
 * @todo Document this file
 **/

function hasloo_pre() { do_action('hasloo_pre'); }
function hasloo_pre_framework() { do_action('hasloo_pre_framework'); }
function hasloo_init() { do_action('hasloo_init'); }

function hasloo_doctype() { do_action('hasloo_doctype'); }

function hasloo_title() { do_action('hasloo_title'); }

function hasloo_meta() { do_action('hasloo_meta'); }

function hasloo_before() { do_action('hasloo_before'); }
function hasloo_after() { do_action('hasloo_after'); }

function hasloo_before_header() { do_action('hasloo_before_header'); }
function hasloo_header() { do_action('hasloo_header'); }
function hasloo_after_header() { do_action('hasloo_after_header'); }

function hasloo_site_title() { do_action('hasloo_site_title'); }
function hasloo_site_description() { do_action('hasloo_site_description'); }

function hasloo_before_content_sidebar_wrap() { do_action('hasloo_before_content_sidebar_wrap'); }	
function hasloo_after_content_sidebar_wrap() { do_action('hasloo_after_content_sidebar_wrap'); }

function hasloo_before_content() { do_action('hasloo_before_content'); }	
function hasloo_after_content() { do_action('hasloo_after_content'); }

function hasloo_home() { do_action('hasloo_home'); } /** optional for child theme home.php files **/

function hasloo_before_loop() { do_action('hasloo_before_loop'); }
function hasloo_loop() { do_action('hasloo_loop'); }
function hasloo_after_loop() { do_action('hasloo_after_loop'); }

function hasloo_before_post() { do_action('hasloo_before_post'); }
function hasloo_after_post() { do_action('hasloo_after_post'); }

function hasloo_before_post_title() { do_action('hasloo_before_post_title'); }
function hasloo_post_title() { do_action('hasloo_post_title'); }
function hasloo_after_post_title() { do_action('hasloo_after_post_title'); }

function hasloo_before_post_content() { do_action('hasloo_before_post_content'); }
function hasloo_post_content() { do_action('hasloo_post_content'); }
function hasloo_after_post_content() { do_action('hasloo_after_post_content'); }

function hasloo_after_endwhile() { do_action('hasloo_after_endwhile'); }
function hasloo_loop_else() { do_action('hasloo_loop_else'); }

function hasloo_before_comments() { do_action('hasloo_before_comments'); }
function hasloo_comments() { do_action('hasloo_comments'); }
function hasloo_list_comments() { do_action('hasloo_list_comments'); }
function hasloo_after_comments() { do_action('hasloo_after_comments'); }

function hasloo_before_pings() { do_action('hasloo_before_pings'); }
function hasloo_pings() { do_action('hasloo_pings'); }
function hasloo_list_pings() { do_action('hasloo_list_pings'); }
function hasloo_after_pings() { do_action('hasloo_after_pings'); }

function hasloo_before_comment() { do_action('hasloo_before_comment'); }
function hasloo_after_comment() { do_action('hasloo_after_comment'); }

function hasloo_before_comment_form() { do_action('hasloo_before_comment_form'); }
function hasloo_comment_form() { do_action('hasloo_comment_form'); }
function hasloo_after_comment_form() { do_action('hasloo_after_comment_form'); }

function hasloo_before_sidebar_widget_area() { do_action('hasloo_before_sidebar_widget_area'); }
function hasloo_sidebar() { do_action('hasloo_sidebar'); }
function hasloo_after_sidebar_widget_area() { do_action('hasloo_after_sidebar_widget_area'); }

function hasloo_before_sidebar_alt_widget_area() { do_action('hasloo_before_sidebar_alt_widget_area'); }
function hasloo_sidebar_alt() { do_action('hasloo_sidebar_alt'); }
function hasloo_after_sidebar_alt_widget_area() { do_action('hasloo_after_sidebar_alt_widget_area'); }

function hasloo_before_footer() { do_action('hasloo_before_footer'); }
function hasloo_footer() { do_action('hasloo_footer'); }
function hasloo_after_footer() { do_action('hasloo_after_footer'); }

/** Admin Hooks **/
function hasloo_import_export_form() { do_action('hasloo_import_export_form'); }