<?php
add_action( 'init', 'of_options' );

if (!function_exists( 'of_options' )) {
	
	function of_options() {
	
		//Access the WordPress Categories via an Array
		$of_categories = array();  
		$of_categories_obj = get_categories( 'hide_empty=0' );
		foreach ($of_categories_obj as $of_cat) {
			$of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
		$categories_tmp = array_unshift($of_categories, "Select");  
		
		//Access portfolio categories via an Array
		$portfolio_cats_args = array( 'hide_empty'	=> '1' );
		$portfolio_cats_terms = get_terms( 'portfolio_cats', $portfolio_cats_args);
		$portfolio_cats_tax = array();
		foreach ( $portfolio_cats_terms as $portfolio_cats_term) {
			$portfolio_cats_tax[$portfolio_cats_term->term_id] = $portfolio_cats_term->slug;
		}
		$portfolio_cats_tax_tmp = array_unshift($portfolio_cats_tax, "Select");      
		
		//Vars
		$color_schemes = array( 'light', 'dark' );
		$cropped_full = array( 'cropped', 'full' );
		$service_cat_layout = array( 'full', 'sidebar' );
		$fixed_static = array( 'fixed', 'static' );
		$blank_self = array( 'blank', 'self' );
		$header_styles = array( 'one', 'two', 'three', 'four' );
		$blog_styles = array( 'one', 'two' );
		$font_size = array( 'select', '12px', '13px', '14px' );
		$font_style = array( "normal", "italic", "bold", "bold italic");
		
		$of_options_homepage_blocks = array(
			"enabled"	=> array (
				"placebo"	=> "placebo", //REQUIRED!
				"home_slider"	=> "FlexSlider",
				"home_revslider"	=> "RevSlider",
				"home_tagline"	=> "Tagline",
				"home_highlights"	=> "Highlights",
				"home_portfolio"	=> "Portfolio",
				"home_blog"	=> "Blog"		
			),
			"disabled"	=> array (
				"placebo"	=> "placebo", //REQUIRED!
				"home_static_video"	=> "Video",
				"home_static_page"	=> "Page Content",
			),
		);
		
		//Stylesheets Reader
		$alt_stylesheet_path = LAYOUT_PATH;
		$alt_stylesheets = array();
		
		if ( is_dir($alt_stylesheet_path) ) {
			if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
				while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
					if(stristr($alt_stylesheet_file, ".css") !== false) {
						$alt_stylesheets[] = $alt_stylesheet_file;
					}
				}    
			}
		}
		
		//Background Images Reader
		$bg_images_path =  get_template_directory(). '/images/bg/'; // change this to where you store your bg images
		$bg_images_url = get_template_directory_uri().'/images/bg/'; // change this to where you store your bg images
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
			if ($bg_images_dir = opendir($bg_images_path) ) { 
				while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
					if(stristr($bg_images_file, ".png") !== false || stristr($bg_images_file, ".jpg") !== false) {
						$bg_images[] = $bg_images_url . $bg_images_file;
					}
				}    
			}
		}
		
		
		/*-----------------------------------------------------------------------------------*/
		/* The Options Array */
		/*-----------------------------------------------------------------------------------*/
		
		// Set the Options Array
		global $of_options;
		$of_options = array();
		
		$of_options[] = array( "name"	=> __( 'General', 'wpex' ),
								"type"	=> "heading",
							);
							
		$of_options[] = array( "name"	=> __( 'Main Logo', 'wpex' ),
								"desc"	=> __( 'Use this field to upload your custom logo for use in the theme header.', 'wpex' ),
								"id"	=> "custom_logo",
								"std"	=> "",
								"type"	=> "media",
							);
							
		$of_options[] = array( "name"	=> __( 'Main Retina Logo', 'wpex' ),
								"desc"	=> __( 'Use this field to upload your custom logo (retina version) for use in the theme header. This should be 2x as large as the standard logo defined above.', 'wpex' ),
								"id"	=> "retina_logo",
								"std"	=> "",
								"type"	=> "media",
								
							);
		
		$of_options[] = array( "name"	=> __( 'Retina Logo Height', 'wpex' ),
								"desc"	=> __( 'Enter the height for your logo in px. This is used to make sure your retina logo loads correctly. It should be 2x the size of your standard logo image.', 'wpex' ),
								"id"	=> "retina_logo_height",
								"std"	=> "",
								"type"	=> "text",
							);
						
		$of_options[] = array( "name"	=> __( 'Retina Logo Width', 'wpex' ),
						"desc"	=> __( 'Enter the width for your logo in px. This is used to make sure your retina logo loads correctly. It should be 2x the size of your standard logo image.', 'wpex' ),
						"id"	=> "retina_logo_width",
						"std"	=> "",
						"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Login Logo', 'wpex' ),
							"desc"	=> __( 'Upload a custom logo for your Wordpress login screen.', 'wpex' ),
							"id"	=> "custom_login_logo",
							"std"	=> "",
							"type"	=> "media");
							
		$of_options[] = array( "name"	=> __( 'Login Logo Height', 'wpex' ),
							"desc"	=> __( 'Enter the height of your custom logo to override the default WordPress image height. Width, must not be changed.', 'wpex' ),
							"id"	=> "custom_login_logo_height",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Favicon', 'wpex' ),
							"desc"	=> __( 'Upload or past the URL for your custom favicon.', 'wpex' ),
							"id"	=> "custom_favicon",
							"std"	=> "",
							"type"	=> "media");
							
		$of_options[] = array( "name"	=> __( 'Custom Responsive Menu Text', 'wpex' ),
							"desc"	=> __( 'Enter your custom responsive menu text. Leave blank to show localized string.', 'wpex' ),
							"id"	=> "responsive_menu_text",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Built-in Shortcodes', 'wpex' ),
							"desc"	=> __( 'Do you wish to use the built-in shortcodes? If not, disable it to remove some bloat and speed things up a bit!', 'wpex' ),
							"id"	=> "built_in_shortcodes",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Retina Support For Featured Images', 'wpex' ),
							"desc"	=> __( 'Enable or disable retina support for featured images. If enabled an @2x image will be created for each featured image when possible. Disable to save server space.', 'wpex' ),
							"id"	=> "enable_retina",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
												
		$of_options[] = array( "name"	=> __( 'Auto Lightbox For WP Galleries', 'wpex' ),
							"desc"	=> __( 'Select to automatically add lightbox support for the default WordPress image galleries.', 'wpex' ),
							"id"	=> "wp_gallery_lightbox",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Clean-up WP Head', 'wpex' ),
							"desc"	=> __( 'Enable to clean up your site\'s header from auto code added by WP, such as the WP version.', 'wpex' ),
							"id"	=> "clean_up_head",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
							
		$of_options[] = array( "name"	=> __( 'Header', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Top Bar', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
												
		$of_options[] = array( "name"	=> __( 'Top Bar', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable the top bar', 'wpex' ),
							"id"	=> "disable_top_bar",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Top Bar Static or Fixed', 'wpex' ),
							"desc"	=> __( 'Select if you want a static or fixed top bar. The Fixed option will keep the bar always visible.', 'wpex' ),
							"id"	=> "top_bar_position",
							"std"	=> "fixed",
							"fold"	=> "disable_top_bar",
							"type"	=> "select",
							"options"	=> $fixed_static);
							
		$of_options[] = array( "name"	=> __( 'Top Bar Callout Button Text', 'wpex' ),
							"desc"	=> __( 'Enter the text for your top bar callout button.', 'wpex' ),
							"id"	=> "callout_text",
							"std"	=> "Sample Text",
							"fold"	=> "disable_top_bar",
							"type"	=> "text"); 
							
		$of_options[] = array( "name"	=> __( 'Top Bar Callout Button Link', 'wpex' ),
							"desc"	=> __( 'Enter the url to link your top bar callout button to', 'wpex' ),
							"id"	=> "callout_link",
							"std"	=> "#sample-url",
							"fold"	=> "disable_top_bar",
							"type"	=> "text"); 
		
		$of_options[] = array( "name"	=> __( 'Top Bar Link Target', 'wpex' ),
							"desc"	=> __( 'Select your link target', 'wpex' ),
							"id"	=> "callout_target",
							"std"	=> "blank",
							"fold"	=> "disable_top_bar",
							"type"	=> "select",
							"options"	=> $blank_self);
	
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Main Header', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Header Search Bar', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable the search bar in the header', 'wpex' ),
							"id"	=> "enable_disable_search",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Header Callout Text (Phone Number)', 'wpex' ),
							"desc"	=> __( 'Enter your text for the callout area in the header. This is the area where you most likely want to add a phone number.', 'wpex' ),
							"id"	=> "header_phone",
							"std"	=> "Add anything here!",
							"type"	=> "textarea");			
		
							
		$of_options[] = array( "name"	=> __( 'Header Style', 'wpex' ),
							"desc"	=> __( 'Select your header style', 'wpex' ),
							"id"	=> "header_style",
							"std"	=> "three",
							"type"	=> "select",
							"options"	=> $header_styles);
							
		$of_options[] = array( "name"	=> __( 'Header Top/Bottom Padding', 'wpex' ),
							"desc"	=> __( 'Enter your header top and bottom padding in pixels. Default is 25px.', 'wpex' ),
							"id"	=> "header_padding",
							"std"	=> "25px",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Logo Top Margin', 'wpex' ),
							"desc"	=> __( 'Enter a top margin value for your logo in pixels Default is 0px.', 'wpex' ),
							"id"	=> "logo_top_margin",
							"std"	=> "0px",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Header Social/Phone/Search Top Margin', 'wpex' ),
							"desc"	=> __( 'Enter a top margin value for your header aside section which includes the social icons, phone number and search form. Default is 25px.', 'wpex' ),
							"id"	=> "header_aside_margin",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Header Social/Phone/Search On Small Devices', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable the callout text, social and header seach on small screens.', 'wpex' ),
							"id"	=> "header_right_small_screens",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Background', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( "name"	=> __( 'Background Image', 'wpex' ),
							"desc"	=> __( 'Select a background pattern for your site. If you choose "none" you can then upload your custom background image or select a custom background color at Apperance->Background.', 'wpex' ),
							"id"	=> "custom_bg",
							"std"	=> $bg_images_url."bg0.png",
							"type"	=> "tiles",
							"options"	=> $bg_images,
		);
									
							
		// STYLING
		$of_options[] = array( "name"	=> __( 'Styling', 'wpex' ),
							"type"	=> "heading");
																				
		$of_options[] = array( "name"	=> __( 'Responsive Layout', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable the responsive layout', 'wpex' ),
							"id"	=> "disable_responsive",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Main Wrapper Background Pattern', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable the light diamond pattern on main content background', 'wpex' ),
							"id"	=> "disable_background_pattern",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Main Wrapper Shadow', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable the main wrapper drop shadow', 'wpex' ),
							"id"	=> "disable_main_shadow",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Right Border On Last Menu Item', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable the border on the lasst menu icon. Best if you do not have a lot of menu items on your main navigation', 'wpex' ),
							"id"	=> "disable_menu_last_border",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$url =  ADMIN_DIR . 'assets/images/';
		$of_options[] = array( "name"	=> __( 'Sidebar Layout', 'wpex' ),
							"desc"	=> __( 'Select the sidebar position for your site. Choose left or right.', 'wpex' ),
							"id"	=> "sidebar_position",
							"std"	=> "right",
							"type"	=> "images",
							"options"	=> array(
								'right'	=> $url . '2cr.png',
								'left'	=> $url . '2cl.png' )
							);
			
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'General Styling', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
				
		$of_options[] = array( "name"	=> __( 'Highlight Color', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "highlight_color",
							"std"	=> "#fc6440",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Main Body Link Color', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "main_link_color",
							"std"	=> "#ec651b",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Homepage Tagline Link Color', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "home_tagline_link_color",
							"std"	=> "#fc6440",
							"type"	=> "color");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Top Bar Styling', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
					
		$of_options[] = array( "name"	=> __( 'Top Bar Background', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "top_bar_background",
							"std"	=> "#444",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Top Bar Text', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "top_bar_color",
							"std"	=> "#bbb",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Callout Button Background', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "callout_background",
							"std"	=> "#fc6440",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Callout Button Background Hover', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "callout_background_hover",
							"std"	=> "#fc6440",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Callout Button Text', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "callout_color",
							"std"	=> "#fff",
							"type"	=> "color");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Header Styling', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
					
		$of_options[] = array( "name"	=> __( 'Header Background', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "header_background",
							"std"	=> "#FFF",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Header Text', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "header_color",
							"std"	=> "#444",
							"type"	=> "color");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Navigation Styling', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Navigation Background', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "nav_bg_color",
							"std"	=> "#2b2b2b",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Navigation Link', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "nav_link_color",
							"std"	=> "#FFF",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Navigation Link Hover Background', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "nav_hover_color",
							"std"	=> "#fc6440",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Navigation Link Hover Text', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "nav_link_hover_color",
							"std"	=> "#FFF",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Navigation Current Item Background', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "nav_current_background_color",
							"std"	=> "#fc6440",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Navigation Current Item Text', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "nav_current_link_color",
							"std"	=> "#FFF",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Navigation Light Border', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "nav_light_border_color",
							"std"	=> "#3c3c3c",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Navigation Dark Border', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "nav_dark_border_color",
							"std"	=> "#111",
							"type"	=> "color");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'FlexSlider Styling', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Slider Caption Background', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "slider_caption_background_color",
							"std"	=> "#000",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Slider Caption Text', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "slider_caption_color",
							"std"	=> "#FFF",
							"type"	=> "color");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Footer Styling', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
					
		$of_options[] = array( "name"	=> __( 'Footer Background', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "footer_background",
							"std"	=> "#2b2b2b",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Footer Text', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "footer_color",
							"std"	=> "#cccccc",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Footer Borders', 'wpex' ),
							"desc"	=> __( 'Select your preferred color.', 'wpex' ),
							"id"	=> "footer_border",
							"std"	=> "#444444",
							"type"	=> "color");
							
		$of_options[] = array( "name"	=> __( 'Typography', 'wpex' ),
								"type"	=> "heading");
								
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Main', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
								
		$of_options[] = array( "name"	=> "Body",
							"desc"	=> __( 'Select your font', 'wpex' ),
							"id"	=> "body_gfont",
							"std"	=> 'default',
							"type"	=> "select_google_font",
							"preview"	=> array(
											"text"	=> __( 'Font Preview Text', 'wpex' ),
											"size"	=> "30px"
							),
							"options"	=>  listgooglefontoptions() );
		
		$of_options[] = array( "name"	=> __( 'Headings', 'wpex' ),
							"desc"	=> __( 'Headings font properties. This will be applied to h1, h2, h3, h4, h5, h6 tags.', 'wpex' ),
							"id"	=> "headings_gfont",
							"std"	=> 'default',
							"type"	=> "select_google_font",
							"preview"	=> array(
											"text"	=> __( 'Font Preview Text', 'wpex' ),
											"size"	=> "30px"
							),
							"options"	=>  listgooglefontoptions() );
	
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Callout Button', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							 
		$of_options[] = array( "name"	=> __( 'Callout Button Font Family', 'wpex' ),
							"desc"	=> __( 'Select your font', 'wpex' ),
							"id"	=> "callout_gfont",
							"std"	=> 'default',
							"type"	=> "select_google_font",
							"preview"	=> array(
											"text"	=> __( 'Font Preview Text', 'wpex' ),
											"size"	=> "30px"
							),
							"options"	=>  listgooglefontoptions() );
							
		$of_options[] = array( "name"	=> __( 'Callout Button Font Size', 'wpex' ),
							"desc"	=> __( 'Select your desired font size in pixels.', 'wpex' ),
							"id"	=> "callout_font_size",
							"std"	=> '13px',
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Callout Button Font Style', 'wpex' ),
							"desc"	=> __( 'Select your desired font style.', 'wpex' ),
							"id"	=> "callout_font_style",
							"std"	=> 'bold',
							"type"	=> "select",
							"options"	=> $font_style
						);
							
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Navigation', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Navigation', 'wpex' ),
							"desc"	=> __( 'Navigation button font properties.', 'wpex' ),
							"id"	=> "navigation_gfont",
							"std"	=> 'default',
							"type"	=> "select_google_font",
							"preview"	=> array(
											"text"	=> __( 'Font Preview Text', 'wpex' ),
											"size"	=> "30px"
							),
							"options"	=>  listgooglefontoptions() );
							
		$of_options[] = array( "name"	=> __( 'Navigation Font Size', 'wpex' ),
							"desc"	=> __( 'Select your desired font size in pixels.', 'wpex' ),
							"id"	=> "navigation_font_size",
							"std"	=> '13px',
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Navigation Font Style', 'wpex' ),
							"desc"	=> __( 'Select your desired font style.', 'wpex' ),
							"id"	=> "navigation_font_style",
							"std"	=> 'bold',
							"type"	=> "select",
							"options"	=> $font_style
						);
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Homepage Tagline', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Homepage Tagline', 'wpex' ),
							"desc"	=> __( 'Homepage tagine font properties.', 'wpex' ),
							"id"	=> "tagline_gfont",
							"std"	=> 'default',
							"type"	=> "select_google_font",
							"preview"	=> array(
											"text"	=> __( 'Font Preview Text', 'wpex' ),
											"size"	=> "30px"
							),
							"options"	=>  listgooglefontoptions() );
							
		$of_options[] = array( "name"	=> __( 'Homepage Tagline Font Size', 'wpex' ),
							"desc"	=> __( 'Select your desired font size in pixels.', 'wpex' ),
							"id"	=> "tagline_font_size",
							"std"	=> '28px',
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Homepage Tagline Font Style', 'wpex' ),
							"desc"	=> __( 'Select your desired font style.', 'wpex' ),
							"id"	=> "tagline_font_style",
							"std"	=> 'normal',
							"type"	=> "select",
							"options"	=> $font_style
						);
		
		$of_options[] = array( "name"	=> __( 'Social', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( "name"	=> __( 'Social Icons In Header', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable the social icons in the header.', 'wpex' ),
							"id"	=> "disable_social",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Social Style', 'wpex' ),
							"desc"	=> __( 'Select your social icon style.', 'wpex' ),
							"id"	=> "social_style",
							"std"	=> "one",
							"fold"	=> "disable_social",
							"type"	=> "images",
							"options"	=> array(
								'one'	=> $url . 'twitter.png',
								'two'	=> $url . 'twitter2.png' )
							);
							
		$social_links = wpex_social_links();		
		foreach( $social_links as $social_link ) {
			$of_options[] = array( "name"	=> ucfirst($social_link),
								'desc'	=> ' '. __( 'Enter your ', 'wpex' ) . $social_link . __( ' url', 'wpex' ) .' <br />'. __( 'Include http:// at the front of the url.', 'wpex' ),
								'id'	=> $social_link,
								'std'	=> '#',
								"fold"	=> "disable_social",
								'type'	=> 'text' );
		}

				
				
		// HOME			 
		$of_options[] = array( "name"	=> __( 'Home', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( "name"	=> __( 'Homepage Layout Manager', 'wpex' ),
							"desc"	=> __( 'Organize how you want the layout to appear on the homepage.', 'wpex' ),
							"id"	=> "homepage_blocks",
							"std"	=> $of_options_homepage_blocks,
							"type"	=> "sorter");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Slider', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
					
		$of_options[] = array( 	"name"	=>  __( 'Slider Height', 'wpex' ),
							"desc"	=>  __( 'Enter your custom featured image height in pixels. Default is 9999 (no cropping).', 'wpex' ),
							"id"	=> "slider_height",
							"std"	=> "9999",
							"type"	=> "text"
					);	

		$of_options[] = array( 	"name"	=>  __( 'Animation', 'wpex' ),
							"desc"	=>  __( 'Select your desired slider animation.', 'wpex' ),
							"id"	=> "slider_animation",
							"std"	=> "fade",
							"type"	=> "select",
							"options"	=> array(
								'fade'	=> 'fade',
								'slide'	=> 'slide',
							)
					);
					
		$of_options[] = array( 	"name"	=>  __( 'Animation Direction', 'wpex' ),
								"desc"	=>  __( 'Select your desired direction for the slider animation.<br /><br /><strong>Note:</strong> If you choose vertical slides, all slides must be the same height to prevent issues.', 'wpex' ),
								"id"	=> "slider_direction",
								"std"	=> "horizontal",
								"type"	=> "select",
								"options"	=> array(
									'horizontal'	=> 'horizontal',
									'vertical'	=> 'vertical',
								)
						);
						
		$of_options[] = array( 	"name"	=> __( 'Auto Slideshow', 'wpex' ),
								"desc"	=> __( 'Do you wish to enable or disable the automatic slideshow', 'wpex' ),
								"id"	=> "slider_slideshow",
								"std"	=> '1',
								"on"	=> __( 'Enable', 'wpex' ),
								"off"	=> __( 'Disable', 'wpex' ),
								"type"	=> "switch"
						);
						
		$of_options[] = array( 	"name"	=> __( 'Randomize Slideshow', 'wpex' ),
								"desc"	=> __( 'Do you wish to enable or disable random slide order.', 'wpex' ),
								"id"	=> "slider_randomize",
								"std"	=> '0',
								"on"	=> __( 'Enable', 'wpex' ),
								"off"	=> __( 'Disable', 'wpex' ),
								"type"	=> "switch"
						);
						
		$of_options[] = array( 	"name"	=> __( 'Slideshow Speed', 'wpex' ),
								"desc"	=> __( 'Adjust the slideshow speed of your homepage slider. Time in milliseconds', 'wpex' ),
								"id"	=> "slider_slideshow_speed",
								"std"	=> "7000",
								"min"	=> "2000",
								"step"	=> "500",
								"max"	=> "20000",
								"type"	=> "sliderui" 
					);
					
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'RevSlider', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);

							
		$of_options[] = array( "name"	=> __( 'Slider Revolution Shortcode', 'wpex' ),
							"desc"	=> __( 'Enter your slider revolution shortcode here. Of course anything would really work in this module, but it is intended for your slider revolution shortcode..', 'wpex' ),
							"id"	=> "home_revslider",
							"std"	=> "",
							"type"	=> "textarea");
							
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Static Video', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
					
		$of_options[] = array( "name"	=> __( 'Video', 'wpex' ),
							"desc"	=> __( 'Here you can enter HTML, shortcodes, embed code...etc. This module is called "Static Video" but really it could be used for anything you like. Embeded videos will expand to the full-width of the site, much like the slider.', 'wpex' ),
							"id"	=> "home_video",
							"std"	=> "",
							"type"	=> "textarea");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Homepage Content', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Page Content Title', 'wpex' ),
							"desc"	=> __( 'Enter the text for your static page module. (optional)', 'wpex' ),
							"id"	=> "home_static_page_title",
							"std"	=> "disable",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Page Content Title URL', 'wpex' ),
							"desc"	=> __( 'Enter the url for your static page module. (optional)', 'wpex' ),
							"id"	=> "home_static_page_title_url",
							"std"	=> "",
							"type"	=> "text");
					
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Tagline', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
						
		$of_options[] = array( "name"	=> __( 'Tagline Title', 'wpex' ),
							"desc"	=> __( 'Enter your custom title for this module. Leave blank to show nothing. Leave blank to use the localized alternative for translations. Enter the words "disable" to disable the title.', 'wpex' ),
							"id"	=> "home_tagline_title",
							"std"	=> "disable",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Tagline URL', 'wpex' ),
							"desc"	=> __( 'Enter a url to link your homepage tagline module title to (optional).', 'wpex' ),
							"id"	=> "home_tagline_title_url",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Tagline Content', 'wpex' ),
							"desc"	=> __( 'Control your homepage tagline here. HTML and shortcodes allowed.', 'wpex' ),
							"id"	=> "home_tagline",
							"std"	=> 'Office is the <a href="#">PERFECT</a> solution for your business & portfolio website.',
							"type"	=> "textarea");	
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Highlights', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Highlights Title', 'wpex' ),
							"desc"	=> __( 'Enter your custom title for this module. Leave blank to show nothing. Leave blank to use the localized alternative for translations. Enter the words "disable" to disable the title.', 'wpex' ),
							"id"	=> "home_highlights_title",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Highlights Title URL', 'wpex' ),
							"desc"	=> __( 'Enter a url to link your highlights title to (optional).', 'wpex' ),
							"id"	=> "home_highlights_title_url",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Portfolio', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Portfolio Title', 'wpex' ),
							"desc"	=> __( 'Enter your custom title for this module. Leave blank to show nothing. Leave blank to use the localized alternative for translations. Enter the words "disable" to disable the title.', 'wpex' ),
							"id"	=> "home_portfolio_title",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Portfolio Title URL', 'wpex' ),
							"desc"	=> __( 'Enter a url to link your portfolio items title to (optional).', 'wpex' ),
							"id"	=> "home_portfolio_title_url",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Portfolio Category', 'wpex' ),
							"desc"	=> __( 'If you do not want to show all the recent portfolio items, select a specific category below.', 'wpex' ),
							"id"	=> "home_portfolio_cat",
							"std"	=> "",
							"type"	=> "select",
							"options"	=> $portfolio_cats_tax);
							
		$of_options[] = array( "name"	=> __( 'How Many Latest Portfolio Items', 'wpex' ),
							"desc"	=> __( 'How many latest portfolio items do you want to show on the homepage.', 'wpex' ),
							"id"	=> "home_portfolio_count",
							"std"	=> "8",
							"type"	=> "text");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Blog Posts', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Blog Posts Title', 'wpex' ),
							"desc"	=> __( 'Enter your custom title for this module. Leave blank to show nothing. Leave blank to use the localized alternative for translations. Enter the words "disable" to disable the title.', 'wpex' ),
							"id"	=> "home_blog_title",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Blog Items Title URL', 'wpex' ),
							"desc"	=> __( 'Enter a url to link your blog items title to (optional).', 'wpex' ),
							"id"	=> "home_blog_title_url",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Blog Category', 'wpex' ),
							"desc"	=> __( 'If you do not want to show all the recent blog posts, select a specific category below.', 'wpex' ),
							"id"	=> "home_blog_cat",
							"std"	=> "",
							"type"	=> "select",
							"options"	=> $of_categories);
							
		$of_options[] = array( "name"	=> __( 'How Many Latest Blog Posts', 'wpex' ),
							"desc"	=> __( 'How many latest blog items do you want to show on the homepage.', 'wpex' ),
							"id"	=> "home_blog_count",
							"std"	=> "3",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Blog Items Excerpt Length', 'wpex' ),
							"desc"	=> __( 'Enter your excerpt length for the latest blog items.', 'wpex' ),
							"id"	=> "home_blog_excerpt_length",
							"std"	=> "20",
							"type"	=> "text");
		
		$of_options[] = array( "name"	=> __( 'Portfolio', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Entries/Archives', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Template: Items Per Page', 'wpex' ),
							"desc"	=> __( 'How many portfolio items do you wish to show on the portfolio page before activating the pagination. (filtered portfolio will always show all)', 'wpex' ),
							"id"	=> "portfolio_pagination",
							"std"	=> "-1",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Category: Items Per Portfolio', 'wpex' ),
							"desc"	=> __( 'How many portfolio items do you wish to show on the portfolio categories before activating the pagination.', 'wpex' ),
							"id"	=> "portfolio_cat_pagination",
							"std"	=> "12",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Entry Featured Image Height', 'wpex' ),
							"desc"	=> __( 'Enter your custom featured image height in pixels. Default is 140.', 'wpex' ),
							"id"	=> "portfolio_entry_thumb_height",
							"std"	=> "140",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Entry Title', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable the titles on portfolio entries.', 'wpex' ),
							"id"	=> "disable_portfolio_title",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Posts', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
					
		$of_options[] = array( "name"	=> __( 'Custom Post Featured Image Height', 'wpex' ),
							"desc"	=> __( 'Enter your custom featured image height in pixels. Default is 9999 (unlimited height).', 'wpex' ),
							"id"	=> "portfolio_post_thumb_height",
							"std"	=> "9999",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Custom Full-Width Post Featured Image Height', 'wpex' ),
							"desc"	=> __( 'Enter your custom featured image height in pixels. Default is 9999 (unlimited height).', 'wpex' ),
							"id"	=> "portfolio_post_full_thumb_height",
							"std"	=> "9999",
							"type"	=> "text");
		
		$of_options[] = array( "name"	=> __( 'Portfolio Meta Box (date, client, link)', 'wpex' ),
								"desc"	=> __( 'Select to enable or disable the portfolio meta. This is the note-paper style box with the post details.', 'wpex' ),
								"id"	=> "disable_portfolio_meta",
								"std"	=> '1',
								"on"	=> __( 'Enable', 'wpex' ),
								"off"	=> __( 'Disable', 'wpex' ),
								"type"	=> "switch",
							);
											
		$of_options[] = array( "name"	=> __( 'Related Items', 'wpex' ),
								"desc"	=> __( 'Select to enable or disable the related portfolio items on single portfolio posts.', 'wpex' ),
								"id"	=> "disable_related_port",
								"std"	=> '1',
								"on"	=> __( 'Enable', 'wpex' ),
								"off"	=> __( 'Disable', 'wpex' ),
								"type"	=> "switch",
							);
		
		$of_options[] = array( "name"	=> __( 'Staff', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Entries/Archives', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Template: Items Per Page', 'wpex' ),
							"desc"	=> __( 'How many portfolio items do you wish to show on the portfolio page before activating the pagination. (filtered portfolio will always show all)', 'wpex' ),
							"id"	=> "staff_pagination",
							"std"	=> "-1",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Category: Items Per Portfolio', 'wpex' ),
							"desc"	=> __( 'How many portfolio items do you wish to show on the portfolio categories before activating the pagination.', 'wpex' ),
							"id"	=> "staff_cat_pagination",
							"std"	=> "28",
							"type"	=> "text");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Posts', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
					
		$of_options[] = array( "name"	=> __( 'Featured Images', 'wpex' ),
								"desc"	=> __( 'Select to enable or disable featured images on staff posts. If enabled staff photos will automatically be added to the posts.', 'wpex' ),
								"id"	=> "staff_post_featured_iimg",
								"std"	=> '0',
								"on"	=> __( 'Enable', 'wpex' ),
								"off"	=> __( 'Disable', 'wpex' ),
								"type"	=> "switch"
							);
								
		$of_options[] = array( "name"	=> __( 'Blog', 'wpex' ),
							"type"		=> "heading");
							
		$of_options[] = array( 	"name"	=> "",
								"desc"		=> "",
								"id"		=> "subheading",
								"std"		=> "<h3 style=\"margin: 0;\">". __( 'Entries', 'wpex' ) ."</h3>",
								"icon"		=> true,
								"type"		=> "info"
						);
										
		$of_options[] = array( "name"		=> __( 'Entry Style', 'wpex' ),
								"desc"		=> __( 'Select your preferred style for your blog entries.', 'wpex' ),
								"id"		=> "blog_style",
								"std"		=> "one",
								"type"		=> "select",
								"options"	=> $blog_styles,
							);
							
		$of_options[] = array( "name"	=> __( 'Full Content Entries', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable full content entries. If disabled blog entries will show excerpts.staff_post_featured_iimg', 'wpex' ),
							"id"	=> "enable_full_blog",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Excerpt Length', 'wpex' ),
							"desc"	=> __( 'Add your own custom blog excerpt length. Used for blog page, archives and search results. Default is 60 for entry style one and 30 for entry style two.', 'wpex' ),
							"id"	=> "blog_excerpt",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Entry Featured Image Height', 'wpex' ),
							"desc"	=> __( 'Enter your custom featured image height in pixels. Default is 220. Enter 9999 to remove all height cropping.', 'wpex' ),
							"id"	=> "blog_entry_thumb_height",
							"std"	=> "",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Entry Meta', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable the meta box with the date and categories', 'wpex' ),
							"id"	=> "enable_disable_entry_meta",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Posts', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Post Featured Images', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable the featured image on blog posts.', 'wpex' ),
							"id"	=> "enable_disable_post_image",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Post Featured Image Height', 'wpex' ),
							"desc"	=> __( 'Enter your custom featured image height in pixels. Default is 220. Enter 9999 to remove all height cropping.', 'wpex' ),
							"id"	=> "blog_post_thumb_height",
							"std"	=> "",
							"fold"	=> "enable_disable_post_image",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Post Featured Image Lightbox', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable the the lightbox functionality on blog post thumbnails.', 'wpex' ),
							"id"	=> "blog_post_image_lightbox",
							"std"	=> '1',
							"fold"	=> "enable_disable_post_image",
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");		
												
		$of_options[] = array( "name"	=> __( 'Post Meta', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable the meta box with the date and categories', 'wpex' ),
							"id"	=> "enable_disable_single_meta",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Post Tags', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable the tags display on single posts', 'wpex' ),
							"id"	=> "blog_post_tags",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Post Author Bio', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable the author info box on single posts.', 'wpex' ),
							"id"	=> "blog_bio",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
		
		$of_options[] = array( "name"	=> __( 'Comments', 'wpex' ),
								"type"	=> "heading");
								
		$of_options[] = array( "name"	=> __( 'Comments For Blog Posts', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable comments support for these posts.', 'wpex' ),
							"id"	=> "enable_disable_blog_comments",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
												
		$of_options[] = array( "name"	=> __( 'Comments For Pages', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable comments support for these posts.', 'wpex' ),
							"id"	=> "enable_disable_page_comments",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");

							
		$of_options[] = array( "name"	=> __( 'Comments For Portfolio Posts', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable comments support for these posts.', 'wpex' ),
							"id"	=> "enable_disable_portfolio_comments",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Comments For Testimonial Posts', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable comments support for these posts.', 'wpex' ),
							"id"	=> "enable_disable_testimonials_comments",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Comments For FAQ Posts', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable comments support for these posts.', 'wpex' ),
							"id"	=> "enable_disable_faqs_comments",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Comments For Staff Posts', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable comments support for these posts.', 'wpex' ),
							"id"	=> "enable_disable_staff_comments",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Comments For Service Posts', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable comments support for these posts.', 'wpex' ),
							"id"	=> "enable_disable_services_comments",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Breadcrumbs', 'wpex' ),
							"type"	=> "heading");	
							
		$of_options[] = array( "name"	=> __( 'Breadcrumbs', 'wpex' ),
							"desc"	=> __( 'Select to enable/disable breadcrumbs navigation. Note: If you have Yoast SEO active with internal links enabled the theme will display Yoast breadcrumbs instead of the built-in ones.', 'wpex' ),
							"id"	=> "disable_breadcrumbs",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Portfolio Page URL', 'wpex' ),
							"desc"	=> __( 'Enter the URL to your portfolio page. Used for breadcrumbs.', 'wpex' ),
							"id"	=> "portfolio_url",
							"std"	=> "",
							"fold"	=> "disable_breadcrumbs",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Staff Page URL', 'wpex' ),
							"desc"	=> __( 'Enter the URL to your staff page. Used for breadcrumbs.', 'wpex' ),
							"id"	=> "staff_url",
							"std"	=> "",
							"fold"	=> "disable_breadcrumbs",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Service Page URL', 'wpex' ),
							"desc"	=> __( 'Enter the URL to your service page. Used for breadcrumbs.', 'wpex' ),
							"id"	=> "services_url",
							"std"	=> "",
							"fold"	=> "disable_breadcrumbs",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Testimonials Page URL', 'wpex' ),
							"desc"	=> __( 'Enter the URL to your testimonials page. Used for breadcrumbs.', 'wpex' ),
							"id"	=> "testimonials_url",
							"std"	=> "",
							"fold"	=> "disable_breadcrumbs",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'FAQs Page URL', 'wpex' ),
							"desc"	=> __( 'Enter the URL to your FAQs page. Used for breadcrumbs.', 'wpex' ),
							"id"	=> "faqs_url",
							"std"	=> "",
							"fold"	=> "disable_breadcrumbs",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Blog Page URL', 'wpex' ),
							"desc"	=> __( 'Enter the URL to your blog page. Used for breadcrumbs.', 'wpex' ),
							"id"	=> "blog_url",
							"std"	=> "",
							"fold"	=> "disable_breadcrumbs",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Post Types', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Portfolio', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
								
		$of_options[] = array( "name"	=> __( 'Portfolio Name', 'wpex' ),
							"desc"	=> __( 'Enter a custom name for your portfolio post type.', 'wpex' ),
							"id"	=> "portfolio_post_type_name",
							"std"	=> "Portfolio",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Portfolio Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your portfolio post type. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "portfolio_post_type_slug",
							"std"	=> "portfolio",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Portfolio Cateogory Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your portfolio taxnomy. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "portfolio_tax_slug",
							"std"	=> "portfolio-category",
							"type"	=> "text");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Staff', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Staff Name', 'wpex' ),
							"desc"	=> __( 'Enter a custom name for your staff post type.', 'wpex' ),
							"id"	=> "staff_post_type_name",
							"std"	=> "Staff",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Staff Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your staff post type. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "staff_post_type_slug",
							"std"	=> "staff",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Staff Cateogory Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your staff taxnomy. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "staff_tax_slug",
							"std"	=> "staff-department",
							"type"	=> "text");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Services', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Services Name', 'wpex' ),
							"desc"	=> __( 'Enter a custom name for your staff post type.', 'wpex' ),
							"id"	=> "services_post_type_name",
							"std"	=> "Services",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Services Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your staff post type. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "services_post_type_slug",
							"std"	=> "services",
							"type"	=> "text"); 
							
		$of_options[] = array( "name"	=> __( 'Services Cateogory Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your services taxnomy. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "services_tax_slug",
							"std"	=> "department",
							"type"	=> "text");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'Testimonials', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'Testimonials Name', 'wpex' ),
							"desc"	=> __( 'Enter a custom name for your staff post type.', 'wpex' ),
							"id"	=> "testimonials_post_type_name",
							"std"	=> "Testimonials",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Testimonials Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your staff post type. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "testimonials_post_type_slug",
							"std"	=> "testimonials",
							"type"	=> "text");
							
		$of_options[] = array( 	"name"	=> "",
							"desc"	=> "",
							"id"	=> "subheading",
							"std"	=> "<h3 style=\"margin: 0;\">". __( 'FAQs', 'wpex' ) ."</h3>",
							"icon"	=> true,
							"type"	=> "info"
					);
							
		$of_options[] = array( "name"	=> __( 'FAQs Name', 'wpex' ),
							"desc"	=> __( 'Enter a custom name for your staff post type.', 'wpex' ),
							"id"	=> "faqs_post_type_name",
							"std"	=> "FAQs",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'FAQs Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your staff post type. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "faqs_post_type_slug",
							"std"	=> "faqs",
							"type"	=> "text"); 
							
		$of_options[] = array( "name"	=> __( 'FAQs Cateogory Slug', 'wpex' ),
							"desc"	=> __( 'Enter a custom slug for your faqs taxnomy. Go <strong>save your permalinks</strong> after changing this.', 'wpex' ),
							"id"	=> "faqs_tax_slug",
							"std"	=> "faqs-category",
							"type"	=> "text");
							
		$of_options[] = array( "name"	=> __( 'Footer', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( "name"	=> __( 'Footer Widgets', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable the widgetized footer.', 'wpex' ),
							"id"	=> "disable_widgetized_footer",
							"std"	=> '1',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Full-Width Footer Widgets', 'wpex' ),
							"desc"	=> __( 'Select to enable or disable full-width footer widgets.', 'wpex' ),
							"id"	=> "full_width_footer_widgets",
							"std"	=> '0',
							"on"	=> __( 'Enable', 'wpex' ),
							"off"	=> __( 'Disable', 'wpex' ),
							"type"	=> "switch");
							
		$of_options[] = array( "name"	=> __( 'Custom Copyright', 'wpex' ),
							"desc"	=> __( 'Add your own custom text/html for copyright region.', 'wpex' ),
							"id"	=> "custom_copyright",
							"std"	=> "",
							"type"	=> "textarea");
																
		$of_options[] = array( "name"	=> __( 'Tracking', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( "name"	=> __( 'Header Tracking Code', 'wpex' ),
							"desc"	=> __( 'Paste your Google Analytics (or other) tracking code here. This will be added into the header template of your theme.', 'wpex' ),
							"id"	=> "tracking_header",
							"std"	=> "",
							"type"	=> "textarea");    
							
		$of_options[] = array( "name"	=> __( 'Footer Tracking Code', 'wpex' ),
							"desc"	=> __( 'Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.', 'wpex' ),
							"id"	=> "tracking_footer",
							"std"	=> "",
							"type"	=> "textarea");
		
		$of_options[] = array( "name"	=> __( 'Custom CSS', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( "name"	=> __( 'Custom CSS', 'wpex' ),
							"desc"	=> __( 'Quickly add some CSS to your theme by adding it to this block.', 'wpex' ),
							"id"	=> "custom_css",
							"std"	=> "",
							"type"	=> "textarea");  
							
		$of_options[] = array( "name"	=> __( 'Backup', 'wpex' ),
							"type"	=> "heading");
							
		$of_options[] = array( "name"	=> __( 'Backup and Restore Options', 'wpex' ),
							"id"	=> "of_backup",
							"std"	=> "",
							"type"	=> "backup",
							"desc"	=> __( 'You can use the two buttons below to backup your current options, and then restore it back at a later time.<br /><br />This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.', 'wpex' ),
							);
							
		$of_options[] = array( "name"	=> __( 'Transfer Theme Options Data', 'wpex' ),
							"id"	=> "of_transfer",
							"std"	=> "",
							"type"	=> "transfer",
							"desc"	=> __( 'You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options.', 'wpex' )
							);
										
		}
} ?>