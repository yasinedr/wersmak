<?php 
/**
 * WCFM Vendors Template
 */
?>
<div id="<?php echo esc_attr($id);?>" class="<?php echo esc_attr( $class );?>">	
	<?php if( ! empty( $title ) ) { ?>
		<div class="section-heading">
			<h2><?php echo esc_html($title); ?></h2>
		</div>
	<?php } ?>
	<div class="kapee-vendors-list <?php echo esc_attr($slider_class);?>">
		<?php
		global $WCFM, $WCFMmp;
		$count = 0;
		foreach ( $vendors as $key => $vendor_id ) {
			
			$store_user      = wcfmmp_get_store( $vendor_id );
			$store_info      = $store_user->get_shop_info();
			$gravatar        = $store_user->get_avatar();
			$store_name      = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'kapee-extensions' );
			$store_name      = apply_filters( 'wcfmmp_store_title', $store_name , $vendor_id );
			$store_url       = wcfmmp_get_store_url( $vendor_id );
			$store_address   = $store_user->get_address_string(); 
			$store_phone		= isset($store_info['phone']) ? $store_info['phone'] : '';
			$banner          = $store_user->get_list_banner();
			$has_bg_class 		= $banner ? "has-vendor-background" : '';
			if( $banner ) {
				$store_banner_url = $banner;
				
			}else{
				$banner = isset( $WCFMmp->wcfmmp_marketplace_options['store_list_default_banner'] ) ? $WCFMmp->wcfmmp_marketplace_options['store_list_default_banner'] : $WCFMmp->plugin_url . 'assets/images/default_banner.jpg';
				$store_banner_url = apply_filters( 'wcfmmp_list_store_default_bannar', $banner );
			}
			
			
			
			if( $rows > 1 && $count % $rows == 0 ){
				echo '<div class="carousel-group">';
			}
			?>
			<div class="kapee-single-vendor woocommerce <?php echo esc_attr($column_class);?>">
				<div class="kapee-store-wrapper">
					<div class="kapee-store-content">
						<div class="kapee-store-content-wrapper <?php echo esc_attr($has_bg_class);?>" style="background-image: url( '<?php echo is_array( $store_banner_url ) ? esc_attr( $store_banner_url[0] ) : esc_attr( $store_banner_url ); ?>');">
							<div class="kapee-store-content-container">
								<div class="kapee-store-data">
									<h2><?php echo esc_html( $store_name ); ?></h2>

									<div class="star-rating">
										<?php $store_user->show_star_rating(); ?>
									</div>
									
									<?php if ( !empty($store_address) || !empty($store_phone) ){ ?>
										<ul class="store-details">
											<?php if ( $store_address ){
												$allowed_tags = array(
													'span' => array(
														'class' => array(),
													),
													'br' => array()
												); ?>
												<li class="store-address">
													<i class="pls-location-pin" aria-hidden="true"></i> <?php echo wp_kses( $store_address, $allowed_tags ); ?>
												</li>
											<?php } ?>
											
											<?php if ( $store_phone ) { ?>
												<li class="store-phone">
													<i class="pls-smartphone" aria-hidden="true"></i> <?php echo esc_html( $store_phone ); ?>
												</li>
											<?php } ?>
										</ul>
									<?php } ?>									
								</div>
							</div>
						</div>
					</div>
					<div class="kapee-store-footer">
						<div class="vendor-avatar">
							<img src="<?php echo esc_url($gravatar); ?>" alt="<?php esc_html_e('Logo','kapee-extensions'); ?>"/>		
						</div>
						<a class="button kapee-store-link" href="<?php echo esc_attr( $store_url ); ?>"><?php esc_html_e( 'Visit Store', 'kapee-extensions' )?></a>
					</div>
				</div>
			</div>	
		<?php 
				if( $rows > 1 && ($count % $rows == $rows - 1 || $count == $vendors_count - 1) ){
					echo '</div>';
				}
				$count++;
			}	?>
	</div>
</div>