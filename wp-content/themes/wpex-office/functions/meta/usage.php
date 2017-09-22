<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'wpex_metaboxes' );

function wpex_metaboxes( array $meta_boxes ) {

	$prefix = 'office_';


	// Slides
	$meta_boxes[] = array(
		'id'         => 'slides_metabox',
		'title'      => 'Slide Options',
		'pages'      => array( 'slides', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Caption Title','wpex'),
				'desc' => __('Select to enable or disable your slide caption.','wpex'),
				'id' => $prefix . 'enable_caption',
				'type' => 'select',
				'options' => array(
					array( 'name' => 'disable', 'value' => 'disable', ),
					array( 'name' => 'enable', 'value' => 'enable', ),
				),
				'multiple' => false,
				'std' => 'default'
			),
			array(
				'name' => __('Slide Link URL','wpex'),
				'desc' => __('Enter a URL to link this slide to - perfect for linking slides to pages on your site or other sites. Do not forget the http:// in the url. (Optional)','wpex'),
				'id' => $prefix . 'slides_url',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' => __('Link Target','wpex'),
				'desc' => __('Select the target for the slide link.','wpex'),
				'id' => $prefix . 'slides_url_target',
				'type' => 'select',
				'options' => array(
					array( 'name' => 'disable', 'value' => 'disable', ),
					array( 'name' => 'enable', 'value' => 'enable', ),
				),
				'multiple' => false,
				'std' => 'default'
			),
			array(
				'name' => __('oEmbed URL (video)','wpex'),
				'desc' =>  __('Enter the video URL that is compatible with WP\'s built-in oEmbed feature.', 'wpex') .' <a href="http://codex.wordpress.org/Embeds" target="_blank">'. __('Learn More', 'wpex') .' &rarr;</a>',
				'id' => $prefix . 'slides_video_oembed',
				'type' => 'oembed',
				'std' => ''
        	),
			array(
				'name' => __('Description','wpex'),
				'desc' => __('Enter a description for your slide.','wpex'),
				'id' => $prefix . 'slides_description',
				'type' => 'wysiwyg',
				'options' => array(	'textarea_rows' => 3 ),
				'std' => ''
			),
		),
	);
	
	
	
	
	// Highlights
	$meta_boxes[] = array(
		'id'         => 'hp_highlights_metabox',
		'title'      => 'Highlight Options',
		'pages'      => array( 'hp_highlights', ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Link URL','wpex'),
				'desc' => __('Enter a URL to link the title of this highlight to. Optional.','wpex'),
				'id' => $prefix . 'hp_highlights_url',
				'type' => 'text',
				'std' => ''
			),
			array(
				'name' => __('Link Target','wpex'),
				'desc' => __('Select the target for the highlight link. Do not forget the http:// in the url. (Optional)','wpex'),
				'id' => $prefix . 'hp_highlights_url_target',
				'type' => 'select',
				'options' => array(
					array( 'name' => 'self', 'value' => 'self', ),
					array( 'name' => 'blank', 'value' => 'blank', ),
				),
				'multiple' => false,
				'std' => 'default'
			),
		),
	);
	
	
	
	// Portfolio
	$meta_boxes[] = array(
		'id'         => 'portfolio_metabox',
		'title'      => 'Post Options',
		'pages'      => array( 'portfolio', ),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
				array(
					'name' => __('Slider Shortcode', 'wpex'),
					'id' => 'wpex_page_slider_shortcode',
					'type' => 'text',
					'std' => '',
					'desc' => __('Here you can enter your slider shortcode to display at the top of the page, such as that of a revolution slider.', 'wpex')
				),
				array(
					'name' => __('Post Style', 'wpex'),
					'id' => $prefix . 'portfolio_style',
					'type' => 'select',
					'options' => array(
						array( 'name' => 'Default', 'value' => 'default' ),
						array( 'name' => 'Full', 'value' => 'full' ),
						array( 'name' => 'Grid', 'value' => 'grid' )
					),
					'std' => 'default',
					'desc' => __('Select your post style.', 'wpex')
				),
				array(
					'name' => __('Image Slider', 'wpex'),
					'id' => 'wpex_portfolio_post_slider',
					'desc' => __('If enabled please add your images via the gallery metabox below.', 'wpex'),
					'stf' => 'enabled',
					'type'    => 'select',
					'options' => array(
						array( 'name' => 'Enabled', 'value' => 'enabled', ),
						array( 'name' => 'Disabled', 'value' => 'disabled', ),
					),
				),
				array(
					'name' => __('Post Details', 'wpex'),
					'id' => 'wpex_portfolio_post_details',
					'desc' => __('Display the portfolio post details section? The global option in the theme admin panel will override this.', 'wpex'),
					'stf' => 'enabled',
					'type'    => 'select',
					'options' => array(
						array( 'name' => 'Enabled', 'value' => 'enabled', ),
						array( 'name' => 'Disabled', 'value' => 'disabled', ),
					),
				),
				array(
					'name' => __('Disable Featured Image', 'wpex'),
					'id' => 'wpex_post_thumb',
					'type' => 'checkbox',
					'std' => '',
					'desc' => __('Check this box to disable the featured image on this post when the slider is disabled.', 'wpex')
				),
				array(
					'name' => __('oEmbed URL (video)','wpex'),
					'desc' =>  __('Enter the video URL that is compatible with WP\'s built-in oEmbed feature.', 'wpex') .' <a href="http://codex.wordpress.org/Embeds" target="_blank">'. __('Learn More', 'wpex') .' &rarr;</a>',
					'id' => 'wpex_portfolio_post_video',
					'type' => 'oembed',
					'std' => ''
				),
				/* Coming soon
				array(
					'name' => __('Post Media Alternative', 'wpex'),
					'id' => 'wpex_portfolio_post_media_alternative',
					'desc' => __('If you rather not show a video, featured image or slider on the top of the single post, you can enter your alternative content here.', 'wpex'),
					'type' => 'wysiwyg',
					'std' => '',
					'options' => array(	'textarea_rows' => 3, ),
				), */
				array(
					'name' => __('Custom Date','wpex'),
					'desc' => __('Enter a custom date for the project details. Enter "disabled" to completely disable the date.','wpex'),
					'id' =>  'wpex_portfolio_post_date',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => __('Cost','wpex'),
					'desc' => __('Enter your cost for the project details.','wpex'),
					'id' => $prefix . 'portfolio_cost',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => __('Client','wpex'),
					'desc' => __('Enter a client name the project details.','wpex'),
					'id' => $prefix . 'portfolio_client',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => __('Link URL','wpex'),
					'desc' => __('Enter a URL for the project details. Don\'t forget the http://','wpex'),
					'id' => $prefix . 'portfolio_url',
					'type' => 'text',
					'std' => ''
				),
				array(
					'name' => __('Link Name','wpex'),
					'desc' => __('Enter a name for the project details link. If left blank it will just show the URL linking to itself.','wpex'),
					'id' => 'wpex_portfolio_post_url_name',
					'type' => 'text',
					'std' => ''
				),
			),
		);
		
		
		
	
	// Staff
	$meta_boxes[] = array(
		'id'         => 'staff_metabox',
		'title'      => 'Post Options',
		'pages'      => array( 'staff', ),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Position','wpex'),
				'desc' => __('Enter a position for your staff member.','wpex'),
				'id' => $prefix . 'staff_position',
				'type' => 'text',
				'std' => ''
        	),
		),
	);
	
	
	// Post Type names
	$portfolio_post_type_name = ( wpex_get_data('portfolio_post_type_name') ) ? wpex_get_data('portfolio_post_type_name') : __('Portfolio','wpex');
	$staff_post_type_name = ( wpex_get_data('staff_post_type_name') ) ? wpex_get_data('staff_post_type_name') : __('Staff','wpex');
	$services_post_type_name = ( wpex_get_data('services_post_type_name') ) ? wpex_get_data('services_post_type_name') : __('Services','wpex');
	$testimonials_post_type_name = ( wpex_get_data('testimonials_post_type_name') ) ? wpex_get_data('testimonials_post_type_name') : __('Testimonials','wpex');
	$faqs_post_type_name = ( wpex_get_data('faqs_post_type_name') ) ? wpex_get_data('faqs_post_type_name') : __('FAQs','wpex');	
	
	// Pages
	$meta_boxes[] = array(
		'id'         => 'pages_metabox',
		'title'      => 'Page Options',
		'pages'      => array( 'page', ),
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true,
		'fields'     => array(
			array(
				'name' => __('Image Slider', 'wpex'),
				'id' => $prefix . 'page_slider',
				'type' => 'select',
				'options' => array(
					array( 'name' => 'disable', 'value' => 'disable', ),
					array( 'name' => 'enable', 'value' => 'enable', ),
				),
				'multiple' => false,
				'std' => 'disable',
				'desc' => __('Choose to enable or disable the page slider based on image', 'wpex') . ' <a href="http://www.youtube.com/watch?v=RIcRKzzlsVY" target="_blank">attachments &rarr;</a>'
			),
			array(
				'name' => __('Slider Shortcode', 'wpex'),
				'id' => 'wpex_page_slider_shortcode',
				'type' => 'text',
				'std' => '',
				'desc' => __('Here you can enter your slider shortcode to display at the top of the page, such as that of a revolution slider.', 'wpex')
			),
			array(
				'name' => __('Blog Category', 'wpex'),
				'id' => $prefix . 'blog_parent',
				'type' => 'select',
				'options' => wpex_meta_term_array('category'),
				'desc' => __('Select a category for your blog page.', 'wpex')
			),
			array(
				'name' => $portfolio_post_type_name . ' ' . __('Category', 'wpex'),
				'id' => $prefix . 'portfolio_parent',
				'type' => 'select',
				'options' => wpex_meta_term_array('portfolio_cats'),
				'desc' => __('Select a category for your portfolio page.<br />For the filterable portfolio and portfolio by category it must be a parent category.', 'wpex')
			),
			array(
				'name' => $services_post_type_name . ' ' . __('Category', 'wpex'),
				'id' => $prefix . 'service_parent',
				'type' => 'select',
				'options' => wpex_meta_term_array('service_cats'),
				'desc' => __('Select a category for your services page.', 'wpex')
			),
			array(
				'name' => $faqs_post_type_name . ' ' . __('Category', 'wpex'),
				'id' => $prefix . 'faqs_parent',
				'type' => 'select',
				'options' => wpex_meta_term_array('faqs_cats'),
				'desc' => __('Select a category for your FAQs page.<br />Must choose a parent category.', 'wpex')
			),
			array(
				'name' => $staff_post_type_name . ' ' . __('Category', 'wpex'),
				'id' => $prefix . 'staff_parent',
				'type' => 'select',
				'options' => wpex_meta_term_array('staff_departments'),
				'desc' => __('Select a category for your staff page.<br />Must choose a parent category for the staff by category page template.', 'wpex')
			),
		),
	);
	
	

	return $meta_boxes;
}

add_action( 'init', 'cmb_initializewpexmeta_boxes', 9999 );

function cmb_initializewpexmeta_boxes() {

	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once( get_template_directory() .'/functions/meta/init.php');

}