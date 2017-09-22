<?php
require_once(IGALLERY_CLASS_PATH.'/com/sakurapixel/php/plugin_base.php');
require_once(IGALLERY_CLASS_PATH.'/com/sakurapixel/php/customposts/utils/CPTHelper.php');
require_once(IGALLERY_CLASS_PATH.'/com/sakurapixel/php/customposts/iGalleryCPT.php');
require_once(IGALLERY_CLASS_PATH.'/com/sakurapixel/php/iGalleryShortcodes.php');

/**
 * iGalleryWrapper
 */
define('IGALLERY_SLUG', 'sk_igallery');

class SKIGallery extends iGalleryPluginBase {
	
	//fire up 
	public function start($options='')
	{	
		parent::start();
		add_action( 'wp_ajax_igallery_ajax_req', array($this, 'igallery_ajax_req') );	
	}
	
	//iGallery attachement request
	public function igallery_ajax_req(){
		$nonce = $_POST['i_nonce'];
		if ( ! wp_verify_nonce( $nonce, 'igallery-nonce-default' ) ){
			die( 'Busted!');	
		}		
		
		$at_id = $_POST['attachementID'];		
		$post_thumb = wp_get_attachment_thumb_url($at_id);
		if(!$post_thumb){
			$post_thumb = 'http://placehold.it/150x150';
		}
		
		$response = json_encode( array( 'thumb_url' => $post_thumb));	 
		header( "Content-Type: application/json");
		echo $response;
		exit;		
	}	
	
	//init handler - override 
	public function initializeHandler(){
		parent::initializeHandler();	
		$this->addiGalleryCPT();		
	}
	
	private $iGCPT;
	/*
	 * create youtube CPT
	 */
	private function addiGalleryCPT(){
		$settings = array('post_type' => IGALLERY_SLUG, 'name' => __('iGallery', IGALLERY_TEXTDOMAIN), 'menu_icon' => IGALLERY_TEMPPATH.'/com/sakurapixel/images/icons/images-stack.png',
		'singular_name' => __('iGallery', IGALLERY_TEXTDOMAIN), 'rewrite' => 'igallery', 'add_new' => __('Add new', IGALLERY_TEXTDOMAIN),
		'edit_item' => __('Edit', IGALLERY_TEXTDOMAIN), 'new_item' => __('New', IGALLERY_TEXTDOMAIN), 'view_item' => __('View item', IGALLERY_TEXTDOMAIN), 'search_items' => __('Search items', IGALLERY_TEXTDOMAIN),
		'not_found' => __('No item found', IGALLERY_TEXTDOMAIN), 'not_found_in_trash' => __('Item not found in trash', IGALLERY_TEXTDOMAIN), 
		'supports' => array('title'));
		
		$cptHelper = new IgalleryCPTHelper($settings);
		$this->iGCPT = new iGalleryCPT();
		$this->iGCPT->create($cptHelper, $settings);
		
	}
	
	//admin init handler - override 
	public function adminInitHandler(){
		//add meta boxes pages
		$this->iGCPT->addMetaBox(__('Gallery\'s groups', IGALLERY_TEXTDOMAIN), 'groups_container_id', 'groups_metabox');
		$this->iGCPT->addMetaBox(__('Gallery properties', IGALLERY_TEXTDOMAIN), 'i_properties_id', 'properties_metabox', 'side', 'default');			
	}

	
	
