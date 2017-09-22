<?php
/**
 * Registers custom taxomies for use with this theme
 *
 * @package WordPress
 * @subpackage Office
 * @since Office 1.0
*/

add_action( 'init', 'office_register_taxonomies' );

if ( !function_exists('office_register_taxonomies') ) {
	
	function office_register_taxonomies() {
		
		//portfolio
		$portfolio_post_type_name = wpex_get_data('portfolio_post_type_name') ? wpex_get_data('portfolio_post_type_name') : __('Portfolio','wpex');
		$portfolio_tax_slug = wpex_get_data('portfolio_tax_slug') ? wpex_get_data('portfolio_tax_slug') : 'portfolio-category';
	
		//staff category slug
		$staff_post_type_name = wpex_get_data('staff_post_type_name') ? wpex_get_data('staff_post_type_name') : __('Staff','wpex');
		$staff_taxonomy_slug = wpex_get_data('staff_tax_slug') ? wpex_get_data('staff_tax_slug') : 'department';
	
		//services category slug
		$service_post_type_name = wpex_get_data('service_post_type_name') ? wpex_get_data('service_post_type_name') : __('Services','wpex');
		$service_taxonomy_slug = wpex_get_data('services_tax_slug') ? wpex_get_data('services_tax_slug') : 'service-category';
	
		//faqs category slug
		$faqs_post_type_name = wpex_get_data('faqs_post_type_name') ? wpex_get_data('faqs_post_type_name') : __('FAQs','wpex');
		$faqs_taxonomy_slug = wpex_get_data('faqs_tax_slug') ? wpex_get_data('faqs_tax_slug') : 'faq-category';
	
		// Portfolio taxonomies
		register_taxonomy('portfolio_cats','portfolio',array(
			'hierarchical' => true,
			'labels' => apply_filters('office_portfolio_tax_labels', array(
				'name' => $portfolio_post_type_name . ' ' . __( 'Categories', 'wpex' ),
				'singular_name' => $portfolio_post_type_name . ' '. __( 'Category', 'wpex' ),
				'search_items' =>  __( 'Search Categories', 'wpex' ),
				'all_items' => __( 'All Categories', 'wpex' ),
				'parent_item' => __( 'Parent Category', 'wpex' ),
				'parent_item_colon' => __( 'Parent Category:', 'wpex' ),
				'edit_item' => __( 'Edit  Category', 'wpex' ),
				'update_item' => __( 'Update Category', 'wpex' ),
				'add_new_item' => __( 'Add New  Category', 'wpex' ),
				'new_item_name' => __( 'New Category Name', 'wpex' ),
				'choose_from_most_used'	=> __( 'Choose from the most used categories', 'wpex' )
				)
			),
			'query_var' => true,
			'rewrite' => array( 'slug' => $portfolio_tax_slug ),
		));
		
		
		// Staff taxonomies
		register_taxonomy('staff_departments','staff',array(
			'hierarchical' => true,
			'labels' => apply_filters('office_staff_tax_labels', array(
				'name' => $staff_post_type_name . ' ' . __( 'Categories', 'wpex' ),
				'singular_name' => $staff_post_type_name . ' '. __( 'Category', 'wpex' ),
				'search_items' =>  __( 'Search Categories', 'wpex' ),
				'all_items' => __( 'All Categories', 'wpex' ),
				'parent_item' => __( 'Parent Category', 'wpex' ),
				'parent_item_colon' => __( 'Parent Category:', 'wpex' ),
				'edit_item' => __( 'Edit  Category', 'wpex' ),
				'update_item' => __( 'Update Category', 'wpex' ),
				'add_new_item' => __( 'Add New  Category', 'wpex' ),
				'new_item_name' => __( 'New Category Name', 'wpex' ),
				'choose_from_most_used'	=> __( 'Choose from the most used categories', 'wpex' )
				)
			),
			'query_var' => true,
			'rewrite' => array( 'slug' => $staff_taxonomy_slug ),
		));
		
		
		// FAQ taxonomies
		register_taxonomy('faqs_cats','faqs',array(
			'hierarchical' => true,
			'labels' => apply_filters('office_faqs_tax_labels', array(
				'name' => $faqs_post_type_name . ' ' . __( 'Categories', 'wpex' ),
				'singular_name' => $faqs_post_type_name . ' '. __( 'Category', 'wpex' ),
				'search_items' =>  __( 'Search Categories', 'wpex' ),
				'all_items' => __( 'All Categories', 'wpex' ),
				'parent_item' => __( 'Parent Category', 'wpex' ),
				'parent_item_colon' => __( 'Parent Category:', 'wpex' ),
				'edit_item' => __( 'Edit  Category', 'wpex' ),
				'update_item' => __( 'Update Category', 'wpex' ),
				'add_new_item' => __( 'Add New  Category', 'wpex' ),
				'new_item_name' => __( 'New Category Name', 'wpex' ),
				'choose_from_most_used'	=> __( 'Choose from the most used categories', 'wpex' )
				)
			),
			'query_var' => true,
			'rewrite' => array( 'slug' => $faqs_taxonomy_slug ),
		));
		
		// Service taxonomies
		register_taxonomy('service_cats','services',array(
			'hierarchical' => true,
			'labels' => apply_filters('office_service_tax_labels', array(
				'name' => $service_post_type_name . ' ' . __( 'Categories', 'wpex' ),
				'singular_name' => $service_post_type_name . ' '. __( 'Category', 'wpex' ),
				'search_items' =>  __( 'Search Categories', 'wpex' ),
				'all_items' => __( 'All Categories', 'wpex' ),
				'parent_item' => __( 'Parent Category', 'wpex' ),
				'parent_item_colon' => __( 'Parent Category:', 'wpex' ),
				'edit_item' => __( 'Edit  Category', 'wpex' ),
				'update_item' => __( 'Update Category', 'wpex' ),
				'add_new_item' => __( 'Add New  Category', 'wpex' ),
				'new_item_name' => __( 'New Category Name', 'wpex' ),
				'choose_from_most_used'	=> __( 'Choose from the most used categories', 'wpex' )
				)
			),
			'query_var' => true,
			'rewrite' => array( 'slug' => $service_taxonomy_slug ),
		));
	
	}
	
} ?>