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
class Wadi_Media_Carousel extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-media-carousel-addon';
    }
    
    public function get_title()
    {
        return sprintf( '%1$s %2$s', 'Wadi', __( 'Media Carousel', 'wadi-addons' ) );
    }
    
    public function get_icon()
    {
        return 'wadi-media-carousel';
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
            'media',
            'media carousel'
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
    
    protected function render_carousel_navigation( $settings )
    {
        // Horizontal
        $migrated_next = isset( $settings['__fa4_migrated']['wadi_selected_media_carousel_next_icon'] );
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        $has_icon_next = !$is_new || !empty($settings['wadi_media_carousel_next_icon']['value']);
        $migrated_prev = isset( $settings['__fa4_migrated']['wadi_selected_media_carousel_prev_icon'] );
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        $has_icon_prev = !$is_new || !empty($settings['wadi_selected_media_carousel_prev_icon']['value']);
        // Vertical
        $migrated_next_vertical = isset( $settings['__fa4_migrated']['wadi_selected_media_carousel_next_icon_vertical'] );
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        $has_icon_next_vertical = !$is_new || !empty($settings['wadi_media_carousel_next_icon_vertical']['value']);
        $migrated_prev_vertical = isset( $settings['__fa4_migrated']['wadi_selected_media_carousel_prev_icon_vertical'] );
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();
        $has_icon_prev_vertical = !$is_new || !empty($settings['wadi_selected_media_carousel_prev_icon_vertical']['value']);
        if ( $settings['wadi_media_carousel_dots_navigation'] === 'yes' ) {
            ?>
            <div class="swiper-pagination"></div>
        <?php 
        }
        ?>


        <?php 
        
        if ( $settings['wadi_media_carousel_arrow_navigation'] === 'yes' ) {
            ?>
            <?php 
            
            if ( $settings['wadi_media_carousel_direction'] === 'horizontal' ) {
                ?>
            <div class="wadi-swiper-button wadi-media_carousel_swiper-button-prev">
            <?php 
                
                if ( $is_new || $migrated_prev ) {
                    ?>
                    <?php 
                    Icons_Manager::render_icon( $settings['wadi_selected_media_carousel_prev_icon'] );
                    ?>
                <?php 
                } else {
                    ?>
                    <i class="wadi_media_carousel_icon_prev <?php 
                    echo  esc_attr( $settings['wadi_media_carousel_prev_icon'] ) ;
                    ?>"></i>
                <?php 
                }
                
                ?>
            </div>
            <div class="wadi-swiper-button wadi-media_carousel_swiper-button-next">
            <?php 
                
                if ( $is_new || $migrated_next ) {
                    ?>
                <?php 
                    Icons_Manager::render_icon( $settings['wadi_selected_media_carousel_next_icon'] );
                    ?>
            <?php 
                } else {
                    ?>
                <i class="wadi_media_carousel_icon_next <?php 
                    echo  esc_attr( $settings['wadi_media_carousel_next_icon'] ) ;
                    ?>"></i>
            <?php 
                }
                
                ?>
            </div>
            <?php 
            } else {
                ?>
                <div class="wadi-swiper-button-vertical wadi-media_carousel_swiper-button-prev wadi_carousel_prev_vertical">
            <?php 
                
                if ( $is_new || $migrated_prev_vertical ) {
                    ?>
                    <?php 
                    Icons_Manager::render_icon( $settings['wadi_selected_media_carousel_prev_icon_vertical'] );
                    ?>
                <?php 
                } else {
                    ?>
                    <i class="wadi_media_carousel_icon_prev_vertical <?php 
                    echo  esc_attr( $settings['wadi_media_carousel_prev_icon_vertical'] ) ;
                    ?>"></i>
                <?php 
                }
                
                ?>
            </div>
            <div class="wadi-swiper-button-vertical wadi-media_carousel_swiper-button-next wadi_carousel_next_vertical">
            <?php 
                
                if ( $is_new || $migrated_next_vertical ) {
                    ?>
                <?php 
                    Icons_Manager::render_icon( $settings['wadi_selected_media_carousel_next_icon_vertical'] );
                    ?>
            <?php 
                } else {
                    ?>
                <i class="wadi_media_carousel_icon_next_vertical <?php 
                    echo  esc_attr( $settings['wadi_media_carousel_next_icon_vertical'] ) ;
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
        
        ?>

        <?php 
        if ( $settings['wadi_media_carousel_scrollbar'] === 'yes' ) {
            ?>
            <div class="swiper-scrollbar"></div>
        <?php 
        }
        ?>

        <?php 
    }
    
    protected function render()
    {
    }
    
    protected function content_template()
    {
    }
    
    // Render URL for Image based on Image Size Choosen
    protected function get_slide_image_url( $slide, array $settings )
    {
        $image_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $slide['wadi_media_carousel_image']['id'], 'wadi_media_carousel_image', $settings );
        if ( !$image_url ) {
            $image_url = $slide['wadi_media_carousel_image']['url'];
        }
        return $image_url;
    }

}