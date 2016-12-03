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
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body <?php body_class(); ?>>
<?php hasloo_before(); ?>

<div id="wrap">
<?php hasloo_before_header(); ?>
<?php hasloo_header(); ?>
<?php hasloo_after_header(); ?>
<div id="inner">
 