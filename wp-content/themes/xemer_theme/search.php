<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package xemer_theme
 */

get_header();
?>

<?php
if (have_posts()):
	while (have_posts()):
		the_post();
		?>
		viết html item viết ở đây.
		<?php
	endwhile;
else:
	echo 'Không có bài viết nào';
endif;
?>

<?php
get_footer();
