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

class Wadi_Accordion extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-accordion-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Accordion', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-accordion-wadi';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_register_script('script-handle_accordion', WADI_ADDONS_URL . 'assets/min/wadi-accordion.min.js', [ 'elementor-frontend', 'jquery' ], '1.0.0', true);
        if(!is_rtl()) {
            wp_register_style('style-handle_accordion', WADI_ADDONS_URL . 'assets/min/wadi-accordion.css');
        } else {
            wp_register_style('style-handle_accordion', WADI_ADDONS_URL . 'assets/min/wadi-accordion.rtl.css');
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
        return [ 'wadi-addons','tabs', 'accordion', 'toggle', 'faq', 'questions', 'answers' ];
    }


    public function get_script_depends()
    {
        return [ 'script-handle_accordion' ];
    }

    public function get_style_depends()
    {
        return [ 'style-handle_accordion' ];
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
            'wadi_accordion_items_section',
            [
                'label' => esc_html('Accordion', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $repeater = new Repeater();

        $repeater->start_controls_tabs(
            'style_tabs'
        );
        
        $repeater->start_controls_tab(
            'wadi_accordion_content',
            [
                'label' => esc_html__( 'Content', 'wadi-addons' ),
            ]
        );

        
        $repeater->add_control(
            'wadi_accordion_title',
            [
                'label' => esc_attr__('Title', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_attr__('Title', 'wadi-addons'),
                'placeholder' => esc_attr__('Title', 'wadi-addons'),
                'dynamic' => [
                    'active' => true,
                ],
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'wadi_accordion_content_type',
            array(
                'label'   => __('Content Type', 'wadi-addons'),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'text_editor'         => __('Text Editor', 'wadi-addons'),
                    'elementor_templates' => __('Elementor Template', 'wadi-addons'),
                ),
                'default' => 'text_editor',
            )
        );

        $repeater->add_control(
            'wadi_accordion_content_text',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'default' => esc_html__('Accordion Content', 'wadi-addons'),
                'placeholder' => esc_html__('Accordion Content', 'wadi-addons'),
                'type' => Controls_Manager::WYSIWYG,
                'show_label' => false,
                'condition' => [
                    'wadi_accordion_content_type' => 'text_editor'
                ]
            ]
        );


        $repeater->add_control(
            'wadi_accordion_content_elementor_template',
            array(
                'label'       => __('Elementor Template', 'wadi-addons'),
                'description' => __('Elementor Template is a template which you can choose from Elementor library. Each template will be shown in content', 'wadi-addons'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->getTemplateInstance()->elementor_templates_list(),
                'label_block' => true,
                'condition'   => array(
                    'wadi_accordion_content_type' => 'elementor_templates',
                ),
            )
        );

        

        $repeater->add_control(
            'title_icon',
            [
                'label' => esc_html__('Title Icon', 'wadi-addons'),
                'type' => Controls_Manager::ICONS,
                'description' => esc_html__('Set Title Icon', 'wadi-addons'),
				'fa4compatibility' => 'wadi_title_icon',
				'skin' => 'inline',
				'label_block' => true,
			]
        );
        
        $repeater->end_controls_tab();

        $repeater->start_controls_tab(
            'wadi_accordion_settings',
            [
                'label' => esc_html__( 'Settings', 'wadi-addons' ),
            ]
        );

        $repeater->add_responsive_control(
            'wadi_accordion_content_style_heading',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $repeater->add_responsive_control(
            'wadi_accordion_content_padding',
            [
                'label' => esc_html__('Content Padding', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .wadi-accordion-container {{CURRENT_ITEM}}.accordion_item .accordion__content .wadi-accordion-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        $repeater->add_responsive_control(
            'wadi_accordion_title_style_heading',
            [
                'label' => esc_html__('Title', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_responsive_control(
            'wadi_accordion_title_padding',
            [
                'label' => esc_html__('Title Padding', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .wadi-accordion-container {{CURRENT_ITEM}}.accordion_item .accordion__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
        );

        
        $repeater->end_controls_tab();
        
        $repeater->end_controls_tabs();


        $this->add_control(
            'accordions',
            [
                'label' => esc_html__('Accordions Items', 'wadi-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'wadi_accordion_title' => esc_html__('Accordion Item #1', 'wadi-addons'),
                        'wadi_accordion_content_text' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'wadi-addons'),
                    ],
                    [
                        'wadi_accordion_title' => esc_html__('Accordion Item #2', 'wadi-addons'),
                        'wadi_accordion_content_text' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'wadi-addons'),
                    ],
                    [
                        'wadi_accordion_title' => esc_html__('Accordion Item #3', 'wadi-addons'),
                        'wadi_accordion_content_text' => esc_html__('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'wadi-addons'),
                    ],
                ],
                'title_field' => '{{{ wadi_accordion_title }}}',
                'fields'             => $repeater->get_controls(),
                'frontend_available' => true,
            ]
        );

        $this->add_control(
			'wadi_selected_icon',
			[
				'label' => esc_html__( 'Icon', 'wadi-addons' ),
				'type' => Controls_Manager::ICONS,
				'separator' => 'before',
				'fa4compatibility' => 'wadi_icon',
				'default' => [
					'value' => 'fas fa-plus',
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
			]
		);

		$this->add_control(
			'wadi_selected_active_icon',
			[
				'label' => esc_html__( 'Active Icon', 'wadi-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wadi_icon_active',
				'default' => [
					'value' => 'fas fa-minus',
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
					'wadi_selected_icon[value]!' => '',
				],
			]
		);


        $this->add_control(
            'wadi_faq_schema',
            array(
                'label'       => esc_attr__( 'Enable FAQ Schema', 'wadi-addons' ),
                'description' => esc_html__( 'Note: Schema will not work if dynamic content (Elementor Templates) is used in FAQ\'s, make sure that all repeater content is Text Editor type and not used Elementor Templates, it will return text answer (Elementor Template)', 'wadi-addons' ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => esc_attr__( 'Yes', 'wadi-addons' ),
                'label_off'   => esc_attr__( 'No', 'wadi-addons' ),
                'default'     => 'no',
				'separator' => 'before',
            )
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_tabs_display_options',
            [
                'label' => esc_html__('Display Options', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$start = is_rtl() ? 'flex-end' : 'flex-start';
		$end   = is_rtl() ? 'flex-start' : 'flex-end';

        
        $alignment_options =  [
            $start => [
                'title' =>   __('Left', 'wadi-addons'),
                'icon' => 'eicon-text-align-left'
            ],
            'center' => [
                'title' =>   __('Center', 'wadi-addons'),
                'icon' => 'eicon-text-align-center'
            ],
            $end  => [
                'title' =>   __('Right', 'wadi-addons'),
                'icon' => 'eicon-text-align-right'
            ],
        ];

        $alignment_options_rtl = array_reverse($alignment_options);

        $the_alignment_options = is_rtl() ? $alignment_options_rtl :$alignment_options;


        $this->add_responsive_control(
            'wadi_accordion_alignment',
            [
                'label' => esc_attr__('Accordion Title Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => $the_alignment_options,
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .wadi-accordion-title-text' => 'justify-content: {{VALUE}}',
                ],
            ]
        );

        $icon_start = is_rtl() ? 'row' : 'row-reverse';
        $icon_end = is_rtl() ? 'row-reverse' : 'row';

        $options = [
            $icon_start => [
                'title' => esc_html__( 'Start', 'elementor' ),
                'icon' => 'eicon-h-align-left',
            ],
            $icon_end => [
                'title' => esc_html__( 'End', 'elementor' ),
                'icon' => 'eicon-h-align-right',
            ],
        ];

        $options_rtl = array_reverse($options);

        $the_options = is_rtl() ? $options_rtl :$options;

        $this->add_responsive_control(
			'accordion_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'wadi-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => $the_options,
				'default' => is_rtl() ? 'row-reverse' : 'row',
				'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .accordion__title' => 'flex-direction: {{VALUE}};'
                    ]
			]
		);

        $this->add_control(
			'accordion_toggle',
			[
				'label' => esc_html__( 'Accordion Toggle', 'wadi-addons' ),
                'description' => esc_html('Allow multiple accordion stay open, default is Hide', 'wadi-addons'),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'wadi-addons' ),
				'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
				'default' => '',
                'frontend_available' => true
			]
		);

        $this->add_control(
            'wadi_accordion_on_load_toggle',
            [
                'label' => esc_html__('Accordion On Load Toggle', 'wadi-addons'),
                'description' => esc_html('Set Accordion to toggle accordion item on Page Load', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'frontend_available' => true
            ]
        );

        $this->add_control(
            'wadi_accordion_set_on_load_toggle_index',
            [
                'label' => esc_html__('Active Index', 'wadi-addons'),
                'description' => esc_html('Set the index of Accordion Item to toggle on Page Load, it is zero indexed so 0 means first accordion item.', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'min' => 0,
                'step' => 1,
                'condition' => [
                    'wadi_accordion_on_load_toggle' => 'yes'
                ],
                'frontend_available' => true
            ]
        );


        $this->add_control(
			'wadi_title_html_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'wadi-addons' ),
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
				'default' => 'div',
				'separator' => 'before',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_accordion_style_section',
            [
                'label' => esc_attr__('Accordion', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_accordion_border',
				'label' => esc_html__( 'Border Type', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi-accordion-container',
			]
		);

        
        $this->add_responsive_control(
            'wadi_accordion_border_radius',
            [
                'label' => esc_html__('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
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
                'selectors' => [
                    '{{WRAPPER}} .accordion_item .accordion__title' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .accordion_item .accordion__title.current-item' => 'border-radius: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0;',
                    '{{WRAPPER}} .accordion_item .accordion__title.current-item ~ .accordion__content .wadi-accordion-content-wrapper' => 'border-radius: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};'
                ]
            ]
        );



        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_accordion_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi-accordion-container',
			]
		);

        $this->add_responsive_control(
			'wadi_accordion_margin',
			[
				'label'      => __( 'Margin', 'wadi-addons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .accordion_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->end_controls_section();
        

        $this->start_controls_section(
            'wadi_accordion_title_style_section',
            [
                'label' => esc_attr__('Title', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_accordion_title_border',
				'label' => esc_html__( 'Border Type', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .accordion__title',
			]
		);

        $this->start_controls_tabs(
            'accordion_style_tabs'
        );
        
        // Normal Title Tab
        $this->start_controls_tab(
            'accordion_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_accordion_title_background_color',
            [
                 'label' => esc_attr__('Background Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title' => 'background-color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_control(
            'wadi_accordion_title_color',
            [
                 'label' => esc_attr__('Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title .wadi-accordion-title-text' => 'color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_accordion_title_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi-accordion-title-text',
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_accordion_title_typography',
				'selector' => '{{WRAPPER}} .wadi-accordion-title-text',
			]
		);


        $this->end_controls_tab();

        // Hover Title Tab
        $this->start_controls_tab(
            'accordion_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_accordion_title_background_color_hover',
            [
                 'label' => esc_attr__('Background Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title:hover' => 'background-color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_control(
            'wadi_accordion_title_color_hover',
            [
                 'label' => esc_attr__('Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title:hover .wadi-accordion-title-text, {{WRAPPER}} .accordion__title.current-item .wadi-accordion-title-text:hover' => 'color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_accordion_title_text_shadow_hover',
				'label' => esc_html__( 'Text Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi-accordion-title-text:hover',
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_accordion_title_typography_hover',
				'selector' => '{{WRAPPER}} .wadi-accordion-title-text:hover',
			]
		);


        $this->end_controls_tab();

        // Active Title Tab
        $this->start_controls_tab(
            'accordion_style_active_tab',
            [
                'label' => esc_html__( 'Active', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_accordion_title_background_color_active',
            [
                 'label' => esc_attr__('Background Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title.current-item' => 'background-color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_control(
            'wadi_accordion_title_color_active',
            [
                 'label' => esc_attr__('Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title.current-item .wadi-accordion-title-text' => 'color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_accordion_title_text_shadow_active',
				'label' => esc_html__( 'Text Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .accordion__title.current-item .wadi-accordion-title-text',
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_accordion_title_typography_active',
				'selector' => '{{WRAPPER}} .accordion__title.current-item .wadi-accordion-title-text',
			]
		);


        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        
        $this->add_responsive_control(
            'wadi_accordion_title_padding',
			[
                'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .wadi-accordion-title-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
                ]
            );

            $this->add_control(
                'wadi_accordion_title_focus_switcher',
                [
                    'label' => esc_html__('Accordion Title Focus', 'wadi-addons'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'wadi-addons'),
                    'label_off' => esc_html__('No', 'wadi-addons'),
                    'return_value' => 'yes',
                    'default' => 'no',
                    'separator' => 'before'
                ]
            );

        
        $this->add_control(
            'wadi_accordion_title_focus',
            [
                'label' => esc_html__( 'Accordion Title Focus', 'wadi-addons' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'wadi_accordion_title_focus_switcher' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'wadi_accordion_title_focus_color',
            [
                 'label' => esc_attr__('Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .wadi-accordion-container .accordion__title:focus' => 'outline-color: {{VALUE}};',
                 ],
                 'condition' => [
                    'wadi_accordion_title_focus_switcher' => 'yes'
                ],
             ]
        );

        $this->add_control(
            'wadi_accordion_title_focus_width',
            [
                 'label' => esc_attr__('Outline Width', 'wadi-addons'),
                 'type' => Controls_Manager::SLIDER,
                 'range' => [
                     'px' => [
                         'min' => 0,
                         'max' => 20,
                     ],
                 ],
                 'selectors' => [
                     '{{WRAPPER}} .wadi-accordion-container .accordion__title:focus' => 'outline-width: {{SIZE}}{{UNIT}};',
                 ],
                 'condition' => [
                    'wadi_accordion_title_focus_switcher' => 'yes'
                ],
             ]
        );

        // outline style
        $this->add_control(
            'wadi_accordion_title_focus_style',
            [
                'label' => esc_html__( 'Outline Style', 'wadi-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'solid' => esc_html__( 'Solid', 'wadi-addons' ),
                    'dashed' => esc_html__( 'Dashed', 'wadi-addons' ),
                    'dotted' => esc_html__( 'Dotted', 'wadi-addons' ),
                    'double' => esc_html__( 'Double', 'wadi-addons' ),
                    'none' => esc_html__( 'None', 'wadi-addons' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}} .wadi-accordion-container .accordion__title:focus' => 'outline-style: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_accordion_title_focus_switcher' => 'yes'
                ],
            ]
        );

        // Title Icon

        $this->add_control(
            'wadi_accordion_title_heading',
            [
                'label' => esc_html__('Title Icon', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'wadi_accordion_title_icon_size',
            [
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
					'{{WRAPPER}} .wadi_accordion_icon_title i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wadi_accordion_icon_title svg' => 'width: {{SIZE}}{{UNIT}}!important;  height: {{SIZE}}{{UNIT}}!important;',
				],
            ]
        );

        $this->add_control(
            'wadi_accordion_title_icon_color',
            [
                 'label' => esc_attr__('Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                    '{{WRAPPER}} .wadi_accordion_icon_title i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wadi_accordion_icon_title svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                 ],
             ]
        );

        
        $this->add_control(
            'wadi_accordion_title_icon_hover',
            [
                 'label' => esc_attr__('Hover Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title:hover .wadi_accordion_icon_title i, {{WRAPPER}} .accordion__title.current-item:hover  .wadi_accordion_icon_title i' => 'color: {{VALUE}};',
                     '{{WRAPPER}} .accordion__title:hover .wadi_accordion_icon_title svg, {{WRAPPER}} .accordion__title.current-item:hover .wadi_accordion_icon_title svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                 ],
                 
             ]
        );
        $this->add_control(
            'wadi_accordion_title_icon_active',
            [
                 'label' => esc_attr__('Active Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title.current-item .wadi_accordion_icon_title i' => 'color: {{VALUE}};',
                     '{{WRAPPER}}  .accordion__title.current-item  .wadi_accordion_icon_title svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_responsive_control(
			'wadi_accordion_title_icon_spacing',
			array(
				'label'     => __( 'Spacing', 'wadi-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .wadi_accordion_icon_title' => 'margin: 0 {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}};',
				),
			)
		);


        $this->end_controls_section();


        // Icon

        $this->start_controls_section(
            'wadi_accordion_icon_style_section',
            [
                'label' => esc_attr__('Icon', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_responsive_control(
            'wadi_accordion_icon_size',
            [
                'label' => esc_attr__('Icon Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem' ],
                'default' => [
                    'size' => 20,
                    'unit' => 'px',
                ],
                'selectors' => [
					' {{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-closed i,{{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-opened i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-closed svg, {{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-opened svg' => 'width: {{SIZE}}{{UNIT}}!important;  height: {{SIZE}}{{UNIT}}!important;',
				],
            ]
        );

        $this->start_controls_tabs(
            'wadi_accordion_icon_style_tabs'
        );
        
        $this->start_controls_tab(
            'wadi_accordion_icon_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
            ]
        );
        
        $this->add_control(
            'wadi_accordion_icon_color',
            [
                 'label' => esc_attr__('Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-closed i,{{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-opened i' => 'color: {{VALUE}};',
                     '{{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-closed svg, {{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-opened svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                 ],
             ]
        );

        $this->add_control(
            'wadi_accordion_icon_bg_color',
            [
                 'label' => esc_attr__('Background Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-closed,{{WRAPPER}} .wadi-accordion-icons .wadi-accordion-icon-opened' => 'background-color: {{VALUE}};',
                 ],
             ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'wadi_accordion_icon_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
            ]
        );
        

        $this->add_control(
            'wadi_accordion_icon_color_hover',
            [
                 'label' => esc_attr__('Hover Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title:hover .wadi-accordion-icons .wadi-accordion-icon-closed i,{{WRAPPER}} .accordion__title:hover .wadi-accordion-icons .wadi-accordion-icon-opened i, {{WRAPPER}} .accordion__title.current-item:hover .wadi-accordion-icons .wadi-accordion-icon-closed i,{{WRAPPER}} .accordion__title.current-item:hover .wadi-accordion-icons .wadi-accordion-icon-opened i' => 'color: {{VALUE}};',
                     '{{WRAPPER}} .accordion__title:hover .wadi-accordion-icons .wadi-accordion-icon-closed svg, {{WRAPPER}} .accordion__title:hover .wadi-accordion-icons .wadi-accordion-icon-opened svg, {{WRAPPER}} .accordion__title.current-item:hover .wadi-accordion-icons .wadi-accordion-icon-closed svg, {{WRAPPER}} .accordion__title.current-item:hover .wadi-accordion-icons .wadi-accordion-icon-opened svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                 ],
                 
             ]
        );

        
        $this->add_control(
            'wadi_accordion_icon_bg_color_hover',
            [
                 'label' => esc_attr__('Hover Background Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title:hover .wadi-accordion-icons .wadi-accordion-icon-closed ,{{WRAPPER}} .accordion__title:hover .wadi-accordion-icons .wadi-accordion-icon-opened , {{WRAPPER}} .accordion__title.current-item:hover .wadi-accordion-icons .wadi-accordion-icon-closed ,{{WRAPPER}} .accordion__title.current-item:hover .wadi-accordion-icons .wadi-accordion-icon-opened ' => 'background-color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'wadi_accordion_icon_style_active_tab',
            [
                'label' => esc_html__( 'Active', 'wadi-addons' ),
            ]
        );
        
        $this->add_control(
            'wadi_accordion_icon_color_active',
            [
                 'label' => esc_attr__('Active Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title.current-item .wadi-accordion-icons .wadi-accordion-icon-closed i,{{WRAPPER}} .accordion__title.current-item .wadi-accordion-icons .wadi-accordion-icon-opened i' => 'color: {{VALUE}};',
                     '{{WRAPPER}} .accordion__title.current-item .wadi-accordion-icons .wadi-accordion-icon-closed svg, {{WRAPPER}} .accordion__title.current-item .wadi-accordion-icons .wadi-accordion-icon-opened svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                 ],
                 
             ]
        );
        
        
        $this->add_control(
            'wadi_accordion_icon_bg_color_active',
            [
                 'label' => esc_attr__('Active Background Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .accordion__title.current-item .wadi-accordion-icons .wadi-accordion-icon-closed ,{{WRAPPER}} .accordion__title.current-item .wadi-accordion-icons .wadi-accordion-icon-opened' => 'background-color: {{VALUE}};',
                 ],
                 
             ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        
        $this->add_responsive_control(
            'wadi_accordion_icon_padding',
            [
                'label'     => __( 'Icon Padding', 'wadi-addons' ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'default'   => array(
                    'unit' => 'px',
                    'size' => 0,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .accordion__title .wadi-accordion-icons .wadi-accordion-icon-closed ,{{WRAPPER}} .accordion__title .wadi-accordion-icons .wadi-accordion-icon-opened' => 'padding: {{SIZE}}{{UNIT}};',
                ),
                'separator' => 'before',
             ]
        );

        $this->add_responsive_control(
			'wadi_accordion_icon_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'textdomain' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'rem', 'em' ],
				'selectors' => [
                    '{{WRAPPER}} .accordion__title .wadi-accordion-icons .wadi-accordion-icon-closed ,{{WRAPPER}} .accordion__title .wadi-accordion-icons .wadi-accordion-icon-opened' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'wadi_accordion_icon_spacing',
			array(
				'label'     => __( 'Spacing', 'wadi-addons' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default'   => array(
					'unit' => 'px',
					'size' => 0,
				),
				'selectors' => array(
					'{{WRAPPER}} .wadi-accordion-icons' => 'margin: 0 {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}};',
				),
			)
		);

        $this->end_controls_section();
        
         // Content

         $this->start_controls_section(
            'wadi_accordion_content_style_section',
            [
                'label' => esc_attr__('Content', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_accordion_content_typography',
				'selector' => '{{WRAPPER}} .wadi-accordion-content-wrapper',
			]
		);


        $this->start_controls_tabs(
            'contetn_style_tabs'
        );
        
        $this->start_controls_tab(
            'wadi_accordion_content_style_normal',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_accordion_content_background_color',
            [
                 'label' => esc_attr__('Background Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .wadi-accordion-content-wrapper' => 'background-color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_control(
            'wadi_accordion_content_text_color',
            [
                 'label' => esc_attr__('Text Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .wadi-accordion-content-wrapper' => 'color: {{VALUE}};',
                 ],
                 
             ]
        );

        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'wadi_accordion_content_style_hover',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_accordion_content_background_color_hover',
            [
                 'label' => esc_attr__('Background Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .wadi-accordion-content-wrapper:hover' => 'background-color: {{VALUE}};',
                 ],
                 
             ]
        );

        $this->add_control(
            'wadi_accordion_content_text_color_hover',
            [
                 'label' => esc_attr__('Text Color', 'wadi-addons'),
                 'type' => Controls_Manager::COLOR,
                 'selectors' => [
                     '{{WRAPPER}} .wadi-accordion-content-wrapper:hover' => 'color: {{VALUE}};',
                 ],
                 
             ]
        );
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_responsive_control(
			'wadi_accordion_content_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .wadi-accordion-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before'
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_accordion_content_border',
				'label' => esc_html__( 'Border Type', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi-accordion-content-wrapper',
			]
		);


        $this->end_controls_section();

    }

    protected function render_accordion_title_icon($value, $repeater_icon) {
        $accordion_title_icon = isset( $value['__fa4_migrated']['title_icon'] );
        $wadi_accordion_title_icon   = empty( $value['wadi_title_icon'] );
        ?>
        <?php if ( isset( $value['wadi_title_icon'] ) || isset( $value['title_icon'] ) ) { ?>
            <span <?php echo wp_kses_post( $this->get_render_attribute_string( $repeater_icon ) ); ?>>
                <?php
                if ( $accordion_title_icon || $wadi_accordion_title_icon ) {
                    \Elementor\Icons_Manager::render_icon( $value['title_icon'], array( 'aria-hidden' => 'true' ) );
                } else {
                    ?>
                    <i class="<?php echo esc_attr( $value['wadi_title_icon'] ); ?>"></i>
                <?php } ?>
            </span>
        <?php }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $accordionItems = $settings['accordions'];
        $id = $this->get_id();
        $id_int = substr($this->get_id_int(), 0, 3);
        
        
        $migrated = isset( $settings['__fa4_migrated']['selected_icon'] );
		$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

		$has_icon = ( ! $is_new || ! empty( $settings['wadi_selected_icon']['value'] ) );

        $this->add_render_attribute('accordion_container', 'class', 'wadi-accordion-container');

        $this->add_render_attribute(
            'accordion_wrap',
            [
                'id' => 'wadi-accordion-'. $id,
                'class' => [
                    'wadi-accordion-wrap'
                ]
            ]
        );


        ?>

        <div <?php echo wp_kses_post($this->get_render_attribute_string('accordion_container')); ?>>
            <!-- Accordion -->
            <section class="wadi-accordion-section">
                <div <?php echo wp_kses_post($this->get_render_attribute_string('accordion_wrap')); ?>>
                <?php foreach( $accordionItems as $index => $item ) : 
                    $accordion_title_setting_key = $this->get_repeater_setting_key( 'wadi_accordion_title', 'accordions', $index );
                    
                    $this->add_render_attribute(
                        $accordion_title_setting_key,
                        [
                            'id' => 'wadi-accordion-'. $id,
                            'class' => [
                                'accordion__title',
                                'elementor-repeater-item-' . esc_attr($item['_id']),
                            ],
                            'aria-controls' => 'wadi-accordion__'. $id_int,
                        ]
                    );

                    $accordion_content_setting_key = $this->get_repeater_setting_key( 'accordion_item_content', 'accordions', $index );
                    $this->add_render_attribute(
                        $accordion_content_setting_key,
                        [
                            'id' => 'wadi-accordion__'. $id_int,
                            'class' => [
                                'accordion__content',
                                'elementor-repeater-item-' . esc_attr($item['_id']),
                            ],
                            'aria-labelledby' => 'wadi-accordion-'. $id,
                        ]
                    );
                    $this->add_inline_editing_attributes( $accordion_content_setting_key, 'advanced' );

                    $accordio_title_icon = $this->get_repeater_setting_key( 'title_icon', 'accordions', $index );
                    $this->add_render_attribute(
                        $accordio_title_icon,
                        [
                            'id' => 'wadi_accordion_icon_title_'. $id_int,
                            'class' => [
                                'wadi_accordion_icon_title',
                            ],
                            'aria-labelledby' => 'wadi-accordion-icon-title-'. $id,
                        ]
                    );

                    $accordion_item = $this->get_repeater_setting_key( 'accordion_item', 'accordions', $index );
                    $this->add_render_attribute(
                        $accordion_item,
                        [
                            'id' => 'wadi_accordion_item_'. $id_int,
                            'class' => [
                                'accordion_item',
                                'elementor-repeater-item-' . esc_attr($item['_id']),
                            ],
                            'aria-labelledby' => 'accordion_item-'. $id,
                        ]
                    );
                    ?>
                    <!-- Accordion item -->
                    <div <?php echo wp_kses_post($this->get_render_attribute_string($accordion_item)); ?> >
                        <button
                        <?php echo wp_kses_post($this->get_render_attribute_string($accordion_title_setting_key)); ?>
                        >
                        <?php
                        
                        if($item['title_icon']['value']) :
                            $this->render_accordion_title_icon($item, $accordio_title_icon);
                        endif;
                        ?>
                            <<?php Utils::print_validated_html_tag( $settings['wadi_title_html_tag'] ); echo ' class="wadi-accordion-title-text"'; ?>><?php echo wp_kses_post($item['wadi_accordion_title']); ?></<?php Utils::print_validated_html_tag( $settings['wadi_title_html_tag'] );?>>
                            <?php if ( $has_icon ) : ?>
                                <div class="wadi-accordion-icons" aria-hidden="true">
                                <?php
                                if ( $is_new || $migrated ) { ?>
                                    <span class="wadi-accordion-icon-closed"><?php Icons_Manager::render_icon( $settings['wadi_selected_icon'] ); ?></span>
                                    <span class="wadi-accordion-icon-opened"><?php Icons_Manager::render_icon( $settings['wadi_selected_active_icon'] ); ?></span>
                                <?php } else { ?>
                                    <i class="wadi-accordion-icon-closed <?php echo esc_attr( $settings['wadi_icon'] ); ?>"></i>
                                    <i class="wadi-accordion-icon-opened <?php echo esc_attr( $settings['wadi_icon_active'] ); ?>"></i>
                                <?php } ?>
                                </div>
                            <?php endif; ?>
                        </button>
                        <div
                            <?php
                            $this->print_render_attribute_string( $accordion_content_setting_key ); ?>
                            >
                            <div class="wadi-accordion-content-wrapper">
                                <?php if ('text_editor' === $item['wadi_accordion_content_type']) : ?>
                                    <p>
                                    <?php 
                                    echo $this->parse_text_editor($item['wadi_accordion_content_text']); ?>
                                    </p>
                                <?php else: 
                                    $template = $item['wadi_accordion_content_elementor_template'];
                                    
                                    echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($template); // phpcs:ignore CSRF ok.
                                 endif; ?>
                            </div>
                        </div>
                    </div>
                    <!--/ Accordion item -->

                <?php endforeach; ?>
                </div>
            </section>
            <!--/ Accordion -->
            <?php
			if ( isset( $settings['wadi_faq_schema'] ) && 'yes' === $settings['wadi_faq_schema'] ) {
				$json = [
					'@context' => 'https://schema.org',
					'@type' => 'FAQPage',
					'mainEntity' => [],
				];

                //Schema FAQ
				foreach ( $settings['accordions'] as $index => $item ) {
                        $json['mainEntity'][] = [
                            '@type' => 'Question',
                            'name' => wp_strip_all_tags( $item['wadi_accordion_title'] ),
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                // Elemnetor Template will return empty text string, if it Accordion Content Type is set to Text Editor it will return text editor text
                                'text' => $item['wadi_accordion_content_type'] === 'elementor_templates' ? 'Elementor Template' :$this->parse_text_editor( $item['wadi_accordion_content_text'] ),
                            ],
                        ];
				}
				?>
				<script type="application/ld+json"><?php echo wp_json_encode( $json ); ?></script>
			<?php } ?>
        </div>

        <?php
    }
}