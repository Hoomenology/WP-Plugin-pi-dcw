<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              piwebsolution.com
 * @since             1.9.0
 * @package           Pi_Dcw
 *
 * @wordpress-plugin
 * Plugin Name:       Direct checkout, WooCommerce Single page checkout , WooCommerce One page checkout 
 * Plugin URI:        https://www.piwebsolution.com/product/add-to-cart-direct-checkout-for-woocommerce-pro/
 * Description:       WooCommerce single page checkout, lets you show cart and checkout option on single page, that is one page checkout for WooCommerce, along with it you can redirect user directly to checkout as they click add to cart
 * Version:           1.9.0
 * Author:            PI Websolution
 * Author URI:        https://www.piwebsolution.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pi-dcw
 * Domain Path:       /languages
 * WC tested up to: 4.5.2
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Checking Pro version
 */
function pi_dcw_pro_check(){
	if(is_plugin_active( 'add-to-cart-direct-checkout-for-woocommerce-pro/pi-dcw.php')){
		return true;
	}
	return false;
}

if(pi_dcw_pro_check()){
	/** if free version is then deactivate the pro version */
    function pi_dcw_pro_error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'You have pro version of add to cart redirect active please deactivate the pro version first and then activate free version', 'pi-dcw' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pi_dcw_free_error_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}else{
/** check woocommerce */
if(!is_plugin_active( 'woocommerce/woocommerce.php')){
    function pi_dcw_my_error_notice() {
        ?>
        <div class="error notice">
            <p><?php _e( 'Please Install and Activate WooCommerce plugin, without that this plugin cant work', 'pi-dcw' ); ?></p>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'pi_dcw_my_error_notice' );
    deactivate_plugins(plugin_basename(__FILE__));
    return;
}

/* buy link and buy price */
define('PI_DCW_BUY_URL', 'https://www.piwebsolution.com/checkout/?add-to-cart=1015&variation_id=1824');
define('PI_DCW_PRICE', '$16');

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PI_DCW_VERSION', '1.9.0' );
define( 'PISOL_DCW_DELETE_SETTING', false );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pi-dcw-activator.php
 */
function activate_pi_dcw() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pi-dcw-activator.php';
	Pi_Dcw_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pi-dcw-deactivator.php
 */
function deactivate_pi_dcw() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pi-dcw-deactivator.php';
	Pi_Dcw_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pi_dcw' );
register_deactivation_hook( __FILE__, 'deactivate_pi_dcw' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pi-dcw.php';

function pisol_dcw_plugin_link( $links ) {
	$links = array_merge( array(
        '<a href="' . esc_url( admin_url( '/admin.php?page=pi-dcw' ) ) . '">' . __( 'Settings' ) . '</a>',
        '<a style="color:#0a9a3e; font-weight:bold;" target="_blank" href="' . esc_url(PI_DCW_BUY_URL) . '">' . __( 'Buy PRO Version','pi-dcw' ) . '</a>'
	), $links );
	return $links;
}
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pisol_dcw_plugin_link' );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pi_dcw() {

	$plugin = new Pi_Dcw();
	$plugin->run();

}
run_pi_dcw();
}