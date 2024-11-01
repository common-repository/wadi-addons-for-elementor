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
use Elementor\Group_Control_Image_Size;

// Wadi Classes
use WadiAddons\Includes\WadiHelpers;
use WadiAddons\Includes\WadiQueries;

if (! defined('ABSPATH')) {
    exit;
}

class Wadi_Info_Box extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-info-box-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Info Box', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-info-box';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        if(!is_rtl()) {
            wp_register_style('style-wadi-info-box-handle', WADI_ADDONS_URL . 'assets/min/wadi-info-box.css', [], WADI_ADDONS_VERSION, 'all');
        } else {
            wp_register_style('style-wadi-info-box-handle', WADI_ADDONS_URL . 'assets/min/wadi-info-box.rtl.css', [], WADI_ADDONS_VERSION, 'all');
        }
    }

    public function get_style_depends()
    {
        return [ 'style-wadi-info-box-handle' ];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'wadi_info_box_content_section',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_info_box_title_prefix',
            [
                'label' => esc_html__('Title Prefix', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Type title prefix here', 'wadi-addons'),
                'dynamic'  => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'wadi_info_box_title',
            [
                'label' => esc_html__('Title', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Default title', 'wadi-addons'),
                'placeholder' => esc_html__('Type your title here', 'wadi-addons'),
                'dynamic'  => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'wadi_info_box_description',
            [
                'label' => esc_html__('Description', 'wadi-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Default description', 'wadi-addons'),
                'placeholder' => esc_html__('Type your description here', 'wadi-addons'),
                'dynamic'  => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_info_box_media_section',
            [
                'label' => esc_html__('Media', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_info_box_graphic_element_switch',
            [
        'label' => esc_html__('Graphic Element', 'wadi-addons'),
        'type' => Controls_Manager::SWITCHER,
        'label_on' => esc_html__('Yes', 'wadi-addons'),
        'label_off' => esc_html__('No', 'wadi-addons'),
        'default' => 'yes',
    ]
        );

        $this->add_control(
            'wadi_info_box_graphic_element',
            [
            'label' => esc_html__('Graphics', 'wadi-addons'),
            'type' => Controls_Manager::CHOOSE,
            'options' => [
                'none' => [
                    'title' => esc_html__('None', 'wadi-addons'),
                    'icon' => 'eicon-ban',
                ],
                'image' => [
                    'title' => esc_html__('Image', 'wadi-addons'),
                    'icon' => 'fa fa-image',
                ],
                'icon' => [
                    'title' => esc_html__('Icon', 'wadi-addons'),
                    'icon' => 'eicon-star',
                ],
            ],
            'default' => 'icon',
            'condition' => [
                'wadi_info_box_graphic_element_switch' => 'yes'
            ]
        ]
        );

        
		$this->add_control(
			'wadi_info_box_media_position',
			array(
				'label'       => __( 'Select Position', 'wadi-addons' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'wadi_media_above-content',
				'label_block' => true,
				'options'     => array(
					'wadi_media_above-content' => __( 'Above Content', 'wadi-addons' ),
					'wadi_media_below-content' => __( 'Below Content', 'wadi-addons' ),
					'wadi_media_left-content'  => __( 'Left of Content', 'wadi-addons' ),
					'wadi_media_right-content' => __( 'Right of Content', 'wadi-addons' ),
				),
                'condition'   => array(
                    'wadi_info_box_graphic_element_switch' => 'yes',
                    'wadi_info_box_graphic_element' => [ 'icon', 'image'],
                ),
			)
		);

        $this->add_control(
            'image',
            [
        'label' => esc_html__('Choose Image', 'wadi-addons'),
        'type' => Controls_Manager::MEDIA,
        'default' => [
            'url' => Utils::get_placeholder_image_src(),
        ],
        'dynamic' => [
            'active' => true,
        ],
        'condition' => [
            'wadi_info_box_graphic_element' => 'image',
            'wadi_info_box_graphic_element_switch' => 'yes'
        ]
    ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
        'name' => 'image_size_wadi_info_box', // Actually its `image_size`
        'default' => 'thumbnail',
        'condition' => [
            'wadi_info_box_graphic_element' => 'image',
            'wadi_info_box_graphic_element_switch' => 'yes'
        ],
    ]
        );


        $this->add_control(
            'wadi_info_box_image_size_fit',
            [
                'label' => esc_html__('Images Fit', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cover' => esc_html__('Cover', 'wadi-addons'),
                    'contain' => esc_html__('Contain', 'wadi-addons'),
                    'auto' => esc_html__('Auto', 'wadi-addons'),
                ],
                'default' => 'cover',
                'selectors' => [
                    '{{WRAPPER}} .wadi_info_box_media.wadi_info_box_media_image  img' => 'object-fit: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_info_box_graphic_element' => 'image',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
			'wadi_info_box_image_height',
			[
				'label' => esc_html__( 'Image Height', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_media.wadi_info_box_media_image img' => 'height: {{SIZE}}{{UNIT}};'
					// '{{WRAPPER}} .wadi_info_box_media.wadi_info_box_media_image' => 'height: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_graphic_element' => 'image',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ]
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_image_width',
			[
				'label' => esc_html__( 'Image Width', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_media.wadi_info_box_media_image img' => 'width: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_graphic_element' => 'image',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ]
			]
		);

        $this->add_control(
            'selected_icon',
            [
        'label' => esc_html__('Choose Icon', 'wadi-addons'),
        'type' => Controls_Manager::ICONS,
        'fa4compatibility' => 'icon',
        'default' => [
            'value' => 'fas fa-star',
            'library' => 'fa-solid',
        ],
		'skin' => 'inline',
        'condition' => [
            'wadi_info_box_graphic_element' => 'icon',
            'wadi_info_box_graphic_element_switch' => 'yes'
        ]
    ]
        );

        $this->add_responsive_control(
			'wadi_info_box_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 2000,
						'step' => 5,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'rem' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],
                'default' => [
                    'unit' => 'px',
                    'size' => '60'
                ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon i, {{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_graphic_element' => 'icon',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ]
			]
		);


        $this->add_responsive_control(
			'wadi_info_box_icon_rotate',
			array(
				'label'      => __( 'Icon Rotate', 'wadi-addons' ),
				'type'       => Controls_Manager::SLIDER,
                'range' => [
					'px' => [
						'min' => 0,
						'max' => 360,
						'step' => 1,
					]
                ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon i, {{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon svg' =>  'transform: rotate({{SIZE}}deg);',
				],
				'condition' => [
                    'wadi_info_box_graphic_element' => 'icon',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ]
			)
		);

        $this->add_control(
			'wadi_info_box_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon i, {{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon svg' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon svg, {{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon svg > *' => 'fill: {{VALUE}}',
				],
                'condition' => [
                    'wadi_info_box_graphic_element' => 'icon',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ]
			]
		);
        $this->add_control(
			'wadi_info_box_icon_hover_color',
			[
				'label' => esc_html__( 'Icon Hover Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon:hover i, {{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon:hover svg' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon svg:hover, {{WRAPPER}} .wadi_info_box_icon_wrapper .wadi_info_box_icon svg:hover > *' => 'fill: {{VALUE}}',
				],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                            [
                                'terms' => [
                                    [
                                        'name' => 'wadi_info_box_graphic_element',
                                        'operator' => '==',
                                        'value' => 'icon',
                                    ],
                                    [
                                        'name' => 'wadi_info_box_graphic_element_switch',
                                        'operator' => '==',
                                        'value' => 'yes',
                                    ],
                                ],
                                'relation' => 'or',
                                'terms' => [
                                    [
                                        'name' => 'wadi_info_box_cta_switch',
                                        'operator' => '!==',
                                        'value' => 'yes',
                                    ],
                                    [
                                        'name' => 'wadi_info_box_cta_types',
                                        'operator' => '!==',
                                        'value' => 'whole_box',
                                    ]
                                ]
                            ]
                        ],

                    ]
                ]
            );

        $this->add_control(
			'wadi_info_box_image_styling_heading',
			[
				'label' => esc_html__( 'Image Styling', 'wadi-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
                'condition' => [
                    'wadi_info_box_graphic_element' => 'image',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ]
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
				'name' => 'wadi_info_box_custom_css_filters',
				'selector' => '{{WRAPPER}} .wadi_info_box_media.wadi_info_box_media_image img',
                'condition' => [
                    'wadi_info_box_graphic_element' => 'image',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ]
			]
		);

        $this->add_responsive_control(
            'wadi_flip_box_front_image_opacity',
            [
        'label' => esc_html__('Opacity', 'wadi-addons'),
        'type' => Controls_Manager::SLIDER,
        'default' => [
            'size' => 1,
        ],
        'range' => [
            'px' => [
                'max' => 1,
                'min' => 0.01,
                'step' => 0.01
            ],
        ],
        'condition' => [
            'wadi_info_box_graphic_element' => 'image',
            'wadi_info_box_graphic_element_switch' => 'yes'
        ],
        'selectors' => [
            '{{WRAPPER}} .wadi_info_box_media.wadi_info_box_media_image img' => 'opacity: {{SIZE}};',
        ],
    ]
        );


        $this->add_responsive_control(
            'wadi_info_box_background_hover_transition',
            array(
                'label'     => __( 'Transition Duration', 'wadi-addons' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => array(
                    'size' => 0.3,
                ),
                'range'     => array(
                    'px' => array(
                        'max'  => 3,
                        'step' => 0.1,
                    ),
                ),
                'condition' => [
                    'wadi_info_box_graphic_element' => 'image',
                    'wadi_info_box_graphic_element_switch' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_info_box_media.wadi_info_box_media_image img' => 'transition-duration: {{SIZE}}s',
                ],
            )
        );

        
        $this->add_control(
            'wadi_info_box_media_animation',
            array(
                'label'     => __( 'Hover Animation', 'wadi-addons' ),
                'type'      => Controls_Manager::HOVER_ANIMATION,
                'condition' => [
                    'wadi_info_box_graphic_element_switch' => 'yes',
                    'wadi_info_box_graphic_element' => array( 'icon', 'image'),
                ],
            )
        );

        // Heading Responsive Control
        $this->add_responsive_control(
            'wadi_info_box_heading_responsive',
            [
                'label' => esc_html__( 'Responsive Display', 'wadi-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'wadi_info_box_graphic_element_switch' => 'yes',
                    'wadi_info_box_graphic_element' => [ 'icon', 'image'],
                ],
            ]
        );

        // Hiden on Desktop
        $this->add_control(
            'wadi_info_box_media_responsive_hidden_on_desktop',
            [
                'label' => esc_html__( 'Hide on Desktop', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__( 'Show', 'wadi-addons' ),
                'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
                'condition' => [
                    'wadi_info_box_graphic_element_switch' => 'yes',
                    'wadi_info_box_graphic_element' => [ 'icon', 'image'],
                ],
            ]
        );

        $this->add_control(
            'wadi_info_box_media_responsive_hidden_on_tablet',
            [
                'label' => esc_html__( 'Hide On Tablet', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__( 'Show', 'wadi-addons' ),
                'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
                'condition' => [
                    'wadi_info_box_graphic_element_switch' => 'yes',
                    'wadi_info_box_graphic_element' => [ 'icon', 'image'],
                ],
            ]
        );

        // Hidden on Mobile
        $this->add_control(
            'wadi_info_box_media_responsive_hidden_on_mobile',
            [
                'label' => esc_html__( 'Hide on Mobile', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__( 'Show', 'wadi-addons' ),
                'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
                'condition' => [
                    'wadi_info_box_graphic_element_switch' => 'yes',
                    'wadi_info_box_graphic_element' => [ 'icon', 'image'],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_info_box_separator_section',
            [
                'label' => esc_html__('Separator', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'wadi_info_box_separator_switch',
            [
                'label' => esc_html__('Separator', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'wadi-addons'),
                'label_off' => esc_html__('Hide', 'wadi-addons'),
                'return_value' => 'yes',
                'default' => 'no'
            ]
        );

        $this->add_control(
            'wadi_info_box_separator_position',
            [
                'label' => esc_html__( 'Separator Position', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'after_title',
				'options' => [
                    'after_media' => esc_html__( 'After Media', 'wadi-addons' ),
                    'after_prefix' => esc_html__( 'After Prefix', 'wadi-addons' ),
					'after_title'  => esc_html__( 'After Title', 'wadi-addons' ),
					'after_description' => esc_html__( 'After Description', 'wadi-addons' )
                ],
                'condition' => [
                    'wadi_info_box_separator_switch' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'wadi_info_box_separator_style',
            [
                'label' => esc_html__( 'Separator Style', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'solid',
				'options' => [
					'solid'  => esc_html__( 'Solid', 'wadi-addons' ),
					'dashed' => esc_html__( 'Dashed', 'wadi-addons' ),
					'dotted' => esc_html__( 'Dotted', 'wadi-addons' ),
					'double' => esc_html__( 'Double', 'wadi-addons' ),
                ],
                'condition' => [
                    'wadi_info_box_separator_switch' => 'yes'
                ],
                'selectors'   => array(
					'{{WRAPPER}} .wadi_info_box_separator .info_box_separator' => 'border-top-style: {{VALUE}}; display: inline-block;',
				),
            ]
        );

        $this->add_control(
            'wadi_info_box_separator_color',
            [
                'label' => esc_html__('Separator Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_separator .info_box_separator' => 'border-top-color: {{VALUE}}',
				],
                'condition' => [
                    'wadi_info_box_separator_switch' => 'yes'
                ]
            ]
        );


        $this->add_responsive_control(
			'wadi_info_box_separator_thickness',
			[
				'label' => esc_html__( 'Separator Thickness', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','rem', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 3,
				],
				'selectors' => [
					'{{WRAPPER}}  .wadi_info_box_separator .info_box_separator' => 'border-top-width: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_separator_switch' => 'yes'
                ]
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_separator_width',
			[
				'label' => esc_html__( 'Separator Width', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1500,
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
					]
				],
				'default' => [
					'unit' => '%',
					'size' => 30,
				],
				'selectors' => [
					'{{WRAPPER}}  .wadi_info_box_separator .info_box_separator' => 'width: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_separator_switch' => 'yes'
                ]
			]
		);
        $this->add_responsive_control(
			'wadi_info_box_separator_spacing',
			[
				'label' => esc_html__( 'Separator Spacing', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_separator .info_box_separator' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_separator_switch' => 'yes'
                ]
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_info_box_cta',
            [
                'label' => esc_html__('Call To Action', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT, 
            ]
        );


        $this->add_control(
			'wadi_info_box_cta_switch',
			[
				'label' => esc_html__( 'Call To Action', 'wadi-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'wadi-addons' ),
				'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

        $this->add_control(
            'wadi_info_box_cta_types',
            [
                'label' => esc_html__('Type', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'link',
				'options' => [
					'link'  => esc_html__( 'Link', 'wadi-addons' ),
					'whole_box' => esc_html__( 'Whole Box', 'wadi-addons' ),
				],
                'condition' => [
                    'wadi_info_box_cta_switch' => 'yes'
                ]
            ]
        );

        // Icon Switch for Link
        $this->add_control(
            'wadi_info_box_cta_link_icon_switch',
            [
                'label' => esc_html__('Icon', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'wadi-addons' ),
                'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                ],
            ]
        );

        $iconPosition = [
            'before'    => [
                'title' => __( 'Before', 'wadi-addons' ),
                'icon'  => 'eicon-h-align-left',
            ],
            'after' => [
                'title' => __( 'After', 'wadi-addons' ),
                'icon'  => 'eicon-h-align-right',
            ],
        ];

        $iconPositionRTL = array_reverse($iconPosition);

        $theIconPosition = !is_rtl() ? $iconPosition : $iconPositionRTL; 

        // Before or After
        $this->add_control(
            'wadi_info_box_cta_link_icon_position',
            [
                'label' => esc_html__('Icon Position', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'before',
                'options'     => $theIconPosition,
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'cta_link_icon',
            [
                'label' => esc_html__('Icon', 'wadi-addons'),
                'type' => Controls_Manager::ICONS,
                'fa4compatibility' => 'cta_icon',
                'default' => [
                    'value' => 'fas fa-arrow-right',
                    'library' => 'solid',
                ],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
            ]
        );

        $this->add_control(
			'wadi_info_box_link_text',
			array(
				'label'     => esc_html__( 'Text', 'wadi-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__( 'Read More', 'wadi-addons' ),
				'dynamic'   => array(
					'active' => true,
				),
				'condition' => [
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_types' => 'link',
                ]
			)
		);

        $this->add_control(
			'wadi_info_box_link_url',
			array(
				'label'         => esc_html__( 'Link', 'wadi-addons' ),
				'type'          => Controls_Manager::URL,
                'placeholder' => esc_html__( 'https://your-link.com', 'wadi-addons' ),
				'default' => [
					'url' => '#',
					'is_external' => true,
					'nofollow' => true,
					'custom_attributes' => '',
				],
				'dynamic'       => array(
					'active' => true,
				),
				'show_external' => true, // Show the 'open in new tab' button.
                'label_block' => true,
                'condition' => [
                    'wadi_info_box_cta_switch' => 'yes',
                ]
			)
		);

        
        $this->end_controls_section();

        // Styling

        $this->start_controls_section(
            'wadi_info_box_general_styling',
            [
                'label' => esc_html__('General', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

                        
        $infoBoxAlignment = [
            'left'   => array(
                'title' => __('Left', 'wadi-addons'),
                'icon'  => 'eicon-text-align-left',
            ),
            'center' => array(
                'title' => __('Center', 'wadi-addons'),
                'icon'  => 'eicon-text-align-center',
            ),
            'right'  => array(
                'title' => __('Right', 'wadi-addons'),
                'icon'  => 'eicon-text-align-right',
            ),
        ];

        $rtl_infoBoxAlignment = array_reverse($infoBoxAlignment);

        $theInfoBoxAlignment = !is_rtl()? $infoBoxAlignment :  $rtl_infoBoxAlignment;



        $this->add_responsive_control(
            'wadi_info_box_alignment',
            [
                'label' => esc_html__('Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => $theInfoBoxAlignment,
                'default' => 'center',
				'toggle' => true,
                'selectors' => array(
					'{{WRAPPER}} .wadi_info_box_container' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
					'{{WRAPPER}} .wadi_info_box_container .wadi_info_box_graphic_media' => 'justify-content: {{VALUE}};',
				),
            ]
        );

        $this->add_responsive_control(
            'wadi_info_box_media_vertical_alignment',
            [
                'label' => esc_html__('Vertical Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options'     => array(
					'top'    => array(
						'title' => __( 'Top', 'wadi-addons' ),
						'icon'  => 'eicon-v-align-top',
					),
					'middle' => array(
						'title' => __( 'Middle', 'wadi-addons' ),
						'icon'  => 'eicon-v-align-middle',
					),
                    'bottom'    => array(
						'title' => __( 'Bottom', 'wadi-addons' ),
						'icon'  => 'eicon-v-align-bottom',
					),
				),
                'default' => 'top',
                'toggle' => true,
                'selectors_dictionary' => array(
                    'top'   => 'flex-start',
                    'middle' => 'center',
                    'bottom'  => 'flex-end',
                ),
                'selectors' => [
                    '{{WRAPPER}} .wadi_info_box_graphic_media' => 'align-items: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_info_box_media_position!' => 'wadi_media_above-content',
                    'wadi_info_box_media_position!' => 'wadi_media_below-content'
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_info_box_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_info_box_container',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_info_box_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_info_box_container',
			]
		);

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_info_box_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_info_box_container',
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
                'default' => [
                    'top' => '40',
                    'right' => '40',
                    'bottom' => '40',
                    'left' => '40',
                    'isLinked' => true,
                ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();


        $this->start_controls_section(
            'wadi_info_box_content_styling',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_control(
            'wadi_info_box_title_prefix_styling',
            [
                'label' => esc_html__( 'Prefix', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
			'wadi_info_box_title_prefix_tag',
			array(
				'label'     => __( 'Prefix Tag', 'wadi-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'  => __( 'H1', 'wadi-addons' ),
					'h2'  => __( 'H2', 'wadi-addons' ),
					'h3'  => __( 'H3', 'wadi-addons' ),
					'h4'  => __( 'H4', 'wadi-addons' ),
					'h5'  => __( 'H5', 'wadi-addons' ),
					'h6'  => __( 'H6', 'wadi-addons' ),
					'div' => __( 'div', 'wadi-addons' ),
					'p'   => __( 'p', 'wadi-addons' ),
				),
				'default'   => 'h5',
				'condition' => array(
					'wadi_info_box_title_prefix!' => '',
				),
			)
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_info_box_title_prefix_typography',
				'selector' => '{{WRAPPER}} .wadi_info_box_title_wrapper .wadi_info_box_title_prefix',
			]
		);

        $this->add_control(
			'wadi_info_box_prefix_color',
			[
				'label' => esc_html__( 'Prefix Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_title_prefix' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'wadi_info_box_prefix_color_hover',
			[
				'label' => esc_html__( 'Prefix Hover Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_title_prefix:hover' => 'color: {{VALUE}}',
				],
			]
		);

      
        $this->add_control(
            'wadi_info_box_title_styling',
            [
                'label' => esc_html__( 'Title', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
            ]
        );

        $this->add_control(
			'wadi_info_box_title_tag',
			array(
				'label'     => __( 'Prefix Tag', 'wadi-addons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'  => __( 'H1', 'wadi-addons' ),
					'h2'  => __( 'H2', 'wadi-addons' ),
					'h3'  => __( 'H3', 'wadi-addons' ),
					'h4'  => __( 'H4', 'wadi-addons' ),
					'h5'  => __( 'H5', 'wadi-addons' ),
					'h6'  => __( 'H6', 'wadi-addons' ),
					'div' => __( 'div', 'wadi-addons' ),
					'p'   => __( 'p', 'wadi-addons' ),
				),
				'default'   => 'h2',
				'condition' => array(
					'wadi_info_box_title!' => '',
				),
			)
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_info_box_title_typography',
				'selector' => '{{WRAPPER}} .wadi_info_box_title_wrapper .wadi_info_box_title',
			]
		);

        $this->add_control(
			'wadi_info_box_title_color',
			[
				'label' => esc_html__( 'Title Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_title' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'wadi_info_box_title_hover_color',
			[
				'label' => esc_html__( 'Title Hover Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_title:hover' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
            'wadi_info_box_content_container_styling',
            [
                'label' => esc_html__( 'Content Container', 'wadi-addons' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'wadi_info_box_content_container_width',
			[
				'label' => esc_html__( 'Width', 'wadi-addons' ),
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
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_responsive_control(
			'wadi_info_box_content_container_margin',
			[
				'label' => esc_html__( 'Content Container Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'wadi_info_box_content_container_padding',
			[
				'label' => esc_html__( 'Content Container Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_info_box_media_styling_section',
            [
                'label' => esc_html__('Media', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
			[
				'name' => 'wadi_info_box_media_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_info_box_media_image img, {{WRAPPER}} .wadi_info_box_icon',
			]
        );

        $this->add_responsive_control(
            'wadi_info_box_media_container_width',
			[
				'label' => esc_html__( 'Width', 'wadi-addons' ),
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
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_graphic_media' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
        );

        $this->add_responsive_control(
			'wadi_info_box_media_container_margin',
			[
				'label' => esc_html__( 'Media Container Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_graphic_media, {{WRAPPER}} .wadi_info_box_graphic_media wadi_info_box_icon_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_info_box_media_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_info_box_media_image img, {{WRAPPER}} .wadi_info_box_icon',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_info_box_media_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_info_box_media_image img, {{WRAPPER}} .wadi_info_box_icon',
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_media_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_media_image img, {{WRAPPER}} .wadi_info_box_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'wadi_info_box_media_adv_radius',
            array(
        'label'       => __('Advanced Border Radius', 'wadi-addons'),
        'type'        => Controls_Manager::SWITCHER,
        'description' => __('Apply custom radius values. Get the radius value from ', 'wadi-addons') . '<a href="https://9elements.github.io/fancy-border-radius/" target="_blank">here</a>',
    )
        );

        $this->add_control(
            'wadi_info_box_media_adv_radius_value',
            array(
        'label'     => __('Border Radius', 'wadi-addons'),
        'type'      => Controls_Manager::TEXT,
        'dynamic'   => array( 'active' => true ),
        'selectors' => array(
            '{{WRAPPER}} .wadi_info_box_media_image img , {{WRAPPER}} .wadi_info_box_icon' => 'border-radius: {{VALUE}};',
        ),
        'condition' => array(
            'wadi_info_box_media_adv_radius' => 'yes',
        ),
    )
        );

        $this->add_responsive_control(
			'wadi_info_box_media_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_media_image img, {{WRAPPER}} .wadi_info_box_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_media_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_media_image img, {{WRAPPER}} .wadi_info_box_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_info_box_cta_styling_section',
            [
                'label' => esc_html__('Call To Action', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ],
            ]
        );

        $infoBoxLinkAlignment = [
            'left'   => array(
                'title' => __('Left', 'wadi-addons'),
                'icon'  => 'eicon-text-align-left',
            ),
            'center' => array(
                'title' => __('Center', 'wadi-addons'),
                'icon'  => 'eicon-text-align-center',
            ),
            'right'  => array(
                'title' => __('Right', 'wadi-addons'),
                'icon'  => 'eicon-text-align-right',
            ),
        ];

        $rtl_infoBoxLinkAlignment = array_reverse($infoBoxLinkAlignment);

        $theInfoBoxLinkAlignment = !is_rtl()? $infoBoxLinkAlignment :  $rtl_infoBoxLinkAlignment;

        $this->add_responsive_control(
            'wadi_info_box_alignment_link',
            [
                'label' => esc_html__('Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => $theInfoBoxLinkAlignment,
                'default' => 'center',
				'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .wadi_info_box_cta' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
				),
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'selector' => '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link',
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ]
			]
		);

        $this->add_control(
            'wadi_info_box_cta_color',
            [
                'label' => esc_html__( 'Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link' => 'color: {{VALUE}}',
				],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ]
            ]
        );

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wadi_info_box_cta_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link',
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ]
			]
		);

        // $this->add_responsive_control(
		// 	'wadi_info_box_cta_width',
		// 	[
		// 		'label' => esc_html__( 'Width', 'wadi-addons' ),
		// 		'type' => \Elementor\Controls_Manager::SLIDER,
		// 		'size_units' => [ 'px', '%' ],
		// 		'range' => [
		// 			'px' => [
		// 				'min' => 0,
		// 				'max' => 1000,
		// 				'step' => 5,
		// 			],
		// 			'%' => [
		// 				'min' => 0,
		// 				'max' => 100,
		// 			],
		// 		],
		// 		'default' => [
		// 			'unit' => '%',
		// 			'size' => 50,
		// 		],
		// 		'selectors' => [
		// 			'{{WRAPPER}} .wadi_info_box_cta' => 'width: {{SIZE}}{{UNIT}};',
		// 		],
        //         'condition' => [
        //             'wadi_info_box_cta_types' => 'link',
        //             'wadi_info_box_cta_switch' => 'yes'
        //         ]
		// 	]
		// );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_info_box_cta_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link',
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ]
			]
		);

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_info_box_cta_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link',
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ]
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_cta_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ]
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_cta_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_cta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ]
			]
		);

        $this->add_responsive_control(
			'wadi_info_box_cta_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes'
                ]
			]
		);

        // Link Icon Styling Heading
        
        $this->add_control(
            'wadi_info_box_cta_link_icon_heading',
            [
                'label' => esc_html__( 'Link Icon Styling', 'wadi-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'wadi_info_box_cta_link_icon_color',
            [
                'label' => esc_html__( 'Color', 'wadi-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon svg, {{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon svg >*' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_info_box_cta_link_icon_size',
            [
                'label' => esc_html__( 'Size', 'wadi-addons' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
            ]
        );

        // Link Icon Styling background color

        $this->add_control(
            'wadi_info_box_cta_link_icon_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'wadi-addons' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
            ]
        );

        // Link Icon Styling Border Radius
        $this->add_control(
            'wadi_info_box_cta_link_icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
            ]
        );


        // Icon Border
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_info_box_cta_link_icon_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon',
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
			]
		);

        // Icon Margin
        $this->add_responsive_control(
			'wadi_info_box_cta_link_icon_margin',
			[
				'label'      => __( 'Margin', 'wadi-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
			]
		);

        // Icon Padding
        $this->add_responsive_control(
			'wadi_info_box_cta_link_icon_padding',
			[
				'label'      => __( 'Padding', 'wadi-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .wadi_info_box_cta .wadi_info_box_link .wadi_cta_link_icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_info_box_cta_types' => 'link',
                    'wadi_info_box_cta_switch' => 'yes',
                    'wadi_info_box_cta_link_icon_switch' => 'yes'
                ]
			]
		);


        $this->end_controls_section();
    }

    /**
	 * Render Separator.
	 *
	 * @since 1.1.0
	 * @access protected
	 * @param object $settings for settings.
	 */
	protected function render_separator( $settings ) {
		if ( 'yes' === $settings['wadi_info_box_separator_switch'] ) {
			?>
			<div class="wadi_info_box_separator">
				<div class="info_box_separator"></div>
			</div>
			<?php
		}
	}

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        if ( ! empty( $settings['wadi_info_box_link_url']['url'] ) ) {
			$this->add_link_attributes( 'wadi_info_box_link', $settings['wadi_info_box_link_url'] );
		}
        $migration_allowed = Icons_Manager::is_migration_allowed();

        if ('icon' === $settings['wadi_info_box_graphic_element']) {
            $this->add_render_attribute('selected_icon', 'class', 'wadi_info_box_icon_wrapper');

            if (! isset($settings['icon']) && ! $migration_allowed) {
                // add old default
                $settings['icon'] = 'fa fa-star';
            }

            if (! empty($settings['icon'])) {
                $this->add_render_attribute('wadi_icon_info_box', 'class', $settings['icon']);
            }
        }

        $has_icon = ! empty($settings['icon']) || ! empty($settings['selected_icon']);
        $migrated_icon = isset($settings['__fa4_migrated']['selected_icon']);
        $is_new_icon = empty($settings['icon']) && $migration_allowed;
        

        // Cta Link Icon

        
        if ('yes' === $settings['wadi_info_box_cta_link_icon_switch']) {
            $this->add_render_attribute('cta_link_icon', 'class', 'wadi_info_box_cta_link_wrapper');

            if (! isset($settings['cta_icon']) && ! $migration_allowed) {
                // add old default
                $settings['cta_icon'] = 'fa fa-star';
            }

            if (! empty($settings['cta_icon'])) {
                $this->add_render_attribute('cta_link_icon', 'class', $settings['cta_icon']);
            }
        }

            $cta_has_icon = ! empty($settings['cta_icon']) || ! empty($settings['cta_link_icon']);
            $cta_migrated_icon = isset($settings['__fa4_migrated']['cta_link_icon']);
            $cta_is_new_icon = empty($settings['cta_icon']) && $migration_allowed;
            

        // Hover Animation

		$animation_class = '';

        if ( 'icon' === $settings['wadi_info_box_graphic_element'] || 'image' === $settings['wadi_info_box_graphic_element'] && isset($settings['wadi_info_box_media_animation']) ) {
            $animation_class = 'elementor-animation-' . $settings['wadi_info_box_media_animation'];
        }

        // Alignment

        $info_box_media_position = $settings['wadi_info_box_media_position'];
        ?>

        <div class="wadi_info_box_container <?php echo $info_box_media_position; ?>">
            <?php if( 'yes' === $settings['wadi_info_box_cta_switch'] ) : ?>

            <?php if('whole_box' === $settings['wadi_info_box_cta_types'] ) : ?>
                            <a <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_info_box_link' ) ); ?> class="wadi_info_box_whole_box_link"></a>
                <?php endif; ?>
            <?php endif; ?>

            <?php

			$prefix_size_tag = WadiHelpers::validate_tags( $settings['wadi_info_box_title_prefix_tag'] );
			$title_size_tag = WadiHelpers::validate_tags( $settings['wadi_info_box_title_tag'] );


            /**
             * Inline Editing Code
             */

            $this->add_inline_editing_attributes( 'wadi_info_box_title_prefix' );
            $this->add_render_attribute( 'wadi_info_box_title_prefix', 'class', 'wadi_info_box_title_prefix' );

            $this->add_inline_editing_attributes( 'wadi_info_box_title' );
            $this->add_render_attribute( 'wadi_info_box_title', 'class', 'wadi_info_box_title' );

            $this->add_inline_editing_attributes( 'wadi_info_box_description', 'advanced' );
            $this->add_render_attribute( 'wadi_info_box_description', 'class', 'wadi_info_box_description_text' );

            $this->add_inline_editing_attributes( 'wadi_info_box_link_text');
            $this->add_render_attribute( 'wadi_info_box_link_text', 'class', 'wadi_info_box_link_text' );

            
        if ('yes' === $settings['wadi_info_box_graphic_element_switch'] && !empty($settings['wadi_info_box_graphic_element_switch']) && 'none' !== $settings['wadi_info_box_graphic_element']):
            
            $this->add_render_attribute(
                'wadi_info_box_graphic_media',
                [
                    'class' => ['wadi_info_box_graphic_media'],
                ]
            );

            if('yes' === $settings['wadi_info_box_media_responsive_hidden_on_desktop']) {
                $this->add_render_attribute('wadi_info_box_graphic_media', 'class', 'wadi_info_box_media_responsive_hidden_on_desktop');
            }

            if('yes' === $settings['wadi_info_box_media_responsive_hidden_on_tablet']) {
                $this->add_render_attribute('wadi_info_box_graphic_media', 'class', 'wadi_info_box_media_responsive_hidden_on_tablet');
            }

            if('yes' === $settings['wadi_info_box_media_responsive_hidden_on_mobile']) {
                $this->add_render_attribute('wadi_info_box_graphic_media', 'class', 'wadi_info_box_media_responsive_hidden_on_mobile');
            }
            
            ?>
            <div <?php echo wp_kses_post($this->get_render_attribute_string('wadi_info_box_graphic_media')); ?>>
                <?php
                if ('image' === $settings['wadi_info_box_graphic_element'] && ! empty($settings['image']['url'])) :
                ?>
                    <div class="wadi_info_box_media wadi_info_box_media_image  <?php echo esc_attr( $animation_class ); ?>">
                        <?php echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html($settings, 'image_size_wadi_info_box', 'image')); ?>
                    </div>
                    <?php
                    elseif ('icon' === $settings['wadi_info_box_graphic_element'] && $has_icon) : ?>
                        <div <?php $this->print_render_attribute_string('selected_icon'); ?>>
                            <div class="wadi_info_box_icon  <?php echo esc_attr( $animation_class ); ?>">
                                <?php if ($is_new_icon || $migrated_icon) {
                                    Icons_Manager::render_icon($settings['selected_icon']); 
                                 } else { ?>
                                    <i class="<?php echo esc_attr($settings['icon']); ?>"></i>
                                <?php }; ?>
                            </div>
                        </div>
                    <?php endif; ?>
            </div>
            <?php if( 'after_media' === $settings['wadi_info_box_separator_position']) : ?>
                        <?php $this->render_separator($settings); ?>    
            <?php endif; ?>

        <?php endif; ?>
            <div class="wadi_info_box_content">
                <div class="wadi_info_box_title_wrapper">
                    <?php if (!empty($settings['wadi_info_box_title_prefix'])) : ?>
                        <<?php echo esc_attr($prefix_size_tag)  . ' '. wp_kses_post($this->get_render_attribute_string('wadi_info_box_title_prefix')); ?>>
                            <?php echo wp_kses_post($settings['wadi_info_box_title_prefix']); ?>
                        </<?php echo esc_attr($prefix_size_tag) ?>>
                    <?php endif; ?>
                    <?php if( 'after_prefix' === $settings['wadi_info_box_separator_position']) : ?>
                        <?php $this->render_separator($settings); ?>    
                    <?php endif; ?>
                    <?php if (!empty($settings['wadi_info_box_title'])) : ?>
                        <<?php echo esc_attr($title_size_tag) . ' '. wp_kses_post($this->get_render_attribute_string('wadi_info_box_title'));  ?>>
                            <?php echo wp_kses_post($settings['wadi_info_box_title']); ?>
                        </<?php echo esc_attr($title_size_tag) ?>>
                    <?php endif; ?>
                    <?php if( 'after_title' === $settings['wadi_info_box_separator_position']) : ?>
                        <?php $this->render_separator($settings); ?>    
                    <?php endif; ?>
                </div>
                <?php if (!empty($settings['wadi_info_box_description'])) : ?>
                    <div <?php echo wp_kses_post($this->get_render_attribute_string('wadi_info_box_description')); ?>>
                        <?php echo wp_kses_post($this->parse_text_editor($settings['wadi_info_box_description'])); // phpcs:ignore CSRF ok.?>
                    </div>
                <?php endif; ?>
                <?php if( 'after_description' === $settings['wadi_info_box_separator_position']) : ?>
                        <?php $this->render_separator($settings); ?>    
                <?php endif; ?>
                <?php if( 'yes' === $settings['wadi_info_box_cta_switch'] ) : ?>
                    <div class="wadi_info_box_cta">
                        <?php if('link' === $settings['wadi_info_box_cta_types'] ) : ?>
                            <a  <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_info_box_link' ) ); ?> class="wadi_info_box_link">
                                <?php
                                if( 'yes' === $settings['wadi_info_box_cta_link_icon_switch'] && 'before' === $settings['wadi_info_box_cta_link_icon_position']  ) :
                                
                                ?>
                                <div <?php echo wp_kses_post($this->get_render_attribute_string('cta_link_icon')); ?>>
                                    <div class="wadi_cta_link_icon">
                                        <?php if ($cta_is_new_icon || $cta_migrated_icon) {
                                            Icons_Manager::render_icon($settings['cta_link_icon']); 
                                        } else { ?>
                                            <i class="<?php echo esc_attr($settings['cta_icon']); ?>"></i>
                                        <?php }; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                                <span <?php echo $this->get_render_attribute_string('wadi_info_box_link_text'); ?>><?php echo wp_kses_post($settings['wadi_info_box_link_text']); ?></span>
                                <?php
                                if( 'yes' === $settings['wadi_info_box_cta_link_icon_switch'] && 'after' === $settings['wadi_info_box_cta_link_icon_position']  ) :
                                
                                ?>
                                <div <?php echo wp_kses_post($this->get_render_attribute_string('cta_link_icon')); ?>>
                                    <div class="wadi_cta_link_icon">
                                        <?php if ($cta_is_new_icon || $cta_migrated_icon) {
                                            Icons_Manager::render_icon($settings['cta_link_icon']); 
                                        } else { ?>
                                            <i class="<?php echo esc_attr($settings['cta_icon']); ?>"></i>
                                        <?php }; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Render Info Box widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.1.0
     * @access protected
     */
    protected function content_template()
    {
        ?>
        <# function render_separator() {
            if ( 'yes' === settings.wadi_info_box_separator_switch ) { #>
                <div class="wadi_info_box_separator">
                    <div class="info_box_separator"></div>
                </div>
            <# }
        }

        
        /**
        // Classes for Elements
        view.addRenderAttribute( 'wadi_info_box_title_prefix', 'class', 'wadi_info_box_title_prefix' );
        view.addRenderAttribute( 'wadi_info_box_title', 'class', 'wadi_info_box_title' );
        view.addRenderAttribute( 'wadi_info_box_description', 'class', 'wadi_info_box_description_text' );
        view.addRenderAttribute( 'wadi_info_box_link_text', 'class', 'wadi_info_box_link_text' );
         * Inline Editing JS Rendering
         */
        view.addInlineEditingAttributes('wadi_info_box_title_prefix');
        view.addInlineEditingAttributes('wadi_info_box_title');
        view.addInlineEditingAttributes('wadi_info_box_description',  'basic');
        view.addInlineEditingAttributes('wadi_info_box_link_text');



        /**
         * Validate HTML Tags
         */
        var prefixSizeTag = elementor.helpers.validateHTMLTag( settings.wadi_info_box_title_prefix_tag );
        var titleSizeTag = elementor.helpers.validateHTMLTag( settings.wadi_info_box_title_tag );

        /**
         * Alignment
         */

        var mediaPosition = settings.wadi_info_box_media_position;

        /**
         * Icon JS Rendering
         */

        var iconHTML = elementor.helpers.renderIcon(view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object'),
            migrated = elementor.helpers.isIconMigrated(settings, 'selected_icon' );

        /**
         * CTA Link Icon JS Rendering
         */

         if('yes' === settings.wadi_info_box_cta_link_icon_switch) {
         
            view.addRenderAttribute( 'cta_link_icon', 'class', 'wadi_info_box_cta_link_wrapper' );

            
        var ctaIconHTML = elementor.helpers.renderIcon(view, settings.cta_link_icon, { 'aria-hidden': true }, 'i' , 'object'),
            ctaIconMigrated = elementor.helpers.isIconMigrated(settings, 'cta_link_icon' );
        }

        /**
         * Hover Animation
         */

        var animationClass = '';

        if ( 'icon' === settings.wadi_info_box_graphic_element || 'image' === settings.wadi_info_box_graphic_element && 'none' !== settings.wadi_info_box_graphic_element ) {
            animationClass = 'elementor-animation-' + settings.wadi_info_box_media_animation;
        }

        // Start Rendering HTML JS Backbone
        #>

        <div class="wadi_info_box_container {{mediaPosition}}">
            <# if ( 'yes' === settings.wadi_info_box_cta_switch ) { #>
                <# if('whole_box' === settings.wadi_info_box_cta_types ) { #>
                    <a href={{settings.wadi_info_box_link_url.url}} class="wadi_info_box_whole_box_link"></a>
                <# } #>
            <# } #>


            <#
            if ('yes' === settings.wadi_info_box_graphic_element_switch && "" !== settings.wadi_info_box_graphic_element_switch && 'none' !== settings.wadi_info_box_graphic_element ) { 
                
                view.addRenderAttribute( 'wadi_info_box_graphic_media', 'class', 'wadi_info_box_graphic_media' );

                if( 'yes' === settings.wadi_info_box_media_responsive_hidden_on_desktop ) {
                    view.addRenderAttribute( 'wadi_info_box_graphic_media', 'class', 'wadi_info_box_media_responsive_hidden_on_desktop' );
                }

                if( 'yes' === settings.wadi_info_box_media_responsive_hidden_on_tablet ) {
                    view.addRenderAttribute( 'wadi_info_box_graphic_media', 'class', 'wadi_info_box_media_responsive_hidden_on_tablet' );
                }

                if( 'yes' === settings.wadi_info_box_media_responsive_hidden_on_mobile ) {
                    view.addRenderAttribute( 'wadi_info_box_graphic_media', 'class', 'wadi_info_box_media_responsive_hidden_on_mobile' );
                }
                
                
                #>
                <div {{{view.getRenderAttributeString( 'wadi_info_box_graphic_media' )}}} >
                    <# if ('image' === settings.wadi_info_box_graphic_element && "" !== settings.image.url ) { #>
                        <div class="wadi_info_box_media wadi_info_box_media_image {{animationClass}}">
                            <img src="{{settings.image.url}}" />
                        </div>
                    <# } else if ('icon' === settings.wadi_info_box_graphic_element) {
                        view.addRenderAttribute( 'selected_icon', 'class', 'wadi_info_box_icon_wrapper' );
                        #>
                        <div {{{view.getRenderAttributeString( 'selected_icon' )}}} >
                            <div class="wadi_info_box_icon {{animationClass}}">
                            <# if ( iconHTML && iconHTML.rendered && ( ! settings.icon || migrated ) ) { #>
                                {{{ iconHTML.value }}}
                            <# } else { #>
                                <i class="{{ settings.icon }}" aria-hidden="true"></i>
                            <# } #>
                            </div>
                        </div>
                        <# } #> 
                </div>

                <# if ( 'after_media' === settings.wadi_info_box_separator_position) {
                        render_separator()
                    }
                }
            #>
            <div class="wadi_info_box_content">
                <div class="wadi_info_box_title_wrapper">
                    <# if("" !== settings.wadi_info_box_title_prefix) { #>
                        <{{ prefixSizeTag }} {{{ view.getRenderAttributeString( 'wadi_info_box_title_prefix' ) }}}>
                            {{{ settings.wadi_info_box_title_prefix }}}
                        </{{ prefixSizeTag }}>
                    <# } #>
                    <# if( 'after_prefix' === settings.wadi_info_box_separator_position) {
                        render_separator();
                    } #>
                    <# if("" !== settings.wadi_info_box_title) { #>
                        <{{ titleSizeTag }} {{{ view.getRenderAttributeString( 'wadi_info_box_title' ) }}}>
                            {{{ settings.wadi_info_box_title }}}
                        </{{ titleSizeTag }}>
                    <# } #>
                    <# if( 'after_title' === settings.wadi_info_box_separator_position) {
                        render_separator();
                    } #>
                </div>
                    <# if("" !== settings.wadi_info_box_description) { #>
                        <div {{{ view.getRenderAttributeString( 'wadi_info_box_description' ) }}}>
                            {{{ settings.wadi_info_box_description }}}
                    </div>
                    <# } #>
                    <# if( 'after_description' === settings.wadi_info_box_separator_position) {
                        render_separator();
                    } #>
                    <# if('yes' === settings.wadi_info_box_cta_switch) { #>
                        <div class="wadi_info_box_cta">
                            <# if('link' === settings.wadi_info_box_cta_types ) { #>
                                <a href={{settings.wadi_info_box_link_url.url}} class="wadi_info_box_link">
                                <#
                                if( 'yes' === settings.wadi_info_box_cta_link_icon_switch && 'before' === settings.wadi_info_box_cta_link_icon_position  ) {
                                    #>
                                    <div {{{view.getRenderAttributeString('cta_link_icon')}}} >
                                        <div class="wadi_cta_link_icon">
                                        <# if ( ctaIconHTML && ctaIconHTML.rendered && ( ! settings.cta_icon || ctaIconMigrated ) ) { #>
                                            {{{ ctaIconHTML.value }}}
                                        <# } else { #>
                                            <i class="{{ settings.cta_icon }}" aria-hidden="true"></i>
                                        <# } #>
                                        </div>
                                    </div>
                                <#} #>
                                    <span {{{view.getRenderAttributeString('wadi_info_box_link_text')}}}>{{{settings.wadi_info_box_link_text}}}</span>
                                    <#
                                if( 'yes' === settings.wadi_info_box_cta_link_icon_switch && 'after' === settings.wadi_info_box_cta_link_icon_position  ) {
                                    #>
                                    <div {{{view.getRenderAttributeString('cta_link_icon')}}} >
                                        <div class="wadi_cta_link_icon">
                                        <# if ( ctaIconHTML && ctaIconHTML.rendered && ( ! settings.cta_icon || ctaIconMigrated ) ) { #>
                                            {{{ ctaIconHTML.value }}}
                                        <# } else { #>
                                            <i class="{{ settings.cta_icon }}" aria-hidden="true"></i>
                                        <# } #>
                                        </div>
                                    </div>
                                <#} #>
                                </a>
                            <# } #>
                        </div>
                    <# } #>

            </div>
        </div>

        
        <?php

    }
}
