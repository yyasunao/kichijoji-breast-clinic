<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package 吉祥寺ブレストクリニック
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="site-branding inner wrapper">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="/newsite/wp/wp-content/uploads/2023/11/logo.svg" alt="<?php bloginfo( 'name' ); ?>"></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="/newsite/wp/wp-content/uploads/2023/11/logo.svg" alt="<?php bloginfo( 'name' ); ?>"></a></p>
				<?php
			endif;
			$kichijoji_breast_clinic_description = get_bloginfo( 'description', 'display' );
			if ( $kichijoji_breast_clinic_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $kichijoji_breast_clinic_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<?php endif; ?>
			<a href="tel:0422-23-7600" class="phone pc">TEL.<span>0422-23-7600</span></a>
			<a href="appointment" class="appointment">Web予約</a>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation inner">
			<button class="menu-btn sp">
				<span></span>
				<span></span>
				<span></span>
			</button>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'global',
					'menu_id'        => 'header-menu',
				)
			);
			?>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->
