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
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

// Wadi Classes
use WadiAddons\Includes\WadiHelpers;
use WadiAddons\Includes\WadiQueries;

if (! defined('ABSPATH')) {
    exit;
}

class Wadi_Dual_Heading extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-dual-heading-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Dual Heading', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-dual-heading';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        if(!is_rtl()) {
            wp_register_style('style-handle_dual_heading', WADI_ADDONS_URL . 'assets/min/wadi-dual-heading.css');
        } else {
            wp_register_style('style-handle_dual_heading', WADI_ADDONS_URL . 'assets/min/wadi-dual-heading.rtl.css');
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
        return [ 'wadi-addons','advanced', 'dual heading' , 'double heading', 'multi', 'header', 'text' ];
    }

    public function get_style_depends()
    {
        return [ 'style-handle_dual_heading' ];
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
            'wadi_dual_heading_section_heading_text_section',
            [
                'label' => esc_html__('Heading Text', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control(
            'wadi_dual_heading_text_before',
            [
                'label' => esc_html__('Before Text', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Wadi', 'wadi-addons'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        
        $this->add_control(
            'wadi_dual_heading_text_main',
            [
                'label' => esc_html__('Highlighted Text', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Addons', 'wadi-addons'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'wadi_dual_heading_text_after',
            [
                'label' => esc_html__('After Text', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );


        $this->add_control(
            'wadi_dual_heading_link_switch',
            [
                'label' => esc_html__('Link', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'wadi_dual_heading_link_type',
            [
                'label'       => esc_html__('Link Type', 'wadi-addons'),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'url'  => esc_html__('URL', 'wadi-addons'),
                    'existing_page' => esc_html__('Existing Page', 'wadi-addons'),
                ],
                'default'     => 'url',
                'label_block' => true,
                'condition'   => [
                    'wadi_dual_heading_link_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_dual_heading_link_url',
            [
                'label'       => esc_html__('Link', 'wadi-addons'),
                'type'        => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'default'     => [
                    'url' => '#',
                ],
                'placeholder' => 'https://www.wadiweb.com/',
                'label_block' => true,
                'separator'   => 'after',
                'condition' => [
                    'wadi_dual_heading_link_switch' => 'yes',
                    'wadi_dual_heading_link_type' => 'url',
                ]
            ]
        );

        $this->add_control(
            'wadi_dual_heading_link_existing_link',
            [
                'label'       => esc_html__('Existing Page', 'wadi-addons'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->getTemplateInstance()->get_all_posts(),
                'condition'   => [
                    'wadi_dual_heading_link_switch'   => 'yes',
                    'wadi_dual_heading_link_type' => 'existing_page',
                ],
                'multiple'    => false,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'wadi_dual_heading_text_background_switch',
            [
                'label' => esc_html__('Background Text', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'wadi_dual_heading_text_background',
            [
                'label' => esc_html__('Text', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. ', 'wadi-addons'),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'wadi_dual_heading_text_background_switch' => 'yes',
                ],
                'selectors' => array(
					'{{WRAPPER}} .wadi_dual_heading_container:before' => 'content: "{{VALUE}}"',
				),
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_dual_heading_general_settings_style',
            [
                'label' => esc_html__('General Settings', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wadi_dual_heading_html_tag',
            [
                'label' => esc_html__('HTML Tag', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'p' => 'P',
                    'span' => 'Span'
                ],
                'default' => 'h2',
            ]
        );

        
        $dualHeadingAlignment = [
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

        $rtl_dualHeadingAlignment = array_reverse($dualHeadingAlignment);

        $theDualHeadingAlignment = !is_rtl()? $dualHeadingAlignment :  $rtl_dualHeadingAlignment;


        $this->add_responsive_control(
            'wadi_dual_heading_alignment',
            [
                'label' => esc_html__('Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => $theDualHeadingAlignment,
                'default' => 'center',
                'toggle' => true,
                'selectors' => array(
                    '{{WRAPPER}} .wadi_dual_heading_container' => 'text-align: {{VALUE}}; justify-content: {{VALUE}};',
                ),
            ]
        );

        $this->add_responsive_control(
            'wadi_dual_heading_rotate',
            [
                'label' => esc_html__('Rotate', 'wadi-addons'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['deg'],
                'range' => [
                    'deg' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
				'default'    => [
					'unit' => 'deg',
					'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_container' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );


        $this->add_responsive_control(
            'wadi_dual_heading_text_stacked',
            [
                'label' => esc_html__('Stacked', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
				'label_off' => esc_html__( 'No', 'wadi-addons' ),
				'return_value' => 'yes',
				'default' => 'no',
                'description' => esc_html__('Display Type Inline or Block', 'wadi-addons')
            ]
        );


        $this->add_control(
			'wadi_dual_heading_text_stack_on',
			array(
				'label'        => esc_html__( 'Stack on', 'wadi-addons' ),
				'description'  => esc_html__( 'Select breakpoints where heading will stack.', 'wadi-addons' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'mobile',
				'options'      => array(
					'desktop'   => esc_html__( 'Desktop', 'wadi-addons' ),
					'tablet' => esc_html__( 'Tablet (1023px >)', 'wadi-addons' ),
					'mobile' => esc_html__( 'Mobile (767px >)', 'wadi-addons' ),
				),
				'condition'    => array(
					'wadi_dual_heading_text_stacked' => 'yes',
				),
                'prefix_class' => 'wadi_dual_haeding_stacked__',
			)
		);
        
        $this->add_responsive_control(
            'wadi_dual_heading_text_spacing',
            [
                'label' => esc_html__('Spacing', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'rem', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                // 'default' => [
                //     'unit' => 'px',
                //     'size' => 0,
                // ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_text_before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wadi_dual_heading_text_after'  => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.wadi_dual_haeding_stacked__desktop .wadi_dual_heading_text_before' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: 0px; display: block;',
					'{{WRAPPER}}.wadi_dual_haeding_stacked__desktop .wadi_dual_heading_text_after' => 'margin-top: {{SIZE}}{{UNIT}}; margin-left: 0px; display: block;',
					'(tablet){{WRAPPER}}.wadi_dual_haeding_stacked__tablet .wadi_dual_heading_text_before ' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: 0px; display: block;',
					'(tablet){{WRAPPER}}.wadi_dual_haeding_stacked__tablet .wadi_dual_heading_text_after ' => 'margin-top: {{SIZE}}{{UNIT}}; margin-left: 0px; display: block;',
					'(mobile){{WRAPPER}}.wadi_dual_haeding_stacked__mobile .wadi_dual_heading_text_before ' => 'margin-bottom: {{SIZE}}{{UNIT}}; margin-right: 0px; display: block;',
					'(mobile){{WRAPPER}}.wadi_dual_haeding_stacked__mobile .wadi_dual_heading_text_after ' => 'margin-top: {{SIZE}}{{UNIT}}; margin-left: 0px; display: block;',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_dual_heading_text_styles',
            [
                'label' => esc_html__('Dual Heading Styling', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'wadi_dual_heading_text_styles_tabs'
        );
        
        $this->start_controls_tab(
            'wadi_dual_heading_text_style_main_tab',
            [
                'label' => esc_html__( 'Main', 'wadi-addons' ),
            ]
        );
        
        $this->add_control(
            'wadi_dual_heading_main_text_color',
            [
                'label' => esc_html__('Text Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffcc00',
                'selectors' => [
					'{{WRAPPER}} .wadi_dual_heading_text_main' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_dual_heading_main_text_typography',
				'selector' => '{{WRAPPER}} .wadi_dual_heading_text_main',
			]
		);

        
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_dual_heading_main_text_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_dual_heading_text_main',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_dual_heading_main_text_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_dual_heading_text_main',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_dual_heading_main_text_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_dual_heading_text_main',
			]
		);
        
        $this->add_responsive_control(
            'wadi_dual_heading_main_text_border_radius',
            [
                'label' => esc_html__('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem','em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_text_main' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'wadi_dual_heading_main_text_padding',
            [
                'label' => esc_html__('Padding', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem','em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_text_main' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_dual_heading_main_text_margin',
            [
                'label' => esc_html__('Margin', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem','em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_text_main' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'wadi_dual_heading_main_text_advanced_styling_switch',
            [
                'label' => esc_html__('Advanced Styling', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'default' => 'no',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wadi_dual_heading_main_text_fill_background',
            [
                'label' => esc_html__('Fill Background', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'description' => __('Used best with image background to fill text background with an image', 'wadi-addons'),
                'default' => 'no',
                'condition' => [
                    'wadi_dual_heading_main_text_advanced_styling_switch' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'wadi_dual_heading_main_text_animated_background',
            [
                'label' => esc_html__('Animated Background', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'default' => 'no',
                'descripton' => esc_html__('Set image background for animated effect.','wadi-addons'),
                'condition' => [
                    'wadi_dual_heading_main_text_advanced_styling_switch' => 'yes',
                ]
            ]
        );

        $this->add_control(
			'wadi_dual_heading_main_text_animated_background_description',
			[
				'raw' => esc_html__( 'Set image background for animated effect.', 'wadi-addons' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'wadi_dual_heading_main_text_advanced_styling_switch' => 'yes',
                ]
			]
		);

        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'wadi_dual_heading_text_style_highlight_tab',
            [
                'label' => esc_html__( 'Highlight', 'wadi-addons' ),
            ]
        );
        
        $this->add_control(
            'wadi_dual_heading_highlight_text_color',
            [
                'label' => esc_html__('Text Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'default' => '#2F45BC',
                'selectors' => [
					'{{WRAPPER}} .wadi_dual_heading_text_before, {{WRAPPER}} .wadi_dual_heading_text_after' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_dual_heading_highlight_text_typography',
				'selector' => '{{WRAPPER}} .wadi_dual_heading_text_before, {{WRAPPER}} .wadi_dual_heading_text_after',
			]
		);

        
		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_dual_heading_highlight_text_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_dual_heading_text_before, {{WRAPPER}} .wadi_dual_heading_text_after',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_dual_heading_highlight_text_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_dual_heading_text_before, {{WRAPPER}} .wadi_dual_heading_text_after',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_dual_heading_highlight_text_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_dual_heading_text_before, {{WRAPPER}} .wadi_dual_heading_text_after',
			]
		);
        
        $this->add_responsive_control(
            'wadi_dual_heading_highlight_text_border_radius',
            [
                'label' => esc_html__('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem','em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_text_before, {{WRAPPER}} .wadi_dual_heading_text_after' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_responsive_control(
            'wadi_dual_heading_highlight_text_padding',
            [
                'label' => esc_html__('Padding', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem','em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_text_before, {{WRAPPER}} .wadi_dual_heading_text_after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_dual_heading_highlight_text_margin',
            [
                'label' => esc_html__('Margin', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'rem','em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_text_before, {{WRAPPER}} .wadi_dual_heading_text_after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'wadi_dual_heading_highlight_text_advanced_styling_switch',
            [
                'label' => esc_html__('Advanced Styling', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'default' => 'no',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wadi_dual_heading_highlight_text_fill_background',
            [
                'label' => esc_html__('Fill Background', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'default' => 'no',
                'condition' => [
                    'wadi_dual_heading_highlight_text_advanced_styling_switch' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'wadi_dual_heading_highlight_text_animated_background',
            [
                'label' => esc_html__('Animated Background', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'default' => 'no',
                'condition' => [
                    'wadi_dual_heading_highlight_text_advanced_styling_switch' => 'yes',
                ]
            ]
        );

        $this->add_control(
			'wadi_dual_heading_highlight_text_animated_background_description',
			[
				'raw' => esc_html__( 'Set image background for animated effect.', 'wadi-addons' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                'condition' => [
                    'wadi_dual_heading_highlight_text_advanced_styling_switch' => 'yes',
                ]
			]
		);

        $this->end_controls_tab();
        
        $this->end_controls_tabs();


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_dual_heading_background_text_content_style',
            [
                'label' => esc_html__('Background Text', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'wadi_dual_heading_text_background_switch' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'wadi_dual_heading_background_text_content_background_offset_toggle',
            array(
                'label'        => __( 'Offset Background Text', 'uael' ),
                'type'         => Controls_Manager::POPOVER_TOGGLE,
                'label_off'    => __( 'None', 'wadi-addons' ),
                'label_on'     => __( 'Custom', 'wadi-addons' ),
                'return_value' => 'yes',
            )
        );
        $this->start_popover();
        $this->add_responsive_control(
            'wadi_dual_heading_background_text_content_horizontal_offset',
            [
                'label' => esc_html__('Horizontal Offset', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [  'px', 'rem', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => -2000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => -100,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_container:before' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'wadi_dual_heading_text_background_switch' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_dual_heading_background_text_content_vertical_offset',
            [
                'label' => esc_html__('Vertical Offset', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [  'px', 'rem', '%', 'em' ],
                'range' => [
                    'px' => [
                        'min' => -2000,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    'rem' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => -100,
                        'max' => 100,
                    ]
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_dual_heading_container:before' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition' => [
                    'wadi_dual_heading_text_background_switch' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
			'wadi_dual_heading_background_text_content__rotate',
			array(
				'label'      => __( 'Rotate', 'wadi-addons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['deg'],
                'range' => [
                    'deg' => [
                        'min' => -360,
                        'max' => 360,
                        'step' => 1,
                    ],
                ],
				'default'    => [
					'unit' => 'deg',
					'size' => 0,
                ],
				'selectors'  => [
					'{{WRAPPER}} .wadi_dual_heading_container:before' => 'transform: rotate({{SIZE}}{{UNIT}})',
                ],
				'condition'  => [
					'wadi_dual_heading_text_background_switch' => 'yes',
                ],
			)
		);
        $this->end_popover();

        $this->add_control(
            'wadi_dual_heading_background_text_content_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'global'    => array(
					'default' => Global_Colors::COLOR_PRIMARY,
				),
				'selectors' => array(
					'{{WRAPPER}} .wadi_dual_heading_container:before' => 'color: {{VALUE}}',
				),
                'condition' => [
                    'wadi_dual_heading_text_background_switch' => 'yes',
                ]
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_dual_heading_background_text_content_typography',
				'selector' => '{{WRAPPER}} .wadi_dual_heading_container:before',
                'condition' => [
                    'wadi_dual_heading_text_background_switch' => 'yes',
                ]
			]
		);

        $this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_dual_heading_background_text_content_text_shadow',
				'selector' => '{{WRAPPER}} .wadi_dual_heading_container:before',
                'condition' => [
                    'wadi_dual_heading_text_background_switch' => 'yes',
                ]
			]
		);

        $this->add_control(
			'wadi_dual_heading_background_text_content_z_index',
			[
                'label' => esc_html__('Z-index', 'wadi-addons'),
				'type' => Controls_Manager::NUMBER,
				'min' => -10000,
				'max' => 10000000,
				'step' => 1,
                'selectors' => array(
					'{{WRAPPER}} .wadi_dual_heading_container:before' => 'z-index: {{VALUE}}',
				),
			]
		);

        $this->end_controls_section();
    }
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        
        $dual_heading_url = '';

        if( 'yes' === $settings['wadi_dual_heading_link_switch']) {
            if ( 'url' === $settings['wadi_dual_heading_link_type'] ) {
                if( ! empty( $settings['wadi_dual_heading_link_url']['url'])) {
                    $this->add_link_attributes( 'wadi_dual_heading_link', $settings['wadi_dual_heading_link_url'] );
                }
            } elseif( 'existing_page' === $settings['wadi_dual_heading_link_type'] ) {
                $dual_heading_url = get_permalink($settings['wadi_dual_heading_link_existing_link']);
            }
        }

        
        $dual_heading_html_tag = WadiHelpers::validate_tags($settings['wadi_dual_heading_html_tag']);

        
        $this->add_render_attribute(
            'wadi_dual_heading_container',
            [
                'class' =>  [
                    'wadi_dual_heading_container',
                    'wadi_dual_heading_container_' . esc_attr($this->get_id()),
                ],
                'data-background-text' => [
                    wp_kses_post($settings['wadi_dual_heading_text_background'])
                ],
            ]
        );
        $this->add_render_attribute(
            'wadi_dual_heading_text_before',
            [
                'class' =>  [
                    'wadi_dual_heading_text_before',
                    'wadi_dual_heading_content_text',
                    $settings['wadi_dual_heading_highlight_text_advanced_styling_switch'] === 'yes' && $settings['wadi_dual_heading_highlight_text_fill_background'] === 'yes' ? 'wadi_dual_heading_highlight_text_background_fill': '',
                    $settings['wadi_dual_heading_highlight_text_advanced_styling_switch'] === 'yes' && $settings['wadi_dual_heading_highlight_text_animated_background'] === 'yes' ? 'wadi_dual_heading_highlight_text_animated_background': '',
                ],
            ]
        );
        
        $this->add_render_attribute(
            'wadi_dual_heading_text_main',
            [
                'class' =>  [
                    'wadi_dual_heading_text_main',
                    'wadi_dual_heading_content_text',
                    $settings['wadi_dual_heading_main_text_advanced_styling_switch'] === 'yes' && $settings['wadi_dual_heading_main_text_fill_background'] === 'yes' ? 'wadi_dual_heading_main_text_background_fill': '',
                    $settings['wadi_dual_heading_main_text_advanced_styling_switch'] === 'yes' && $settings['wadi_dual_heading_main_text_animated_background'] === 'yes' ? 'wadi_dual_heading_main_text_animated_background': '',
                ],
            ]
        );

        $this->add_render_attribute(
            'wadi_dual_heading_text_after',
            [
                'class' =>  [
                    'wadi_dual_heading_text_after',
                    'wadi_dual_heading_content_text',
                    $settings['wadi_dual_heading_highlight_text_advanced_styling_switch'] === 'yes' && $settings['wadi_dual_heading_highlight_text_fill_background'] === 'yes' ? 'wadi_dual_heading_highlight_text_background_fill': '',
                    $settings['wadi_dual_heading_highlight_text_advanced_styling_switch'] === 'yes' && $settings['wadi_dual_heading_highlight_text_animated_background'] === 'yes' ? 'wadi_dual_heading_highlight_text_animated_background': '',
                ],
            ]
        );
                
        $this->get_render_attribute_string( 'wadi_dual_heading_link' ) ;
                
    
        $this->add_inline_editing_attributes('wadi_dual_heading_text_before', 'none');
        $this->add_inline_editing_attributes('wadi_dual_heading_text_main', 'none');
        $this->add_inline_editing_attributes('wadi_dual_heading_text_after', 'none'); ?>

        <div <?php echo wp_kses_post($this->get_render_attribute_string('wadi_dual_heading_container')); ?>>

        <?php if('yes' === $settings['wadi_dual_heading_link_switch'] ) : ?>
            <?php if('url' === $settings['wadi_dual_heading_link_type']) : ?>
                <a <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_dual_heading_link' ) ); ?> class="wadi_dual_haeding_link_url">
                <?php elseif ('existing_page' === $settings['wadi_dual_heading_link_type'] ) : ?>
                <a  href="<?php echo esc_url($dual_heading_url); ?>" target="_blank" class="wadi_dual_haeding_link_existing_page">
            <?php endif; ?>
        <?php endif; ?>

                <<?php echo wp_kses_post($dual_heading_html_tag); ?>>
                <?php if (!empty($settings['wadi_dual_heading_text_before'])) : ?>
                    <span <?php echo wp_kses_post($this->get_render_attribute_string('wadi_dual_heading_text_before')) ?>><?php echo wp_kses_post($settings['wadi_dual_heading_text_before']); ?></span>
                <?php endif; ?>
                <?php if (!empty($settings['wadi_dual_heading_text_main'])) : ?>
                    <span <?php echo wp_kses_post($this->get_render_attribute_string('wadi_dual_heading_text_main')) ?>><?php echo wp_kses_post($settings['wadi_dual_heading_text_main']); ?></span>
                <?php endif; ?>
                <?php if (!empty($settings['wadi_dual_heading_text_after'])) : ?>
                    <span <?php echo wp_kses_post($this->get_render_attribute_string('wadi_dual_heading_text_after')) ?>><?php echo wp_kses_post($settings['wadi_dual_heading_text_after']); ?></span>
                <?php endif; ?>
                
                </<?php echo wp_kses_post($dual_heading_html_tag); ?>>
                <?php if('yes' === $settings['wadi_dual_heading_link_switch'] ) : ?>
                </a>
                <?php endif; ?>
            
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

        <# 
        
        var dualHeadingHtmlTag = elementor.helpers.validateHTMLTag( settings.wadi_dual_heading_html_tag );
        
        var dualHeadingUrl = '';

        if ( '' !== settings.wadi_dual_heading_link_url.url && 'url' === settings.wadi_dual_heading_link_type ) {
			dualHeadingUrl = settings.wadi_dual_heading_link_url.url ;
		} else if( 'existing_page' === settings.wadi_dual_heading_link_type ) {
			dualHeadingUrl = settings.wadi_dual_heading_link_existing_link;
        }


        view.addRenderAttribute(
		'wadi_dual_heading_container',
            {
                'class': [ 
                    'wadi_dual_heading_container',
                    'wadi_dual_heading_container_' + view.getID(),
                    ],
                    'data-background-text': [
                        settings.wadi_dual_heading_text_background
                    ],
            }
	    );

        var highlight_text_fill_background;
        var highlight_text_animated_background;
        highlight_text_fill_background = settings.wadi_dual_heading_highlight_text_advanced_styling_switch === 'yes' && settings.wadi_dual_heading_highlight_text_fill_background === 'yes' ? 'wadi_dual_heading_highlight_text_background_fill': '',
        highlight_text_animated_background = settings.wadi_dual_heading_highlight_text_advanced_styling_switch === 'yes' && settings.wadi_dual_heading_highlight_text_animated_background === 'yes' ? 'wadi_dual_heading_highlight_text_animated_background': '',

        view.addRenderAttribute(
		'wadi_dual_heading_text_before',
            {
                'class': [
                        'wadi_dual_heading_text_before',
                        'wadi_dual_heading_content_text',
                        highlight_text_fill_background,
                        highlight_text_animated_background
                    ]
            }
	    );

        view.addRenderAttribute(
		'wadi_dual_heading_text_main',
            {
                'class':  [
                    'wadi_dual_heading_text_main',
                    'wadi_dual_heading_content_text',
                    settings.wadi_dual_heading_main_text_advanced_styling_switch === 'yes' && settings.wadi_dual_heading_main_text_fill_background === 'yes' ? 'wadi_dual_heading_main_text_background_fill': '',
                    settings.wadi_dual_heading_main_text_advanced_styling_switch === 'yes' && settings.wadi_dual_heading_main_text_animated_background === 'yes' ? 'wadi_dual_heading_main_text_animated_background': '',
                ],
            }
	    );

        view.addRenderAttribute(
		'wadi_dual_heading_text_after',
            {
                'class': [
                    'wadi_dual_heading_text_after',
                    'wadi_dual_heading_content_text',
                    highlight_text_fill_background,
                    highlight_text_animated_background
                ],
            }
	    );

        /*
        * Inline Editing JS Rendering
        */
        view.addInlineEditingAttributes('wadi_dual_heading_text_before');
        view.addInlineEditingAttributes('wadi_dual_heading_text_main');
        view.addInlineEditingAttributes('wadi_dual_heading_text_after');

        #>

        <div  {{{ view.getRenderAttributeString( 'wadi_dual_heading_container' ) }}}>
            <# if ('yes' === settings.wadi_dual_heading_link_switch ) { #>
                <a href="{{dualHeadingUrl}}">
            <# } #>

            <{{dualHeadingHtmlTag}}>
                <# if ( '' !== settings.wadi_dual_heading_text_before ) { #>
                    <span {{{ view.getRenderAttributeString( 'wadi_dual_heading_text_before' ) }}}>
                        {{{ settings.wadi_dual_heading_text_before }}}
                    </span>
                <# } #>
                <# if ( '' !== settings.wadi_dual_heading_text_main ) { #>
                    <span {{{ view.getRenderAttributeString( 'wadi_dual_heading_text_main' ) }}}>
                        {{{ settings.wadi_dual_heading_text_main }}}
                    </span>
                <# } #>
                <# if ( '' !== settings.wadi_dual_heading_text_after ) { #>
                    <span {{{ view.getRenderAttributeString( 'wadi_dual_heading_text_after' ) }}}>
                        {{{ settings.wadi_dual_heading_text_after }}}
                    </span>
                <# } #>
            </{{dualHeadingHtmlTag}}>
            <# if('yes' === settings.wadi_dual_heading_link_switch ) { #>
            </a>
            <# } #>

        </div>
        <?php
    }
}
