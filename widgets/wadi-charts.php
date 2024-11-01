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
// Wadi Classes
use  WadiAddons\Includes\WadiHelpers ;
use  WadiAddons\Includes\WadiQueries ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Wadi_Charts extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-charts-addon';
    }
    
    public function get_title()
    {
        return sprintf( '%1$s %2$s', 'Wadi', __( 'Charts', 'wadi-addons' ) );
    }
    
    public function get_icon()
    {
        return 'wadi-chart-2';
    }
    
    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }
    
    public function __construct( $data = array(), $args = null )
    {
        parent::__construct( $data, $args );
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
            'charts',
            'pie chart',
            'doughnut chart',
            'csv chart',
            'google sheets',
            'line chart',
            'bar chart',
            'graphic chart'
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
    
    protected function render()
    {
    }

}