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

class Wadi_Testimonial extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-testimonial-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Testimonial', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-testimonial-wadi';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        if(!is_rtl()) {
            wp_register_style('style-handle_testimonial', WADI_ADDONS_URL . 'assets/min/wadi-testimonial.css');
        } else {
            wp_register_style('style-handle_testimonial', WADI_ADDONS_URL . 'assets/min/wadi-testimonial.rtl.css');
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
        return [ 'wadi-addons','wadi', 'testimonials' ];
    }

    public function get_style_depends()
    {
        return [ 'style-handle_testimonial' ];
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
            'wadi_testimonial_settings',
            [
                'label' => esc_html__('Testimonial Settings', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
                $this->add_control(
                    'wadi_testimonial_skin',
                    [
                        'label' => esc_html__( 'Skin', 'wadi-addons' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'default',
                        'options' => [
                            'default' => esc_html__( 'Default', 'wadi-addons' ),
                            'bubble' => esc_html__( 'Bubble', 'wadi-addons' ),
                        ],
                        'prefix_class' => 'wadi_testimonial_skin--',
                        'render_type' => 'template',
                    ]
                );
        
                $this->add_control(
                    'wadi_testimonial_layout',
                    [
                        'label' => esc_html__( 'Layout', 'wadi-addons' ),
                        'type' => Controls_Manager::SELECT,
                        'default' => 'image_inline',
                        'options' => [
                            'image_inline' => esc_html__( 'Image Inline', 'wadi-addons' ),
                            'image_stacked' => esc_html__( 'Image Stacked', 'wadi-addons' ),
                            'image_above' => esc_html__( 'Image Above', 'wadi-addons' ),
                            'image_left' => esc_html__( 'Image Left', 'wadi-addons' ),
                            'image_right' => esc_html__( 'Image Right', 'wadi-addons' ),
                        ],
                        'prefix_class' => 'wadi_testimonial_layout--',
                        'render_type' => 'template',
                    ]
                );
                
                                                          
        $testimonial_alignments = [
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

        $rtl_testimonial_alignments = array_reverse($testimonial_alignments);

        $the_testimonial_alignments = is_rtl() ? $rtl_testimonial_alignments : $testimonial_alignments;
        
        
                $this->add_responsive_control(
                    'wadi_testimonial_alignment',
                    [
                        'label' => esc_html__( 'Alignment', 'wadi-addons' ),
                        'type' => Controls_Manager::CHOOSE,
                        'default' => 'center',
                        'options' => $the_testimonial_alignments,
                        'prefix_class' => 'wadi_testimonial_alignment--',
                        'selectors_dictionary' => [
                            'left' => 'left',
                            'center' => 'center',
                            'right' => 'right',
                        ],
                        'selectors' => [
                            '{{WARPPER}} .wadi_testimonial_container' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_testimonial_content',
            [
                'label' => esc_html__('Testimonial', 'wadi-addons'),
                'type' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'wadi_testimonial_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `wadi_testimonial_image_size` and `wadi_testimonial_image_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

        $this->add_control(
			'wadi_testimonial_image',
			[
				'label' => esc_html__( 'Choose Image', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

        $this->add_control(
            'wadi_testimonial_content_text',
            [
                'label' => esc_html__('Testimonail Text', 'wadi-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'default' => esc_html__( 'Donec id elit non mi porta gravida at eget metus. Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Cras mattis consectetur purus sit amet fermentum. Nullam id dolor id nibh ultricies vehicula ut id elit. Donec id elit non mi porta gravida at eget metus.', 'wadi-addons' ),
				'placeholder' => esc_html__( 'Testimonail Text', 'wadi-addons' ),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'wadi_testimonial_writer_text',
            [
                'label' => esc_html__('Name', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Name of the testimonial provider', 'wadi-addons'),
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html('John Doe'),
            ]
        );

        $this->add_control(
            'wadi_testimonial_cite_separator',
            [
                'label' => esc_html__('Separator', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Separator between Name of testimonial writer and organization/position.', 'wadi-addons'),
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html('—'),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'wadi_testimonial_layout',
                                    'operator' => '!==',
                                    'value' => 'image_right'
                                ],
                                [
                                    'name' => 'wadi_testimonial_layout',
                                    'operator' => '!==',
                                    'value' => 'image_left'
                                ],
                                [
                                    'name' => 'wadi_testimonial_skin',
                                    'value' => 'bubble',
                                ]
                            ]
                        ],
                        [
                            'terms' => [
                                [
                                    'name' => 'wadi_testimonial_skin',
                                    'operator' => '===',
                                    'value' => 'default',
                                ]
                            ]
                        ],
                    ]
                ]
            ]
        );

        $this->add_control(
            'wadi_testimonial_organization',
            [
                'label' => esc_html__('Organization', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('The Organization and/or the testimonial provider position within the organization', 'wadi-addons'),
                'dynamic' => [
                    'active' => true,
                ],
                'default' => esc_html('XYZ, CEO'),
            ]
        );

        $this->add_control(
			'wadi_testimonial_link',
			[
				'label' => esc_html__( 'Link', 'wadi-addons' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'wadi-addons' ),
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_addons-content',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wadi_testimonial_content_size',
            [
                'label' => esc_html__('Content Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px','rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
						'step' => 5,
					],
                    'rem' => [
                        'min' => 0,
                        'max' => 200,
                    ],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_review_content' => 'width: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->add_control(
            'wadi_testimonial_color_text',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'description' => esc_html__('Testimonial Content Text Color', 'wadi-addons'),
                'selectors' => [
                    '{{WRAPPER}} .wadi_testimonial_text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_testimonial_background_text',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_testimonial_text',
			]
		);

        $this->add_control(
			'wadi_testimonial_background_text_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'wadi_testimonial_background_text_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
			'wadi_testimonial_background_text_border-radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_style_image',
            [
                'label' => esc_html__('Image', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wadi_testimonial_image_sizing',
            [
                'label' => esc_html__('Image Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px','rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
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
				'default' => [
					'unit' => 'px',
					'size' => 180,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_image img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->add_control(
			'wadi_testimonial_image_border-radius',
			[
				'label' => esc_html__( 'Image Border Radius', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'wadi_testimonial_image_margin',
			[
				'label' => esc_html__( 'Image Margin', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'wadi_testimonial_image_padding',
			[
				'label' => esc_html__( 'Image Padding', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_testimonial_image_border_width',
				'label' => esc_html__( 'Image Border Width', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_image img',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_testimonial_style_footer',
            [
                'label' => esc_html__('Testimonial Info', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_testimonial_cite_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_cite',
			]
		);
        
        $this->add_control(
			'wadi_testimonial_cite_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_cite' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'wadi_testimonial_cite_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_cite' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_testimonial_cite_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_cite',
			]
		);

        $this->add_control(
			'wadi_testimonial_cite_border-radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_footer .wadi_testimonial_cite' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'wadi_testimonial_writer_heading',
            [
                'label' => esc_html__('Name', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        

        $this->add_control(
            'wadi_testimonial_writer_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_writer' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_testimonial_writer_content_typography',
				'selector' => '{{WRAPPER}} .wadi_testimonial_writer',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_testimonial_writer_content_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_testimonial_writer',
			]
		);

        $this->add_control(
            'wadi_testimonial_separator_heading',
            [
                'label' => esc_html__('Separator', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'wadi_testimonial_separator_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_cite_separator' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_control(
            'wadi_testimonial_separator_typography',
            [
                'label' => esc_html__('Separator Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px','rem', '%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
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
				'default' => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_cite_separator' => 'font-size: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->add_control(
            'wadi_testimonial_organization_heading',
            [
                'label' => esc_html__('Organization', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wadi_testimonial_organization_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container .wadi_testimonial_cite .wadi_testimonial_organization' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_testimonial_organization_typography',
				'selector' => '{{WRAPPER}} .wadi_testimonial_organization',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_testimonial_organization_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_testimonial_organization',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_testimonial_container_style',
            [
                'label' => esc_html__('Container', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_testimonial_container_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_testimonial_container',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_testimonial_container_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_testimonial_container',
			]
		);

        $this->add_control(
			'wadi_testimonial_container_border-radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_testimonial_container_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_testimonial_container',
			]
		);
        $this->add_control(
			'wadi_testimonial_container_margin',
			[
				'label' => esc_html__( 'margin', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'wadi_testimonial_container_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_testimonial_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

    }

    private function testimonialCite($slide, $location, $name_attr, $org_attr) {
        if ( empty( $slide['wadi_testimonial_writer_text'] ) && empty( $slide['wadi_testimonial_organization'] ) ) {
			return '';
		}

        $skin = $this->get_settings( 'wadi_testimonial_skin' );
		$layout = 'bubble' === $skin ? 'image_inline' : $this->get_settings( 'wadi_testimonial_layout' );
		$locations_outside = [ 'image_above', 'image_right', 'image_left' ];
		$locations_inside = [ 'image_inline', 'image_stacked' ];

		$print_outside = ( 'outside' === $location && in_array( $layout, $locations_outside ) );
		$print_inside = ( 'inside' === $location && in_array( $layout, $locations_inside ) );
        
		$html = '';
        if ($print_outside || $print_inside) {
            $html .= '<div class="wadi_testimonial_cite">';
            if (!empty($slide['wadi_testimonial_writer_text'])) :
                $html .= '<div class="wadi_testimonial_writer">';
            $html .= '<div '. $this->get_render_attribute_string($name_attr) .'>'. $slide['wadi_testimonial_writer_text'] .'</div>';
            $html .= '</div>';
            endif;
            if (!empty($slide['wadi_testimonial_cite_separator'])) :
                $html .= '<span class="wadi_testimonial_cite_separator">'.$slide['wadi_testimonial_cite_separator'] .'</span>';
            endif;
            if (!empty($slide['wadi_testimonial_organization'])) :
                $html .= '<div class="wadi_testimonial_organization">';
            $html .= '<div '. wp_kses_post( $this->get_render_attribute_string( $org_attr ) ) .'>'. $slide['wadi_testimonial_organization'] .'</div>';
            $html .= '</div>';
            endif;
            $html .= '</div>';
        }
        echo wp_kses_post($html);
        
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();



        if ( ! empty( $settings['wadi_testimonial_link']['url'] ) ) {
			$this->add_link_attributes( 'link', $settings['wadi_testimonial_link'] );
		}
        
		$this->add_inline_editing_attributes( 'wadi_testimonial_content_text', 'advanced' );
        $this->add_inline_editing_attributes( 'wadi_testimonial_writer_text' );
		$this->add_inline_editing_attributes( 'wadi_testimonial_organization' );

        
        ?>
            <div class="wadi_testimonial_container">
                <div class="wadi_testimonial_review_content">
                    <div class="wadi_testimonial_text" <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_testimonial_content_text' ) ); ?>>
                        <?php echo $this->parse_text_editor( $settings['wadi_testimonial_content_text'] ); // phpcs:ignore CSRF ok. ?>    
                    </div>
                    <?php $this->testimonialCite($settings, 'outside', 'wadi_testimonial_writer_text', 'wadi_testimonial_organization'); ?>
                </div>
                <div class="wadi_testimonial_footer">
                    <?php if(!empty($settings['wadi_testimonial_image']['url'])) : ?>
                    <div class="wadi_testimonial_image">
                        <?php 
                            $image_html = \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'wadi_testimonial_image' );
                            if ( ! empty( $settings['wadi_testimonial_link']['url'] ) ) :
								$image_html = '<a ' . $this->get_render_attribute_string( 'link' ) . '>' . $image_html . '</a>';
							endif;
							echo wp_kses_post( $image_html );
                        ?>
                    </div>
                    <?php endif; ?>
                    <?php $this->testimonialCite($settings, 'inside', 'wadi_testimonial_writer_text', 'wadi_testimonial_organization'); ?>
                </div>
            </div>
        <?php
    }

    protected function content_template()
    {
        ?>

            <#
			view.addInlineEditingAttributes('wadi_testimonial_content_text', 'advanced');
			view.addInlineEditingAttributes('wadi_testimonial_writer_text');
            view.addInlineEditingAttributes('wadi_testimonial_organization');

            var image = {
				id: settings.wadi_testimonial_image.id,
				url: settings.wadi_testimonial_image.url,
				size: settings.wadi_testimonial_image_size,
				dimension: settings.wadi_testimonial_image_custom_dimension,
				model: view.getEditModel()
			};

            var imageUrl = false, hasImage = '';

            var skin = settings.wadi_testimonial_skin;
            var layout = 'bubble' == skin ? 'image_inline' : settings.wadi_testimonial_layout;
            var locations_outside = [ 'image_above', 'image_right', 'image_left' ];
            var locations_inside = [ 'image_inline', 'image_stacked' ];

                    
		if ( '' !== settings.wadi_testimonial_image.url ) {
			imageUrl = elementor.imagesManager.getImageUrl( image );
			hasImage = ' elementor-has-image';

			var imageHtml = '<img src="' + imageUrl + '" alt="testimonial" />';
			if ( settings.wadi_testimonial_link.url ) {
				imageHtml = '<a href="' + settings.wadi_testimonial_link.url + '">' + imageHtml + '</a>';
			}
		}
        
            #>

            <div class="wadi_testimonial_container">
                <div class="wadi_testimonial_review_content">
                    <div {{{ view.getRenderAttributeString('wadi_testimonial_content_text') }}} >{{{settings.wadi_testimonial_content_text}}}</div>
                    <#

                        if( locations_outside.includes(layout) ) {
                        
                    #>
                    <div class="wadi_testimonial_cite">
                        <#
                            if('' != settings.wadi_testimonial_writer_text) {
                                #>
                                <div class="wadi_testimonial_writer">
                                    <div {{{ view.getRenderAttributeString('wadi_testimonial_writer_text') }}}>{{{settings.wadi_testimonial_writer_text}}}</div>
                                </div>
                                <#
                                if('' != settings.wadi_testimonial_cite_separator) {
                                    #>
                                    <span class="wadi_testimonial_cite_separator">
                                        {{{settings.wadi_testimonial_cite_separator}}}    
                                    </span>
                                    <#
                                }

                                if('' != settings.wadi_testimonial_organization) {
                                    #>
                                    <div class="wadi_testimonial_organization">
                                        <div {{{ view.getRenderAttributeString('wadi_testimonial_organization') }}}>
                                            {{{settings.wadi_testimonial_organization}}}
                                        </div>
                                    </div>
                                    <#
                                }
                            }
                        #>
                    </div>
                    <# } #>
                </div>
                    <div class="wadi_testimonial_footer">
                        <#
                            if('' != imageHtml) {
                                #>
                                <div class="wadi_testimonial_image">
                                    {{{imageHtml}}}
                                </div>

                                <#
                            }

                            if( locations_inside.includes(layout)) {
                        
                        #>
                        <div class="wadi_testimonial_cite">
                            <#
                                if('' !== settings.wadi_testimonial_writer_text) {
                                    #>
                                    <div class="wadi_testimonial_writer">
                                        <div {{{ view.getRenderAttributeString('wadi_testimonial_writer_text') }}}>{{{settings.wadi_testimonial_writer_text}}}</div>
                                    </div>
                                    <#
                                    if('' !== settings.wadi_testimonial_cite_separator) {
                                        #>
                                        <span class="wadi_testimonial_cite_separator">
                                            {{{settings.wadi_testimonial_cite_separator}}}    
                                        </span>
                                        <#
                                    }
                                    if('' !== settings.wadi_testimonial_organization) {
                                        #>
                                        <div class="wadi_testimonial_organization">
                                            <div {{{ view.getRenderAttributeString('wadi_testimonial_organization') }}}>
                                                {{{settings.wadi_testimonial_organization}}}
                                            </div>
                                        </div>
                                        <#
                                    }
                                }
                            #>
                        </div>
                        <# } #>
                    </div>

            </div>
        <?php
    }
}
