<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       piwebsolution.com
 * @since      1.0.0
 *
 * @package    Pi_Dcw
 * @subpackage Pi_Dcw/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pi_Dcw
 * @subpackage Pi_Dcw/public
 * @author     PI Websolution <sales@piwebsolution.com>
 */
class Pi_Dcw_Public {

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

	public $continue_shopping;
	public $global_redirect;
	public $ajax_support;
	public $redirect_page;
	public $redirect_custom_url;
	public $custom_url;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->continue_shopping = get_option('pi_dcw_global_disable_continue_shopping_btn',0);
		$this->global_redirect = get_option('pi_dcw_global_redirect',1);
		$this->ajax_support = get_option('pi_dcw_global_ajax_support',1);
		$this->redirect_page = get_option('pi_dcw_global_redirect_to_page',0);
		$this->redirect_custom_url = get_option('pi_dcw_global_redirect_custom_url',0);
		$this->custom_url = get_option('pi_dcw_global_custom_url',"");
		$this->disable_cart = get_option('pi_dcw_disable_cart',1);
		$this->single_page_checkout = get_option('pi_dcw_single_page_checkout',1);
		
		add_filter( 'woocommerce_add_to_cart_redirect', array($this,'redirect_to_selected_page') );

		if(!pi_dcw_pro_check()){
			add_filter( 'woocommerce_get_script_data', array( $this, 'add_script_data' ), 10, 2 );
		}

		if($this->continue_shopping){
			add_filter( 'wc_add_to_cart_message_html', array($this,'remove_continue_shopping'));
		}

		if($this->disable_cart == 1){
			add_action( 'template_redirect',array($this,'cart_redirect'));
			remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
		}

		if($this->single_page_checkout == 1){
		add_filter( 'the_content', array($this,'get_checklist_template') ) ;
		}

		add_filter( 'woocommerce_checkout_fields' , array($this,'removeFields') );

		add_filter( 'woocommerce_coupons_enabled', array($this,'removeCoupon') );
	}

	
	function get_checklist_template($content) {
		global $post;
		$cart_id = wc_get_page_id('checkout');
		if ($post->ID == $cart_id) {
			wp_enqueue_style($this->plugin_name);
			wp_enqueue_script($this->plugin_name);
			ob_start();
			if ( !is_wc_endpoint_url( 'order-received' ) ){
				echo do_shortcode('[woocommerce_cart]');
			}
			echo do_shortcode('[woocommerce_checkout]');
			
			
			
			$output = ob_get_contents();
			ob_end_clean();
			$content = $output;
		}
		return $content;
	}

	function cart_redirect($permalink) {
		$cart_id = wc_get_page_id('cart');
		$checkout_id = wc_get_page_id('checkout');

		if($cart_id == $checkout_id) { return; }

		if ( ! is_cart() ) { return; }
		if ( WC()->cart->get_cart_contents_count() == 0 ) {
            wp_redirect( apply_filters( 'wcdcp_redirect', wc_get_page_permalink( 'shop' ) ) );
            exit;
        }

        // Redirect to checkout page
        wp_redirect( wc_get_checkout_url(), '301' );
        exit;
	}

	function redirect_to_selected_page( $url ) {

		if (defined('DOING_AJAX') && DOING_AJAX) return "";

		$global_redirect = $this->global_redirect;

		if(isset($_POST['pi_quick_checkout']) || isset($_GET['pi_quick_checkout'])){
			$url = Pi_WooCommerce_Quick_Buy_Auto_Add::get_redirect_url();
			return $url;
		}
		
		if($global_redirect == 1){
			$redirect_to = (int)$this->redirect_page;
			if($this->redirect_custom_url == 0){
				if($redirect_to == 0 || $redirect_to == ""){
					$url = wc_get_checkout_url();
				}else{
					$url = get_permalink($redirect_to);
				}
			}else{
				if($this->custom_url == ""){
					$url = wc_get_checkout_url();
				}else{
					$url = $this->custom_url;
				}
			}
		}

		if ( ! isset( $_REQUEST['add-to-cart'] ) || ! is_numeric( $_REQUEST['add-to-cart'] ) ) {
			return $url;
		}


		$product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
		$disable_redirect_for_this_product = apply_filters("pi_disable_redirect_for_this_product","no", $product_id); 
		if($disable_redirect_for_this_product == "yes"){
			$url = "";
		}

		$overwrite_global = apply_filters("pi_dcw_product_overwrite_global","no", $product_id); 
		$product_redirect_to_page = apply_filters("pi_dcw_product_redirect_to_page","no", $product_id); 
		
		if($overwrite_global == 'yes' && $disable_redirect_for_this_product != 'yes'){
			if($product_redirect_to_page == ""){
				$url = wc_get_checkout_url();
			}else{
				$url = $product_redirect_to_page;
			}
		}

		return $url;
	}

	function remove_continue_shopping( $string, $product_id = 0 ) {
		$start = strpos( $string, '<a href=' ) ?: 0;
		$end = strpos( $string, '</a>', $start ) ?: 0;
		return substr( $string, $end ) ?: $string;
	}

	public function add_script_data( $params, $handle ) {
		$global_redirect = $this->global_redirect;

		if($global_redirect == 1){
			if ( 'wc-add-to-cart' == $handle ) {
				$params = array_merge( $params, array(
					'cart_redirect_after_add' => 'yes',
				) );
			}
		}else{
			if ( 'wc-add-to-cart' == $handle ) {
				$params = array_merge( $params, array(
					'cart_redirect_after_add' => 'no',
				) );
			}
		}
		
		
		
		return $params;
	}

	

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pi_Dcw_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pi_Dcw_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/pi-dcw-public.css', array(), $this->version, 'all' );

		$pi_dcw_buy_now_bg_color = get_option('pi_dcw_buy_now_bg_color', '#ee6443');
		$pi_dcw_buy_now_text_color = get_option('pi_dcw_buy_now_text_color', '#ffffff');

		$buy_now_button_size = get_option('pisol_dcw_button_size','');

		$button_size = "";
		if(!empty($buy_now_button_size)){
			$button_size = "
				.pisol_buy_now_button.pisol_single_buy_now{
					width:{$buy_now_button_size}px !important;
					max-width:100% !important;
				}
			";
		}

		$css = "
		.pisol_buy_now_button{
			color:{$pi_dcw_buy_now_text_color} !important;
			background-color: {$pi_dcw_buy_now_bg_color} !important;
		}
		";
		wp_add_inline_style($this->plugin_name, $css.$button_size);


	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pi_Dcw_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pi_Dcw_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/pi-dcw-public.js', array( 'jquery' ), $this->version, false );
		
	}

	function removeFields($fields){
		$remove_order_comment = get_option('pi_dcw_remove_order_comment',0);

		if($remove_order_comment == 1){
			unset($fields['order']['order_comments']);
		}

		
		return $fields;
	}

	function removeCoupon( $enabled ) {
		$remove_have_coupon = get_option('pi_dcw_remove_have_coupon',0);

		if ( is_checkout() && $remove_have_coupon == 1 ) {
			$enabled = false;
		}
		return $enabled;
	}

}
