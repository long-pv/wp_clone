<?php

/**
 * Template name: Page block
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package xemer_theme
 */

get_header();

// components
include_once get_template_directory() . '/template-parts/components/banner.php';
include_once get_template_directory() . '/template-parts/components/featured.php';

get_footer();
