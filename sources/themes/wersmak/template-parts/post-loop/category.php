<?php
/**
 * Displays the post loop entry categories
 *
 * @author 	PressLayouts
 * @package kapee/template-parts/post-loop
 * @since 1.3.0
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! kapee_get_loop_prop( 'post-category' ) ) return;
?>		
		
<div class="entry-category">	
	<span class="cat-links"><?php echo get_the_category_list( esc_html__( ', ', 'kapee' ) );?> </span>
</div>