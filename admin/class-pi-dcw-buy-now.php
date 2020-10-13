<?php

class Class_Pi_Dcw_Buy_Now{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'buy-now';

    private $tab_name = "Buy Now Button";

    private $setting_key = 'dcw_buy_now_setting';

    private $pages =array();
    
    private $pro_version = false;

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING );
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }


        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $this->settings = array(
            array('field'=>'sunday', 'class'=> 'bg-secondary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('On product page','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_enable_buy_now_button','desc'=>__('Buy now button on single product page','pi-dcw'), 'label'=>__('Buy now button on product page','pi-dcw'),'type'=>'switch', 'default'=>0),

            array('field'=>'pi_dcw_buy_now_button_text','desc'=>__('Buy now button label','pi-dcw'), 'label'=>__('Label of the buy now button','pi-dcw'),'type'=>'text', 'default'=>'SHOP Now', 'pro'=>true),

            array('field'=>'pi_dcw_buy_now_button_position','desc'=>__('Position of the button','pi-dcw'), 'label'=>__('Position of the button','pi-dcw'),'type'=>'select', 'default'=>'after_form', 'value'=>array('before_form'=>__('Before add to cart form','pi-dcw'), 'after_form'=>__('After add to cart form','pi-dcw'), 'after_button'=>__('After add to cart button','pi-dcw'), 'before_button'=>__('Before add to cart button','pi-dcw')), 'pro'=>true),

            array('field'=>'pi_dcw_buy_now_button_redirect','desc'=>__('Redirect to cart or checkout page','pi-dcw'), 'label'=>__('Redirect to cart/checkout page','pi-dcw'),'type'=>'select', 'default'=>'checkout', 'value'=>array('checkout'=>__('Checkout','pi-dcw'), 'cart'=>__('Cart','pi-dcw')), 'pro'=>true),

            array('field'=>'pisol_dcw_button_size','desc'=>'Buy now button size on product page (PX)', 'label'=>__('Buy now button size on product page'),'type'=>'number', 'default'=>'', 'min'=>100, 'placeholder'=>'px'),


            array('field'=>'sunday', 'class'=> 'bg-secondary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('On product archive page','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_enable_buy_now_button_loop','desc'=>__('Buy now button on Product archive page like loop , category','pi-dcw'), 'label'=>__('Buy now button on product archive page','pi-dcw'),'type'=>'switch', 'default'=>0),

            array('field'=>'pi_dcw_buy_now_button__loop_text','desc'=>__('Buy now button label','pi-dcw'), 'label'=>__('Label of the buy now button','pi-dcw'),'type'=>'text', 'default'=>'SHOP Now', 'pro'=>true),

            array('field'=>'pi_dcw_buy_now_button_loop_position','desc'=>__('Position of the button','pi-dcw'), 'label'=>__('Position of the button','pi-dcw'),'type'=>'select', 'default'=>'after_button', 'value'=>array('after_button'=>__('After add to cart button','pi-dcw'), 'before_button'=>__('Before add to cart button','pi-dcw')), 'pro'=>true),
            
            array('field'=>'pi_dcw_buy_now_button_loop_redirect','desc'=>__('Redirect to cart or checkout page','pi-dcw'), 'label'=>__('Redirect to cart/checkout page','pi-dcw'),'type'=>'select', 'default'=>'checkout', 'value'=>array('checkout'=>__('Checkout','pi-dcw'), 'cart'=>__('Cart','pi-dcw')), 'pro'=>true),

            array('field'=>'pi_dcw_enable_buy_now_for_variable_product_on_loop','desc'=>__('this will show the buy now button for variable product, and this buy now button will add the first variation of that product to cart <strong class="text-primary">You must have set Default value for all the required variation attributes, else the buy now button for that product may not work</strong>','pi-dcw'), 'label'=>__('Show buy now option on variable product, on category / Shop page','pi-dcw'),'type'=>'switch', 'default'=>0, 'pro'=>true),

            array('field'=>'sunday', 'class'=> 'bg-secondary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Buy now Button design','pi-dcw'), 'type'=>'setting_category'),

            array('field'=>'pi_dcw_buy_now_bg_color','desc'=>__('Background color of Buy now button','pi-dcw'), 'label'=>__('Background color','pi-dcw'),'type'=>'color', 'default'=>'#ee6443'),

            array('field'=>'pi_dcw_buy_now_text_color','desc'=>__('Text color of Buy now button','pi-dcw'), 'label'=>__('Text color','pi-dcw'),'type'=>'color', 'default'=>'#ffffff'),
        );
        $this->register_settings();
    }

   
    

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name, 'http2-push-content' ); ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_dcw($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="Save Option" />
        </form>
       <?php
    }

   
}

new Class_Pi_Dcw_Buy_Now($this->plugin_name);