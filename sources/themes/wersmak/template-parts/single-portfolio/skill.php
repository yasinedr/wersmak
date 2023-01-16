<?php
/**
 * Template part for displaying skill of project
 *
 * @author 	PressLayouts
 * @package kapee/template-parts/single-portfolio
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! kapee_get_option('single-portfolio-skill', 1) ) return;
?>

<div class="project-info-item">
	<h5><?php esc_html_e( 'Skill', 'kapee' );?><span>:</span></h5>
	<p><?php echo kapee_get_taxonomy_list(get_the_ID(),'portfolio_skill', ', '); // PHPCS:Ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
</div>