<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package 吉祥寺ブレストクリニック
 */

get_header();
?>

	<main id="primary" class="site-main">
		<?php
		$cat = get_the_category();
		$cat = $cat[0];
		?>
		<h1 class="cat-title"><?php echo $cat->cat_name; ?></h1>

		<div class="post-thumbnail">
			<?php echo get_the_post_thumbnail(14); ?>
		</div><!-- post-thumbnail -->

		<div class="bg-b">
			<div class="inner">
				<div class="post-blk">
					<?php
					while ( have_posts() ) :

						the_post();
						get_template_part( 'template-parts/content-news', get_post_type() );

						the_post_navigation(
							array(
								'prev_text' => '<span class="nav-subtitle">' . esc_html__( '', 'kichijoji-breast-clinic' ) . '</span> <span class="nav-title">%title</span>',
								'next_text' => '<span class="nav-subtitle">' . esc_html__( '', 'kichijoji-breast-clinic' ) . '</span> <span class="nav-title">%title</span>',
							)
						);

						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) :
							comments_template();
						endif;

					endwhile; // End of the loop.
					?>
				</div><!-- post-blk -->
			</div><!-- inner -->
		</div><!-- bg-b -->

		<?php get_template_part( 'file/fixed-menu' );?>
		
		<p class="page-top"><img src="/newsite/wp/wp-content/uploads/2023/12/arrow_pagetop.svg" alt=""></p>
	</main><!-- #main -->

<?php
get_footer();
