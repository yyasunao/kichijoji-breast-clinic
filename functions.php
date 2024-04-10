<?php
/**
 * 吉祥寺ブレストクリニック functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package 吉祥寺ブレストクリニック
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.1' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kichijoji_breast_clinic_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on 吉祥寺ブレストクリニック, use a find and replace
		* to change 'kichijoji-breast-clinic' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'kichijoji-breast-clinic', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'kichijoji-breast-clinic' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'kichijoji_breast_clinic_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'kichijoji_breast_clinic_setup' );

/**
 * Admin style
 */
function kichijoji_breast_clinic_child_add_admin_style(){
	$theme = wp_get_theme();
	$theme_ver = $theme->Version;

	wp_enqueue_style( 'kichijoji_breast_clinic_child_admin_style', get_theme_file_uri( '/style-admin.css' ), '', $theme_ver );
}
add_action( 'admin_enqueue_scripts', 'kichijoji_breast_clinic_child_add_admin_style', 99 );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kichijoji_breast_clinic_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'kichijoji_breast_clinic_content_width', 640 );
}
add_action( 'after_setup_theme', 'kichijoji_breast_clinic_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function kichijoji_breast_clinic_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'kichijoji-breast-clinic' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'kichijoji-breast-clinic' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'kichijoji_breast_clinic_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function kichijoji_breast_clinic_scripts() {
	global $post;

	wp_enqueue_style( 'kichijoji-breast-clinic-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'kichijoji-breast-clinic-style', 'rtl', 'replace' );
	wp_enqueue_style( 'kichijoji-breast-clinic-validationEngine', get_template_directory_uri() . '/js/validation/css/validationEngine.jquery.css', array(), _S_VERSION );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'kichijoji-breast-clinic-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'kichijoji-breast-clinic-child-japanese-holidays', "https://cdn.rawgit.com/osamutake/japanese-holidays-js/v1.0.10/lib/japanese-holidays.min.js" , array('jquery'), "", true );
	wp_enqueue_script( 'breast-clinic-jquery-ui', 'https://code.jquery.com/ui/1.9.2/jquery-ui.js', array('jquery'), false, true );
	wp_enqueue_script( 'kichijoji-breast-clinic-datepicker', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js', array('jquery'), false, true );
	wp_enqueue_script( 'kichijoji-breast-clinic-validationEngine-ja', get_template_directory_uri() . '/js/validation/languages/jquery.validationEngine-ja.js', array('jquery'), false, true );
	wp_enqueue_script( 'kichijoji-breast-clinic-validationEngine', get_template_directory_uri() . '/js/validation/jquery.validationEngine.min.js', array('jquery'), false, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	//予約フォーム
	$get_page = get_page_by_path( "appointment" );
	$get_page_id = $get_page->ID;
	if( $get_page_id == $post->post_parent ) {
		wp_enqueue_script( 'kichijoji-breast-clinic-appointment', get_template_directory_uri() . '/js/appointment.js', array('jquery'), '1.1', true );
		wp_localize_script(
			'kichijoji-breast-clinic-appointment',
			'kbc_localize_data',
			array(
				'get_stylesheet_directory_uri' => get_stylesheet_directory_uri(),
				'ABSPATH' => ABSPATH,
				'post_name' => $post->post_name,

			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'kichijoji_breast_clinic_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

