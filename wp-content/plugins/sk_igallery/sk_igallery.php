<?php
/*
Plugin Name: iGallery
Plugin URI: http://sakurapixel.com
Description: iGallery is a WordPress Plugin that shows your images in an interactive way
Author: SakuraPixel
Version: 1.1.0
Author URI: http://sakurapixel.com
*/

define('IGALLERY_TEMPPATH', plugins_url('', __FILE__));
define('IGALLERY_CLASS_PATH', plugin_dir_path(__FILE__));
define('IGALLERY_TEXTDOMAIN', 'igallery');
load_plugin_textdomain(IGALLERY_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


require_once(IGALLERY_CLASS_PATH.'/com/sakurapixel/php/igallery_core.php');


$igallery_app = new SKIGallery();
$igallery_app->start();

//register activation handler
register_activation_hook(__FILE__, 'igallery_activate' );
function igallery_activate()
{	
	if(version_compare(get_bloginfo('version'), '3.5', '<' )){
		deactivate_plugins(basename( __FILE__ ));
	}
}

?>
