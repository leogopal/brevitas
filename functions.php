<?php
/**
 * Brevitas functions and definitions
 *
 * @package Brevitas
 */

define( 'BREVITAS_NAME', 'Brevitas' );
define( 'BREVITAS_AUTHOR', 'Leo Gopal' );
define( 'BREVITAS_AUTHOR_URI', 'http://leogopal.com/' );
define( 'BREVITAS_VERSION', '1.0.0' );
define( 'BREVITAS_IMG', get_template_directory_uri() . '/assets/images/' );

if ( ! function_exists( 'brevitas_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function brevitas_setup() {
	global $content_width;

	/**
	 * Set the content width based on the theme's design and stylesheet.
	 */
	if ( ! isset( $content_width ) ) {
		$content_width = 624; /* pixels */
	}

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Brevitas, use a find and replace
	 * to change 'brevitas' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'brevitas', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'brevitas_custom_background_args', array(
		'default-color'	=> '323232',
		'default-image'	=> get_template_directory_uri() . '/assets/images/bg.png',
	) ) );

	/**
	 * Enable support for Post Thumbnails on posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Enable title-tag support
	 */
	add_theme_support( "title-tag" );

	/**
	 * Custom Header theme support
	 */
	add_theme_support( 'custom-header' );

	// add a hard cropped (for uniformity) image size for the product grid
	add_image_size( 'brevitas_featured_image', 738, 200, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'header' => __( 'Header Menu', 'brevitas' ),
	) );

	// Enable support for HTML5 markup.
	add_theme_support( 
		'html5',
		array( 
			'comment-list', 
			'search-form', 
			'comment-form', 
			'gallery', 
			'caption' 
		)
	);
}
endif; // brevitas_setup
add_action( 'after_setup_theme', 'brevitas_setup' );

/**
 * Register Admin Editor Styles
 * 
 * @link https://codex.wordpress.org/Function_Reference/add_editor_style
 */
function brevitas_add_editor_styles() {
    add_editor_style( 'brevitas-editor-style.css' );
}
add_action( 'admin_init', 'brevitas_add_editor_styles' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function brevitas_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Main Sidebar', 'brevitas' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<span class="widget-title">',
		'after_title'   => '</span>',
	) );
}
add_action( 'widgets_init', 'brevitas_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function brevitas_scripts() {

	wp_enqueue_script( 'brevitas-pace-loader', get_template_directory_uri() . '/assets/js/pace.min.js', array(), BREVITAS_VERSION, false );
	// main stylesheet
	wp_enqueue_style( 'brevitas-style', get_stylesheet_uri() );
	// Roboto Slab from Google fonts
	wp_enqueue_style( 'brevitas-google-fonts-roboto-slab', 	'//fonts.googleapis.com/css?family=Roboto+Slab:400,700' );
	// Font Awesome
	wp_enqueue_style( 'brevitas-font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome/css/font-awesome.min.css' );
	// responsive navigation script
	wp_enqueue_script( 'brevitas-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), BREVITAS_VERSION, true );
	// skip link script
	wp_enqueue_script( 'brevitas-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), BREVITAS_VERSION, true );
	// comments reply support
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) :
		wp_enqueue_script( 'comment-reply' );
	endif;
}
add_action( 'wp_enqueue_scripts', 'brevitas_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Replace excerpt ellipses with new ellipses and link to full article
 */
function brevitas_excerpt_more( $more ) {
	return '...</p> <p class="continue-reading"><a class="more-link" href="' . get_permalink( get_the_ID() ) . '">' . get_theme_mod( 'brevitas_read_more', __( 'Continue reading', 'brevitas' ) ) . '<i class="fa fa-caret-right"></i></a></p>';
}
add_filter( 'excerpt_more', 'brevitas_excerpt_more' );


/**
 * Only show regular posts in search results. Also account for the bbPress search form.
 */
function brevitas_search_filter( $query ) {
	if ( $query->is_search && ! is_admin() && ( class_exists( 'bbPress' ) && ! is_bbpress() ) )
		$query->set( 'post_type', 'post' );
		
	return $query;
}
add_filter( 'pre_get_posts','brevitas_search_filter' );


/**
 * stupid skip link thing with the more tag -- remove it -- NOW
 */
function brevitas_remove_more_tag_link_jump( $link ) {
    $offset = strpos( $link, '#more-' );
    
    if ( $offset ) :
        $end = strpos( $link, '"', $offset );
    endif;
    
    if ( $end ) :
        $link = substr_replace( $link, '', $offset, $end-$offset );
    endif;
    
    return $link;
} 
add_filter( 'the_content_more_link', 'brevitas_remove_more_tag_link_jump' );