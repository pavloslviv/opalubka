<?php
/**
 * Used for single portfolio posts meta
 *
 * @package WordPress
 * @subpackage Office Theme
 * @since Office 2.0
 */
?>

<?php if( wpex_get_data('disable_portfolio_meta','1' ) == '1' && get_post_meta( get_the_ID(), 'wpex_portfolio_post_details', true ) !== 'disabled' ) : ?>
	<?php $terms = get_the_term_list( get_the_ID(), 'portfolio_cats' ); ?>
	<?php if( get_post_meta(get_the_ID(), 'office_portfolio_style', TRUE) == 'full' || get_post_meta(get_the_ID(), 'office_portfolio_style', TRUE) == 'grid' ) { ?>
	<div id="single-portfolio-left" class="clearfix">
	<?php } ?>
		<div id="single-portfolio-meta" class="clearfix">
			<ul>
				<?php
				// Date
				if( get_post_meta(get_the_ID(), 'wpex_portfolio_post_date', TRUE) !=='' && get_post_meta(get_the_ID(), 'wpex_portfolio_post_date', TRUE) !== 'disabled') { ?>
					<li><span><?php _e('Date','wpex'); ?></span><?php echo get_post_meta(get_the_ID(), 'wpex_portfolio_post_date', TRUE); ?></li>
				<?php } elseif( get_post_meta(get_the_ID(), 'wpex_portfolio_post_date', TRUE) == 'disabled' ) { ?>
					<?php //display nothing if disabled ?>
				<?php } else { ?>
					<li><span><?php _e('Date','wpex'); ?>:</span><?php the_date('M Y'); ?></li>
				<?php } ?>
				
				<?php
				// Categories
				if($terms) { ?>
					<li><span><?php _e('Labeled','wpex'); ?>:</span><?php echo get_the_term_list( get_the_ID(), 'portfolio_cats', '',', ',' ') ?></li>
				<?php } ?>  
				  
				<?php
				// Cost
				if( get_post_meta(get_the_ID(), 'office_portfolio_cost', TRUE) !=='' ) { ?>
					<li><span><?php _e('Cost','wpex'); ?>:</span><?php echo get_post_meta(get_the_ID(), 'office_portfolio_cost', TRUE); ?></li>
				<?php } ?>
				
				<?php
				// Client
				if( get_post_meta(get_the_ID(), 'office_portfolio_client', TRUE) !=='' ) { ?>
					<li><span><?php _e('Client','wpex'); ?>:</span><?php echo get_post_meta(get_the_ID(), 'office_portfolio_client', TRUE); ?></li>
				<?php } ?>
				
				<?php
				// URL
				if ( get_post_meta(get_the_ID(), 'office_portfolio_url', TRUE) !=='' && get_post_meta(get_the_ID(), 'wpex_portfolio_post_url_name', TRUE) == '') { ?>
					<li><span><?php _e('Website','wpex'); ?>:</span><a href="<?php echo get_post_meta(get_the_ID(), 'office_portfolio_url', TRUE); ?>" title="<?php _e('Visit Website','wpex'); ?>"><?php echo get_post_meta(get_the_ID(), 'office_portfolio_url', TRUE); ?></a></li>
				<?php } ?>
				
				<?php
				// URL with Name
				if( get_post_meta(get_the_ID(), 'office_portfolio_url', TRUE) !=='' && get_post_meta(get_the_ID(), 'wpex_portfolio_post_url_name', TRUE) !== '') { ?>
					<li><span><?php _e('Website','wpex'); ?>:</span><a href="<?php echo get_post_meta(get_the_ID(), 'office_portfolio_url', TRUE); ?>" title="<?php echo get_post_meta(get_the_ID(), 'wpex_portfolio_post_url_name', TRUE); ?>"><?php echo get_post_meta(get_the_ID(), 'wpex_portfolio_post_url_name', TRUE); ?></a></li>
				<?php } ?>
				
				
			</ul>
		</div><!-- /single-portfolio-meta -->
	<?php if( get_post_meta(get_the_ID(), 'office_portfolio_style', TRUE) == 'full' || get_post_meta(get_the_ID(), 'office_portfolio_style', TRUE) == 'grid' ) { ?>
	</div>
	<?php } ?>
<?php endif; ?>