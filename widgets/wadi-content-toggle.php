<?php

namespace WadiAddons\Widgets;

use  Elementor\Control_Color ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Control_Media ;
use  Elementor\Group_Control_Border ;
use  Elementor\Group_Control_Box_Shadow ;
use  Elementor\Group_Control_Text_Shadow ;
use  Elementor\Group_Control_Typography ;
use  Elementor\Icons_Manager ;
use  Elementor\Repeater ;
use  Elementor\Utils ;
use  Elementor\Core\Kits\Documents\Tabs\Global_Colors ;
// Wadi Classes
use  WadiAddons\Includes\WadiHelpers ;
use  WadiAddons\Includes\WadiQueries ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Wadi_Content_Toggle extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-content-toggle-addon';
    }
    
    public function get_title()
    {
        return sprintf( '%1$s %2$s', 'Wadi', __( 'Content Toggle', 'wadi-addons' ) );
    }
    
    public function get_icon()
    {
        return 'wadi-content-toggle';
    }
    
    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }
    
    public function __construct( $data = array(), $args = null )
    {
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
            'content toggle',
            'content switcher',
            'content switch'
        ];
    }
    
    public function get_script_depends()
    {
    }
    
    public function get_style_depends()
    {
    }
    
    /**
     * Get Elementor Helper Instance.
     *
     * @since 1.0.0
     * @access public
     */
    public function getTemplateInstance()
    {
        $this->template_instance = WadiQueries::getInstance();
        return $this->template_instance;
    }
    
    protected function register_controls()
    {
    }
    
    /**
     * Render content toggle switch types list.
     *
     * @since 1.1.0
     * @return array Array of content toggle switch list type
     * @access public
     */
    public function get_content_toggle_switch_type()
    {
        $switch_type = array(
            'round'     => esc_html__( 'Round', 'wadi-addons' ),
            'rectangle' => esc_html__( 'Rectangle', 'wadi-addons' ),
        );
        return $switch_type;
    }
    
    protected function render()
    {
    }
    
    protected function content_template()
    {
    }

}