<?php
/******************************************
/* Recent Posts Widget
******************************************/
class office_recent_portfolio extends WP_Widget {
							
    /** constructor */
    function office_recent_portfolio() {
        parent::WP_Widget(false, $name = 'Office - Recent Portfolio Items');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $number = apply_filters('widget_title', $instance['number']);
        $offset = apply_filters('widget_title', $instance['offset']);
		$category = apply_filters('widget_title', $instance['category']); ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; ?>
							<ul class="widget-recent-portfolio">
							<?php
							if($category && $category !='all-cats'){
									//tax query
										$tax_query = array(
											array(
												  'taxonomy' => 'portfolio_cats',
												  'field' => 'id',
												  'terms' => $category
												  )
											);
								} else { $tax_query = NULL; }
								
								global $post;
								$tmp_post = $post;
								$args = array(
									'post_type' => 'portfolio',
									'numberposts' => $number,
									'offset'=> $offset,
									'tax_query' => $tax_query
								);
								$myposts = get_posts( $args );
								foreach( $myposts as $post ) : setup_postdata($post); ?>
                                <?php if ( has_post_thumbnail() ) {  ?>
									<li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="<?php echo aq_resize( wp_get_attachment_url( get_post_thumbnail_id() ), '65',  '65',  true ); ?>" alt="<?php the_title(); ?>" /></a></li>
                                    <?php } ?>
								<?php endforeach; ?>
								<?php $post = $tmp_post; ?>
							</ul>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['number'] = strip_tags($new_instance['number']);
	$instance['offset'] = strip_tags($new_instance['offset']);
	$instance['category'] = strip_tags($new_instance['category']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Recent Work', 'id' => '', 'number'=> 8, 'offset'=> '', 'category' => 'all-cats'));			
        $title = esc_attr($instance['title']);
        $number = esc_attr($instance['number']);
        $offset = esc_attr($instance['offset']);
		$category = esc_attr($instance['category']); ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wpex'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title','wpex'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number to Show:', 'wpex'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Offset (the number of posts to skip):', 'wpex'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('offset'); ?>" name="<?php echo $this->get_field_name('offset'); ?>" type="text" value="<?php echo $offset; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select a Category:', 'wpex'); ?></label> 
          <select class='office-select' name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>">
          <option value="all-cats" <?php if($category == 'all-cats') { ?>selected="selected"<?php } ?>><?php _e('All', 'wpex'); ?></option>
          <?php
          //get terms
          $cat_args = array( 'hide_empty' => '1' );
          $cat_terms = get_terms('portfolio_cats', $cat_args);
          foreach ( $cat_terms as $cat_term) { ?>
              <option value="<?php echo $cat_term->term_id; ?>" id="<?php echo $cat_term->term_id; ?>" <?php if($category == $cat_term->term_id) { ?>selected="selected"<?php } ?>><?php echo $cat_term->name; ?></option>
          <?php } ?>
          </select>
          </p>

        <?php 
    }


} // class office_recent_portfolio
// register Recent Posts widget
add_action('widgets_init', create_function('', 'return register_widget("office_recent_portfolio");'));	
?>