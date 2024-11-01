<?php

namespace WadiAddons\Admin;

use WadiAddons\Admin\Admin_Helper;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 
 * Check if it is PRO Addons and if PRO is installed.
 *
 * @since 1.0.0
 *  
 */

 $check_pro_addon = wa_fs()->is_premium() && wa_fs()->can_use_premium_code() ? true : false;
 

/**
 *
 * Wadi Elements List
 *
 * Used to determine which Elements are registered in Elementor
 *
 */


$wadi_elements_list = array(
    'wadi-elements' => array(
        'icon' => 'wadi-addons-widgets',
        'title' => esc_html__('All Widget', 'wadi-addon'),
        'elements' => array(
            array(
                'key'      => 'wadi-accordion',
                'name'     => 'wadi-accordion-addon',
                'title'    => esc_html__('Accordion', 'wadi-addons'),
                'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com/wadi-accordion-widget-for-elementor/', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'accordion-wadi',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-testimonial',
                'name'     => 'wadi-testimonial-addon',
                'title'    => esc_html__('Testimonial', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'testimonial-wadi',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-media-carousel',
                'name'     => 'wadi-media-carousel-addon',
                'title'    => esc_html__('Media Carousel', 'wadi-addons'),
                'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com/wadi-media-carousel-for-elementor/', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'media-carousel',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-gallery-grid',
                'name'     => 'wadi-gallery-grid-addon',
                'title'    => esc_html__('Gallery Grid', 'wadi-addons'),
                'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com/wadi-gallery-grid-widget-for-elementor/', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'gallery-grid',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-flip-box',
                'name'     => 'wadi-flip-box-addon',
                'title'    => esc_html__('Flip Box', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'flip-box',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-carousel',
                'name'     => 'wadi-carousel-addon',
                'title'    => esc_html__('Carousel', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'carousel',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-blockquote',
                'name'     => 'wadi-blockquote-addon',
                'title'    => esc_html__('Blockquote', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'blockquote',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-price-list',
                'name'     => 'wadi-price-list-addon',
                'title'    => esc_html__('Price List', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'price-list-1',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-posts',
                'name'     => 'wadi-posts-addon',
                'title'    => esc_html__('Posts', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'posts',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-pricing-table',
                'name'     => 'wadi-pricing-table-addon',
                'title'    => esc_html__('Pricing Table', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'price-table-wadi',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-tabs',
                'name'     => 'wadi-tabs-addon',
                'title'    => esc_html__('Tabs', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'tabs-wadi',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-info-box',
                'name'     => 'wadi-info-box-addon',
                'title'    => esc_html__('Info Box', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'info-box',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-content-toggle',
                'name'     => 'wadi-content-toggle-addon',
                'title'    => esc_html__('Content Toggle', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'content-toggle',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-dual-heading',
                'name'     => 'wadi-dual-heading-addon',
                'title'    => esc_html__('Dual Heading', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'dual-heading',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-charts',
                'name'     => 'wadi-charts-addon',
                'title'    => esc_html__('Charts', 'wadi-addons'),
                'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com/wadi-charts-widget-for-elementor/', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'chart-2',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-table',
                'name'     => 'wadi-table-addon',
                'title'    => esc_html__('Table', 'wadi-addons'),
                'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com/wadi-table-widget-for-elementor/', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'table1',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-modal',
                'name'     => 'wadi-modal-addon',
                'title'    => esc_html__('Modal Popup', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'popup',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-video',
                'name'     => 'wadi-video-addon',
                'title'    => esc_html__('Video', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'widget_icon' => 'video',
                'is_checked' => true,
            ),
            array(
                'key'      => 'wadi-image-accordion',
                'name'     => 'wadi-image-accordion-addon',
                'title'    => esc_html__('Image Accordion', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'image-accordion-2',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-lottie-animation',
                'name'     => 'wadi-lottie-animation-addon',
                'title'    => esc_html__('Lottie Animation', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'lottie',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-image-comparison',
                'name'     => 'wadi-image-comparison-addon',
                'title'    => esc_html__('Image Comparison', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'image-comparison-2',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-fancy-text',
                'name'     => 'wadi-fancy-text-addon',
                'title'    => esc_html__('Fancy Text', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'animted-text',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-testimonial-carousel',
                'name'     => 'wadi-testimonial-carousel-addon',
                'title'    => esc_html__('Testimonial Carousel', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'testimonial-carousel',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-paypal-checkout',
                'name'     => 'wadi-paypal-checkout-addon',
                'title'    => esc_html__('PayPal Checkout', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'paypal-checkout',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-paypal-subscription',
                'name'     => 'wadi-paypal-subscription-addon',
                'title'    => esc_html__('PayPal Subscription', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'paypal-subscription',
                'is_checked' => false,
            ),
            array(
                'key'      => 'wadi-paypal-donation',
                'name'     => 'wadi-paypal-donation-addon',
                'title'    => esc_html__('Paypal Donation', 'wadi-addons'),
                // 'demo'     => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'doc'      => Admin_Helper::get_campaign_link('https://www.wadiweb.com', 'wadi-widgets-page', 'wp-dash', 'dashboard'),
                // 'tutorial' => 'https://www.youtube.com/watch?v=1t8kAbUg4t4&list=RDMMZPdk5GaIDjo',
                'is_pro'   => $check_pro_addon,
                'widget_icon' => 'paypal-donation',
                'is_checked' => true,
            ),
        )
    ),
);

return $wadi_elements_list;