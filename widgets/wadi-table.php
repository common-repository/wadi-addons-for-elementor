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
use  Elementor\Group_Control_Image_Size ;
// Wadi Classes
use  WadiAddons\Includes\WadiHelpers ;
use  WadiAddons\Includes\WadiQueries ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Wadi_Table extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-table-addon';
    }
    
    public function get_title()
    {
        return sprintf( '%1$s %2$s', 'Wadi', __( 'Table', 'wadi-addons' ) );
    }
    
    public function get_icon()
    {
        return 'wadi-table1';
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
            'table',
            'CSV Table',
            'spreadsheet',
            'csv spreadsheet',
            'table spreadsheet',
            'elementor table'
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
     * Is Element a Row
     *
     * Check if the first cell type is set to row.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function is_elem_row()
    {
        $settings = $this->get_settings_for_display();
        if ( isset( $settings['wadi_table_body_repeater'] ) ) {
            if ( 'row' === $settings['wadi_table_body_repeater'][0]['wadi_table_body_element_type'] ) {
                return false;
            }
        }
        return true;
    }
    
    protected function render_cell_icon_body( $value, $repeater_icon )
    {
        $body_icon_migrated = isset( $value['__fa4_migrated']['body_selected_icon_a'] );
        $body_icon_is_new = empty($value['body_icon_a']);
        ?>
			<?php 
        
        if ( isset( $value['body_icon_a'] ) || isset( $value['body_selected_icon_a'] ) ) {
            ?>
				<span <?php 
            echo  wp_kses_post( $this->get_render_attribute_string( $repeater_icon ) ) ;
            ?>>
					<?php 
            
            if ( $body_icon_migrated || $body_icon_is_new ) {
                \Elementor\Icons_Manager::render_icon( $value['body_selected_icon_a'], array(
                    'aria-hidden' => 'true',
                ) );
            } else {
                ?>
						<i class="<?php 
                echo  esc_attr( $value['body_icon_a'] ) ;
                ?>"></i>
					<?php 
            }
            
            ?>
				</span>
			<?php 
        }
    
    }
    
    protected function render_cell_icon_header( $value, $repeater_header_icon )
    {
        $header_icon_migrated = isset( $value['__fa4_migrated']['selected_icon_a'] );
        $header_icon_is_new = empty($value['icon_a']);
        ?>
        <?php 
        
        if ( isset( $value['icon_a'] ) || isset( $value['selected_icon_a'] ) ) {
            ?>
            <span <?php 
            echo  wp_kses_post( $this->get_render_attribute_string( $repeater_header_icon ) ) ;
            ?>>
                <?php 
            
            if ( $header_icon_migrated || $header_icon_is_new ) {
                \Elementor\Icons_Manager::render_icon( $value['selected_icon_a'], array(
                    'aria-hidden' => 'true',
                ) );
            } else {
                ?>
                    <i class="<?php 
                echo  esc_attr( $value['icon_a'] ) ;
                ?>"></i>
                <?php 
            }
            
            ?>
            </span>
        <?php 
        }
    
    }
    
    protected function render_cell_image_body( $value )
    {
        ?>

        <span  <?php 
        echo  wp_kses_post( $this->get_render_attribute_string( 'wadi_cell_image_container_' . esc_attr( $value['_id'] ) ) ) ;
        ?>>
            <img <?php 
        echo  wp_kses_post( $this->get_render_attribute_string( 'wadi_cell_image_' . esc_attr( $value['_id'] ) ) ) ;
        ?>>
        </span>
        <?php 
    }
    
    protected function render_cell_image_header( $value )
    {
        ?>

        <span  <?php 
        echo  wp_kses_post( $this->get_render_attribute_string( 'wadi_header_cell_image_container_' . esc_attr( $value['_id'] ) ) ) ;
        ?>>
            <img <?php 
        echo  wp_kses_post( $this->get_render_attribute_string( 'wadi_header_cell_image_' . esc_attr( $value['_id'] ) ) ) ;
        ?>>
        </span>
        <?php 
    }
    
    protected function render()
    {
    }

}