<?php
require_once(IGALLERY_CLASS_PATH.'/com/sakurapixel/php/customposts/GenericPostType.php');
/**
 * Youtube CPT
 */
class iGalleryCPT extends iGalleryGenericPostType {
	
	/* VIDEO CONTAINER
	================================================== */
	public function groups_metabox(){
		global $post;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
			
			
			$custom = get_post_meta($post->ID, $this->getPostSlug().'-data', false);			
			//main json data
			$mainJSONData = "";
			
			if(isset($custom[0])){
				$mainJSONData = (isset($custom[0]['mainJSONData']))?$mainJSONData = $custom[0]['mainJSONData']:"";
			}				
		?>
		
	<!--boxes wrapper-->
	<div class="metabox_wrapper">
									
		<!--add group-->
		<div class="contentBox">			
			<a id="addGroupBTN" class="button-primary alignright"><?php _e('Add group', IGALLERY_TEXTDOMAIN);?></a>
			<div class="vspace1"></div>
		</div>
		<!--add group-->	
		
		<!--groups-->
		<div id="accordion" class="contentBox">			
			 			 						
		</div>
		<!--groups-->		
		
		
		<!--json data-->
		<textarea id="mainJSONData" class="boxContentTextarea" name="<?php echo $this->getPostSlug().'-data'?>[mainJSONData]" rows="4"><?php echo $mainJSONData; ?></textarea>
		<!--/json data-->
		
	</div>
	<!--/boxes wrapper-->
		<?php		
	}
	
	/* PROPERTIES CONTAINER
	================================================== */
	public function properties_metabox(){
		global $post;
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		
			$custom_size = get_post_meta($post->ID, $this->getPostSlug().'-thumbs_size', false);
			
			$wdt = 200;
			$hgt = 150;
			if(isset($custom_size[0])){
				$wdt = (float)$custom_size[0]['width'];
				$hgt = (float)$custom_size[0]['height'];
				($wdt<100)?$wdt=100:$wdt=$wdt;
				($wdt>400)?$wdt=400:$wdt=$wdt;
				($hgt<100)?$hgt=100:$hgt=$hgt;
				($hgt>400)?$hgt=400:$hgt=$hgt;								
			}
			
			$gap01 = 50;
			$gap02 = 50;
			$lightbox_colors = "ec761a";
			$group_titles_color = "2a2929";
			$back_btn_label = 'Back';
			$back_btn_color = '2a2929';
			$custom_data = get_post_meta($post->ID, $this->getPostSlug().'-extra_data', false);
			if(isset($custom_data[0])){				
				$gap01 = (float)$custom_data[0]['gap01'];
				$gap02 = (float)$custom_data[0]['gap02'];
				($gap01<0)?$gap01=0:$gap01=$gap01;
				($gap01>100)?$gap01=100:$gap01=$gap01;
				($gap02<0)?$gap02=0:$gap02=$gap02;
				($gap02>100)?$gap02=100:$gap02=$gap02;
				
				$lightbox_colors = $custom_data[0]['lightbox_colors'];
				$group_titles_color	= $custom_data[0]['group_titles_color'];
				$back_btn_label	= $custom_data[0]['back_btn_label'];
				$back_btn_color	= $custom_data[0]['back_btn_color'];
				
				$groupsLabelsCBChecked = '';	
				$groupsLabelsCB = (isset($custom_data[0]['groupsLabelsCB']))?$custom_data[0]['groupsLabelsCB']:"";
				if($groupsLabelsCB=='ON'){
					$groupsLabelsCBChecked = 'checked';
				}
		
																
			}
			
		?>
		
		<!--properties container-->
		<div id="propertiesContainer">
			
			<!--shortcode-->
			<div class="contentBox">
				<label class="customLabel"><?php _e('Shortcode:  ', IGALLERY_TEXTDOMAIN);?>  <span id="shortcode" data-postid="<?php echo $post->ID;?>" class="sk_defaultText"><span><?php echo '[igallery id="'.$post->ID.'"]';?></span></span></label>
			</div>
			<!--/shortcode-->
			
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Thumbnails size</p>
				<div class="vspace2"></div>
                <!--thumbnail width and height-->
                <input id="spinnerThumbW" class="spinnerBox" name="<?php echo $this->getPostSlug().'-thumbs_size';?>[width]" value="<?php echo $wdt;?>" /><label class="customLabel">Width</label>                
                <div class="hLine"></div>
                <input id="spinnerThumbH" class="spinnerBox" name="<?php echo $this->getPostSlug().'-thumbs_size';?>[height]" value="<?php echo $hgt;?>" /><label class="customLabel">Height</label>
                <!--/endthumbnail width and height--> 				
			</div>
			
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Thumbnails gap</p>
				<div class="vspace2"></div>
                <input id="spinnerGap01" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[gap01]" value="<?php echo $gap01;?>" /><label class="customLabel">Gallery gap</label>                
                <div class="hLine"></div>
                <input id="spinnerGap02" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[gap02]" value="<?php echo $gap02;?>" /><label class="customLabel">Opened gallery gap</label>			
			</div>			
			
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Colors</p>
				<div class="vspace2"></div>                
                <input id="lightbox_colors" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[lightbox_colors]" value="<?php echo $lightbox_colors;?>" /><label class="customLabel">Lightbox buttons color</label>                
                <div class="hLine"></div>
                <input id="group_titles_color" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[group_titles_color]" value="<?php echo $group_titles_color;?>" /><label class="customLabel">Group's title color</label>                			
			</div>
			
			<!--back button-->
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Back button</p>
				<div class="vspace2"></div>                
                <input id="back_btn_label" class="" name="<?php echo $this->getPostSlug().'-extra_data';?>[back_btn_label]" value="<?php echo $back_btn_label;?>" /><label class="customLabel">Label</label>
                <div class="hLine"></div>
                <input id="back_btn_color" class="spinnerBox" name="<?php echo $this->getPostSlug().'-extra_data';?>[back_btn_color]" value="<?php echo $back_btn_color;?>" /><label class="customLabel">Color</label>                             			
			</div>
			<!--/back button-->
			
			<!--show groups labels-->
			<div class="contentBoxProperties contentBoxBackground">
				<p class="contentBoxSubtitle">Groups title</p>                      
                <div class="hLine"></div>
                <input type="checkbox" name="<?php echo $this->getPostSlug().'-extra_data';?>[groupsLabelsCB]" value="ON" <?php echo $groupsLabelsCBChecked;?> /><label class="customLabel">Show (On/Off)</label>                     			
			</div>
			<!--/show groups labels-->									
				
		</div>
		<!--/properties container-->		
		
		
		<?php		
	}
	
		
}


?>