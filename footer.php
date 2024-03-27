<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package 吉祥寺ブレストクリニック
 */

?>

	<footer id="colophon" class="site-footer bg-w">
		<div class="inner wrapper">
			<div class="info-blk">
				<p class="footer-logo"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="/newsite/wp/wp-content/uploads/2023/11/logo.svg" alt="<?php bloginfo( 'name' ); ?>"></a></p>
				<p class="address">
					東京都武蔵野市吉祥寺本町2-10-8　フィオリトゥーラ吉祥寺Ⅰ-2F<br>JR・京王井の頭線吉祥寺駅徒歩5分
				</p>
				<p class="phone">
					お電話でのご予約・お問い合わせ<br><a href="tel:0422-23-7600">TEL.<span>0422-23-7600</span></a>
				</p>
				<ul class="notes">
					<li>予約優先
						<p>お急ぎの場合は、予約なしでの受診も可能です（最終受付は診療終了の30分前まで／お待ち時間が長くなる可能性もございます）。<br><a href="#">予約なしでの受診をご希望の方へのご案内はこちら</a></p>
					</li>
					<li>お支払いは、現金のみです。</li>
				</ul>

				<?php get_template_part( 'file/medical-hours-tbl' );?>
			</div><!-- info-blk -->

			<div class="map">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3239.883186906716!2d139.5749547002121!3d35.70449207921939!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6018ee48f053865b%3A0xd1a0b4149a05179c!2z5ZCJ56Wl5a-644OW44Os44K544OI44Kv44Oq44OL44OD44Kv!5e0!3m2!1sja!2sjp!4v1701320606693!5m2!1sja!2sjp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
			</div><!-- map -->
		</div><!-- inner -->

		<div class="nav-blk bg-b">
			<nav class="footer-navigation inner">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer1',
						'menu_id'        => 'footer-menu1',
					)
				);
				wp_nav_menu(
					array(
						'theme_location' => 'footer2',
						'menu_id'        => 'footer-menu2',
					)
				);
				?>
			</nav>
		</div><!-- nav-blk -->

		<div class="site-info">
			&copy; <?php echo date('Y'); ?> 【乳腺専門】<?php bloginfo( 'name' ); ?>. All Rights Reserved.
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
