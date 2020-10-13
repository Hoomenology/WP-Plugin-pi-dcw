<?php

class Pi_Dcw_Menu{

    public $plugin_name;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action($this->plugin_name.'_promotion', array($this,'promotion'));

        
    }

    function plugin_menu(){
        
        $this->menu = add_submenu_page(
            'woocommerce',
            __( 'Direct Checkout', 'pi-dcw' ),
            __( 'Direct Checkout', 'pi-dcw' ),
            'manage_options',
            'pi-dcw',
            array($this, 'menu_option_page'),
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
 
    }

    public function bootstrap_style() {

		wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );

	}

    function menu_option_page(){
        ?>
        <div class="bootstrap-wrapper">
        <div class="container mt-2">
            <div class="row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="row">
                            <div class="col-12 col-sm-2 py-2">
                                    <a href="https://www.piwebsolution.com/" target="_blank"><img class="img-fluid ml-2" src="<?php echo plugin_dir_url( __FILE__ ); ?>img/pi-web-solution.png"></a>
                            </div>
                            <div class="col-12 col-sm-10 d-flex text-center small">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pb-3 pt-0">
                    <div class="row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </div>
        <?php
    }

    function promotion(){
        ?>
        <?php if(  !pi_dcw_pro_check() ) : ?>
        <div class="col-12 col-sm-12 col-md-4 pt-3">

                <div class="bg-dark text-light text-center mb-3">
                    <a href="<?php echo PI_DCW_BUY_URL; ?>" target="_blank">
                        <?php new pisol_promotion('add_to_cart_installation_date'); ?>
                    </a>
                </div>
            
           <div class="bg-primary p-3 text-light text-center mb-3">
                <h2 class="text-light  "><span>Get Pro for <h2 class="h1 font-weight-bold my-2 text-light"><?php echo PI_DCW_PRICE; ?></h2> </span></h2>
                <div class="inside">
                    PRO version Features are:<br><br>
                    <ul class="text-left  h6 font-weight-light">
                    <li class="border-top py-2 h6 ">Our Redirect also works with <span class="font-weight-bold text-dark">Ajax add to cart</span> button</li>
                    <li class="border-top py-2 h6  mb-0">
                    <span class="font-weight-bold text-dark">Buy now button for Variable product on Archive pages</span>
                    </li>
                    <li class="border-top py-2 h6  mb-0">Set custom redirect on each product, so they get redirected to some related product where they can buy that as well</li>
                    <li class="border-top py-2 h6  mb-0"><span class="font-weight-bold text-dark">Product Overwrite</span> for redirect setting</li>
                    <li class="border-top py-2 h6  mb-0"><span class="font-weight-bold text-dark">Disable redirect</span> for an specific product</li>
                    <li class="border-top py-2 h6  mb-0"><span class="font-weight-bold text-dark">Enable redirect</span> for an only specific product</li>
                    <li class="border-top border-top py-2 h6">Set different <span class="font-weight-bold text-dark">Redirect page</span> for a specific product</li>
                    <li class="border-top border-top py-2 h6"><span class="font-weight-bold text-dark">Modify the label</span> of Buy now / quick purchase button</li>
                    <li class="border-top border-top py-2 h6"><span class="font-weight-bold text-dark">Change the position</span> of the buy now / quick purchase button</li>
                    <li class="border-top border-top py-2 h6"><span class="font-weight-bold text-dark">Change redirect page</span> for buy now / quick purchase button</li>
                    <li class="border-top border-top py-2 h6"><span class="font-weight-bold text-dark">Disable buy now button</span> for a specific product</li>
                    <li class="border-top border-top py-2 h6"><span class="font-weight-bold text-dark">Customize the color/size</span> of the Quick view module box</li>
                    <li class="border-top border-top py-2 h6">Disable <span class="font-weight-bold text-dark">"Ship to a different address?"</span></li>
                    <li class="border-top border-top py-2 h6">Remove field from the <span class="font-weight-bold text-dark">billing address</span></li>
                    <li class="border-top border-top py-2 h6">Remove field from the <span class="font-weight-bold text-dark">shipping address</span></li>
                    <li class="border-top border-top py-2 h6">Set any page as <span class="font-weight-bold text-dark">order success page</span></li>
                    </ul>
                    <a class="btn btn-light" href="<?php echo PI_DCW_BUY_URL; ?>" target="_blank">Click to Buy Now</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php
    }

    function isWeekend() {
        return (date('N', strtotime(date('Y/m/d'))) >= 6);
    }

}