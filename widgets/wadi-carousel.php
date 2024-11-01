<?php

namespace WadiAddons\Widgets;

use Elementor\Control_Color;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

// Wadi Classes
use WadiAddons\Includes\WadiHelpers;
use WadiAddons\Includes\WadiQueries;

if (! defined('ABSPATH')) {
    exit;
}

class Wadi_Carousel extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-carousel-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Carousel', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-carousel';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_register_script('script-handle_carousel', WADI_ADDONS_URL . 'assets/min/wadi-carousel.min.js', [ 'elementor-frontend', 'jquery' ], '1.0.0', true);
        wp_register_style('style-handle_carousel', WADI_ADDONS_URL . 'assets/min/wadi-carousel.css');
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
        return [ 'wadi-addons','carousel', 'slider' ];
    }


    public function get_script_depends()
    {
        return [ 'script-handle_carousel' ];
    }

    public function get_style_depends()
    {
        return [ 'style-handle_carousel' ];
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
            'wadi_carousel_section',
            [
                'label' => esc_html__('Carousel Content', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_carousel_content_type',
            [
                'label' => esc_html__('Content Type', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'select' => esc_html__('Select Field', 'wadi-addons'),
                    'repeater' => esc_html__('Repeater', 'wadi-addons'),
                ],
                'default' => 'select',
            ]
        );

        $this->add_control(
            'wadi_carousel_select_content',
            [
                'label' => esc_html__('Select Templates', 'wadi-addons'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->getTemplateInstance()->elementor_templates_list(),
                'multiple' => true,
                'label_block' => true,
                'condition' => [
                    'wadi_carousel_content_type' => 'select'
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'wadi_carousel_repeater_item',
            [
                'label' => esc_html__('Content Item', 'wadi-addons'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->getTemplateInstance()->elementor_templates_list_title(),
            ]
        );

        $repeater->add_control(
			'wadi_custom_navigation',
			[
				'label'       => __( 'Custom Navigation Element Selector', 'wadi-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => __( 'Use this to add an element selector to be used to navigate to this slide. For example #item-1', 'wadi-addons' ),
            ]
		);

        $this->add_control(
            'wadi_carousel_repeater_content',
            [
                'label' => esc_html__('Select Template', 'wadi-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'condition' => [
                    'wadi_carousel_content_type' => 'repeater'
                ],
                'title_field' => 'Template: {{{ wadi_carousel_repeater_item }}}',
                'frontend_available' => true,
            ]
        );

        
        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_carousel_basic_settings',
            [
                'label' => esc_html__( 'Carousel Settings', 'wadi-addons' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
			'wadi_carousel_extra_class',
			array(
				'label'       => esc_html__( 'Extra Class', 'wadi-addons' ),
				'type'        => Controls_Manager::TEXT,
				'description' => esc_html__( 'Add extra class name that will be applied to the carousel, and you can use this class for your customizations.', 'wadi-addons' ),
			)
		);

        $this->add_control(
			'wadi_carousel_initial_slide',
			array(
				'label'       => esc_html__( 'Initial Slide', 'wadi-addons' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => esc_html__( 'Set the starting slide index, by default it is first slide with index 0. If changed to 1 the second slide will display initially (zero indexed).', 'wadi-addons' ),
                'min' => 0,
                'step' => 1,
                'default' => 0,
                'frontend_available' => true,
			)
		);
        
        $this->add_control(
            'wadi_carousel_speed_transition',
            [
                'label' => esc_html__('Transition Speed', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Duration of transition between slides (in ms)', 'wadi-addons'),
                'default' => 300,
                'min' => 100,
                'max' => 10000,
                'step' => 100,
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_direction',
            [
                'label' => esc_html__( 'Carousel Direction', 'wadi-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'horizontal' => 'Horizontal',
                    'vertical' => 'Vertical',
                ],
                'default' => 'horizontal',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_pagination_heading',
            [
                'label' => esc_html__('Pagination', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        /**
         * Navigation Outside
         */

        $this->add_control(
            'wadi_carousel_pagination_outside_switcher',
            [
                'label' => esc_html__('Pagination Outside', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'return_value' => 'yes',
                'default' => 'no',
                'frontend_available' => true,
            ]
        );


        $this->add_control(
            'wadi_carousel_dots_navigation',
            [
                'label' => esc_html__( 'Dots Navigation', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'wadi_carousel_pagination_type',
            [
                'label' => esc_html__('Pagination Type', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'bullets' => esc_attr__('Bullets', 'wadi-addons'),
                    'fraction' => esc_attr__('Fraction', 'wadi-addons'),
                    'progressbar' => esc_attr__('Progress Bar', 'wadi-addons'),
                ],
                'default' => 'bullets',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_carousel_dots_clickable',
            [
                'label' => esc_html__( 'Navigation Dots Clickable', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_pagination_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_dots_pagination_hide_on_click',
            [
                'label' => esc_html__( 'Hide Navigation Dots on Click', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_pagination_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_progress_bar_opposite',
            [
                'label' => esc_html__( 'Progrress Bar Vertically', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_direction' => 'vertical',
                    'wadi_carousel_pagination_type' => 'progressbar',
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_dots_vertical_offset',
            [
                'label' => esc_html__( 'Vertical Offset', 'wadi-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
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
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
                'selectors' => [
					'{{WRAPPER}} .wadi_carousel_container .swiper-pagination-bullets,{{WRAPPER}} .swiper-pagination-bullets.swiper-pagination-horizontal' => 'bottom: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_carousel_direction' => 'horizontal',
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_pagination_type' => 'bullets',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_dots_horizontal_offset',
            [
                'label' => esc_html__( 'Horizontal Offset', 'wadi-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 1000,
						'step' => 5,
					],
					'%' => [
						'min' => 2,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
                'selectors' => [
					'{{WRAPPER}} .swiper-pagination-vertical.swiper-pagination-bullets, {{WRAPPER}} .swiper-vertical .swiper-pagination-bullets' => 'right: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_carousel_direction' => 'vertical',
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_pagination_type' => 'bullets',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_arrow_navigation',
            [
                'label' => esc_html__('Arrow Navigation', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'separator' => 'before',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );
        $this->add_control(
			'wadi_selected_carousel_next_icon',
			[
				'label' => esc_html__( 'Next Icon', 'wadi-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wadi_carousel_next_icon',
				'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-right',
						'angle-right',
						'angle-double-right',
						'caret-right',
						'caret-square-right',
					],
					'fa-regular' => [
						'caret-square-right',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'wadi_selected_carousel_prev_icon[value]!' => '',
                    'wadi_carousel_direction' => 'horizontal',
                    'wadi_carousel_arrow_navigation' => 'yes',
				],
			]
		);

        $this->add_control(
			'wadi_selected_carousel_prev_icon',
			[
				'label' => esc_html__( 'Previous Icon', 'wadi-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wadi_carousel_prev_icon',
				'default' => [
					'value' => 'fas fa-arrow-left',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-left',
						'angle-left',
						'angle-double-left',
						'caret-left',
						'caret-square-left',
					],
					'fa-regular' => [
						'caret-square-left',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'wadi_selected_carousel_next_icon[value]!' => '',
                    'wadi_carousel_direction' => 'horizontal',
                    'wadi_carousel_arrow_navigation' => 'yes',
				],
			]
		);

        $this->add_control(
			'wadi_selected_carousel_next_icon_vertical',
			[
				'label' => esc_html__( 'Next Icon', 'wadi-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wadi_carousel_next_icon_vertical',
				'default' => [
					'value' => 'fas fa-arrow-down',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					],
					'fa-regular' => [
						'caret-square-down',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
                'condition' => [
					'wadi_selected_carousel_prev_icon_vertical[value]!' => '',
                    'wadi_carousel_direction' => 'vertical',
                    'wadi_carousel_arrow_navigation' => 'yes',
				],
			]
		);

        $this->add_control(
			'wadi_selected_carousel_prev_icon_vertical',
			[
				'label' => esc_html__( 'Previous Icon', 'wadi-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wadi_carousel_prev_icon_vertical',
				'default' => [
					'value' => 'fas fa-arrow-up',
					'library' => 'fa-solid',
				],
				'recommended' => [
					'fa-solid' => [
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					],
					'fa-regular' => [
						'caret-square-up',
					],
				],
				'skin' => 'inline',
				'label_block' => false,
                'condition' => [
					'wadi_selected_carousel_next_icon_vertical[value]!' => '',
                    'wadi_carousel_direction' => 'vertical',
                    'wadi_carousel_arrow_navigation' => 'yes',
				],
			]
		);

        $this->add_control(
            'wadi_carousel_scrollbar_heading',
            [
                'label' => esc_html__('Scrollbar', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'terms' => [
                                [
                                    'name' => 'wadi_carousel_direction',
                                    'operator' => '==',
                                    'value' => 'vertical',
                                ],
                                [
                                    'name' => 'wadi_carousel_pagination_outside_switcher',
                                    'operator' => '!=',
                                    'value' => 'yes'
                                ]
                            ]
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'wadi_carousel_direction',
                                    'operator' => '==',
                                    'value' => 'horizontal',
                                ],
                                [
                                    'name' => 'wadi_carousel_pagination_outside_switcher',
                                    'operator' => '==',
                                    'value' => 'yes'
                                ]
                            ]
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'wadi_carousel_direction',
                                    'operator' => '==',
                                    'value' => 'horizontal',
                                ],
                                [
                                    'name' => 'wadi_carousel_pagination_outside_switcher',
                                    'operator' => '!=',
                                    'value' => 'yes'
                                ]
                            ]
                        ],
                       
                    ]
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_scrollbar',
            [
                'label' => esc_html__('Scrollbar', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'terms' => [
                                [
                                    'name' => 'wadi_carousel_direction',
                                    'operator' => '==',
                                    'value' => 'vertical',
                                ],
                                [
                                    'name' => 'wadi_carousel_pagination_outside_switcher',
                                    'operator' => '!=',
                                    'value' => 'yes'
                                ]
                            ]
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'wadi_carousel_direction',
                                    'operator' => '==',
                                    'value' => 'horizontal',
                                ],
                                [
                                    'name' => 'wadi_carousel_pagination_outside_switcher',
                                    'operator' => '==',
                                    'value' => 'yes'
                                ]
                            ]
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'wadi_carousel_direction',
                                    'operator' => '==',
                                    'value' => 'horizontal',
                                ],
                                [
                                    'name' => 'wadi_carousel_pagination_outside_switcher',
                                    'operator' => '!=',
                                    'value' => 'yes'
                                ]
                            ]
                        ],
                       
                    ]
                ]
            ]

        );

        $this->add_control(
            'wadi_carousel_scrollbar_draggable',
            [
                'label' => esc_html__('Scrollbar Draggable', 'wadi-addons'),
                'description' => esc_html__('Enable make scrollbar draggable that allows you to control slider position', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                    [
                                        'name' => 'wadi_carousel_scrollbar',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ]
                                ]
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'vertical',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '==',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ]
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_scrollbar_hide',
            [
                'label' => esc_html__('Hide Scrollbar', 'wadi-addons'),
                'description' => esc_html__('Hide scrollbar automatically after user interaction', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                    [
                                        'name' => 'wadi_carousel_scrollbar',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ]
                                ]
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'vertical',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '==',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ]
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_scrollbar_snap',
            [
                'label' => esc_html__('Scrollbar Snap', 'wadi-addons'),
                'description' => esc_html__('Snap slider position to slides when you release scrollbar', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                    [
                                        'name' => 'wadi_carousel_scrollbar',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ]
                                ]
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'vertical',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '==',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ]
                ],
                'frontend_available' => true,
            ]
        );



        $this->add_control(
            'wadi_carousel_slides_view',
            [
                'label' => esc_html__('Slides View', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wadi_carousel_slides_per_view',
            [
                'label' => esc_html__('Slides Per View (Desktop)', 'wadi-addons'),
                'description' => esc_html__('Please note that Carousel Effects only works for Slide and Coverflow effects.', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
				'max' => 20,
				'step' => 1,
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => ['slide','coverflow'],
                ],
            ]
        );
        $this->add_control(
            'wadi_carousel_slides_per_view_tablet',
            [
                'label' => esc_html__('Slides Per View (Tablet)', 'wadi-addons'),
                'description' => esc_html__('Please note that Carousel Effects only works for Slide and Coverflow effects.', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
				'max' => 20,
				'step' => 1,
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => ['slide','coverflow'],
                ],
            ]
        );
        $this->add_control(
            'wadi_carousel_slides_per_view_mobile',
            [
                'label' => esc_html__('Slides Per View (Mobile)', 'wadi-addons'),
                'description' => esc_html__('Please note that Carousel Effects only works for Slide and Coverflow effects.', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
				'max' => 20,
				'step' => 1,
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => ['slide','coverflow'],
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_space_between',
            [
                'label' => esc_html__('Carousel Space Between', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 10,
                'min' => 0,
				'max' => 1000,
				'step' => 10,
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_breakpoints',
            [
                'label' => esc_html__('Carousel Breakpoints', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1024,
                'min' => 200,
				'max' => 10000,
				'step' => 20,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_centered',
            [
                'label' => esc_html__('Centered', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Centered,  Active slide will be centered, not always on the left side.', 'wadi-addons'),
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'separator' => 'before',
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_grab_cursor',
            [
                'label' => esc_html__('Grab Cursor', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Grab Cursor may a little improve desktop usability. If true, user will see the "grab" cursor when hover on Carousel.', 'wadi-addons'),
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_freemode_heading',
            [
                'label' => esc_html__('Free Mode', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wadi_carousel_freemode',
            [
                'label' => esc_html__('Free Mode', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Enables free mode functionality.', 'wadi-addons'),
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_freemode_minimum_velocity',
            [
                'label' => esc_html__('Free Mode Velocity', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Minimum touchmove-velocity required to trigger free mode momentum', 'wadi-addons'),
                'default' => 0.02,
                'min' => 0,
				'max' => 10,
				'step' => 0.01,
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_freemode' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_free_mode_momentum',
            [
                'label' => esc_html__('Free Mode Momentum', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('If enabled, then slide will keep moving for a while after you release it', 'wadi-addons'),
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_freemode' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'wadi_carousel_freemode_momentum_bounce',
            [
                'label' => esc_html__('Free Mode Momentum Bounce', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('If you want to disable momentum bounce in free mode.', 'wadi-addons'),
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_freemode' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_freemode_momentum_bounce_ratio',
            [
                'label' => esc_html__('Free Mode Bounce Ratio', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Higher value produces larger momentum bounce effect', 'wadi-addons'),
                'default' => 1,
                'min' => 0,
				'max' => 20,
				'step' => 0.1,
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_freemode' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_freemode_momentum_ratio',
            [
                'label' => esc_html__('Free Mode Ratio', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Higher value produces larger momentum distance after you release slider', 'wadi-addons'),
                'default' => 1,
                'min' => 0,
				'max' => 20,
				'step' => 0.1,
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_freemode' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'wadi_carousel_freemode_minimum_velocity_ratio',
            [
                'label' => esc_html__('Free Mode Momentum Velocity Ratio', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'description' => esc_html__('Higher value produces larger momentum velocity after you release slider', 'wadi-addons'),
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_freemode' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_freemode_sticky',
            [
                'label' => esc_html__('Free Mode Snap', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Set to enabled to enable snap to slides positions in free mode', 'wadi-addons'),
                'default' => 1,
                'min' => 0,
				'max' => 20,
				'step' => 0.1,
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_freemode' => 'yes',
                ]
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_carousel_additional_settings',
            [
                'label' => esc_html__('Additional Settings', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_carousel_auto_height',
            [
                'label' => esc_html__('Slides Auto Height', 'wadi-addons'),
                'description' => esc_html__('Make the carousel change height based on the height of the current slide instead of Static Height for the carousel. Currently works only on Carousel Horizontal Direction.', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'no',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_direction' => 'horizontal',
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_loop',
            [
                'label' => esc_html__('Loop', 'wadi-addons'),
                'description' => esc_html__('Make the carousel loop through the slides', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_mousewheel',
            [
                'label' => esc_html__('Mousewheel', 'wadi-addons'),
                'description' => esc_html__('Make the carousel slide on the mouse scroll.', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
			'wadi_carousel_autoplay_heading',
			[
				'label' => esc_html__( 'Autoplay', 'wadi-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'wadi_carousel_autoplay',
            [
                'label' => esc_html__('Autoplay', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_autoplay_delay',
            [
                'label' => esc_html__('Autoplay Delay', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
				'max' => 20000,
				'step' => 100,
                'default' => 1500,
                'description' => esc_html__('Delay between slides on autoplay of Carousel in ms (milliseconds).', 'wadi-addons'),
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_carousel_autoplay_disable_on_interaction',
            [
                'label' => esc_html__('Disable Autoplay on Interaction', 'wadi-addons'),
                'description' => esc_html__('Disable autoplay on interaction with the carousel, if enabled autplay will stop, if disabled it will continue after the interaction.', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'no',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_carousel_autoplay_pause_on_mouseenter',
            [
                'label' => esc_html__('Puase Carousel on Mouse Enter', 'wadi-addons'),
                'description' => esc_html__('Carousel autoplay will pause on mouse enter. If Disable On Interaction is also enabled, it will stop autoplay.', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_carousel_autoplay_reverse_direction',
            [
                'label' => esc_html__('Reverse Direction', 'wadi-addons'),
                'description' => esc_html__('Autoplay Reverse Direction Carousel Autoplay.', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'no',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_autoplay' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'wadi_carousel_autoplay_stop_last_slide',
            [
                'label' => esc_html__('Stop at Last Slide', 'wadi-addons'),
                'description' => esc_html__('Stop Carousel Autoplay at last slide.(has no effect in loop mode)', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_autoplay' => 'yes',
                    'wadi_carousel_loop!' => 'yes',
                ],
            ]
        );

        $this->add_control(
			'wadi_carousel_slide_effects_heading',
			[
				'label' => esc_html__( 'Carousel Effects', 'wadi-addons' ),
                'description' => esc_html__( 'Only works if numebr of displayed slides is 1.', 'wadi-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

        $this->add_control(
            'wadi_carousel_slide_effects',
            [
                'label' => esc_html__( 'Carousel Effects', 'wadi-addons' ),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__('Note: only Slide and Coverflow Effects works for multiple Slides Per View, other effects works only on one Slide Per View.', 'wadi-addons'),
                'options' => [
                    'slide' => esc_html__( 'Slide', 'wadi-addons' ),
                    'coverflow' => esc_html__( 'Coverflow', 'wadi-addons' ),
                    'fade' => esc_html__( 'Fade', 'wadi-addons' ),
                    'flip' => esc_html__( 'Flip', 'wadi-addons' ),
                    'cube' => esc_html__( 'Cube', 'wadi-addons' ),
                    'cards' => esc_html__( 'Cards', 'wadi-addons' ),
                ],
                'default' => 'slide',
                'frontend_available' => true,
            ]
        );

        // Coverflow Effect Options

        $this->add_control(
            'wadi_carousel_coverflow_effect_rotate',
            [
                'label' => esc_html__( 'Coverflow Rotate', 'wadi-addons' ),
                'description' => esc_html__( 'Slide rotate in degrees', 'wadi-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 500,
                'step' => 10,
                'default' => 50,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'coverflow',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_coverflow_effect_slide_shadows',
            [
                'label' => esc_html__( 'Coverflow slideShadows', 'wadi-addons' ),
                'description' => esc_html__( 'Enables slides shadows', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'coverflow',
                ], 
            ]
        );

        $this->add_control(
            'wadi_carousel_coverflow_effect_depth',
            [
                'label' => esc_html__( 'Coverflow Depth', 'wadi-addons' ),
                'description' => esc_html__( 'Depth offset in px (slides translate in Z axis)', 'wadi-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 500,
                'step' => 5,
                'default' => 100,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'coverflow',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_coverflow_effect_modifier',
            [
                'label' => esc_html__( 'Coverflow Modifier', 'wadi-addons' ),
                'description' => esc_html__( 'Effect multiplier', 'wadi-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'default' => 1,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'coverflow',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_coverflow_effect_scale',
            [
                'label' => esc_html__( 'Coverflow Scale', 'wadi-addons' ),
                'description' => esc_html__( 'Slide scale effect', 'wadi-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 10,
                'step' => 0.1,
                'default' => 1,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'coverflow',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_coverflow_effect_stretch',
            [
                'label' => esc_html__( 'Coverflow Stretch', 'wadi-addons' ),
                'description' => esc_html__( 'Stretch space between slides (in px), (Recommended keep it 0).', 'wadi-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000,
                'step' => 1,
                'default' => 0,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'coverflow',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_flip_effect_slide_shadows',
            [
                'label' => esc_html__( 'Flip Slide Shadows', 'wadi-addons' ),
                'description' => esc_html__( 'Enables slides shadows', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'flip',
                    'wadi_carousel_slides_per_view' => 1,
                    'wadi_carousel_slides_per_view_tablet' => 1,
                    'wadi_carousel_slides_per_view_mobile' => 1,
                ], 
            ]
        );

        $this->add_control(
            'wadi_carousel_flip_effect_limit_rotation',
            [
                'label' => esc_html__( 'Flip Limit Rotation', 'wadi-addons' ),
                'description' => esc_html__( 'Limit edge slides rotation', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'flip',
                    'wadi_carousel_slides_per_view' => 1,
                    'wadi_carousel_slides_per_view_tablet' => 1,
                    'wadi_carousel_slides_per_view_mobile' => 1,
                ], 
            ]
        );

        $this->add_control(
            'wadi_carousel_cube_effect_shadow',
            [
                'label' => esc_html__( 'Cube Shadow', 'wadi-addons' ),
                'description' => esc_html__( 'Enables main slider shadow', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'cube',
                    'wadi_carousel_slides_per_view' => 1,
                    'wadi_carousel_slides_per_view_tablet' => 1,
                    'wadi_carousel_slides_per_view_mobile' => 1,
                ], 
            ]
        );

        $this->add_control(
            'wadi_carousel_cube_effect_shadow_offset',
            [
                'label' => esc_html__( 'Cube Shadow Offset', 'wadi-addons' ),
                'description' => esc_html__( 'Main shadow offset in (px)', 'wadi-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 200,
                'step' => 1,
                'default' => 20,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'cube',
                    'wadi_carousel_cube_effect_shadow' => 'yes',
                    'wadi_carousel_slides_per_view' => 1,
                    'wadi_carousel_slides_per_view_tablet' => 1,
                    'wadi_carousel_slides_per_view_mobile' => 1,
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
            'wadi_carousel_cube_effect_shadow_scale',
            [
                'label' => esc_html__( 'Cube Shadow Scale', 'wadi-addons' ),
                'description' => esc_html__( 'Main shadow scale ratio', 'wadi-addons' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5,
                'step' => .01,
                'default' => 0.94,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'cube',
                    'wadi_carousel_cube_effect_shadow' => 'yes',
                    'wadi_carousel_slides_per_view' => 1,
                    'wadi_carousel_slides_per_view_tablet' => 1,
                    'wadi_carousel_slides_per_view_mobile' => 1,
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_carousel_cube_effect_slide_shadows',
            [
                'label' => esc_html__( 'Cube Slide Shadows', 'wadi-addons' ),
                'description' => esc_html__( 'Enables slides shadows', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'cube',
                    'wadi_carousel_cube_effect_shadow' => 'yes',
                    'wadi_carousel_slides_per_view' => 1,
                    'wadi_carousel_slides_per_view_tablet' => 1,
                    'wadi_carousel_slides_per_view_mobile' => 1,
                ], 
            ]
        );

        $this->add_control(
            'wadi_carousel_cards_effect_slide_shadows',
            [
                'label' => esc_html__( 'Cards Slide Shadows', 'wadi-addons' ),
                'description' => esc_html__( 'Enables slides shadows', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_carousel_slide_effects' => 'cards',
                    'wadi_carousel_slides_per_view' => 1,
                    'wadi_carousel_slides_per_view_tablet' => 1,
                    'wadi_carousel_slides_per_view_mobile' => 1,
                ], 
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_carousel_container',
            [
                'label' => esc_html__('Container', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_height',
            [

                'label' => esc_attr__('Height', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'description' => esc_attr__('Height of the Carousel Container (only for Vertical Carousel)', 'wadi-addons'),
                'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 3000,
						'step' => 5,
					],
                    'rem' => [
                        'min' => 10,
                        'max' => 150,
                        'step' => 1,
                    ],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'em' => [
						'min' => 10,
						'max' => 150,
						'step' => 1,
					],
				],
                'default' => [
					'unit' => 'px',
					'size' => 380,
				],
                'selectors' => [
                    '{{WRAPPER}} .wadi_swiper.swiper-vertical' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_direction' => 'vertical',
                ]
            ]
        );
        $this->add_responsive_control(
            'wadi_carousel_width',
            [

                'label' => esc_attr__('width', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'description' => esc_attr__('Width of the Carousel Container', 'wadi-addons'),
                'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
						'min' => 200,
						'max' => 4000,
						'step' => 5,
					],
                    'rem' => [
                        'min' => 10,
                        'max' => 200,
                        'step' => 1,
                    ],
					'%' => [
						'min' => 10,
						'max' => 100,
					],
					'em' => [
						'min' => 10,
						'max' => 200,
						'step' => 1,
					],
				],
                'default' => [
					'unit' => '%',
					'size' => 100,
				],
                'selectors' => [
                    '{{WRAPPER}} .wadi_swiper' => 'width: {{SIZE}}{{UNIT}};',
                ],
                // 'condition' => [
                //     'wadi_carousel_direction' => 'vertical',
                // ]
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_carousel_navigation',
            [
                'label' => esc_html__('Navigation', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'wadi_carousel_dots_navigation',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'wadi_carousel_arrow_navigation',
                            'operator' => '==',
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );

        $this->start_controls_tabs(
            'style_tabs_navigation'
        );
        
        $this->start_controls_tab(
            'style_wadi_navgiation_dots_tab',
            [
                'label' => esc_html__( 'Dots', 'wadi-addons' ),
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control(
            'wadi_carousel_dots_navgiation_size',
            [
                'label' => esc_html__('Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
						'min' => 3,
						'max' => 100,
						'step' => 1,
					],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'carousel_dots_popover-toggle',
            [
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'Carousel Dots Location', 'wadi-addons' ),
                'label_off' => esc_html__( 'Default', 'wadi-addons' ),
                'label_on' => esc_html__( 'Custom', 'wadi-addons' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->start_popover();

        $this->add_control(
            'wadi_carousel_dots_navgiation_horizontal',
            [
                'label' => esc_html__('Dots Horizontal', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
						'min' => -2500,
						'max' => 2500,
						'step' => 1,
					],
                    'rem' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 0.1,
                    ],
					'%' => [
						'max' => 100,
					],
					'em' => [
						'min' => -200,
						'max' => 200,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets.swiper-pagination-horizontal,{{WRAPPER}} .swiper-pagination-bullets.swiper-pagination-vertical' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'wadi_carousel_dots_navgiation_vertical',
            [
                'label' => esc_html__('Dots Vertical', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
						'min' => -2500,
						'max' => 2500,
						'step' => 1,
					],
                    'rem' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 0.1,
                    ],
					'%' => [
						'max' => 100,
					],
					'em' => [
						'min' => -200,
						'max' => 200,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets.swiper-pagination-horizontal, {{WRAPPER}} .swiper-pagination-bullets.swiper-pagination-vertical' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes'
                ]
            ]
        );

        
        $this->end_popover();

        $this->add_control(
            'wadi_navigtaion_dots_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ccc',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'wadi_navigtaion_dots_active_color',
            [
                'label' => esc_html__('Active Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007aff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'wadi_navigtaion_dots_hover_color',
            [
                'label' => esc_html__('Hover Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullet:hover:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes'
                ]
            ]
        );

        
            
            
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'style_wadi_navgiation_arrows_tab',
            [
                'label' => esc_html__( 'Arrows', 'wadi-addons' ),
                'condition' => [
                    'wadi_carousel_arrow_navigation' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_arrows_navgiation_size',
            [
                'label' => esc_html__('Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
						'min' => 3,
						'max' => 100,
						'step' => 1,
					],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .wadi-swiper-button, {{WRAPPER}} .wadi-swiper-button-vertical' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_arrow_navigation' => 'yes'
                ]
            ]
        );



        $this->add_control(
            'carousel_arrows_popover-toggle',
            [
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label' => esc_html__( 'Carousel Arrows Location', 'wadi-addons' ),
                'label_off' => esc_html__( 'Default', 'wadi-addons' ),
                'label_on' => esc_html__( 'Custom', 'wadi-addons' ),
                'return_value' => 'yes',
            ]
        );
        
        $this->start_popover();

        $this->add_responsive_control(
            'wadi_carousel_arrows_navgiation_prev_arrow',
            [
                'label' => esc_html__('Previous Arrow', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
						'min' => -2500,
						'max' => 2500,
						'step' => 1,
					],
                    'rem' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 0.1,
                    ],
					'%' => [
						'max' => 100,
					],
					'em' => [
						'min' => -200,
						'max' => 200,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .wadi-swiper-button-vertical.wadi_carousel_prev_vertical, {{WRAPPER}} .wadi-swiper-button.wadi-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_arrow_navigation' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_arrows_navgiation_next_arrow',
            [
                'label' => esc_html__('Next Arrow', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
						'min' => -2500,
						'max' => 2500,
						'step' => 1,
					],
                    'rem' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 0.1,
                    ],
					'%' => [
						'max' => 100,
					],
					'em' => [
						'min' => -200,
						'max' => 200,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .wadi-swiper-button-vertical.wadi_carousel_next_vertical, {{WRAPPER}} .wadi-swiper-button.wadi-swiper-button-next' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_arrow_navigation' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_arrows_navgiation_vertical_prev_arrow',
            [
                'label' => esc_html__('Vertical Previous Arrow', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
                        'min' => -2500,
						'max' => 2500,
						'step' => 1,
					],
                    'rem' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 0.1,
                    ],
					'%' => [
						'max' => 100,
					],
					'em' => [
						'min' => -200,
						'max' => 200,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .wadi-swiper-button-vertical.wadi_carousel_prev_vertical, {{WRAPPER}} .wadi-swiper-button.wadi-swiper-button-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_arrow_navigation' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_carousel_arrows_navgiation_vertical_next_arrow',
            [
                'label' => esc_html__('Vertical Next Arrow', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range' => [
					'px' => [
                        'min' => -2500,
						'max' => 2500,
						'step' => 1,
					],
                    'rem' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 0.1,
                    ],
					'%' => [
						'max' => 100,
					],
					'em' => [
                        'min' => -200,
						'max' => 200,
						'step' => 0.1,
					],
				],
                'selectors' => [
                    '{{WRAPPER}} .wadi-swiper-button-vertical.wadi_carousel_next_vertical, {{WRAPPER}} .wadi-swiper-button.wadi-swiper-button-next' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_arrow_navigation' => 'yes'
                ]
            ]
        );

        $this->end_popover();

        
        $this->add_control(
            'wadi_navigtaion_arrows_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi-swiper-button-prev i, {{WRAPPER}} .wadi-swiper-button-next i, {{WRAPPER}} .wadi_carousel_prev_vertical i, {{WRAPPER}} .wadi_carousel_next_vertical i, {{WRAPPER}} .wadi-swiper-button-prev svg, {{WRAPPER}} .wadi-swiper-button-next svg, {{WRAPPER}} .wadi_carousel_prev_vertical svg, {{WRAPPER}} .wadi_carousel_next_vertical svg' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .wadi-swiper-button-prev svg, {{WRAPPER}} .wadi-swiper-button-next svg, {{WRAPPER}} .wadi_carousel_prev_vertical svg, {{WRAPPER}} .wadi_carousel_next_vertical svg' => 'fill: {{VALUE}} !important;',
                ],
                'condition' => [
                    'wadi_carousel_arrow_navigation' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'wadi_navigtaion_arrows_hover_color',
            [
                'label' => esc_html__('Hover Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi-swiper-button:hover i, {{WRAPPER}} .wadi-swiper-button-vertical:hover i, {{WRAPPER}} .wadi-swiper-button:hover svg, {{WRAPPER}} .wadi-swiper-button-vertical:hover svg' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .wadi-swiper-button:hover svg, {{WRAPPER}} .wadi-swiper-button-vertical:hover svg' => 'fill: {{VALUE}} !important;',
                ],
                'condition' => [
                    'wadi_carousel_arrow_navigation' => 'yes'
                ]
            ]
        );

        
        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();


        $this->start_controls_section(
            'wadi_scrollbar_styles',
            [
                'label' => esc_html__('Scrollbar', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                    [
                                        'name' => 'wadi_carousel_scrollbar',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ]
                                ]
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'vertical',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '==',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ]
                ],
            ]
        );

        $this->add_control(
            'wadi_scrollbar_line_color',
            [
                'label' => esc_html__('Scrollbar Line Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .swiper-scrollbar' => 'background-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                    [
                                        'name' => 'wadi_carousel_scrollbar',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ]
                                ]
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'vertical',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '==',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ]
                ],
            ]
        );

        $this->add_control(
            'wadi_scrollbar_draggable_color',
            [
                'label' => esc_html__('Scrollbar Draggable', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_carousel_container .swiper-scrollbar-drag' => 'background-color: {{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                    [
                                        'name' => 'wadi_carousel_scrollbar',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ]
                                ]
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'vertical',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '==',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ]
                ],
            ]
        );

        $this->add_control(
			'wadi_scrollbar_line_horizontal_size',
			[
				'label' => esc_html__( 'Scrollbar Size', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
                'defalut' => [
                    'unit' => 'px',
                    'size' => 6,
                ],
				'selectors' => [
					'{{WRAPPER}} .wadi_carousel_container_horizontal .swiper-scrollbar' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                    [
                                        'name' => 'wadi_carousel_scrollbar',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ],
                                    [
                                        'name' => 'wadi_carousel_direction',
                                        'operator' => '==',
                                        'value' => 'horizontal',
                                    ]
                                ]
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '==',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'horizontal',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ]
                ],
			]
		);

        $this->add_control(
			'wadi_scrollbar_line_vertical_size',
			[
				'label' => esc_html__( 'Scrollbar Size', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_carousel_container_vertical .swiper-scrollbar' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                            [
                                'name' => 'wadi_carousel_scrollbar',
                                'operator' => '==',
                                'value' => 'yes',
                            ],
                            [
                                'name' => 'wadi_carousel_direction',
                                'operator' => '==',
                                'value' => 'vertical',
                            ]
                        ]
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'terms' => [
                                        [
                                            'name' => 'wadi_carousel_direction',
                                            'operator' => '==',
                                            'value' => 'vertical',
                                        ],
                                        [
                                            'name' => 'wadi_carousel_pagination_outside_switcher',
                                            'operator' => '!=',
                                            'value' => 'yes'
                                        ]
                                    ]
                                ],
                            ]
                        ]

                    ]
                ],
			]
		);


        
        $this->add_control(
            'style_wadi_carousel_navgiation_fraction_heading',
            [
        'label' => esc_html__('Fractions', 'wadi-addons'),
        'type' => Controls_Manager::HEADING,
        'condition' => [
            'wadi_carousel_dots_navigation' => 'yes',
            'wadi_carousel_pagination_type' => 'fraction',
        ]
    ]
        );

        $this->add_control(
            'wadi_carousel_navigtaion_fraction_size',
            [
                'label' => esc_html__('Fraction Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem' ,'%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 3,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 10,
                        'step' => 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-fraction' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_pagination_type' => 'fraction',
                ],
            ]
        );

        $this->add_control(
            'wadi_carousel_navigtaion_fraction_color',
            [
                'label' => esc_html__('Current Fraction', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007aff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-fraction' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_pagination_type' => 'fraction',
                ]
            ]
        );
        $this->add_control(
            'wadi_carousel_navigtaion_current_fraction_color',
            [
                'label' => esc_html__('Current Fraction', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007aff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-current' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_pagination_type' => 'fraction',
                ]
            ]
        );
        
        $this->add_control(
            'wadi_carousel_navigtaion_total_fraction_color',
            [
                'label' => esc_html__('Total Fraction', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#007aff',
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-fraction .swiper-pagination-total' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_carousel_dots_navigation' => 'yes',
                    'wadi_carousel_pagination_type' => 'fraction',
                ]
            ]
        );



        $this->end_controls_section();

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

    protected function render_carousel_navigation($settings) {
        // Horizontal

        $migrated_next = isset( $settings['__fa4_migrated']['wadi_selected_carousel_next_icon'] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
        
		$has_icon_next = ( ! $is_new || ! empty( $settings['wadi_carousel_next_icon']['value'] ) );
        
        $migrated_prev = isset( $settings['__fa4_migrated']['wadi_selected_carousel_prev_icon'] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
        
		$has_icon_prev = ( ! $is_new || ! empty( $settings['wadi_selected_carousel_prev_icon']['value'] ) );
        
        // Vertical
        $migrated_next_vertical = isset( $settings['__fa4_migrated']['wadi_selected_carousel_next_icon_vertical'] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
        
		$has_icon_next_vertical = ( ! $is_new || ! empty( $settings['wadi_carousel_next_icon_vertical']['value'] ) );
        
        $migrated_prev_vertical = isset( $settings['__fa4_migrated']['wadi_selected_carousel_prev_icon_vertical'] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
        
		$has_icon_prev_vertical = ( ! $is_new || ! empty( $settings['wadi_selected_carousel_prev_icon_vertical']['value'] ) );
        
              
              ?>        
        <?php if($settings['wadi_carousel_scrollbar'] === 'yes' ) : ?>
            <div class="swiper-scrollbar"></div>
        <?php endif; ?>
        <?php if($settings['wadi_carousel_dots_navigation'] === 'yes') : ?>
            <div class="swiper-pagination"></div>
        <?php endif; ?>


        <?php if($settings['wadi_carousel_arrow_navigation'] === 'yes') : ?>
            <?php if($settings['wadi_carousel_direction'] === 'horizontal') : ?>
            <div class="wadi-swiper-button wadi-swiper-button-prev">
            <?php
                if ( $is_new || $migrated_prev ) { ?>
                    <?php Icons_Manager::render_icon( $settings['wadi_selected_carousel_prev_icon'] ); ?>
                <?php } else { ?>
                    <i class="wadi_carousel_icon_prev <?php echo esc_attr( $settings['wadi_carousel_prev_icon'] ); ?>"></i>
                <?php } ?>
            </div>
            <div class="wadi-swiper-button wadi-swiper-button-next">
            <?php
            if ( $is_new || $migrated_next ) { ?>
                <?php Icons_Manager::render_icon( $settings['wadi_selected_carousel_next_icon'] ); ?>
            <?php } else { ?>
                <i class="wadi_carousel_icon_next <?php echo esc_attr( $settings['wadi_carousel_next_icon'] ); ?>"></i>
            <?php } ?>
            </div>
            <?php else : ?>
                <div class="wadi-swiper-button-vertical wadi-swiper-button-prev wadi_carousel_prev_vertical">
            <?php
                if ( $is_new || $migrated_prev_vertical ) { ?>
                    <?php Icons_Manager::render_icon( $settings['wadi_selected_carousel_prev_icon_vertical'] ); ?>
                <?php } else { ?>
                    <i class="wadi_carousel_icon_prev_vertical <?php echo esc_attr( $settings['wadi_carousel_prev_icon_vertical'] ); ?>"></i>
                <?php } ?>
            </div>
            <div class="wadi-swiper-button-vertical wadi-swiper-button-next wadi_carousel_next_vertical">
            <?php
            if ( $is_new || $migrated_next_vertical ) { ?>
                <?php Icons_Manager::render_icon( $settings['wadi_selected_carousel_next_icon_vertical'] ); ?>
            <?php } else { ?>
                <i class="wadi_carousel_icon_next_vertical <?php echo esc_attr( $settings['wadi_carousel_next_icon_vertical'] ); ?>"></i>
            <?php } ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $content_type = $settings['wadi_carousel_content_type'];

        $carousel_select_items = $settings['wadi_carousel_select_content'];

        $carousel_repeater_items = $settings['wadi_carousel_repeater_content'];

        $extra_class = ! empty( $settings['wadi_carousel_extra_class'] ) ? ' ' . $settings['wadi_carousel_extra_class'] : '';
        
       

        $templates = [];

        if ('select' === $content_type) {

            $templates = $carousel_select_items;

        } elseif('repeater' === $content_type) {
            $wadi_custom_navigation = [];

            foreach($carousel_repeater_items as $template) {
                array_push($templates, $template['wadi_carousel_repeater_item']);
                array_push($wadi_custom_navigation, $template['wadi_custom_navigation']);
            }

        }

        if ( empty( $templates ) ) {
			return;
		}


        $this->add_render_attribute(
			'wadi_carousel_container',
			'class',
			array(
				'wadi_carousel_container',
				'wadi_carousel_container_' . esc_attr($settings['wadi_carousel_direction']),
				'wadi_carousel_wrapper_' . esc_attr( $this->get_id() ),
				$extra_class,
			)
		);

        $this->add_render_attribute(
			'wadi_swiper',
			'class',
			array(
                'swiper',
				'wadi_swiper',
				'wadi_carousel_swiper_' . esc_attr( $this->get_id() ),
			)
		);
        ?>
            <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_carousel_container' ) ); ?>>

                <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_swiper' ) ); ?>>
                    <div class="swiper-wrapper">
                        <?php
                        foreach ($templates as $index => $item) {
                            ?>

                            <div class="swiper-slide">
                                <?php
                                if('select' === $content_type) {
                                    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($item); // phpcs:ignore CSRF ok.
                                } elseif('repeater' === $content_type) {
                                    echo $this->getTemplateInstance()->get_elementor_template_content($item); // phpcs:ignore CSRF ok.
                                }
                                ?>
                            </div>

                            <?php
                        }

                    
                        ?>
                    </div>
                    <?php
                    if('yes' != $settings['wadi_carousel_pagination_outside_switcher']) {
                        $this->render_carousel_navigation($settings);
                    } ?>
                
                </div>

                <?php /** Outside Navigation */ ?>

                    <?php
                    if('yes' == $settings['wadi_carousel_pagination_outside_switcher']) {
                        $this->render_carousel_navigation($settings);
                    }
                    
                    ?>
            </div>
        <?php
    }

    protected function content_template()
    {}

}