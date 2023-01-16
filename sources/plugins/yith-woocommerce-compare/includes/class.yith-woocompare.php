<?php
/**
 * Main class
 *
 * @author YITH
 * @package YITH Woocommerce Compare
 * @version 1.1.4
 */

defined( 'YITH_WOOCOMPARE' ) || exit; // Exit if accessed directly.

if ( ! class_exists( 'YITH_Woocompare' ) ) {
	/**
	 * YITH Woocommerce Compare
	 *
	 * @since 1.0.0
	 */
	class YITH_Woocompare {

		/**
		 * Plugin object
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $obj = null;

		/**
		 * AJAX Helper
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $ajax = null;

		/**
		 * Constructor
		 *
		 * @return YITH_Woocompare_Admin | YITH_Woocompare_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {

			add_action( 'widgets_init', array( $this, 'register_widgets' ) );

			// Load Plugin Framework.
			add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );

			if ( $this->is_frontend() ) {
				// Require frontend class.
				require_once 'class.yith-woocompare-frontend.php';

				$this->obj = new YITH_Woocompare_Frontend();
			} elseif ( $this->is_admin() ) {
				// Requires admin classes.
				require_once 'class.yith-woocompare-admin.php';

				$this->obj = new YITH_Woocompare_Admin();
			}

			// Add image size.
			YITH_Woocompare_Helper::set_image_size();

			// Let's filter the woocommerce image size.
			add_filter( 'woocommerce_get_image_size_yith-woocompare-image', array( $this, 'filter_wc_image_size' ), 10, 1 );

			return $this->obj;
		}

		/**
		 * Detect if is frontend
		 *
		 * @return bool
		 */
		public function is_frontend() {
			$is_ajax          = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			$context_check    = isset( $_REQUEST['context'] ) && 'frontend' === sanitize_text_field( wp_unslash( $_REQUEST['context'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$actions_to_check = apply_filters( 'yith_woocompare_actions_to_check_frontend', array( 'woof_draw_products', 'prdctfltr_respond_550', 'wbmz_get_products', 'jet_smart_filters', 'productfilter' ) );
			$action_check     = isset( $_REQUEST['action'] ) && in_array( sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ), $actions_to_check, true ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return (bool) YITH_Woocompare_Helper::is_elementor_editor() || ( ! is_admin() || ( $is_ajax && ( $context_check || $action_check ) ) );
		}

		/**
		 * Detect if is admin
		 *
		 * @return bool
		 */
		public function is_admin() {
			$is_ajax  = ( defined( 'DOING_AJAX' ) && DOING_AJAX );
			$is_admin = ( is_admin() || $is_ajax && isset( $_REQUEST['context'] ) && sanitize_text_field( wp_unslash( $_REQUEST['context'] ) ) === 'admin' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return apply_filters( 'yith_woocompare_check_is_admin', (bool) $is_admin );
		}

		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Load and register widgets
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function register_widgets() {
			register_widget( 'YITH_Woocompare_Widget' );
		}

		/**
		 * Filter WooCommerce image size attr
		 *
		 * @since 2.3.5
		 * @author Francesco Licandro
		 * @param array $size The default image size.
		 * @return array
		 */
		public function filter_wc_image_size( $size ) {

			$size_opt = get_option( 'yith_woocompare_image_size', array() );

			return array(
				'width'  => isset( $size_opt['width'] ) ? absint( $size_opt['width'] ) : 600,
				'height' => isset( $size_opt['height'] ) ? absint( $size_opt['height'] ) : 600,
				'crop'   => isset( $size_opt['crop'] ) ? 1 : 0,
			);
		}

	}
}
