<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header inner">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php kichijoji_breast_clinic_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
			//TransmitMail
			require_once(get_template_directory().'/appointment/index.php');
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
				edit_post_link(
					sprintf(
						/* translators: %s: Name of current post */
						esc_html__( 'Edit %s', 'breast-clinic' ),
						the_title( '<span class="screen-reader-text">"', '"</span>', false )
					),
					'<span class="edit-link">',
					'</span>'
				);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-## -->
