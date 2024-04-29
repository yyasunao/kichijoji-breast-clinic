<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package 吉祥寺ブレストクリニック
 */

get_header();
?>

	<main id="primary" class="site-main">

		<section class="error-404 not-found">
			<div class="inner">
				<header class="page-header">
					<h1 class="page-title"><?php esc_html_e( 'ページが見つかりませんでした', 'kichijoji-breast-clinic' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'お探しのページが見つかりませんでした。URLが間違っているか、ページが存在しません。', 'kichijoji-breast-clinic' ); ?></p>

						<?php
						get_search_form();
						?>

				</div><!-- .page-content -->
			</div><!-- inner -->
		</section><!-- .error-404 -->

	</main><!-- #main -->

<?php
get_footer();
