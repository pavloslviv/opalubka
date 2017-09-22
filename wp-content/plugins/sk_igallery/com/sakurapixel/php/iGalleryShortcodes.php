<?php
/**
 * Shortcode
 */
require_once(IGALLERY_CLASS_PATH.'/com/sakurapixel/php/libs/igallery_resizer.php');
class iGalleryShortcodes{
	
	public function registerShortcodes(){						
		add_shortcode('igallery', array($this, 'igallery'));																										
	}
	
	/* igallery shortcode
	================================================== */	
	public function igallery($atts, $content = null){
		extract(shortcode_atts(array('id' => ''), $atts));
		$return_val = '';		
		
		//get post
		$gallery_post = get_post($id, OBJECT);
		if(!is_null($gallery_post)){
			$gallery_post_meta = get_post_meta($gallery_post->ID, IGALLERY_SLUG.'-data', false);
			$gallery_thumb_size = get_post_meta($gallery_post->ID, IGALLERY_SLUG.'-thumbs_size', false);
			$gallery_extra_data = get_post_meta($gallery_post->ID, IGALLERY_SLUG.'-extra_data', false);
			
			$wdt = 200;
			$hgt = 150;			
			if(isset($gallery_thumb_size[0])){
				$wdt = (float)$gallery_thumb_size[0]['width'];
				$hgt = (float)$gallery_thumb_size[0]['height'];							
			}
			$gap01 = 50;
			$gap02 = 50;
			$lightbox_colors = "ec761a";
			$group_titles_color = "2a2929";
			$back_btn_label = "Back";
			$back_btn_color = "2a2929";						
			if(isset($gallery_extra_data[0])){
				$gap01 = (float)$gallery_extra_data[0]['gap01'];
				$gap02 = (float)$gallery_extra_data[0]['gap02'];
				$lightbox_colors = $gallery_extra_data[0]['lightbox_colors'];
				$group_titles_color	= $gallery_extra_data[0]['group_titles_color'];
				$back_btn_label	= $gallery_extra_data[0]['back_btn_label'];
				$back_btn_color	= $gallery_extra_data[0]['back_btn_color'];
				$groupsLabelsCB = (isset($gallery_extra_data[0]['groupsLabelsCB']))?$gallery_extra_data[0]['groupsLabelsCB']:"";												
			}
			
			$return_val = '<div class="iGalWrapper">'.
			'<div><a href="#" class="iGalBackBTN" style="color: #'.$back_btn_color.';">'.$back_btn_label.'</a></div>'.
			'<div class="galleryPreloader"><div><img src="'.IGALLERY_TEMPPATH.'/images/white_preloader.gif" alt="preloader" /></div></div>'.
			'<div class="iclear-fx"></div>'.
			'<div data-show_groups_labesls="'.$groupsLabelsCB.'" data-lightbox_colors="'.$lightbox_colors.'" data-group_color="'.$group_titles_color.'" data-gap_one="'.$gap01.'" data-gap_two="'.$gap02.'" data-wdt="'.$wdt.'" data-hgt="'.$hgt.'" class="iGalleryContainer">';
			
			if(isset($gallery_post_meta[0])){
				$mainJSONData = (isset($gallery_post_meta[0]['mainJSONData']))?$mainJSONData = $gallery_post_meta[0]['mainJSONData']:$mainJSONData='';				
				$temp_data = json_decode($mainJSONData);

				$groups = $temp_data->groups;
				//interate groups	
				for ($i=0; $i < sizeof($groups); $i++) {
					$groupName = $groups[$i]->name;						
					$groupHTML = '<div class="igalleryGroup" data-groupName="'.$groupName.'">';
										 					
					$groupImages = $groups[$i]->imageItems;									
					for ($j=0; $j < sizeof($groupImages); $j++) {
						//image caption
						$imageCaption = wptexturize($groupImages[$j]->caption);
												
						$attachementID = $groupImages[$j]->attachementID;						
						//image full
						$imageFullArray = wp_get_attachment_image_src($attachementID, 'full');
						($imageFullArray)?$imgFullUrl = $imageFullArray[0]:$imgFullUrl='http://placehold.it/1000x800';
						
						//thumb
						$thumb_url = 'http://placehold.it/'.$wdt.'x'.$hgt;
						if($imageFullArray){
							$thumb_temp_url = ig_resize($imgFullUrl, $wdt, $hgt, true);
							($thumb_temp_url)?$thumb_url = $thumb_temp_url:$thumb_url=$thumb_url;
						}
						$imageItemHTML = '<div data-groupName="'.$groupName.'" data-thub_url="'.$thumb_url.'" data-full_url="'.$imgFullUrl.'" class="imageItemData">'.$imageCaption.'</div>';
						$groupHTML .= $imageItemHTML;						
					}
					$groupHTML .= '</div>';
					$return_val .= $groupHTML;										
				}
				//end interate groups
				$return_val .= '</div></div>';
				
				
			}else{
				$return_val = 'gallery meta data not found';
			}
		}else{
			$return_val = 'gallery not found';
		}
				
		return $return_val;		
	}				

		
}

?>