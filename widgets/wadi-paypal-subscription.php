<?php

/**
 * Smart Paypal Payment Donations
 */
namespace WadiAddons\Widgets;

// Elementor Classes.
use  Elementor\Modules\DynamicTags\Module as TagsModule ;
use  Elementor\Icons_Manager ;
use  Elementor\Utils ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Control_Media ;
use  Elementor\Repeater ;
use  Elementor\Core\Kits\Documents\Tabs\Global_Colors ;
use  Elementor\Core\Kits\Documents\Tabs\Global_Typography ;
use  Elementor\Group_Control_Border ;
use  Elementor\Group_Control_Typography ;
use  Elementor\Group_Control_Box_Shadow ;
use  Elementor\Group_Control_Background ;
use  Elementor\Group_Control_Base ;
use  Elementor\Group_Control_Image_Size ;
// Wadi Classes
use  WadiAddons\Includes\WadiHelpers ;
use  WadiAddons\Includes\WadiQueries ;
use  WadiAddons\Admin\Admin_Helper ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Wadi_Paypal_Subscription extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-paypal-subscription-addon';
    }
    
    public function get_title()
    {
        return sprintf( '%1$s %2$s', 'Wadi', __( 'Paypal Subscription', 'wadi-addons' ) );
    }
    
    public function get_icon()
    {
        return 'wadi-paypal-subscription';
    }
    
    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the widget belongs to.
     *
     * @since 2.1.0
     * @access public
     *
     * @return array Widget keywords.
     */
    public function get_keywords()
    {
        return [
            'wadi-addons',
            'paypal',
            'paypal subscription',
            'payment',
            'paypal subscribe'
        ];
    }
    
    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }
    
    public function __construct( $data = array(), $args = null )
    {
        parent::__construct( $data, $args );
    }
    
    public function get_script_depends()
    {
    }
    
    public function get_style_depends()
    {
    }
    
    protected function register_controls()
    {
    }
    
    protected function render()
    {
    }
    
    protected function content_template()
    {
    }

}