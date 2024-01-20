<div class="news-list">
	<dl class="wrapper">
		<?php
			$wp_query = new WP_Query();
			$my_posts = array(
				'post_type' => 'post',
				'category_name' => 'news',
				'posts_per_page'=> '3',
			);
			$wp_query->query( $my_posts );
			if( $wp_query->have_posts() ): while( $wp_query->have_posts() ) : $wp_query->the_post();
		?>
					
			<dt><a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a></dt><dd><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dd>
			
		<?php endwhile; endif; wp_reset_postdata(); ?>
	</dl>
</div><!-- news-list -->