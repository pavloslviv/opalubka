<?php
/**
 * @package WordPress
 * @subpackage Office Theme
 * Template Name: FAQs
 */
?>

<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

	<?php
    //show slider if enabled
    if ( get_post_meta( get_the_ID(), 'office_page_slider', true) == 'enable' ) get_template_part( 'includes/page-slides'); ?>
    
    <?php
    //get meta to set parent category
    $faqs_filter_parent = ( get_post_meta(get_the_ID(), 'office_faqs_parent', true) !== 'all') ? get_post_meta(get_the_ID(), 'office_faqs_parent', true) : NULL; ?>
    
    <header id="page-heading">
        <h1><?php the_title(); ?></h1>	
        <?php if( wpex_get_data('disable_breadcrumbs','1') == '1') office_breadcrumbs(); ?> 
    </header><!-- /page-heading -->

	<?php
    $content = $post->post_content;
    if(!empty($content)) { ?>
        <div id="faqs-description">
            <?php the_content(); ?>
        </div><!-- /faqs-description -->
    <?php }?>
    
    <?php
	//tax query
	if($faqs_filter_parent) {
		$tax_query = array(
			array(
				  'taxonomy' => 'faqs_cats',
				  'field' => 'id',
				  'terms' => $faqs_filter_parent
				  )
			);
	} else { $tax_query = NULL; } ?>
	
	<?php
	$faqs_posts = new WP_Query(
		array(
			'post_type' => 'faqs',
			'showposts' =>  '-1',
			'tax_query' => $tax_query,
			'no_found_rows' => true
		)
	);
	
	if ( $faqs_posts->posts ) : ?>
    
        <div class="post full-width clearfix">
        
            <?php
            //get portfolio categories
            $cats_args = array(
                'hide_empty' => '1',
                'child_of' => $faqs_filter_parent
            );
            $cats = get_terms('faqs_cats', $cats_args);
            
            //show filter if categories exist
            if( $cats ) :
			
				$args = array(
					'taxonomy' => 'faqs_cats',
					'orderby' => 'name',
					'show_count' => 0,
					'pad_counts' => 0,
					'hierarchical' => 0,
					'title_li' => ''
            	); ?>
                <ul id="faqs-cats" class="clearfix">
                    <li><a class="active" href="#all" rel="all" title="<?php _e('All FAQs', 'wpex'); ?>"><?php _e('All', 'wpex'); ?></a></li>
                    <?php
                    foreach ($cats as $cat ) : ?>
                    	<li><a href="#<?php echo $cat->slug; ?>" rel="<?php echo $cat->slug; ?>" title="<?php echo $cat->name; ?>"><span><?php echo $cat->name; ?></span></a></li>
                    <?php endforeach; ?>
                </ul><!-- /faqs-cats -->
                
            <?php endif; //cats check ?>
        
            <div id="faqs-wrap clearfix">
            
                <ul class="faqs-content">
    
                <?php
                $count=0;
                while ( $faqs_posts->have_posts() ) : $faqs_posts->the_post();
                $count++;
                
                //get terms
                $terms = get_the_terms( get_the_ID(), 'faqs_cats' ); ?>
                
                <li data-id="id-<?php echo $count; ?>" data-type="<?php if($terms) { foreach ($terms as $term) { echo $term->slug .' '; } } else { echo 'none'; } ?>" class="faqs-container">       
                    <div class="faq-item">
                        <h2 class="faq-title"><span><?php the_title(); ?></span></h2>
                        <div class="faq-content entry">
                            <?php the_content(); ?>
                        </div><!-- /faq -->
                    </div><!-- /faq-item -->
                </li><!-- /faqs-container -->
                
                <?php endwhile; wp_reset_postdata(); ?>
                
                </ul><!-- /faqs-content -->
            
            </div><!-- /faqs-wrap -->
            
        </div><!-- /post -->
    
    <?php endif; ?>

<?php endwhile; ?>	  

<?php get_footer(); ?>