<?php
/**
 * WARNING: This file is part of the core Hasloo framework. DO NOT edit
 * this file under any circumstances. Please do all modifications
 * in the form of a child theme.
 */
hasloo_doctype();
hasloo_title();
hasloo_meta();

wp_head(); // we need this for plugins
?>
</head>
<body <?php body_class(); ?>>
<?php hasloo_before(); ?>

<div id="wrap">
<?php hasloo_before_header(); ?>
<?php hasloo_header(); ?>
<?php hasloo_after_header(); ?>
<div id="inner">
<input action="action" type="button" value="Back" onclick="history.go(-1);" />