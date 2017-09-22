<?php
/**
 * @package WordPress
 * @subpackage Office Theme
*/


/*--------------------------------------*/
/* Define Constants
/*--------------------------------------*/
define( 'WPEX_JS_DIR_URI', get_template_directory_uri().'/js/' );
define( 'WPEX_CSS_DIR_URI', get_template_directory_uri().'/css/' );
define( 'WPEX_SHORTCODES_DIR_URI', get_template_directory_uri().'/functions/shortcodes/' );
define( 'WPEX_SHORTCODES_DIR', get_template_directory().'/functions/shortcodes/' );




/*--------------------------------------*/
/* Globals
/*--------------------------------------*/
if ( ! isset( $content_width ) ) $content_width = 660;




/*--------------------------------------*/
/*	Theme Setup
/*--------------------------------------*/

// Recommended Plugins
require_once( get_template_directory() .'/functions/recommend-plugins.php' );

// Theme Support
add_theme_support( 'custom-background');
add_theme_support( 'post-thumbnails' );
add_theme_support( 'woocommerce' ); // Not fully supported yet => but the nag is annoying!

// Register navigation menus
register_nav_menus(
	array(
		'top_menu' => __('Top','wpex'),
		'main_menu' => __('Main','wpex'),
		'footer_menu' => __('Footer','wpex')
	)
);

// Localization Support
load_theme_textdomain( 'wpex', get_template_directory()  .'/lang' );

/*--------------------------------------*/
/*	Include functions
/*--------------------------------------*/

// Functions needed prior to loading the admin
require_once( get_template_directory() .'/functions/post-types-taxonomies/register-post-types.php' );
require_once( get_template_directory() .'/functions/post-types-taxonomies/register-taxonomies.php' );
require_once( get_template_directory() .'/functions/social.php' );

// Admin Options
require_once ( get_template_directory() .'/admin/index.php');
require_once( get_template_directory() .'/functions/return-smof-data.php' ); // returns data

// Common files
require_once( get_template_directory() .'/functions/aqua-resizer.php' );
require_once( get_template_directory() .'/functions/custom-login-logo.php' );
require_once( get_template_directory() .'/functions/google-fonts.php');
require_once( get_template_directory() .'/functions/woocommerce-tweaks.php');
require_once( get_template_directory() .'/functions/widgets/widget-areas.php');
require_once( get_template_directory() .'/functions/widgets/flickr-widget.php');
require_once( get_template_directory() .'/functions/widgets/testimonials.php');
require_once( get_template_directory() .'/functions/widgets/recent-portfolio.php');
require_once( get_template_directory() .'/functions/post-types-taxonomies/post-type-helpers.php' );
require_once( get_template_directory() .'/functions/support-external-plugins.php');

// Shortcodes
if ( wpex_get_data( 'built_in_shortcodes', '1' ) == '1' ) {
	require_once( get_template_directory() .'/functions/shortcodes.php');
	require_once( get_template_directory() .'/mce/shortcode-popup.php');
}


// Lets load files/functions only when they are needed
if ( is_admin() ) {
	
	// Load files ONLY in the back-end
	require_once( get_template_directory() .'/functions/mce.php');
	require_once( get_template_directory() .'/functions/meta/gallery-metabox/gmb-admin.php' );
	require_once( get_template_directory() .'/functions/meta/usage.php');
	require_once( get_template_directory() .'/functions/custom-editor-columns.php' );
	require_once( get_template_directory() .'/functions/post-types-taxonomies/tax-filters.php' );
	
} else {

	// Load files only on the front-end
	require_once( get_template_directory() .'/functions/meta/gallery-metabox/gmb-display.php' );
	require_once( get_template_directory() .'/functions/comments-callback.php');
	require_once( get_template_directory() .'/functions/image-default-sizes.php' );
	require_once( get_template_directory() .'/functions/posts-per-page.php' );
	require_once( get_template_directory() .'/functions/pagination.php');
	require_once( get_template_directory() .'/functions/breadcrumbs.php');
	require_once( get_template_directory() .'/functions/custom-excerpts.php');
	require_once( get_template_directory() .'/functions/custom-css.php' );
	require_once( get_template_directory() .'/functions/scripts.php' );
	require_once( get_template_directory() .'/functions/social-output.php' );
	
	if ( wpex_get_data('clean_up_head','1') == '1' ) {
		require_once( get_template_directory() .'/functions/cleanup-head.php' );
	}
	// Remove Canonical Link Added By Yoast WordPress SEO Plugin
	function at_remove_dup_canonical_link() {
	return false;
	}
	add_filter( 'wpseo_canonical', 'at_remove_dup_canonical_link' );
}