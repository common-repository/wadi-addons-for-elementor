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
use  Elementor\Embed ;
use  Elementor\Repeater ;
use  Elementor\Utils ;
// Wadi Classes
use  WadiAddons\Includes\WadiHelpers ;
use  WadiAddons\Includes\WadiQueries ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Wadi_Testimonial_Carousel extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-testimonial-carousel-addon';
    }
    
    public function get_title()
    {
        return sprintf( '%1$s %2$s', 'Wadi', __( 'Testimonial Carousel', 'wadi-addons' ) );
    }
    
    public function get_icon()
    {
        return 'wadi-testimonial-carousel';
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
            'carousel',
            'slider',
            'testimonial',
            'testimonial carousel',
            'testimonial slider'
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
    
    private function testimonialCite(
        $slide,
        $location,
        $name_attr,
        $org_attr
    )
    {
        if ( empty($slide['wadi_testimonial_name']) && empty($slide['wadi_testimonial_organization']) ) {
            return '';
        }
        $skin = $this->get_settings( 'wadi_testimonial_skin' );
        $layout = ( 'bubble' === $skin ? 'image_inline' : $this->get_settings( 'wadi_testimonial_layout' ) );
        $locations_outside = [ 'image_above', 'image_right', 'image_left' ];
        $locations_inside = [ 'image_inline', 'image_stacked' ];
        $print_outside = 'outside' === $location && in_array( $layout, $locations_outside );
        $print_inside = 'inside' === $location && in_array( $layout, $locations_inside );
        $html = '';
        
        if ( $print_outside || $print_inside ) {
            $html .= '<div class="wadi_testimonial_cite">';
            
            if ( !empty($slide['wadi_testimonial_name']) ) {
                $html .= '<div class="wadi_testimonial_writer">';
                $html .= '<div ' . $this->get_render_attribute_string( $name_attr ) . '>' . $slide['wadi_testimonial_name'] . '</div>';
                $html .= '</div>';
            }
            
            if ( !empty($slide['wadi_testimonial_cite_separator']) && !empty($slide['wadi_testimonial_organization']) && !empty($slide['wadi_testimonial_name']) ) {
                $html .= '<span class="wadi_testimonial_cite_separator">' . $slide['wadi_testimonial_cite_separator'] . '</span>';
            }
            
            if ( !empty($slide['wadi_testimonial_organization']) ) {
                $html .= '<div class="wadi_testimonial_organization">';
                $html .= '<div ' . wp_kses_post( $this->get_render_attribute_string( $org_attr ) ) . '>' . $slide['wadi_testimonial_organization'] . '</div>';
                $html .= '</div>';
            }
            
            $html .= '</div>';
        }
        
        echo  wp_kses_post( $html ) ;
    }
    
    /**
     * Wadi Render Navigation For Wadi Carousel
     * 
     * @since 1.0.6
     * 
     * @access protected
     * 
     * @param array $settings array of settings.
     * 
     */
    protected function render_carousel_navigation( $settings )
    {
        // Horizontal
        $migrated_next = isset( $settings['__fa4_migrated']['wadi_selected_testimonial_carousel_next_icon'] );
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        $has_icon_next = !$is_new || !empty($settings['wadi_carousel_next_icon']['value']);
        $migrated_prev = isset( $settings['__fa4_migrated']['wadi_selected_testimonial_carousel_prev_icon'] );
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        $has_icon_prev = !$is_new || !empty($settings['wadi_selected_testimonial_carousel_prev_icon']['value']);
        // Vertical
        $migrated_next_vertical = isset( $settings['__fa4_migrated']['wadi_selected_testimonial_carousel_next_icon_vertical'] );
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        $has_icon_next_vertical = !$is_new || !empty($settings['wadi_carousel_next_icon_vertical']['value']);
        $migrated_prev_vertical = isset( $settings['__fa4_migrated']['wadi_selected_testimonial_carousel_prev_icon_vertical'] );
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        $has_icon_prev_vertical = !$is_new || !empty($settings['wadi_selected_testimonial_carousel_prev_icon_vertical']['value']);
        ?>        

        <?php 
        if ( $settings['wadi_testimonial_carousel_pagination'] === 'yes' ) {
            ?>
            <div class="swiper-pagination"></div>
        <?php 
        }
        ?>


        <?php 
        
        if ( $settings['wadi_testimonial_carousel_arrow_navigation'] === 'yes' ) {
            ?>
            <div class="wadi-swiper-button wadi-testimonial_carousel_swiper-button-prev">
            <?php 
            
            if ( $is_new || $migrated_prev ) {
                ?>
                    <?php 
                Icons_Manager::render_icon( $settings['wadi_selected_testimonial_carousel_prev_icon'] );
                ?>
                <?php 
            } else {
                ?>
                    <i class="wadi_testimonial_carousel_icon_prev <?php 
                echo  esc_attr( $settings['wadi_testimonial_carousel_prev_icon'] ) ;
                ?>"></i>
                <?php 
            }
            
            ?>
            </div>
            <div class="wadi-swiper-button wadi-testimonial_carousel_swiper-button-next">
            <?php 
            
            if ( $is_new || $migrated_next ) {
                ?>
                <?php 
                Icons_Manager::render_icon( $settings['wadi_selected_testimonial_carousel_next_icon'] );
                ?>
            <?php 
            } else {
                ?>
                <i class="wadi_testimonial_carousel_icon_next <?php 
                echo  esc_attr( $settings['wadi_testimonial_carousel_next_icon'] ) ;
                ?>"></i>
            <?php 
            }
            
            ?>
            </div>
        <?php 
        }
        
        ?>
        <?php 
    }
    
    protected function render()
    {
        // Navigation
    }
    
    protected function content_template()
    {
    }
    
    protected function get_slide_image_url( $slide, array $settings )
    {
        $image_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $slide['wadi_testimonial_carousel_image']['id'], 'wadi_testimonial_carousel_image_size', $settings );
        if ( !$image_url ) {
            $image_url = $slide['wadi_testimonial_carousel_image']['url'];
        }
        return $image_url;
    }
    
    protected function get_slide_image_alt_attribute( $slide )
    {
        if ( !empty($slide['wadi_testimonial_name']) ) {
            return $slide['wadi_testimonial_name'];
        }
        if ( !empty($slide['wadi_testimonial_carousel_image']['alt']) ) {
            return $slide['wadi_testimonial_carousel_image']['alt'];
        }
        return '';
    }

}