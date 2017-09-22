<?php
// Testimonial Widget
class office_testimonials extends WP_Widget {
							
    /** constructor */
    function office_testimonials() {
        parent::WP_Widget(false, $name = 'Office - Testimonials');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
        extract( $args );
        $title = $instance['title'];
        $number = $instance['number'];
        $offset = $instance['offset'];
		$excerpt = $instance['excerpt']; ?>
              <?php echo $before_widget; ?>
                        <?php echo $before_title; ?>
                        <?php echo $title; ?>
                        <?php echo $after_title; ?>
                        <?php
						// Load javascript for widget
						wp_enqueue_script('flexslider', get_template_directory_uri() . '/js/flexslider.js', array('jquery'), '2.1', true);
                        wp_enqueue_script('testimonials-widget', get_template_directory_uri() . '/js/testimonial-widget.js', array('jquery','flexslider'), '1.0', true); ?>
							<div class="widget-recent-testimonials">
                            	<div id="testimonials-slider" class="flexslider clearfix">
									<ul class="slides">
									<?php
                                        global $post;
                                        $tmp_post = $post;
                                        $args = array(
                                            'post_type' => 'testimonials',
                                            'numberposts' => $number,
                                            'offset'=> $offset,
                                            'order' => 'rand'
                                        );
                                        $myposts = get_posts( $args );
                                        foreach( $myposts as $post ) : setup_postdata($post);
                                        ?>
                                            <li class="testimonial-slide">
                                                <div class="testimonial-content">
                                                <?php if ( $excerpt == '1' ) { ?>
                                                <?php echo wp_trim_words(get_the_content(), 32); ?>
                                                <a href="<?php the_permalink(); ?>" title="<?php _e('Read More','wpex'); ?>" class="testimonial-widget-readmore"><?php _e('read more','wpex'); ?> &rarr;</a>
                                                <?php } else { ?>
												<?php the_content(); ?>
                                                <?php } ?>
                                                </div>
                                                <div class="testimonial-by"><?php the_title(); ?></div>
                                            </li>
                                            <!-- testimonial-slide -->
                                        <?php endforeach; ?>
                                        <?php $post = $tmp_post; ?>
                                	</ul>
                                </div>
                                <!-- /flex-slider -->
							</div>
                            <!-- /widget-recent-testimonials -->
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['number'] = strip_tags($new_instance['number']);
	$instance['offset'] = strip_tags($new_instance['offset']);
	$instance['excerpt'] = strip_tags($new_instance['excerpt']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Testimonials', 'id' => '', 'number'=> 4, 'offset' => '', 'excerpt' => '1' ));			
        $title = esc_attr($instance['title']);
        $number = esc_attr($instance['number']);
        $offset = esc_attr($instance['offset']);
		$excerpt = esc_attr($instance['excerpt']); ?>
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
        	<input id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="checkbox" value="1" <?php checked( '1', $excerpt ); ?> />
        	<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Check to show only an excerpt','wpex'); ?></label>
		</p>

        <?php 
    }


}
add_action('widgets_init', create_function('', 'return register_widget("office_testimonials");')); ?>