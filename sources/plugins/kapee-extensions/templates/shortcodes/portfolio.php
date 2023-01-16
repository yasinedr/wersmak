<?php 
/**
 * Portfolio Template
 */
?>

<div id="<?php echo esc_attr($id);?>" class="<?php echo esc_attr($class);?>">
<?php
do_action( 'kapee_before_portfolio_loop' );
	
	kapee_portfolio_loop_start();

	while ( $query->have_posts() ) :
		$query->the_post();		
		// Include the portfolio loop content template.
		get_template_part( 'template-parts/portfolio-loop/layout', get_post_format() );

	endwhile;
	
	kapee_portfolio_loop_end();
	
	if( $show_pagination ) { ?>
		<div class="kapee-pagination">
			<?php		
			if ( $pagination != 'default' ){
				
				$load_more_label 		= kapee_get_loop_prop( 'portfolio-pagination-load-more-button-text' );
				$loading_finished_msg 	= kapee_get_loop_prop( 'portfolio-pagination-finished-message' );
				?>
				<div class="kapee-portfolio-load-more" data-pagination_style = "<?php echo esc_attr($pagination);?>"  data-page="2"
				data-total="<?php echo esc_attr($total);?>" data-attribute="<?php echo esc_attr($atts); ?>" data-pagination_style = "<?php echo esc_attr($pagination);?>" data-total="<?php echo esc_attr($total);?>"
					data-load_more_label="<?php echo esc_html($load_more_label); ?>"
					data-loading_finished_msg="<?php echo esc_html($loading_finished_msg); ?>"> 
					<a  href="javascript:void(0);" 
					class="button kapee-load-more <?php echo esc_attr($pagination); ?>"
					>
						<?php echo esc_html($load_more_label); ?>
					</a>
				</div>
			<?php }else{
				echo paginate_links( apply_filters( 'kapee_pagination_args', array(
					'base'         => $base,
					'format'       => $format,
					'add_args'     => false,
					'current'      => max( 1, $current ),
					'total'        => $total,
					'prev_text'    => esc_html__('Previous','kapee-extensions'),
					'next_text'    => esc_html__('Next','kapee-extensions'),
					'type'         => 'plain',
					'end_size'     => 2,
					'mid_size'     => 2,
				) ) );
			}?>
		</div>
	<?php }
	kapee_reset_loop();
?>
</div>