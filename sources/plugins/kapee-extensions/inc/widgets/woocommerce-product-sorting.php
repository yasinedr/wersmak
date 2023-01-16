<?php
/**
 *	Kapee Widget: Product Sorting List
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Kapee_WC_Widget_Product_Sorting extends WC_Widget {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    	= 'kapee_widget_product_sorting widget_layered_nav';
		$this->widget_description	= __( 'Display a product sorting list.', 'kapee-extensions' );
		$this->widget_id          	= 'kapee_product_sorting';
		$this->widget_name        	= __( 'KP: Product Sorting', 'kapee-extensions' );
		$this->settings           	= array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Sort By', 'kapee-extensions' ),
				'label'	=> __( 'Title:', 'kapee-extensions' )
			)
		);
		
		parent::__construct();
		
	}

	/**
	 * Widget function
	 *
	 * @see WP_Widget
	 * @access public
	 * @param array $args
	 * @param array $instance
	 * @return void
	 */
	public function widget( $args, $instance ) {
		if ( ! kapee_is_catalog() ) {
			return;
		}
		global $wp_query;
		
		extract( $args );
		
        if ( $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ) ) {
            $title = $before_title . $title . $after_title;
        }
        
		$output = '';
		
		if ( 1 != $wp_query->found_posts || woocommerce_products_will_display() ) {
			$output .= '<ul id="kapee-product-sorting" class="kapee-product-sorting">';
			
			$orderby = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
			$orderby == ( $orderby ===  'title' ) ? 'menu_order' : $orderby; // Fixed: 'title' is default before WooCommerce settings are saved
			
			$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
				'menu_order'	=> __( 'Default', 'kapee-extensions' ),
				'popularity' 	=> __( 'Popularity', 'kapee-extensions' ),
				'rating'     	=> __( 'Average rating', 'kapee-extensions' ),
				'date'       	=> __( 'Newness', 'kapee-extensions' ),
				'price'      	=> __( 'Price: Low to High', 'kapee-extensions' ),
				'price-desc'	=> __( 'Price: High to Low', 'kapee-extensions' )
			) );
	
			if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
				unset( $catalog_orderby_options['rating'] );
			}
			
			
			/* Build entire current page URL (including query strings) */
			global $wp;
			$link = home_url( $wp->request ); // Base page URL
					
			// Unset query strings used for Ajax shop filters
			unset( $_GET['shop_load'] );
			unset( $_GET['_'] );
			
			$qs_count = count( $_GET );
			
			// Any query strings to add?
			if ( $qs_count > 0 ) {
				$i = 0;
				$link .= '?';
				
				// Build query string
				foreach ( $_GET as $key => $value ) {
					$i++;
					$link .= $key . '=' . $value;
					if ( $i != $qs_count ) {
						$link .= '&';
					}
				}
			}
			
			
            foreach ( $catalog_orderby_options as $id => $name ) {
				if ( $orderby == $id ) {
					$output .= '<li class="chosen active"><a href="#">' . esc_attr( $name ) . '</a></li>';
				} else {
					// Add 'orderby' URL query string
					$link = add_query_arg( 'orderby', $id, $link );
					$output .= '<li><a href="' . esc_url( $link ) . '">' . esc_attr( $name ) . '</a></li>';
				}
            }
			       
        	$output .= '</ul>';
		}
		
		echo $before_widget . $title . $output . $after_widget;
	}
	
}
