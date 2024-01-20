<div class="news-list">
	<dl class="wrapper">
		<?php $paged = get_query_var('paged')? get_query_var('paged') : 1;
			$args = array(
				'post_type' => 'post',
				'category_name' => 'news',
				'posts_per_page' => get_option('posts_per_page'),
				'paged' => $paged,
			);
			$myposts = new WP_Query($args);
			if($myposts->have_posts()): while($myposts->have_posts()): $myposts->the_post();
		?>
					
			<dt><a href="<?php the_permalink(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a></dt>
			<dd><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></dd>
			
		<?php endwhile; endif; wp_reset_postdata(); ?>
	</dl>
</div><!-- news-list -->

<?php
  if(function_exists('wp_pagenavi')) {
    wp_pagenavi(array('query' => $myposts));
  }
?>
<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>