	//admin enqueue scripts handler - override 
	public function adminEnqueueScriptsHandler(){
		parent::adminEnqueueScriptsHandler();
		$screenID = get_current_screen()->id;
		if($screenID==IGALLERY_SLUG){
			
			/*================ENQUEUE===============*/			
			
			//$this->enque_common();			
			
			//ui
			wp_register_style('custom_ui_css', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/ui/css/ui.css');
			wp_enqueue_style('custom_ui_css');	
			wp_register_script('custom_ui', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/ui/js/ui.js');
			wp_enqueue_script('custom_ui');						
						
			
			//jqueryui		
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-draggable');
			wp_enqueue_script('jquery-ui-widget');
			wp_enqueue_script('jquery-ui-selectable');
			wp_enqueue_script('jquery-ui-button');	
			wp_enqueue_script('jquery-ui-mouse');
			wp_enqueue_script('jquery-ui-spinner');
			wp_enqueue_script('jquery-ui-accordion');
			wp_enqueue_script('jquery-ui-dialog');
			
			//utils
			wp_enqueue_script('underscore');
			wp_register_script('sk_utils', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/utils/Utils.js', array('jquery'));	
			wp_enqueue_script('sk_utils');						
					 		 						 	 						
						 
			//jqueryui theme
			wp_register_style('jqueryui-style', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/jqueryui/css/ui-lightness/jquery-ui-1.10.1.custom.min.css');
			wp_enqueue_style('jqueryui-style');			

			

			//enqueue main
			wp_register_script('igallery_main', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/main.js', array('jquery'));	
			wp_enqueue_script('igallery_main');
			
			wp_register_script('igallery_generics', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/generics/generics.js', array('jquery'));	
			wp_enqueue_script('igallery_generics');
			
			//events			
			wp_register_script('event_bus', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/events/eventbus/EventBus.js', array('jquery'));
			wp_enqueue_script('event_bus');
			wp_register_script('generic_events', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/events/genericevents.js', array('jquery'));	
			wp_enqueue_script('generic_events');																
			
			//media																	
			wp_enqueue_media();	
			
			//tween
			wp_register_script('sk_tweenlight', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/tween/TweenLite.min.js', array('jquery'));
			wp_enqueue_script('sk_tweenlight');				
			
			$this->enque_common();
			
			$this->enqueColorPicker();			
			
			//localize script
			$igallery_data = array('AJAX_SERVICE'=>admin_url( 'admin-ajax.php'), 'IGALLERY_NONCE'=> wp_create_nonce('igallery-nonce-default'));				
			wp_localize_script('igallery_main', 'IGALLERY_DTA', $igallery_data);			
		}		
	}

	private function enqueColorPicker(){
			 //color picker style
		     wp_register_style( 'cpicker_style', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/cpicker/css/colorpicker.css');
			 wp_register_style( 'cpicker_layout', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/cpicker/css/layout.css');		 
		     wp_enqueue_style( 'cpicker_style');
			 //wp_enqueue_style( 'cpicker_layout');
			 
			 //color picker script
			 wp_register_script( 'color_picker', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/cpicker/js/colorpicker.js', array('jquery'));
			 wp_register_script( 'color_picker_eye', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/cpicker/js/eye.js', array('jquery'));
			 wp_register_script( 'color_picker_layout', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/cpicker/js/layout.js', array('jquery'));
			 wp_register_script( 'color_picker_utils', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/cpicker/js/utils.js', array('jquery'));
			 wp_enqueue_script('color_picker');
			 wp_enqueue_script('color_picker_eye');	
			 wp_enqueue_script('color_picker_layout');	
			 wp_enqueue_script('color_picker_utils');			 		
	}

	//WP Enqueue scripts handler
	public function WPEnqueueScriptsHandler(){
		parent::WPEnqueueScriptsHandler();		
		$this->enque_common();
		
		wp_register_script('sk_tweenmax', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/tween/TweenMax.min.js', array('jquery'));
		wp_enqueue_script('sk_tweenmax');		
		
		wp_register_style('igallery_css', IGALLERY_TEMPPATH.'/css/igallery.css');
		wp_enqueue_style('igallery_css');		
		
		wp_register_script('igallery_front', IGALLERY_TEMPPATH.'/js/igallery.js', array('jquery'));
		wp_enqueue_script('igallery_front');
		
		//localize script
		$igallery_data_front = array('IMAGES_URL'=>IGALLERY_TEMPPATH.'/images', 'BTN_COLOR_OFF'=>'7b7b7b');				
		wp_localize_script('igallery_front', 'IGALLERY_DTA_FRONT', $igallery_data_front);																
	}

	private function enque_common(){
			//tween								
			wp_register_script('sk_css_plugin', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/tween/CSSPlugin.min.js', array('jquery'));
			wp_enqueue_script('sk_css_plugin');			
			wp_register_script('sk_tween_ease', IGALLERY_TEMPPATH.'/com/sakurapixel/js'.'/tween/easing/EasePack.min.js', array('jquery'));
			wp_enqueue_script('sk_tween_ease');			
	}

	/**
	 * SAVE POST EXTRA DATA
	 */
	 public function savePostHandler(){
		global $post;						
		if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
			return $post_id;
		}
		if(!current_user_can('edit_posts') || !current_user_can('publish_posts')){
			return;
		}		
		//ysave extra data
		if(isset($post) && isset($_POST[$this->iGCPT->getPostSlug().'-data']) && IGALLERY_SLUG==$_POST['post_type']){			
			update_post_meta($post->ID, $this->iGCPT->getPostSlug().'-data', $_POST[$this->iGCPT->getPostSlug().'-data']);
		}
		if(isset($post) && isset($_POST[$this->iGCPT->getPostSlug().'-thumbs_size']) && IGALLERY_SLUG==$_POST['post_type']){			
			update_post_meta($post->ID, $this->iGCPT->getPostSlug().'-thumbs_size', $_POST[$this->iGCPT->getPostSlug().'-thumbs_size']);
		}
		if(isset($post) && isset($_POST[$this->iGCPT->getPostSlug().'-extra_data']) && IGALLERY_SLUG==$_POST['post_type']){
					
			update_post_meta($post->ID, $this->iGCPT->getPostSlug().'-extra_data', $_POST[$this->iGCPT->getPostSlug().'-extra_data']);
		}												
	 }


	/*
	 * register shortcodes 
	 */ 
	public function registerShortcodes(){			
		$shorcodesHelper = new iGalleryShortcodes();
		$shorcodesHelper->registerShortcodes();	
	}
			
	
		
}


?>