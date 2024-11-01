<?php

namespace WadiAddons\Widgets;

use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Control_Color;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Utils;

// Wadi Classes
use WadiAddons\Includes\WadiHelpers;
use WadiAddons\Includes\WadiQueries;

if (! defined('ABSPATH')) {
    exit;
}

class Wadi_Blockquote extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-blockquote-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Blockquote', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-blockquote';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_register_style('style-handle_blockquote', WADI_ADDONS_URL . 'assets/min/wadi-blockquote.css', [], WADI_ADDONS_VERSION, 'all');
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
        return [ 'wadi-addons','wadi', 'blockquote', 'tweet', 'twitter' ];
    }


    public function get_style_depends()
    {
        return [ 'style-handle_blockquote' ];
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
            'wadi_blockquote_content',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_blockquote_content_skins',
            [
                'label' => esc_html__('Skins', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'classic' => esc_html__('Classic', 'wadi-addons'),
                    'bubble' => esc_html__('Bubble', 'wadi-addons'),
                    'bordered' => esc_html__('Bordered', 'wadi-addons'),
                    'quotation' => esc_html__('Quotation', 'wadi-addons'),
                    'basic' => esc_html__('Basic', 'wadi-addons'),
                ],
                'default' => 'classic',
                'prefix_class' => 'wadi_blockquote_skin__',
            ]
        );


        $content_alignment = [
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

        $rtl_content_alignment = array_reverse($content_alignment);

        $the_content_alignment = is_rtl() ? $rtl_content_alignment : $content_alignment;
        

        $this->add_control(
			'wadi_blockquote_content_alignment',
			[
				'label' => esc_html__( 'Content Alignment', 'wadi-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => $the_content_alignment,
                'description' => esc_html__('Note: changes in styles options might affect the alignement of blockquote.','wadi-addons'),
                'prefix_class' => 'wadi_blockquote_alignment__',
                'selectors_dictionary' => [
                    'left' => 'left',
                    'center' => 'center',
                    'right' => 'right',
                ],
                'selectors' => [
                    '{{WARPPER}} .wadi_blockquote_content_wrapper' => 'text-align: {{VALUE}};',
                ],
				'condition' => [
					'wadi_blockquote_content_skins!' => 'bordered',
				],
				'separator' => 'after',
			]
		);

        $this->add_control(
            'wadi_blockquote_content_text',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => esc_attr('Mauris tortor nisi, porta at molestie porta, aliquet quis arcu. Duis luctus tortor eu ex tempus elementum. Donec id nisl turpis. Vivamus id posuere elit. Suspendisse sed leo viverra turpis volutpat eleifend. Aliquam eros massa, aliquet nec volutpat eu, iaculis in sem. Pellentesque vehicula id erat vitae vestibulum. Fusce in lacus id enim venenatis ornare sed tristique nisl. Phasellus a lectus enim. Nunc interdum odio eu mauris convallis aliquet. Phasellus porttitor dignissim diam nec molestie.' ),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'wadi_blockquote_author',
            [
                'label' => esc_html__('Author', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('John Doe', 'wadi-addons'),
                'placeholder' => esc_html__( 'Type author name here', 'wadi-addons' ),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_tweet_section',
            [
                'label' => esc_html__('Tweet', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'wadi_blockquote_tweet_button_switch',
            [
                'label' => esc_html__('Tweet Button', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'separator' => 'before',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'wadi_blockquote_tweet_button',
            [
                'label' => esc_html__('Tweet Button Type', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'text_icon' => esc_html__('Text & Icon', 'wadi-addons'),
                    'icon' => esc_html__('Icon', 'wadi-addons'),
                    'text' => esc_html__('Text', 'wadi-addons'),
                ],
                'default' => 'text_icon',
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );

        $start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';

        $tweet_link_position = [
            $start  => [
                'title' => __('Left', 'wadi-addons'),
                'icon'  => 'eicon-h-align-left',
            ],
            $end => [
                'title' => __('Right', 'wadi-addons'),
                'icon'  => 'eicon-h-align-right',
            ],
        ];

        $rtl_tweet_link_position = array_reverse($tweet_link_position);

        $the_tweet_link_position = is_rtl() ? $rtl_tweet_link_position : $tweet_link_position;

        $this->add_control(
            'wadi_blockquote_tweet_button_text_position',
            [
                'label' => esc_html__('Tweet Text Alignment', 'wadi-addons'),
                'type'      => Controls_Manager::CHOOSE,
                'toggle'    => true,
                'options'   => $the_tweet_link_position,
                'default'   => 'right',
                'selectors_dictionary' => [
                    'left' => 'row',
                    'right' => 'row-reverse',
                ],
                'selectors' => [
                    '{{WARPPER}} .wadi_blockquote_tweet_link' => 'flex-direction:{{VALUE}};',
                ],
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_blockquote_tweet_label',
            [
                'label' => esc_html__('Label', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tweet', 'wadi-addons'),
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_blockquote_tweet_username',
            [
                'label' => esc_html__('Username', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('@username', 'wadi-addons'),
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_blockquote_tweet_url',
            [
                'label' => esc_html__('URL', 'wadi-addons'),
                'type' => Controls_Manager::SELECT2,
                'options' => [
                    'current_page' => esc_html__('Current Page', 'wadi-addons'),
                    'custom_page' => esc_html__('Custom Page', 'wadi-addons'),
                    'none' => esc_html__('None', 'wadi-addons'),
                ],
                'default' => 'current_page',
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_blockquote_tweet_custom_url',
            [
                'label' => esc_html__('Link', 'wadi-addons'),
                'type' => Controls_Manager::URL,
                'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
                'condition' => [
                    'wadi_blockquote_tweet_url' => 'custom_page',
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_blockquote_content_styling',
            [
                'label' => esc_html__('Blockquote Box', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wadi_blockquote_content_styling_heading',
            [
                'label' => esc_html__('Blockquote Content', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'wadi_blockquote_content_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_content' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_blockquote_content_typography',
				'selector' => '{{WRAPPER}} .wadi_blockquote_content',
			]
		);

        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wadi_blockquote_content_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_blockquote_content_wrapper',
			]
		);

        $this->add_responsive_control(
			'wadi_blockquote_content_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_content_wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_blockquote_content_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_blockquote_content_wrapper',
                'condition' => [
                    'wadi_blockquote_content_skins!' => 'bubble',
                ],
			]
		);

        $this->add_responsive_control(
			'wadi_blockquote_content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_content_wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


        $this->add_responsive_control(
			'wadi_blockquote_content_spacing',
			[
				'label' => esc_html__( 'Spacing', 'wadi-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem','%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
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
				'default' => [
					'unit' => 'rem',
					'size' => 1,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_content_wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'wadi_blockquote_container_heading',
            [
                'label' => esc_html__('Blockquote Container', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wadi_blockquote_container_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_blockquote_container',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_blockquote_container_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_blockquote_container',
			]
		);

        $this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_blockquote_container_box_shadow',
				'selector' => '{{WRAPPER}} .wadi_blockquote_container',
			]
		);

        $this->add_responsive_control(
			'wadi_blockquote_container_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'wadi_blockquote_container_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'wadi_blockquote_container_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_blockquote_footer_styling',
            [
                'label' => esc_html__('Blockquote Footer', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'wadi_blockquote_author_styling',
            [
                'label' => esc_html__('Blockquote Author', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'wadi_blockquote_author_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_author' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_blockquote_author_typography',
				'selector' => '{{WRAPPER}} .wadi_blockquote_author',
			]
		);


        $this->add_responsive_control(
			'wadi_blockquote_author_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_author' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->add_control(
            'wadi_blockquote_tweet_styling',
            [
                'label' => esc_html__('Blockquote Tweet', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs(
            'wadi_blockquote_tweet_style_tabs'
        );
        
        $this->start_controls_tab(
            'wadi_blockquote_tweet_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
            ]
        );

        
        $this->add_control(
            'wadi_blockquote_tweet_color',
            [
                'label' => esc_html__('Text Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_tweet > a, {{WRAPPER}} .wadi_blockquote_tweet' => 'color: {{VALUE}}',
				],
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );


        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wadi_blockquote_tweet_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_blockquote_tweet > a',
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
			]
		);
        
        
        $this->end_controls_tab();

        $this->start_controls_tab(
            'wadi_blockquote_tweet_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_blockquote_tweet_color_hover',
            [
                'label' => esc_html__('Text Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_tweet > a:hover, {{WRAPPER}} .wadi_blockquote_tweet' => 'color: {{VALUE}}',
				],
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
            ]
        );


        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'wadi_blockquote_tweet_background_hover',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_blockquote_tweet > a:hover',
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
			]
		);

        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();



        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_blockquote_tweet_typography',
				'selector' => '{{WRAPPER}} .wadi_blockquote_tweet > a',
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
			]
		);

        $this->add_responsive_control(
			'wadi_blockquote_tweet_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_tweet > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
			]
		);
        $this->add_responsive_control(
			'wadi_blockquote_tweet_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_tweet > a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
			]
		);
        $this->add_responsive_control(
			'wadi_blockquote_tweet_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px','rem', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_blockquote_tweet > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_blockquote_tweet_button_switch' => 'yes',
                ],
			]
		);


        $this->end_controls_section();
    }

    protected function render()
    {

        $settings = $this->get_settings_for_display();

        $tweet_button_view = $settings['wadi_blockquote_tweet_button'];
        $tweet_button_label = $settings['wadi_blockquote_tweet_label'];

        $share_link = 'https://twitter.com/intent/tweet';

        $cleanedText = wp_strip_all_tags($settings['wadi_blockquote_content_text']);
        $text = rawurlencode( $cleanedText );
        

		if ( ! empty( $settings['wadi_blockquote_author'] ) ) {
			$text .= ' — ' . $settings['wadi_blockquote_author'];
		}

        $share_link = add_query_arg( 'text', $text, $share_link );

        if ( 'current_page' === $settings['wadi_blockquote_tweet_url'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( home_url() . add_query_arg( false, false ) ), $share_link );
		} elseif ( 'custom_page' === $settings['wadi_blockquote_tweet_url'] ) {
			$share_link = add_query_arg( 'url', rawurlencode( $settings['wadi_blockquote_tweet_custom_url']['url'] ), $share_link );
		}
        
        if ( ! empty( $settings['wadi_blockquote_tweet_username'] ) ) {
			$user_name = $settings['wadi_blockquote_tweet_username'];
			if ( '@' === substr( $user_name, 0, 1 ) ) {
				$user_name = substr( $user_name, 1 );
			}
			$share_link = add_query_arg( 'via', rawurlencode( $user_name ), $share_link );
		}


        $this->add_inline_editing_attributes( 'wadi_blockquote_content_text', 'basic' );
        $this->add_inline_editing_attributes( 'wadi_blockquote_author', 'none' );
		$this->add_inline_editing_attributes( 'wadi_blockquote_tweet_label', 'none' );

        ?>

        <div class="wadi_blockquote_container">
            <div class="wadi_blockquote_content_wrapper">
                <?php if ('' !== $settings['wadi_blockquote_content_text']) : ?>
                <div class="wadi_blockquote_content"  <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_blockquote_content_text' ) ); ?>>
                    <?php echo $this->parse_text_editor( $settings['wadi_blockquote_content_text'] ); // phpcs:ignore CSRF ok. ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="wadi_blockquote_footer">
                <?php if ( '' !== $settings['wadi_blockquote_author'] ) : ?>
                <div class="wadi_blockquote_author" <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_blockquote_author' ) ); ?>>
                    <?php echo wp_kses_post( $settings['wadi_blockquote_author'] ); ?>
                </div>
                <?php endif; ?>
                <?php if( $settings['wadi_blockquote_tweet_button_switch'] === 'yes') : ?>
                <div class="wadi_blockquote_tweet">
                    <a href="<?php echo esc_attr( $share_link ); ?>" class="wadi_blockquote_tweet_link" target="_blank">
                        <?php if( $tweet_button_view === 'text_icon' ) : ?>
                        <span class="wadi_blockquote_tweet_text" <?php echo wp_kses_post( $this->get_render_attribute_string('wadi_blockquote_tweet_label') ); ?>><?php echo wp_kses_post($tweet_button_label); ?></span>
                        <span class="wadi_blockquote_tweet_icon">
                        <?php
								$icon = [
									'value' => 'fab fa-twitter',
									'library' => 'fa-brands',
								];
								if ( ! Icons_Manager::is_migration_allowed() || ! Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ) ) : ?>
									<i class="fa fa-twitter" aria-hidden="true"></i>
								<?php endif; ?>

                        </span>
                        <?php elseif( $tweet_button_view === 'icon' ) : ?>
                        <span class="wadi_blockquote_tweet_icon">
                        <?php
								$icon = [
									'value' => 'fab fa-twitter',
									'library' => 'fa-brands',
								];
								if ( ! Icons_Manager::is_migration_allowed() || ! Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ) ) : ?>
									<i class="fa fa-twitter" aria-hidden="true"></i>
								<?php endif; ?>

                        </span>
                        <?php elseif( $tweet_button_view === 'text' && '' !== $settings['wadi_blockquote_tweet_label'] ): ?>
                        <span class="wadi_blockquote_tweet_text" <?php echo wp_kses_post( $this->get_render_attribute_string('wadi_blockquote_tweet_label') ); ?>><?php echo wp_kses_post($tweet_button_label); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <?php

    }

    protected function content_template()
    {
        ?>
        <#
        var tweetButtonView = settings.wadi_blockquote_tweet_button;

		view.addInlineEditingAttributes( 'wadi_blockquote_content_text', 'basic' );
        view.addInlineEditingAttributes( 'wadi_blockquote_author', 'none' );
        view.addInlineEditingAttributes( 'wadi_blockquote_tweet_label', 'none' );

        #>

        <div class="wadi_blockquote_container">
            <div class="wadi_blockquote_content_wrapper">
                <# 
                    if('' != settings.wadi_blockquote_content_text) { #>

                        <div class="wadi_blockquote_content elementor-inline-editing" {{{ view.getRenderAttributeString('wadi_blockquote_content_text') }}}>{{{settings.wadi_blockquote_content_text}}}</div>
                    <# } #>
            </div>
            <div class="wadi_blockquote_footer">
                <# if('' != settings.wadi_blockquote_author) { #>
                    <div class="wadi_blockquote_author elementor-inline-editing" {{{ view.getRenderAttributeString('wadi_blockquote_author') }}}>{{{settings.wadi_blockquote_author}}}</div>
                <# } #>
                
                <# if( 'yes' == settings.wadi_blockquote_tweet_button_switch ) { #>
                    <div class="wadi_blockquote_tweet">
                        <a href="#" class="wadi_blockquote_tweet_link" target="_blank">
                            <# if( tweetButtonView == 'text_icon') { #>
                                    <span class="wadi_blockquote_tweet_text elementor-inline-editing" {{{ view.getRenderAttributeString('wadi_blockquote_tweet_label') }}}>{{{ settings.wadi_blockquote_tweet_label }}}</span>
                                    <span class="wadi_blockquote_tweet_icon">
                                        <# if ( ! elementor.config.icons_update_needed ) { #>
                                            <i class="fab fa-twitter" aria-hidden="true"></i>
                                        <# } else { #>
                                            <i class="fa fa-twitter" aria-hidden="true"></i>
                                        <# } #>
                                    </span>
                                    <#  } else if (tweetButtonView == 'text') { #>
                                    <span class="wadi_blockquote_tweet_text elementor-inline-editing" {{{ view.getRenderAttributeString('wadi_blockquote_tweet_label') }}}>{{{ settings.wadi_blockquote_tweet_label }}}</span>
                                    <#  } else if(tweetButtonView == 'icon') { #>
                                    <span class="wadi_blockquote_tweet_icon">
                                        <# if ( ! elementor.config.icons_update_needed ) { #>
                                            <i class="fab fa-twitter" aria-hidden="true"></i>
                                        <# } else { #>
                                            <i class="fa fa-twitter" aria-hidden="true"></i>
                                        <# } #>
                                    </span>

                                    <#
                                }
                            #>

                        </a>
                    </div>
                <# } #>

            </div>

        </div>

        <?php
    }
}
