<?php

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
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Wadi_Flip_Box extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-flip-box-addon';
    }
    
    public function get_title()
    {
        return sprintf( '%1$s %2$s', 'Wadi', __( 'Flip Box', 'wadi-addons' ) );
    }
    
    public function get_icon()
    {
        return 'wadi-flip-box';
    }
    
    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }
    
    public function __construct( $data = array(), $args = null )
    {
        parent::__construct( $data, $args );
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
    
    protected function render()
    {
    }
    
    /**
     * Render Flip Box widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function content_template()
    {
    }

}