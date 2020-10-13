<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Pi_Edd
 * @subpackage Pi_Edd/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pi_Edd
 * @subpackage Pi_Edd/admin
 * @author     PI Websolution <rajeshsingh520@gmail.com>
 */
class Pi_Dcw_Pro_Woo {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( ) {
		/** Adding order preparation days */
		add_action( 'woocommerce_product_options_general_product_data', array($this,'order_preparation_days') );
		add_action( 'woocommerce_process_product_meta', array($this,'order_preparation_days_save') );
	}

	function order_preparation_days() {

			$args1 = array(
			'id' => 'pi_dcw_product_redirect',
			'label' => __( 'Exclude this product from any redirect', 'pi-dcw' ),
			);
			$args2 = array(
				'id' => 'pi_dcw_product_overwrite_global',
				'label' => __( 'Overwrite global redirect setting', 'pi-dcw' ),
			);
			
			echo '<div class="pi-container">';
			echo '<div class="option-group disable-redirect">';
			woocommerce_wp_checkbox( $args1 );
			echo '</div>';
			echo '<div class="option-group local-redirect-setting">';
			woocommerce_wp_checkbox( $args2 );
			woocommerce_wp_text_input( 
				array( 
					'id'      => 'pi_dcw_product_redirect_to_page', 
					'label'   => __( 'Redirect to page', 'woocommerce' ), 
					'type' => 'url'
					)
				);
			echo '</div>';

			if(!pi_dcw_pro_check()){
				?>
			<div id="pi-pro-buy">
				<div id="pi-buy-now">
					<h2>BUY PRO VERSION OF<br>DIRECT CHECKOUT FOR<br><span class="pi-price"><?php echo PI_DCW_PRICE; ?></span><br> then you can overwrite global redirect setting</h2>
				</div>
				<div id="pi-buy-container">
				<a href="<?php echo PI_DCW_BUY_URL; ?>" id="pi-buy-button">BUY NOW</a>	
				</div>
			</div>
				<?php
			}
			echo '</div>';

	   }

	   function order_preparation_days_save( $post_id ) {
			$product = wc_get_product( $post_id );

			$product_redirect = ((isset( $_POST['pi_dcw_product_redirect'] )) ? $_POST['pi_dcw_product_redirect'] : 0);
			$product->update_meta_data( 'pi_dcw_product_redirect', sanitize_text_field( $product_redirect ) );
			
			$overwrite_global = ((isset( $_POST['pi_dcw_product_overwrite_global'] )) ? $_POST['pi_dcw_product_overwrite_global'] : 0);
			$product->update_meta_data( 'pi_dcw_product_overwrite_global', sanitize_text_field( $overwrite_global ) );
			
			$page = ((isset( $_POST['pi_dcw_product_redirect_to_page'] )) ? $_POST['pi_dcw_product_redirect_to_page'] : 0);
			$product->update_meta_data( 'pi_dcw_product_redirect_to_page', sanitize_text_field( $page ) );
			
			$product->save();
	   }
	
	   function get_pages(){
        $pages = get_pages( );
        $pages_array = array(""=>__("Select page","pi-dcw"));
        if($pages){
            foreach ( $pages as $page ) {
                $pages_array[$page->ID] = $page->post_title;
            }
        }
        return $pages_array;
    	}
}

new Pi_Dcw_Pro_Woo();