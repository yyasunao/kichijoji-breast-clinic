<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package 吉祥寺ブレストクリニック
 */

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			if ( is_page('appointment') ) {
					get_template_part( 'template-parts/content', 'page-appointment' );
			}
			else
			{
				get_template_part( 'template-parts/content', 'page' );
			}

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

		<?php get_template_part( 'file/fixed-menu' );?>

		<p class="page-top"><img src="/newsite/wp/wp-content/uploads/2023/12/arrow_pagetop.svg" alt=""></p>
	</main><!-- #main -->

<?php
get_footer();
