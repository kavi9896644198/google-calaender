<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="stylesheet" id="dashicons-css" href="http://cannaconnects.io/wp-includes/css/dashicons.min.css?ver=6.0.2" type="text/css" media="all">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> >
<?php 
wp_body_open();
get_template_part( 'templates/header/' . 'sidenav', 'template'); ?>


<div class="main-content" id="panel">