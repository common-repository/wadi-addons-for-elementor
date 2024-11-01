<?php

namespace WadiAddons\Widgets;

// Elementor Classes.
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Base;
use MaxMind\Db\Reader\Util;
use WadiAddons;
// Wadi Classes
use WadiAddons\Includes\WadiHelpers;
use WadiAddons\Includes\WadiQueries;

if (! defined('ABSPATH')) {
    exit;
}

class Wadi_Price_List extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-price-list-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Price List', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-price-list-1';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        if(!is_rtl()) {
            wp_register_style('style-wadi-price-list-handle', WADI_ADDONS_URL . 'assets/min/wadi-price-list.css', [], WADI_ADDONS_VERSION, 'all');
        } else {
            wp_register_style('style-wadi-price-list-handle', WADI_ADDONS_URL . 'assets/min/wadi-price-list.rtl.css', [], WADI_ADDONS_VERSION, 'all');
        }
    }

    public function get_style_depends()
    {
        return [ 'style-wadi-price-list-handle' ];
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
        $this->start_controls_section(
            'wadi_addons_pricing_list_content_section',
            [
                'label' => esc_attr__('Pricing List Item', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'title',
            [
                'label' => esc_attr__('Title', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => array(
                    'active' => true,
                ),
            ]
        );
        $repeater->add_control(
            'item_description',
            [
                'label' => esc_attr__('Description', 'wadi-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic'     => array(
                    'active' => true,
                ),
            ]
        );
        $repeater->add_control(
            'item_price',
            [
                'label' => esc_attr__('Price', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic'     => array(
                    'active' => true,
                ),
            ]
        );

        $repeater->add_control(
            'has_discount_offer',
            [
                'label' => esc_attr__('Offer Discounted Price?', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default'      => 'no',
                'label_on'     => __('Yes', 'wadi-addons'),
                'label_off'    => __('No', 'wadi-addons'),
                'return_value' => 'yes',
            ]
        );

        $repeater->add_control(
            'original_price',
            [
                'label' => esc_attr__('Original Price', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => "$20",
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'has_discount_offer' => 'yes',
                ]
            ]
        );

        $repeater->add_control(
            'list_item_link',
            [
                'label' => esc_attr__('Link', 'wadi-addons'),
                'type' => Controls_Manager::URL,
                'default' => array( 'url' => '#' ),
                        'dynamic' => array(
                            'active' => true,
                        ),
            ]
        );

        $repeater->add_control(
            'list_item_image',
            [
                'label' => esc_attr__('Image', 'wadi-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'pricing_list_items',
            array(
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => array(
                    array(
                        'title'            => __('Item #1', 'wadi_addons'),
                        'item_description' => __('I am item content. Click edit button to change this text.', 'wadi_addons'),
                        'item_price'            => '$20',
                        'list_item_link'   => array( 'url' => '#' ),
                    ),
                    array(
                        'title'            => __('Item #2', 'wadi_addons'),
                        'item_description' => __('I am item content. Click edit button to change this text.', 'wadi_addons'),
                        'item_price'            => '$9',
                        'list_item_link'   => array( 'url' => '#' ),
                    ),
                    array(
                        'title'            => __('Item #3', 'wadi_addons'),
                        'item_description' => __('I am item content. Click edit button to change this text.', 'wadi_addons'),
                        'item_price'            => '$32',
                        'list_item_link'   => array( 'url' => '#' ),
                    ),
                ),
                'title_field' => '{{{ title }}}',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_pricing_list_layouts',
            [
                'label' => esc_html__('Layout', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        
		$start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';
        
        $priceListImagePosition = [
            $start   => [
                'title' => __('Left', 'wadi-addons'),
                'icon'  => 'eicon-text-align-left',
            ],
            'top' => [
                'title' => __('Top', 'wadi-addons'),
                'icon'  => 'eicon-arrow-up',
            ],
            $end  => [
                'title' => __('Right', 'wadi-addons'),
                'icon'  => 'eicon-text-align-right',
            ],
        ];

        $rtl_priceListImagePosition = array_reverse($priceListImagePosition);

        $the_priceListImagePosition = is_rtl() ? $rtl_priceListImagePosition : $priceListImagePosition;
        


        $this->add_control(
            'pricing_list_image_position',
            [
                'label' => esc_html__('Image Position', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => $the_priceListImagePosition,
                'selectors_dictionary' => array(
                    'left'   => 'row',
                    'top' => 'column',
                    'right'  => 'row-reverse',
                ),
                'toggle'               => true,
                'selectors'            => array(
                    '{{WRAPPER}} .wadi_pricing_list_item' => 'flex-direction: {{VALUE}}',
                ),
                'prefix_class' => 'wadi_price_list_image__',
                'label_block' => false,
            ]
        );

        $start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';
        
        $priceListPricePosition = [
            $start  => [
                'title' => __('Left', 'wadi-addons'),
                'icon'  => 'eicon-h-align-left',
            ],
            $end => [
                'title' => __('Right', 'wadi-addons'),
                'icon'  => 'eicon-h-align-right',
            ],
        ];

        $rtl_priceListPricePosition = array_reverse($priceListPricePosition);

        $the_priceListPricePosition = is_rtl() ? $rtl_priceListPricePosition : $priceListPricePosition;
        

        $this->add_control(
            'pricing_list_price_position',
            [
                'label' => esc_html__('Price Position', 'wadi-addons'),
                'type'      => Controls_Manager::CHOOSE,
                'toggle'    => true,
                'options'   => $the_priceListPricePosition,
                'default'   => 'right',
                'selectors_dictionary' => [
                    'left' => 'row-reverse',
                    'right' => 'row ',
                ],
                'selectors' => [
                    '{{WARPPER}} .wadi_pricing_list_header' => 'flex-direction:{{VALUE}};'
                ]
            ]
        );

        $this->add_control(
            'pricing_list_connector_type',
            [
                'label' => esc_html__('Price Connector Type', 'wadi-addons'),
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'solid' => esc_html__('Solid', 'wadi-addons'),
                    'dotted' => esc_html__('Dotted', 'wadi-addons'),
                    'dashed' => esc_html__('Dashed', 'wadi-addons'),
                    'double' => esc_html__('Double', 'wadi-addons'),
                    'none' => esc_html__('None', 'wadi-addons'),
                ],
                'default'     => 'dotted',
				'selectors'   => array(
					'{{WRAPPER}} .wadi_pricing_list_text .wadi_pricing_list_header .wadi_pricing_list_separator' => 'border-bottom-style: {{VALUE}};',
				),
				'label_block' => false,
            ]
        );

        $priceListDescriptionAlignment = [
            'left' => [
                'title' => esc_html__( 'Left', 'wadi-addons' ),
                'icon' => 'eicon-text-align-left',
            ],
            'center' => [
                'title' => esc_html__( 'Center', 'wadi-addons' ),
                'icon' => 'eicon-text-align-center',
            ],
            'right' => [
                'title' => esc_html__( 'Right', 'wadi-addons' ),
                'icon' => 'eicon-text-align-right',
            ],
        ];

        $rtl_priceListDescriptionAlignment = array_reverse($priceListDescriptionAlignment);

        $the_priceListDescriptionAlignment = is_rtl() ? $rtl_priceListDescriptionAlignment : $priceListDescriptionAlignment;
        


        $this->add_responsive_control(
            'pricing_list_description_alignment',
            [
                'label' => esc_html__('Price Description Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'options' => $the_priceListDescriptionAlignment,
                'selectors_dictionary' => array(
                    'left'   => 'left',
                    'center' => 'center',
                    'right'  => 'right',
                ),
                'toggle'               => true,
                'selectors'            => array(
                    '{{WRAPPER}} .wadi_pricing_list_description_text' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
                ),
                'label_block' => false,
            ]
        );

        $this->add_responsive_control(
            'pricing_list_item_vertical_alignment',
            [
                'label' => esc_html__('Vertical Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top' => [
                        'title' => __('Top', 'wadi-addons'),
                        'icon' => 'eicon-arrow-up'
                    ],
                    'center'  => array(
                        'title' => __('Center', 'wadi-addons'),
                        'icon'  => 'eicon-text-align-justify',
                    ),
                    'bottom' => array(
                        'title' => __('Bottom', 'wadi-addons'),
                        'icon'  => 'eicon-arrow-down',
                    ),
                ],
                'default' => 'top',
                'toggle' => false,
                'selectors_dictionary' => array(
                    'top'   => 'flex-start',
                    'center' => 'center',
                    'bottom'  => 'flex-end',
                ),
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'pricing_list_image_position!' => 'top',
                ],
                'label_block' => false
            ]
        );

        $start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';

        $priceListImageHorizontalAlignment = [
            $start => array(
                'title' => __('Left', 'wadi-addons'),
                'icon'  => 'eicon-h-align-left',
            ),
            'center'  => array(
                'title' => __('Center', 'wadi-addons'),
                'icon'  => 'eicon-h-align-center',
            ),
            $end => [
                'title' => __('Right', 'wadi-addons'),
                'icon' => 'eicon-h-align-right'
            ],
        ];

        $rtl_priceListImageHorizontalAlignment = array_reverse($priceListImageHorizontalAlignment);

        $the_priceListImageHorizontalAlignment = is_rtl() ? $rtl_priceListImageHorizontalAlignment : $priceListImageHorizontalAlignment;
        

        $this->add_responsive_control(
            'pricing_list_item_horizontal_alignment',
            [
                'label' => esc_html__('Image Horizontal Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => $the_priceListImageHorizontalAlignment,
                'toggle' => true,
                'selectors_dictionary' => array(
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ),
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'pricing_list_image_position' => 'top',
                ],
                'label_block' => false
            ]
        );

        $this->add_control(
            'wadi_pricing_list_set_height',
            [
                'label' => esc_html__('Set Items Height', 'wadi-addons'),
                'description' => esc_html__('Set minimum height for each item', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on'     => __( 'Yes', 'wadi-addons' ),
				'label_off'    => __( 'No', 'wadi-addons' ),
                'return_value' => 'yes',
            ]
        );

        $this->add_responsive_control(
            'wadi_pricing_list_item_height',
            [
                'label' => esc_html__('Item Height', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px','rem', 'em', '%'],
                'condition' => [
                    'wadi_pricing_list_set_height' => 'yes',
                ],
                'frontend_available' => true,
                'selectors'          => array(
                    '{{WRAPPER}} .wadi_pricing_list_item' => 'min-height: {{SIZE}}{{UNIT}};',
                ),
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'wadi_pricing_list_styling_section',
            [
                'label' => esc_html__('List', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wadi_pricing_list_title_styling_heading',
            [
                'label' => esc_html__('Title', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'    => __('Typography', 'wadi-addons'),
                'name'     => 'wadi_pricing_list_title_typography',
                'global'   => array(
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ),
                'selector' => '{{WRAPPER}} .wadi_pricing_list_title',
            )
        );

        $this->start_controls_tabs(
            'wadi_pricing_list_style_tabs'
        );

        $this->start_controls_tab(
            'wadi_pricing_list_title_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_pricing_list_title_styling_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_title' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'wadi_pricing_list_title_styling_background',
            [
                'label' => esc_html__('Background Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_title' => 'background-color: {{VALUE}};'
                ],
            ]
        );
        
        $this->end_controls_tab();


        $this->start_controls_tab(
            'wadi_pricing_list_title_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
            ]
        );

          
        $this->add_control(
            'wadi_pricing_list_title_styling_hover_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_SECONDARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item:hover .wadi_pricing_list_title' => 'color: {{VALUE}};'
                ],
            ]
        );
        

        $this->add_control(
            'wadi_pricing_list_title_styling_background_hover',
            [
                'label' => esc_html__('Background Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item:hover .wadi_pricing_list_title' => 'background-color: {{VALUE}};'
                ],
            ]
        );
        
        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_responsive_control(
            'wadi_pricing_list_title_border_radius',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_pricing_list_title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
                 'separator' => 'before',
             ]
        );

        $this->add_control(
			'wadi_pricing_list_title_margin',
			[
				'label' => esc_html__( 'Title Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'wadi_pricing_list_title_padding',
			[
				'label' => esc_html__( 'Title Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->add_control(
            'wadi_pricing_list_description_styling_heading',
            [
                'label' => esc_html__('Description', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wadi_pricing_list_description_styling_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_description_text' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'wadi_pricing_list_description_styling_hover_color',
            [
                'label' => esc_html__('Hover Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item:hover .wadi_pricing_list_description_text' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'    => __('Typography', 'wadi-addons'),
                'name'     => 'wadi_pricing_list_description_typography',
                'global'   => array(
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ),
                'selector' => '{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_description_text',
            )
        );

        $this->add_control(
			'wadi_pricing_list_description_margin',
			[
				'label' => esc_html__( 'Description Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_description_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'wadi_pricing_list_description_padding',
			[
				'label' => esc_html__( 'Description Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_description_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'wadi_pricing_list_price_styling_heading',
            [
                'label' => esc_html__('Price', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        
        $this->add_control(
            'wadi_pricing_list_price_styling_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_price_item_wrapper' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'wadi_pricing_list_price_styling_hover_color',
            [
                'label' => esc_html__('Hover Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item:hover .wadi_pricing_list_price_item_wrapper' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'label'    => __('Typography', 'wadi-addons'),
                'name'     => 'wadi_pricing_list_price_typography',
                'global'   => array(
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ),
                'selector' => '{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_price_item_wrapper',
            )
        );

        $this->add_control(
			'wadi_pricing_list_price_margin',
			[
				'label' => esc_html__( 'Price Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_price_item_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'wadi_pricing_list_price_padding',
			[
				'label' => esc_html__( 'Price Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_description_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->add_control(
            'wadi_pricing_list_connecter_styling_heading',
            [
                'label' => esc_html__('Connecter', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
			'wadi_pricing_list_connecter_styling_width',
			[
				'label' => esc_html__( 'Connecter Size', 'plugin-name' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
						'step' => 5,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_header .wadi_pricing_list_separator' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'pricing_list_connector_type!' => ['none'],
                ]
			]
		);

        
        $this->add_control(
            'wadi_pricing_list_connecter_styling_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item .wadi_pricing_list_header .wadi_pricing_list_separator' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_control(
            'wadi_pricing_list_connecter_styling_hover_color',
            [
                'label' => esc_html__('Hover Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_pricing_list_item:hover .wadi_pricing_list_header .wadi_pricing_list_separator' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_pricing_list_general_style_section',
            [
                'label' => esc_html__('General Style', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
			'wadi_pricing_list_items_spacing',
			[
				'label' => esc_html__( 'Pricing List Item Spacing', 'wadi-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','rem','em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
                'frontend_available' => true,
			]
		);

        $this->add_responsive_control(
			'wadi_pricing_image_content_spacing',
			[
				'label' => esc_html__( 'Spacing Image & Content', 'wadi-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px','rem','em', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}}.wadi_price_list_image__right .wadi_pricing_list_item .wadi_pricing_list_image' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wadi_price_list_image__left .wadi_pricing_list_item .wadi_pricing_list_image' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wadi_price_list_image__top .wadi_pricing_list_item .wadi_pricing_list_image' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
                'frontend_available' => true,
			]
		);

        $this->add_responsive_control(
            'wadi_pricing_list_padding',
            array(
                'label'              => __( 'Padding', 'wadi-addons' ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', '%', 'em' ),
                'selectors'          => array(
                    '{{WRAPPER}} .wadi_pricing_list_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'frontend_available' => true,
            )
        );

        $this->start_controls_tabs( 'wadi_pricing_list_item_general' );

		$this->start_controls_tab(
			'wadi_list_normal',
			array(
				'label' => __( 'Normal', 'wadi-addons' ),
			)
		);

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wadi_pricing_list_item_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_pricing_list_item',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_pricing_list_item_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_pricing_list_item',
			]
		);

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_pricing_list_item_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_pricing_list_item',
			]
		);


        $this->end_controls_tab();

		$this->start_controls_tab(
			'wadi_list_hover',
			array(
				'label' => __( 'Hover', 'wadi-addons' ),
			)
		);

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wadi_pricing_list_item_hover_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_pricing_list_item:hover',
			]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_pricing_list_item_hover_border',
				'label' => esc_html__( 'Border Hover', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_pricing_list_item:hover',
			]
		);

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_pricing_list_item_hover_box_shadow',
				'label' => esc_html__( 'Box Shadow Hover', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_pricing_list_item:hover',
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();


        $this->add_responsive_control(
            'wadi_pricing_list_item_border_radius',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_pricing_list_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_pricing_list_image_style_section',
            [
                'label' => esc_html__('Image Style', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Image_Size::get_type(),
            array(
                'name'    => 'wadi_pricing_list_image_size',
                'default' => 'thumbnail',
            )
        );

        $this->add_responsive_control(
            'wadi_pricing_list_image_width',
            array(
                'label'              => __( 'Width', 'uael' ),
                'type'               => Controls_Manager::SLIDER,
                'range'              => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 500,
                    ),
                    'rem'  => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                    'em'  => array(
                        'min' => 0,
                        'max' => 30,
                    ),
                    '%'  => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'size_units'         => array( 'px','rem','em' ,'%' ),
                'selectors'          => array(
                    '{{WRAPPER}} .wadi_pricing_list_image' => 'width: {{SIZE}}{{UNIT}}; min-width:{{SIZE}}{{UNIT}}',
                ),
                'default'            => array(
                    'size' => 150,
                ),
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'wadi_pricing_list_image_border_radius',
            array(
                'label'      => __( 'Border Radius', 'wadi-addons' ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px','rem','em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .wadi_pricing_list_image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
			'wadi_pricing_list_image_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_pricing_list_image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->end_controls_section();

    }

    private function render_image( $item, $instance ) {
		$image_id = $item['list_item_image']['id'];
		$image_size = isset($instance['image_size_size']) ? $instance['image_size_size'] : '';
		$class      = '';

		if ( 'custom' === $image_size ) {
			$image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'wadi_pricing_list_image_size', $instance );
		} else {
			$image_src = wp_get_attachment_image_src( $image_id, $image_size );
			$image_src = ( false !== $image_src ) ? $image_src[0] : '';
		}

        if ( '' === $image_id ) {
            if ( isset( $item['list_item_image']['url'] ) ) {
                $image_src = $item['list_item_image']['url'];
                $class     = 'wadi_addons_default_img';
            }
        }

		return sprintf( '<img src="%s" alt="%s" />', $image_src, $item['title'] );
        // return printf( wp_kses_post( '<img class="%s" src="%s" alt="%s" />' ), esc_attr( $class ), esc_url( $image_src ), wp_kses_post( $item['title'] ) );

	}

    protected function render()
    {
        ?>
        <div class="wadi_pricing_list_container">
            <?php

            $settings  = $this->get_settings_for_display();
            $pricing_list_items = $settings['pricing_list_items'];
            
            
            foreach($pricing_list_items as $index => $item) : 
                

                if ( ! empty( $item['title'] ) || ! empty( $item['item_price'] ) || ! empty( $item['item_description'] ) ) :
                
                    if ( 'yes' === $item['has_discount_offer'] ) {
                        $price_item_class = 'list_item_has_discount_offer';
                        $original_price = $item['original_price'];
                    } else {
                        $price_item_class = '';
                        $original_price = $item['item_price'];
                    }
                    
                    
                    $repeater_price_key = $this->get_repeater_setting_key( 'item_price', 'pricing_list_items', $index );
                    $this->add_render_attribute($repeater_price_key, [
                        'class' => "wadi_pricing_list_price $price_item_class",
                    ]);

                    $repeater_discount_price_key = $this->get_repeater_setting_key( 'original_price', 'pricing_list_items', $index );
                    $this->add_render_attribute($repeater_discount_price_key, [
                        'class' => 'wadi_pricing_list_price_discounted'
                    ]);

                    $repeater_title_key = $this->get_repeater_setting_key( 'title', 'pricing_list_items', $index );
                    
                    $this->add_render_attribute($repeater_title_key, [
                        'class' => "wadi_pricing_list_title",
                    ]);
                    $this->add_inline_editing_attributes( $repeater_title_key );

                    $repeater_description_key = $this->get_repeater_setting_key( 'item_description', 'pricing_list_items', $index );
                    $this->add_render_attribute($repeater_description_key, [
                        'class' => 'wadi_pricing_list_description_text'
                    ]);

                    $this->add_inline_editing_attributes( $repeater_description_key );
                    

            
            endif;
            
            ?>
            
            <div class="wadi_pricing_list_item">
                <?php if (! empty($item['list_item_image']['url'])) : ?>
                <div class="wadi_pricing_list_image">
                    <?php echo wp_kses_post($this->render_image($item, $settings)); ?>
                </div>
                <?php endif; ?>
                <div class="wadi_pricing_list_text">
                    <div class="wadi_pricing_list_header">
                    <a href="<?php echo esc_attr($item['list_item_link']['url']); ?>" <?php $this->print_render_attribute_string( $repeater_title_key ); ?>><?php echo wp_kses_post($item['title']); ?></a>
                    <span class="wadi_pricing_list_separator"></span>
                    <span class="wadi_pricing_list_price_item_wrapper">
                        <span <?php echo wp_kses_post($this->get_render_attribute_string($repeater_price_key)); ?>><?php echo wp_kses_post($original_price); ?></span>
                        <?php 
                        
                            if('yes' === $item['has_discount_offer']) :
                                ?>
                                    <span <?php echo wp_kses_post( $this->get_render_attribute_string( $repeater_discount_price_key ) ); ?>><?php echo wp_kses_post( $item['item_price'] ); ?></span>
                                <?php

                            endif;

                        ?>
                    </span>
                    </div>
                    <p <?php $this->print_render_attribute_string($repeater_description_key); ?>>
                        <?php echo wp_kses_post($item['item_description']); ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    /**
	 * Render Price List widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 * @access protected
	 */

    protected function content_template()
    {
        ?>
  
        <div class="wadi_pricing_list_container">

            <#
                for(var i in settings.pricing_list_items) {
                    var item  = settings.pricing_list_items[i];

                    var image = {
                                id: item.list_item_image.id,
                                url: item.list_item_image.url,
                                size: settings.image_size_size,
                                dimension: settings.image_size_custom_dimension,
                                model: view.getEditModel()
                            };


                    var itemURL = settings.pricing_list_items[i].list_item_link.url;

                    if( 'yes' === item.has_discount_offer) {
                        var priceItemClass = 'list_item_has_discount_offer';
                        var originalPrice = item.original_price;
                    } else {
                        var priceItemClass = '';
                        var originalPrice = item.item_price;
                    }

                    
						var listItemTitle = view.getRepeaterSettingKey( 'title', 'pricing_list_items', i ),
						listItemDescription = view.getRepeaterSettingKey( 'item_description', 'pricing_list_items', i ),
						listItemPrice = view.getRepeaterSettingKey( 'item_price', 'pricing_list_items', i ),
						listItemOriginalPrice = view.getRepeaterSettingKey( 'original_price', 'pricing_list_items', i );

					view.addRenderAttribute( listItemTitle, {
						'class': [ 'wadi_pricing_list_title' ],
					} );

					view.addRenderAttribute( listItemDescription, {
						'class': [ 'wadi_pricing_list_description_text' ],
					} );


					view.addRenderAttribute( listItemPrice, {
						'class': [ 'wadi_pricing_list_price', priceItemClass ],
					} );

					view.addRenderAttribute( listItemOriginalPrice, {
						'class': [ 'wadi_pricing_list_price_discounted' ],
					} );


                    #>

                    <div class="wadi_pricing_list_item">
                <# 
                        var imageURL = elementor.imagesManager.getImageUrl( image );

                        if( ! imageURL) {
                            return;
                        }

                        if(imageURL) { #>
                            <div class="wadi_pricing_list_image">
                                <img src="{{imageURL}}" alt="{{item.title}}">
                            </div>
                        <# } #>
                    
                        <div class="wadi_pricing_list_text">
                            <div class="wadi_pricing_list_header">
                                <a href="{{item.list_item_link.url}}" {{{ view.getRenderAttributeString( listItemTitle ) }}}>{{{item.title}}}</a>
                                <span class="wadi_pricing_list_separator"></span>
                                <span class="wadi_pricing_list_price_item_wrapper">
                                    <span {{{ view.getRenderAttributeString( listItemPrice ) }}}>{{{item.original_price}}}</span>
                                    <# if( 'yes' === item.has_discount_offer ) { #>
                                        <span {{{ view.getRenderAttributeString( listItemOriginalPrice ) }}}>{{{ item.item_price}}}</span>
                                    <# } #>
                                </span>
                            </div>
                            <p {{{ view.getRenderAttributeString( listItemDescription ) }}}>
                                {{{item.item_description}}}
                            </p>
                        </div>
                    </div>
                    <#
                }
            #>
        </div>


        <?php
        
    }

  
}
