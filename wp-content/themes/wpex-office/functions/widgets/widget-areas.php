<?php
/**
 * @package WordPress
 * @subpackage Office WordPress Theme
 * This file registers the theme's widget regions
 */

register_sidebar(array(
		'name' => __('Sidebar','wpex'),
		'id' => 'sidebar',
		'description' => __('Widgets in this area will be shown in the sidebar.','wpex'),
		'before_widget' => '<div class="sidebar-box %2$s clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4><span>',
		'after_title' => '</span></h4>'
));

if( wpex_get_data('disable_widgetized_footer','1') == '1' ) {
	
	register_sidebar(array(
		'name' => __('Footer Left','wpex'),
		'id' => 'footer-left',
		'description' => __('Widgets in this area will be shown in the footer left area.','wpex'),
		'before_widget' => '<div class="footer-widget %2$s clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'name' => __('Footer Middle','wpex'),
		'id' => 'footer-middle',
		'description' => __('Widgets in this area will be shown in the footer middle area.','wpex'),
		'before_widget' => '<div class="footer-widget %2$s clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'name' => __('Footer Right','wpex'),
		'id' => 'footer-right',
		'description' => __('Widgets in this area will be shown in the footer right area.','wpex'),
		'before_widget' => '<div class="footer-widget %2$s clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));
	
}