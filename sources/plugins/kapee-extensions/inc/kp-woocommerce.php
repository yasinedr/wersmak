<?php 
/**
 * Kapee Woocommerce functions
 */
// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

class Kapee_Admin_Woocommerce {
	public $prefix;
	function __construct() {
		$this->prefix = KAPEE_EXTENSIONS_META_PREFIX;
		
		//Add action to register product bran taxonomy
		add_action('init' ,array( $this, 'kapee_brand_register_taxonomies' ),0 );
		/* Product brand functions */
		$brand = 'product_brand';
		add_action( $brand . '_add_form_fields', array( $this, 'kapee_taxonomy_add_brand_new_meta_field' ) );
		add_action( $brand.'_edit_form_fields', array( $this, 'kapee_taxonomy_brand_edit_meta_field' ), 10 );
		//add_filter('manage_edit-'.$brand.'_columns', array( $this,'kapee_add_brand_preview_column') );
		//add_filter('manage_' .$brand.'_custom_column', array( $this,'kapee_add_brand_preview_column_content'),10, 3 );
		
		// Save taxonomy fields
		add_action('edited_'.$brand, array($this, 'kapee_save_brand_fields'));
		add_action('create_'.$brand, array($this, 'kapee_save_brand_fields'));
	}
	
	/**
	 * Function to register product_brand taxonomy
	*/
	function kapee_brand_register_taxonomies() {
		
		$cat_labels = apply_filters('kapee_product_brand_labels', array(
			'name'              => __( 'Brands', 'kapee-extensions' ),
			'singular_name'     => __( 'Brands', 'kapee-extensions' ),
			'search_items'      => __( 'Search Brand', 'kapee-extensions' ),
			'all_items'         => __( 'All Brand', 'kapee-extensions' ),
			'parent_item'       => __( 'Parent Brand', 'kapee-extensions' ),
			'parent_item_colon' => __( 'Parent Brand:', 'kapee-extensions' ),
			'edit_item'         => __( 'Edit Brand', 'kapee-extensions' ),
			'update_item'       => __( 'Update Brand', 'kapee-extensions' ),
			'add_new_item'      => __( 'Add New Brand', 'kapee-extensions' ),
			'new_item_name'     => __( 'New Brand Name', 'kapee-extensions' ),
			'menu_name'         => __( 'Brands', 'kapee-extensions' ),
		));
		
		$cat_args = array(
			'public'			=> true,
			'hierarchical'      => true,
			'labels'            => $cat_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => apply_filters('kapee_brand_slug','brand') ),
		);
		
		// Register Logo Showcase category
		register_taxonomy( 'product_brand', array( 'product' ), $cat_args );
		flush_rewrite_rules();
	}
	
	/**
	 * Function to add product brand meta field
	*/
	function kapee_taxonomy_add_brand_new_meta_field() {
    ?>
		<div class="form-field">
			<label for="kapee-color"><?php _e('Upload Image', 'kapee-extensions'); ?></label>
			<input type="hidden" class="kapee-attachment-id" name="thumbnail_id">
			<img class="kapee-attr-img" src="<?php echo wc_placeholder_img_src();?>" alt="<?php _e('Select Image','kapee-extensions')?>" height="50px" width="50px">
			<button class="kapee-image-upload button" type="button"><?php _e('Upload/Add Image','kapee-extensions');?></button>
			<button class="kapee-image-clear button" type="button" data-src="<?php echo wc_placeholder_img_src();?>"><?php _e('Remove Image','kapee-extensions');?></button>
			 <p class="description"><?php _e('Upload image for this value.', 'kapee-extensions'); ?></p>
	    </div>
		<script>
		jQuery( document ).ajaxComplete( function( event, request, options ) {
			if ( request && 4 === request.readyState && 200 === request.status
				&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {

				var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
				if ( ! res || res.errors ) {
					return;
				}
				// Clear Thumbnail fields on submit
				jQuery( '.kapee-attr-img').attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
				jQuery( '.kapee-attachment-id' ).val( '' );				
				return;
			}
		} );
		</script>
		<?php
	}
	
	/**
	 * Function edit add product brand meta field
	*/
	function kapee_taxonomy_brand_edit_meta_field( $term ) {		
		//getting term ID
	    $term_id = $term->term_id;
	    // Getting stored values
	    $thumbnail_id = get_term_meta($term_id, 'thumbnail_id', true);     
		$image = wc_placeholder_img_src();
		if(!empty($thumbnail_id)){
			$image = kapee_get_image_src( $thumbnail_id,'thumnail');
		}
		
		?>
		<tr class="form-field">
	        <th scope="row" valign="top"><label for="kapee-attr-image"><?php _e('Upload Image', 'kapee-extensions'); ?></label></th>
	        <td>
	            <input type="hidden" class="kapee-attachment-id" value="<?php echo esc_attr($thumbnail_id);?>" name="thumbnail_id">
				<img class="kapee-attr-img" src="<?php echo esc_url($image);?>" alt="<?php _e('Select Image','kapee-extensions')?>" height="50px" width="50px">
				<button class="kapee-image-upload button" type="button"><?php _e('Upload/Add Image','kapee-extensions');?></button>
				<button class="kapee-image-clear button" type="button" data-src="<?php echo wc_placeholder_img_src();?>"><?php _e('Remove Image','kapee-extensions');?></button>
				<p class="description"><?php _e('Upload image for this value.', 'kapee-extensions'); ?></p>
	        </td>
	    </tr>  
		<?php
	}
	
	/**
	 * Function to preview product brand  column field
	*/
	function kapee_add_brand_preview_column($columns){
		$new_columns = array(
								'thumb' => __('Image', 'kapee-extensions'),
							);
		$columns = kapee_add_array( $columns, $new_columns, 1);

	    return $columns;
	}
	
	/**
	 * Function to preview product brand content field
	*/
	function kapee_add_brand_preview_column_content($columns, $column, $term_id ){
		switch ( $column ) {
			case 'thumb':
				$kapee_attachment_id = get_term_meta($term_id, 'thumbnail_id', true);    
				$image = wc_placeholder_img_src();
				if(!empty($kapee_attachment_id)){
					$image = kapee_get_image_src( $kapee_attachment_id,'thumnail');
				}
				echo '<img src="'.$image.'" alt="brand image" width="64" height="64">';
				break;
		}
	}
	
	/**
	 * Function to save product brand meta field
	*/
	function kapee_save_brand_fields($term_id) {
		
	    $thumbnail_id = !empty($_POST['thumbnail_id']) ? $_POST['thumbnail_id'] : ''; 
	    update_term_meta($term_id,'thumbnail_id', $thumbnail_id);
	}
	
}
$kapee_woocommerce_obj = new Kapee_Admin_Woocommerce();