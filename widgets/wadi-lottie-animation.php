<?php

namespace WadiAddons\Widgets;

use  Elementor\Modules\DynamicTags\Module as TagsModule ;
use  Elementor\Control_Color ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Control_Media ;
use  Elementor\Group_Control_Border ;
use  Elementor\Group_Control_Box_Shadow ;
use  Elementor\Group_Control_Text_Shadow ;
use  Elementor\Group_Control_Typography ;
use  Elementor\Group_Control_Background ;
use  Elementor\Icons_Manager ;
use  Elementor\Repeater ;
use  Elementor\Utils ;
// Wadi Classes
use  WadiAddons\Includes\WadiHelpers ;
use  WadiAddons\Includes\WadiQueries ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class Wadi_Lottie_Animation extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-lottie-animation-addon';
    }
    
    public function get_title()
    {
        return sprintf( '%1$s %2$s', 'Wadi', __( 'Lottie Animation', 'wadi-addons' ) );
    }
    
    public function get_icon()
    {
        return 'wadi-lottie';
    }
    
    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }
    
    public function __construct( $data = array(), $args = null )
    {
        parent::__construct( $data, $args );
        // wp_register_script('script-add_indicators', '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/plugins/debug.addIndicators.min.js', [ ], '2.0.7', true);
        wp_register_script(
            'script-scroll_magic',
            '//cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.7/ScrollMagic.min.js',
            [],
            '2.0.7',
            true
        );
        wp_register_script(
            'script-handle_lottie_animation',
            WADI_ADDONS_URL . 'assets/min/wadi-lottie-animation.min.js',
            [ 'elementor-frontend', 'jquery', 'script-scroll_magic' ],
            '1.0.0',
            true
        );
        
        if ( !is_rtl() ) {
            wp_register_style( 'style-handle_lottie_animation', WADI_ADDONS_URL . 'assets/min/wadi-lottie-animation.css' );
        } else {
            wp_register_style( 'style-handle_lottie_animation', WADI_ADDONS_URL . 'assets/min/wadi-lottie-animation.rtl.css' );
        }
    
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
            'Lottie',
            'Lottie Files',
            'Lottie Animation',
            'Graphics',
            'animation'
        ];
    }
    
    public function get_script_depends()
    {
        return [ 'elementor-waypoints', 'script-handle_lottie_animation' ];
    }
    
    public function get_style_depends()
    {
        return [ 'style-handle_lottie_animation' ];
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
    
    private function is_media_file_caption( $settings )
    {
        return 'media_file' === $settings['lottie_animation_src_type'] && 'none' !== $settings['wadi_lottie_animation_caption_source'];
    }
    
    private function is_external_url_caption( $settings )
    {
        return 'external_url' === $settings['lottie_animation_src_type'] && '' !== $settings['wadi_lottie_animation_custom_caption'];
    }
    
    private function get_caption( $settings )
    {
        $is_media_file_caption = $this->is_media_file_caption( $settings );
        $is_external_url_caption = $this->is_external_url_caption( $settings );
        
        if ( $is_media_file_caption && 'custom' === $settings['wadi_lottie_animation_caption_source'] || $is_external_url_caption ) {
            return $settings['wadi_lottie_animation_custom_caption'];
        } else {
            
            if ( 'caption' === $settings['wadi_lottie_animation_caption_source'] ) {
                return wp_get_attachment_caption( $settings['lottie_animation_file']['id'] );
            } else {
                if ( 'title' === $settings['wadi_lottie_animation_caption_source'] ) {
                    return get_the_title( $settings['lottie_animation_file']['id'] );
                }
            }
        
        }
        
        return '';
    }
    
    protected function render()
    {
    }
    
    protected function content_template()
    {
    }

}