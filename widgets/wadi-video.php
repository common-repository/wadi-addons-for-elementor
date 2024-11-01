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
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;

// Wadi Classes
use WadiAddons\Includes\WadiHelpers;
use WadiAddons\Includes\WadiQueries;

if (! defined('ABSPATH')) {
    exit;
}

class Wadi_Video extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-video-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Video', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-video';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_register_script('script-handle_video', WADI_ADDONS_URL . 'assets/min/wadi-video.min.js', [ 'elementor-frontend' , 'jquery'], '1.0.0', true);
        if(!is_rtl()) {
            wp_register_style('style-handle_video', WADI_ADDONS_URL . 'assets/min/wadi-video.css', array());
        } else {
            wp_register_style('style-handle_video', WADI_ADDONS_URL . 'assets/min/wadi-video.rtl.css', array());
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
        return [ 'wadi-addons', 'video', 'youtube', 'vimeo','dailymotion' , 'media', 'self hosted' ,'custom video' ];
    }


    public function get_script_depends()
    {
        return [ 'elementor-waypoints', 'script-handle_video' ];
    }

    public function get_style_depends()
    {
        return [ 'style-handle_video' ];
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
            'section_video_settings',
            [
                'label' => __('Video Settings', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_video_type',
            [
                'label' => __('Video Type', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => __('Youtube', 'wadi-addons'),
                    'vimeo' => __('Vimeo', 'wadi-addons'),
                    'dailymotion' => __('dailymotion', 'wadi-addons'),
                    'self_hosted' => __('Self Hosted', 'wadi-addons'),
                ],
                'label_block' => true,
                'frontend_available' => true,
            ]
        );


        $this->add_control(
            'youtube_url',
            [
                'label' => esc_html__('Video URL', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'placeholder' => __('Enter your video url', 'wadi-addons'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                    'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
                ],
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'vimeo_url',
            [
                'label' => esc_html__('Vimeo URL', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'https://vimeo.com/235215203',
                'placeholder' => __('Enter your vimeo url', 'wadi-addons'),
                'label_block' => true,
                'condition' => [
                    'wadi_video_type' => 'vimeo',
                ],
                'dynamic' => [
                    'active' => true,
                    'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
			'dailymotion_url',
			[
				'label' => esc_html__( 'Link', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'elementor' ) . ' (Dailymotion)',
				'default' => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition' => [
					'wadi_video_type' => 'dailymotion',
				],
			]
		);

        $this->add_control(
            'wadi_self_hosted_video',
            [
                'label' => esc_html__('Self Hosted URL', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'local',
                'options' => [
                    'local' => esc_html__('Local', 'wadi-addons'),
                    'remote' => esc_html__('Remote', 'wadi-addons'),
                ],
                'label_block' => true,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'self_hosted_url',
            [
                'label' => esc_html__('Self Hosted URL', 'wadi-addons'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                    'wadi_self_hosted_video' => 'local',
                ],
                'dynamic' => [
                    'active' => true,
                    'categories' => array(
						TagsModule::MEDIA_CATEGORY,
					),
                ],
                'media_type' => 'video',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'self_hosted_remote_url',
            [
                'label' => esc_html__('Self Hosted Remote URL', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Enter your self hosted remote url', 'wadi-addons'),
                'label_block' => true,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                    'wadi_self_hosted_video' => 'remote',
                ],
                'dynamic' => [
                    'active' => true,
                    'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_video_self_hosted_start',
            [
                'label' => esc_html__('Start Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_video_self_hosted_end',
            [
                'label' => esc_html__('End Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_video_self_hosted_autoplay',
            [
                'label' => esc_html__('Autoplay', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                    'wadi_video_lightbox_switch!' => 'yes'
                ],
            ]
        );

        $this->add_control(
            'wadi_video_self_hosted_loop',
            [
                'label' => esc_html__('Loop', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
            ]
        );

        $this->add_control(
            'wadi_video_self_hosted_controls',
            [
                'label' => esc_html__('Controls', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
            ]
        );
        
        $this->add_control(
            'wadi_video_self_hosted_playsinline',
            [
                'label' => esc_html__('Play Inline', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
            ]
        );

        $this->add_control(
            'wadi_video_self_hosted_muted',
            [
                'label' => esc_html__('Mute', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
            ]
        );

        $this->add_control(
			'wadi_video_self_hosted_download_button',
			[
				'label' => esc_html__( 'Download Button', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on' => esc_html__( 'Show', 'elementor' ),
                'default' => 'Show',
                'return_value' => 'yes',
				'condition' => [
					'wadi_video_type' => 'self_hosted',
				],
			]
		);

        $this->add_control(
            'wadi_video_self_hosted_poster',
            [
                'label' => __('Video Image (Poster)', 'wadi-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'video_image_size',
            [
                'label' => __('Image Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 100,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'condition' => [
                    'wadi_video_type' => 'self_hosted',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_youtube_privacy_switch',
            [
                'label' => __('Youtube Privacy', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );


        $this->add_control(
            'wadi_youtube_video_start',
            [
                'label' => __('Video Start Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );

        
        $this->add_control(
            'wadi_youtube_video_end',
            [
                'label' => __('Video End Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );


        $this->add_control(
            'wadi_youtube_video_modestbranding',
            [
                'label' => __('Modest Branding', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_youtube_video_autoplay',
            [
                'label' => __('Youtube Autoplay', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'youtube',
                    'wadi_video_lightbox_switch!' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_youtube_video_rel',
            [
                'label'     => __( 'Related Videos From', 'wadi-addons' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'no',
                'options'   => [
                    'no'  => esc_html__( 'Current Video Channel', 'wadi-addons' ),
                    'yes' => esc_html__( 'Any Random Video', 'wadi-addons' ),
                ],
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
            ]
        );

        $this->add_control(
            'wadi_youtube_video_loop',
            [
                'label' => __('Loop', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_youtube_video_mute',
            [
                'label' => __('Mute', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_youtube_video_controls',
            [
                'label' => __('Controls', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_vimeo_video_autoplay',
            [
                'label' => __('Vimeo Autoplay', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'vimeo',
                    'wadi_video_lightbox_switch!' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_vimeo_video_loop',
            [
                'label' => __('Vimeo Loop', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'vimeo',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_vimeo_video_muted',
            [
                'label' => __('Vimeo Mute', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'vimeo',
                ],
                'frontend_available' => true,
            ]
        );

        
        $this->add_control(
            'wadi_vimeo_video_headers',
            [
                'label' => __('Vimeo Headers', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'wadi_video_type',
                            'operator' => '==',
                            'value' => 'vimeo',
                        ],
                        [
                            'relation' => 'or',
                            'terms' => [
                                [
                                    'name' => 'wadi_vimeo_video_title',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'wadi_vimeo_video_portrait',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ],
                                [
                                    'name' => 'wadi_vimeo_video_byline',
                                    'operator' => '==',
                                    'value' => 'yes',
                                ],
                            ]
                        ]

                    ]
                ],
                'frontend_available' => true,
            ]
        );


        $this->add_control(
            'wadi_vimeo_video_title',
            array(
                'label'     => __( 'Intro Title', 'wadi-addons' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'wadi-addons' ),
                'label_on'  => __( 'Show', 'wadi-addons' ),
                'default'   => 'yes',
                'condition' => array(
                    'wadi_video_type' => 'vimeo',
                ),
            )
        );

        // $this->add_control(
        //     'wadi_vimeo_video_controls',
        //     [
        //         'label' => __('Vimeo Controls', 'wadi-addons'),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'no',
        //         'label_on' => __('Yes', 'wadi-addons'),
        //         'label_off' => __('No', 'wadi-addons'),
        //         'return_value' => 'yes',
        //         'condition' => [
        //             'wadi_video_type' => 'vimeo',
        //         ],
        //         'frontend_available' => true,
        //     ]
        // );

        $this->add_control(
            'wadi_vimeo_video_portrait',
            array(
                'label'     => __( 'Intro Portrait', 'wadi-addons' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'wadi-addons' ),
                'label_on'  => __( 'Show', 'wadi-addons' ),
                'default'   => 'yes',
                'condition' => array(
                    'wadi_video_type' => 'vimeo',
                ),
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'wadi_vimeo_video_byline',
            array(
                'label'     => __( 'Intro Byline', 'wadi-addons' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'wadi-addons' ),
                'label_on'  => __( 'Show', 'wadi-addons' ),
                'default'   => 'yes',
                'condition' => array(
                    'wadi_video_type' => 'vimeo',
                ),
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'wadi_vimeo_video_start',
            [
                'label' => __('Vimeo Start Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'wadi_video_type' => 'vimeo',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_dailymotion_video_autoplay',
            [
                'label' => __('Dailymotion Autoplay', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_video_type' => 'dailymotion',
                    'wadi_video_lightbox_switch!' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_dailymotion_video_start',
            [
                'label' => __('Dailymotion Start Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'wadi_video_type' => 'dailymotion',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
			'wadi_dailymotion_video_controls',
			[
				'label' => esc_html__( 'Dailymotion Controls', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on' => esc_html__( 'Show', 'elementor' ),
				'default' => 'yes',
				'condition' => [
					'wadi_video_type' => [ 'dailymotion' ],
				],
			]
		);
        $this->add_control(
			'wadi_dailymotion_video_mute',
			[
				'label' => esc_html__( 'Dailymotion Mute', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Yes', 'elementor' ),
				'label_on' => esc_html__( 'No', 'elementor' ),
				'default' => 'no',
				'condition' => [
					'wadi_video_type' => [ 'dailymotion' ],
				],
			]
		);

        $this->add_control(
			'wadi_dailymotion_video_showinfo',
			[
				'label' => esc_html__( 'Dailymotion Info', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on' => esc_html__( 'Show', 'elementor' ),
				'default' => 'yes',
				'condition' => [
					'wadi_video_type' => [ 'dailymotion' ],
				],
			]
		);

        $this->add_control(
			'wadi_dailymotion_video_logo',
			[
				'label' => esc_html__( 'Dailymotion Logo', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'elementor' ),
				'label_on' => esc_html__( 'Show', 'elementor' ),
				'default' => 'yes',
				'condition' => [
					'wadi_video_type' => [ 'dailymotion' ],
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label' => esc_html__( 'Controls Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
					'wadi_video_type' => [ 'vimeo', 'dailymotion' ],
				],
			]
		);

        $this->add_control(
            'wadi_video_sticky_switch',
            [
                'label' => __('Sticky', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_lightbox_switch!' => 'yes', 
                ]
            ]
        );


        $this->add_control(
            'wadi_video_lightbox_switch',
            [
                'label' => __('Lightbox', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_type!' => 'dailymotion',
                    'wadi_video_sticky_switch!' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_video_overlay_section',
            [
                'label' => __( 'Overlay', 'wadi-addons' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        
			$this->add_control(
				'wadi_video_youtube_thumbnail_size',
				array(
					'label'     => __( 'Thumbnail Size', 'wadi-addons' ),
					'type'      => Controls_Manager::SELECT,
					'options'   => array(
						'maxresdefault' => __( 'Maximum Resolution', 'wadi-addons' ),
						'hqdefault'     => __( 'High Quality', 'wadi-addons' ),
						'mqdefault'     => __( 'Medium Quality', 'wadi-addons' ),
						'sddefault'     => __( 'Standard Quality', 'wadi-addons' ),
					),
					'default'   => 'maxresdefault',
					'condition' => array(
						'wadi_video_type' => 'youtube',
					),
				)
			);

			$this->add_control(
				'show_image_overlay',
				array(
					'label'        => __( 'Custom Thumbnail', 'wadi-addons' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_off'    => __( 'No', 'wadi-addons' ),
					'label_on'     => __( 'Yes', 'wadi-addons' ),
					'default'      => 'no',
					'return_value' => 'yes',
				)
			);

			$this->add_control(
				'image_overlay',
				array(
					'label'     => __( 'Select Image', 'wadi-addons' ),
					'type'      => Controls_Manager::MEDIA,
					'default'   => array(
						'url' => Utils::get_placeholder_image_src(),
					),
					'dynamic'   => array(
						'active' => true,
					),
					'condition' => array(
						'show_image_overlay' => 'yes',
					),
				)
			);

			$this->add_group_control(
				Group_Control_Image_Size::get_type(),
				array(
					'name'      => 'image_overlay', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_overlay_size` and `image_overlay_custom_dimension` phpcs:ignore Squiz.PHP.CommentedOutCode.Found.
					'default'   => 'full',
					'separator' => 'none',
					'condition' => array(
						'show_image_overlay' => 'yes',
					),
				)
			);




        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_video_lightbox_section',
            [
                'label' => __('Lightbox', 'wadi-addons'),
                'condition' => [
                    'wadi_video_type!' => 'dailymotion',
                    'wadi_video_lightbox_switch' => 'yes',
                    'wadi_video_sticky_switch!' => 'yes',
                ],
            ]
        );


        $this->add_responsive_control(
			'lightbox_content_animation',
			[
				'label' => esc_html__( 'Entrance Animation', 'elementor' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
                'condition' => [
                    'wadi_video_type!' => 'dailymotion',
                    'wadi_video_lightbox_switch' => 'yes',
                    'wadi_video_sticky_switch!' => 'yes',
                ],
			]
		);

        $this->add_control(
			'aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
					'916' => '9:16',
				],
				'default' => '169',
				'prefix_class' => 'elementor-aspect-ratio-',
				'frontend_available' => true,
                'condition' => [
                    'wadi_video_type!' => 'dailymotion',
                    'wadi_video_lightbox_switch' => 'yes',
                    'wadi_video_sticky_switch!' => 'yes',
                ],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_video_sticky_section',
            [
                'label' => __('Sticky', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'wadi_video_lightbox_switch!' => 'yes',
                    'wadi_video_sticky_switch' => 'yes',
                ],
            ]
        );

        // $this->add_control(
        //     'wadi_video_sticky_switch',
        //     [
        //         'label' => __('Sticky', 'wadi-addons'),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'no',
        //         'label_on' => __('Yes', 'wadi-addons'),
        //         'label_off' => __('No', 'wadi-addons'),
        //         'return_value' => 'yes',
        //         'frontend_available' => true,
        //         'condition' => [
        //             'wadi_video_lightbox_switch!' => 'yes',
        //             'wadi_video_sticky_switch' => 'yes',
        //         ],
        //     ]
        // );

        $this->add_responsive_control(
            'wadi_video_sticky_width',
            [
                'label' => __('Sticky Video Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 4000,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 320,
                ],
				'mobile_default' => [
					'size' => 250,
					'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}}.wadi-aspect-ratio-11 .wadi_video_outer_container_sticky .wadi_video_inner_wrapper' => 'width: {{SIZE}}{{UNIT}};  height: calc( {{SIZE}}{{UNIT}} * 1);',
                    '{{WRAPPER}}.wadi-aspect-ratio-219 .wadi_video_outer_container_sticky .wadi_video_inner_wrapper' => 'width: {{SIZE}}{{UNIT}};  height: calc( {{SIZE}}{{UNIT}} *  0.4285 );',
                    '{{WRAPPER}}.wadi-aspect-ratio-916 .wadi_video_outer_container_sticky .wadi_video_inner_wrapper' => 'width: {{SIZE}}{{UNIT}};  height: calc( {{SIZE}}{{UNIT}} *  0.1778 );',
                    '{{WRAPPER}}.wadi-aspect-ratio-32 .wadi_video_outer_container_sticky .wadi_video_inner_wrapper' => 'width: {{SIZE}}{{UNIT}};  height: calc( {{SIZE}}{{UNIT}} * 0.6666666666666667 );',
                    '{{WRAPPER}}.wadi-aspect-ratio-43 .wadi_video_outer_container_sticky .wadi_video_inner_wrapper' => 'width: {{SIZE}}{{UNIT}};  height: calc( {{SIZE}}{{UNIT}} *  0.75 );',
                    '{{WRAPPER}}.wadi-aspect-ratio-169 .wadi_video_outer_container_sticky .wadi_video_inner_wrapper' => 'width: {{SIZE}}{{UNIT}};  height: calc( {{SIZE}}{{UNIT}} *   0.5625);',
                ],
                'condition' => [
                    'wadi_video_lightbox_switch!' => 'yes',
                    'wadi_video_sticky_switch' => 'yes',
                ],
            ]
        );

        $left_direction = is_rtl() ? 'right' : 'left';

		$right_direction = is_rtl() ? 'left' : 'right';

        $this->add_control(
            'wadi_video_sticky_position',
            [
                'label' => __('Sticky Position', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'top_left',
                'options' => [
					'top_left'     => sprintf( esc_html__( 'Top %s', 'wadi-addons' ), ucfirst( $left_direction ) ),
					'top_right'    => sprintf( esc_html__( 'Top %s', 'wadi-addons' ), ucfirst( $right_direction ) ),
					'bottom_left'  => sprintf( esc_html__( 'Bottom %s', 'wadi-addons' ), ucfirst( $left_direction ) ),
					'bottom_right' => sprintf( esc_html__( 'Bottom %s', 'wadi-addons' ), ucfirst( $right_direction ) ),
					'center_left'  => sprintf( esc_html__( 'Center %s', 'wadi-addons' ), ucfirst( $left_direction ) ),
					'center_right' => sprintf( esc_html__( 'Center %s', 'wadi-addons' ), ucfirst( $right_direction ) ),
                ],
                'condition' => [
                    'wadi_video_lightbox_switch!' => 'yes',
                    'wadi_video_sticky_switch' => 'yes',
                ],
                'prefix_class' => 'wadi_video_sticky_',
				'render_type'  => 'template',
            ]
        );

        $this->add_responsive_control(
            'wadi_video_sticky_spacing',
            [
                'label' => __('Sticky Spacing', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'description' => esc_html__('Set spacing between sticky and the document edge.', 'wadi-addons'),
                'size_units' => ['px', 'rem', '%', 'em'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .wadi_video_inner_wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_lightbox_switch!' => 'yes',
                    'wadi_video_sticky_switch' => 'yes',
                ],
            ]
        );


        $this->add_control(
            'wadi_sticky_hide_on',
            array(
                'label'              => __( 'Hide Sticky Video on', 'wadi-addons' ),
                'type'               => Controls_Manager::SELECT2,
                'multiple'           => true,
                'label_block'        => true,
                'options'            => array(
                    'desktop' => __( 'Desktop', 'wadi-addons' ),
                    'tablet'  => __( 'Tablet', 'wadi-addons' ),
                    'mobile'  => __( 'Mobile', 'wadi-addons' ),
                ),
                'condition'          => array(
                    'wadi_video_lightbox_switch!' => 'yes',
                    'wadi_video_sticky_switch' => 'yes',
                ),
                'render_type'        => 'template',
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'wadi_video_close_sticky',
            [
                'label' => __('Close Button', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_lightbox_switch!' => 'yes',
                    'wadi_video_sticky_switch' => 'yes',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_video_play_icon',
            [
                'label' => esc_html__('Play Icon', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_video_play_icon_switch',
            [
                'label' => __('Play Icon', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'wadi_video_play_icon_vertical_position',
            [
                'label' => __('Vertical Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_play_icon' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_video_play_icon_horizontal_position',
            [
                'label' => __('Horizontal Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_play_icon' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Video Text Section
        $this->start_controls_section(
            'wadi_video_text_section',
            [
                'label' => esc_html__('Video Text', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_video_text_switch',
            [
                'label' => __('Video Text', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_video_text_text',
            [
                'label' => __('Text', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Watch Video', 'wadi-addons'),
                'placeholder' => __('Watch Video', 'wadi-addons'),
                'frontend_available' => true,
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_video_text_vertical_position',
            [
                'label' => __('Vertical Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_text' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_video_text_horizontal_position',
            [
                'label' => __('Horizontal Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_text' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
        

        $this->start_controls_section(
            'wadi_video_section_style',
            [
                'label' => __('Video', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_control(
			'wadi_video_aspect_ratio',
			[
				'label' => esc_html__( 'Aspect Ratio', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'169' => '16:9',
					'219' => '21:9',
					'43' => '4:3',
					'32' => '3:2',
					'11' => '1:1',
					'916' => '9:16',
				],
				'default' => '169',
				'prefix_class' => 'wadi-aspect-ratio-',
				'frontend_available' => true,
			]
		);


        $this->add_control(
            'wadi_video_border_radius',
            [
                'label' => __('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem' ,'%', 'em'],
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
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .video_outer_container iframe.wadi_video_iframe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .video_outer_container .wadi_self_hosted_video_container.show_video video.wadi_video_player' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .video_outer_container .wadi_video_thumb video.wadi_video_thumbnail' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wadi_video_border',
                'label' => __('Border', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_thumbnail, {{WRAPPER}} .video_outer_container iframe.wadi_video_iframe, {{WRAPPER}} .video_outer_container .wadi_self_hosted_video_container.show_video video.wadi_video_player, {{WRAPPER}} .video_outer_container .wadi_video_thumb video.wadi_video_thumbnail'
            ]
        );

        // box shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wadi_video_box_shadow',
                'label' => esc_html__('Box Shadow', 'wadi-addons'),
                'description' => esc_html__('Add box shadow to video container', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_thumbnail, {{WRAPPER}} .video_outer_container iframe.wadi_video_iframe, {{WRAPPER}} .video_outer_container .wadi_self_hosted_video_container.show_video video.wadi_video_player, {{WRAPPER}} .video_outer_container .wadi_video_thumb video.wadi_video_thumbnail'
            ]
        );

        $this->end_controls_section();

        // Play Icon
        $this->start_controls_section(
            'wadi_video_play_icon_style',
            [
                'label' => __('Play Icon', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // icon size

        $this->add_responsive_control(
            'wadi_video_play_icon_size',
            [
                'label' => __('Icon Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem' ,'%', 'em'],
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
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );
        

        $this->add_control(
            'wadi_video_play_icon_color',
            [
                'label' => __('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_play_icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_video_play_icon_background_color',
            [
                'label' => __('Background Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wadi_video_play_icon_border',
                'label' => __('Border', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_play_icon i',
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_video_play_icon_border_radius',
            [
                'label' => __('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem' ,'%', 'em'],
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
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wadi_video_play_icon_box_shadow',
                'label' => esc_html__('Box Shadow', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_play_icon i',
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // icon padding slider
        $this->add_responsive_control(
            'wadi_video_play_icon_padding',
            [
                'label' => __('Padding', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem' ,'%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'padding: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_video_text_style',
            [
                'label' => __('Video Text', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        $this->add_control(
            'wadi_video_text_color',
            [
                'label' => __('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_text' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'wadi_video_text_typography',
                'label' => __('Typography', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .video_outer_container .wadi_video_text',
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // background
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wadi_video_text_background',
                'label' => __('Background', 'wadi-addons'),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .video_outer_container .wadi_video_text',
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wadi_video_text_border',
                'label' => __('Border', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .video_outer_container .wadi_video_text',
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // border radius
        $this->add_control(
            'wadi_video_text_border_radius',
            [
                'label' => __('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem' ,'%', 'em'],
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
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_video_text_padding',
            [
                'label' => __('Padding', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // margin
        $this->add_responsive_control(
            'wadi_video_text_margin',
            [
                'label' => __('Margin', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .video_outer_container .wadi_video_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_sticky_styling_section',
            [
                'label' => __('Sticky Video', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                ],
            ]
        );

        // Heading Sticky Play Icon
        $this->add_control(
            'wadi_sticky_play_icon_heading',
            [
                'label' => __('Sticky Play Icon', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Icon Vertical Position Sticky
        $this->add_control(
            'wadi_sticky_play_icon_vertical_position',
            [
                'label' => __('Vertical Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_play_icon' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Icon Horizontal Position Sticky
        $this->add_control(
            'wadi_sticky_play_icon_horizontal_position',
            [
                'label' => __('Horizontal Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_play_icon' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Icon size
        $this->add_responsive_control(
            'wadi_sticky_play_icon_size',
            [
                'label' => __('Icon Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'rem', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Play Icon Sticky
        $this->add_control(
            'wadi_sticky_play_icon_color',
            [
                'label' => __('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Icon padding
        $this->add_responsive_control(
            'wadi_sticky_play_icon_padding',
            [
                'label' => __('Padding', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Icon margin
        $this->add_responsive_control(
            'wadi_sticky_play_icon_margin',
            [
                'label' => __('Margin', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Icon background
        $this->add_control(
            'wadi_sticky_play_icon_background',
            [
                'label' => __('Background', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Icon Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wadi_sticky_play_icon_border',
                'label' => __('Border', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_thumb .wadi_video_play_icon i',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Icon Border Radius
        $this->add_control(
            'wadi_sticky_play_icon_border_radius',
            [
                'label' => __('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_thumb .wadi_video_play_icon i' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_play_icon_switch' => 'yes',
                ],
            ]
        );

        // Sticky Text Heading

        $this->add_control(
            'wadi_sticky_text_heading',
            [
                'label' => __('Sticky Video Text', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );


        // Video Text Vertical Sticky

        $this->add_responsive_control(
            'wadi_sticky_video_text_vertical_position',
            [
                'label' => __('Vertical Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // Video Text Horizontal Sticky
        
        $this->add_responsive_control(
            'wadi_sticky_video_text_horizontal_position',
            [
                'label' => __('Horizontal Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 50,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );


        // Text color
        $this->add_control(
            'wadi_sticky_text_color',
            [
                'label' => __('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // Text Typography
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'wadi_sticky_text_typography',
                'selector' => '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // background
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wadi_sticky_text_background',
                'label' => __('Background', 'wadi-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // Text Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wadi_sticky_text_border',
                'label' => __('Border', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // Text Border Radius
        $this->add_control(
            'wadi_sticky_text_border_radius',
            [
                'label' => __('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // Text Padding

        $this->add_responsive_control(
            'wadi_sticky_text_padding',
            [
                'label' => __('Padding', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // Text Margin

        $this->add_responsive_control(
            'wadi_sticky_text_margin',
            [
                'label' => __('Margin', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'rem', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .video_outer_container .wadi_video_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_text_switch' => 'yes',
                    'wadi_video_text_text!' => '',
                ],
            ]
        );

        // Close Button Heading
        $this->add_control(
            'wadi_sticky_close_button_heading',
            [
                'label' => __('Close Button', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_close_sticky' => 'yes',
                ],
            ]
        );

        // Close Button Color
        $this->add_control(
            'wadi_sticky_close_button_color',
            [
                'label' => __('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .wadi_video_inner_wrapper_{{ID}}  .wadi_close_sticky_container' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_close_sticky' => 'yes',
                ],
            ]
        );
        
        // Close Button background

        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wadi_sticky_close_button_background',
                'label' => __('Background', 'wadi-addons'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .wadi_video_outer_container_sticky .wadi_video_inner_wrapper_{{ID}}  .wadi_close_sticky_container',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_close_sticky' => 'yes',
                ],
            ]
        );

        // Close Button Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'wadi_sticky_close_button_border',
                'label' => __('Border', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .wadi_video_outer_container_sticky .wadi_video_inner_wrapper_{{ID}}  .wadi_close_sticky_container',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_close_sticky' => 'yes',
                ],
            ]
        );

        // Close Button Border Radius

        $this->add_control(
            'wadi_sticky_close_button_border_radius',
            [
                'label' => __('Border Radius', 'wadi-addons'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .wadi_video_inner_wrapper_{{ID}}  .wadi_close_sticky_container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_close_sticky' => 'yes',
                ],
            ]
        );

        // Close Button Box Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'wadi_sticky_close_button_box_shadow',
                'label' => __('Box Shadow', 'wadi-addons'),
                'selector' => '{{WRAPPER}} .wadi_video_outer_container_sticky .wadi_video_inner_wrapper_{{ID}}  .wadi_close_sticky_container',
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_close_sticky' => 'yes',
                ],
            ]
        );

        // Close Button Scale on Hover
        $this->add_control(
            'wadi_sticky_close_button_scale_hover',
            [
                'label' => __('Scale on Hover', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range' => [
                    'px' => [
                        'max' => 3,
                        'min' => 0,
                        'step' => 0.1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1.3,
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_video_outer_container_sticky .wadi_video_inner_wrapper_{{ID}}  .wadi_close_sticky_container:hover' => 'transform: scale({{SIZE}});',
                ],
                'condition' => [
                    'wadi_video_sticky_switch' => 'yes',
                    'wadi_video_close_sticky' => 'yes',
                ],

            ]
        );

        $this->end_controls_section();


    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $id = $this->get_id();
        

        $video_type = $settings['wadi_video_type'];

        $this->get_video_embed();

    }

    protected function getYoutubeIdFromUrl($url) {
        $parts = parse_url($url);
        if(isset($parts['query'])){
            parse_str($parts['query'], $qs);
            if(isset($qs['v'])){
                return $qs['v'];
            }else if(isset($qs['vi'])){
                return $qs['vi'];
            }
        }
        if(isset($parts['path'])){
            $path = explode('/', trim($parts['path'], '/'));
            return $path[count($path)-1];
        }
        return false;
    }

    /**
	 * Returns Video ID.
	 *
	 * @since 1.0.3
	 * @access protected
	 */
	protected function get_video_id() {

		$settings = $this->get_settings_for_display();
		$id       = '';
		$url      = $settings[ $settings['wadi_video_type'] . '_url' ];

		if ( 'youtube' === $settings['wadi_video_type'] ) {

            $parts = parse_url($url);
            if(isset($parts['query'])){
                parse_str($parts['query'], $qs);
                if(isset($qs['v'])){
                    return  $id = $qs['v'];
                }else if(isset($qs['vi'])){
                    return  $id = $qs['vi'];
                }
            }
            if(isset($parts['path'])){
                $path = explode('/', trim($parts['path'], '/'));
                return $id = $path[count($path)-1];
            }
            return false;

		} elseif ( 'vimeo' === $settings['wadi_video_type'] ) {

			$id = preg_replace( '/[^\/]+[^0-9]|(\/)/', '', rtrim( $url, '/' ) );

		} elseif( 'dailymotion' === $settings['wadi_video_type'] ) {
            if (preg_match('!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $url, $m)) {
                if (isset($m[6])) {
                    $id = $m[6];
                }
                if (isset($m[4])) {
                    $id = $m[4];
                }
                $id = $m[2];
            }
        }

		return $id;
	}


    /**
	 * Returns Video URL.
	 *
	 * @param array  $params Video Param array.
	 * @param string $id Video ID.
	 * @since 1.0.3
	 * @access protected
	 */
	protected function get_url( $params, $id ) {

		$settings = $this->get_settings_for_display();
		$url      = '';

		if ( 'vimeo' === $settings['wadi_video_type'] ) {

			$url = 'https://player.vimeo.com/video/';

		} elseif ( 'youtube' === $settings['wadi_video_type'] ) {

			$cookie = '';

			if ( 'yes' === $settings['wadi_youtube_privacy_switch'] ) {
				$cookie = '-nocookie';
			}
			$url = 'https://www.youtube' . $cookie . '.com/embed/';

		} elseif('dailymotion' === $settings['wadi_video_type']) {
            $url = 'https://www.dailymotion.com/embed/video/';
        }

		$url = add_query_arg( $params, $url . $id );
        
        
		$url .= ( empty( $params ) ) ? '?' : '&';
        
        if( 'yes' === $settings['wadi_video_lightbox_switch']) {
            $url .= 'autoplay=1';
        }
        
		if ( 'vimeo' === $settings['wadi_video_type'] && '' !== $settings['wadi_vimeo_video_start'] ) {

			$time = gmdate( 'H\hi\ms\s', $settings['wadi_vimeo_video_start'] );
			$url .= '#t=' . $time;
		} elseif ( 'vimeo' === $settings['wadi_video_type'] ) {

			$url .= '#t=';
		}

		$url = apply_filters( 'wadi_video_url_filter', $url, $id );

		return $url;
	}

    /**
	 * Returns Vimeo Headers.
	 *
	 * @param string $id Video ID.
	 * @since 1.0.3
	 * @access protected
	 */
	protected function get_viemeo_video_headers( $id ) {

		$settings = $this->get_settings_for_display();

		if ( 'vimeo' !== $settings['wadi_video_type'] ) {
			return;
		}

		$response = wp_remote_get( "https://vimeo.com/api/v2/video/$id.php" );

		if ( is_wp_error( $response ) ) {
			return;
		}
		$vimeo = maybe_unserialize( $response['body'] );

		if (
			'yes' === $settings['wadi_vimeo_video_portrait'] ||
			'yes' === $settings['wadi_vimeo_video_title'] ||
			'yes' === $settings['wadi_vimeo_video_byline']
		) { ?>
		<div class="wadi_vimeo_wrap">
			<?php if ( 'yes' === $settings['wadi_vimeo_video_portrait'] ) { ?>
			<div class="wadi_vimeo_video_portrait">
				<a href="<?php esc_url( $vimeo[0]['user_url'] ); ?>"><img src="<?php echo esc_url( $vimeo[0]['user_portrait_huge'] ); ?>" alt=""></a>
			</div>
			<?php } ?>
			<?php
			if (
				'yes' === $settings['wadi_vimeo_video_title'] ||
				'yes' === $settings['wadi_vimeo_video_byline']
			) {
				?>
			<div class="wadi_vimeo_video_headers">
				<?php if ( 'yes' === $settings['wadi_vimeo_video_title'] ) { ?>
				<div class="wadi_vimeo_video_title">
					<a href="<?php $settings['vimeo_url']; ?>"><?php echo esc_attr( $vimeo[0]['title'] ); ?></a>
				</div>
				<?php } ?>
				<?php if ( 'yes' === $settings['wadi_vimeo_video_byline'] ) { ?>
				<div class="wadi_vimeo_video_byline">
					<?php esc_attr_e( 'from ', 'wadi-addons' ); ?> <a href="<?php $settings['vimeo_url']; ?>"><?php echo esc_attr( $vimeo[0]['user_name'] ); ?></a>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
		<?php
	}


    /**
     * Render Wadi Video
     * 
     * @since 1.0.3
     * @access protected
     * 
     */

     protected function get_video_embed() {

        $settings = $this->get_settings_for_display();
        $is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();
        $elem_id = $this->get_id();
        $id = $this->get_video_id();
        $embed_params = $this->get_video_embed_params();
        $viewport       = 0;
		$viewport       = apply_filters( 'wadi_sticky_video_viewport', $viewport );
        
        $video_type = $settings['wadi_video_type'];
        
        
        $src = $this->get_url( $embed_params, $id );
        
        
		$this->add_render_attribute( 'wadi_video_container', 
            [
                'id' => 'wadi_video_container_' .$elem_id,
                'class' => ['wadi_video_container', 'wadi_video_container_' .$elem_id],
            ]
        );
        $this->add_render_attribute( 'wadi_video_container', 'data-vsticky-viewport', $viewport );

		$this->add_render_attribute( 'wadi_video_wrapper', 
            [
                'class' => ['wadi_video_wrapper', 'wadi_video_wrapper_' .$elem_id],
                'data-src' => $src,
                'data-video-type' => $video_type,
            ]
        );

        if ('vimeo' === $video_type || 'youtube' === $video_type || 'dailymotion' === $video_type) {
            $lightbox_video_url = $src;
        } elseif('self_hosted' === $video_type && 'local' === $settings['wadi_self_hosted_video'] ) {
            $lightbox_video_url = $settings['self_hosted_url']['url'];
        } elseif('self_hosted' === $video_type && 'remote' === $settings['wadi_self_hosted_video'] ) {
            $lightbox_video_url = $settings['self_hosted_remote_url'];
        }
        
        
		if ( $settings['wadi_video_self_hosted_start'] || $settings['wadi_video_self_hosted_end'] ) {
			$lightbox_video_url .= '#t=';
		}

		if ( $settings['wadi_video_self_hosted_start'] ) {
			$lightbox_video_url .= $settings['wadi_video_self_hosted_start'];
		}

		if ( $settings['wadi_video_self_hosted_end'] ) {
			$lightbox_video_url .= ',' . $settings['wadi_video_self_hosted_end'];
		}


        $lightbox_options = [
            'type' => 'video',
            'videoType' => 'self_hosted' === $video_type ? 'hosted' : $video_type,
            'url' => $lightbox_video_url,
            'modalOptions' => [
                'id' => 'elementor-lightbox-' . $elem_id,
                'entranceAnimation' => $settings['lightbox_content_animation'],
                'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
                'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
                'videoAspectRatio' => $settings['aspect_ratio'],
                
            ],
        ];

        // if ( 'yes' === $settings['wadi_sticky_video'] ) {
        //     $this->add_render_attribute( 'wadi_video_wrapper', 
        //         [
        //             'data-sticky-video' => 'yes',
        //             'data-sticky-video-viewport' => $viewport,
        //         ]
        //     );
        // }

        $this->add_render_attribute('video_outer_container',
        [
            'class' => ['video_outer_container', 'video_outer_container_' .$elem_id],

        ]);

        if ('self_hosted' === $video_type) {
            $lightbox_options['videoParams'] = $this->get_hosted_parameter();
        
            // echo '<pre>';
            // print_r($lightbox_options);
            // echo '</pre>';
        }
        
        
        if('yes' === $settings['wadi_video_lightbox_switch']) {
            $this->add_render_attribute( 'video_outer_container', 
                [
                    'data-elementor-open-lightbox' => "yes",
                    'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
                    'data-elementor-lightbox-slideshow' => $this->get_id(),
                ]
            );

            // if('self_hosted' === $video_type && 'local' === $settings['wadi_self_hosted_video'] ) {
            //     $this->add_render_attribute( 'video_outer_container', 
            //         [
            //             'data-elementor-lightbox-video' => $settings['self_hosted_url']['url'],
            //         ]
            //     );
            // } elseif('self_hosted' === $video_type && 'remote' === $settings['wadi_self_hosted_video'] ) {
            //     $this->add_render_attribute( 'video_outer_container', 
            //         [
            //             'data-elementor-lightbox-video' => $settings['self_hosted_remote_url'],
            //         ]
            //     );
            // }
        }

        if('yes' === $settings['wadi_video_sticky_switch']) {
            $this->add_render_attribute( 'video_outer_container', 
                [
                    'data-video-sticky' => "yes",
                    'data-video-sticky-viewport' => $viewport,
                    // 'class' => ['wadi_video_outer_container_sticky .wadi_video_inner_wrapper'],
                ]
            );
        }


        if('self_hosted' === $settings['wadi_video_type']) {
            $this->add_render_attribute( 'wadi_video_player', [
                'class' => ['wadi_video_player'],
                'id' => 'wadi_video_player_' . $elem_id,
                'data-video-type' => $video_type,
                'data-video-id' => $elem_id,
                'data-video-autoplay' => $settings['wadi_video_self_hosted_autoplay'],
                'data-video-loop' => $settings['wadi_video_self_hosted_loop'],
                'data-video-mute' => $settings['wadi_video_self_hosted_muted'],
                'data-video-start' => $settings['wadi_video_self_hosted_start'],
                'data-video-end' => $settings['wadi_video_self_hosted_end'],
                'data-video-controls' => $settings['wadi_video_self_hosted_controls'],
                ] );
                // 'autoplay' => $settings['wadi_video_self_hosted_autoplay'],
                // 'loop' => $settings['wadi_video_self_hosted_loop'],
                // 'muted' => $settings['wadi_video_self_hosted_muted'],
                // 'controls' => $settings['wadi_video_self_hosted_controls'],
                // 'playsinline' => $settings['wadi_video_self_hosted_playsinline'],
                // 'poster' => $settings['wadi_video_self_hosted_poster']

                if('yes' === $settings['wadi_video_self_hosted_autoplay']) {
                    $this->add_render_attribute( 'wadi_video_player', 'autoplay' );
                }
                if('yes' === $settings['wadi_video_self_hosted_loop']) {
                    $this->add_render_attribute( 'wadi_video_player',  'loop' );
                }
                if('yes' === $settings['wadi_video_self_hosted_muted']) {
                    $this->add_render_attribute( 'wadi_video_player', 'muted' );
                }
                if('yes' === $settings['wadi_video_self_hosted_controls']) {
                    $this->add_render_attribute( 'wadi_video_player','controls' );
                }
                if('yes' === $settings['wadi_video_self_hosted_playsinline']) {
                    $this->add_render_attribute( 'wadi_video_player', 'playsinline' );
                }
                if($settings['wadi_video_self_hosted_poster']) {
                    $this->add_render_attribute( 'wadi_video_player', 'poster', $settings['wadi_video_self_hosted_poster'] );
                }
                if('yes' === $settings['wadi_video_self_hosted_download_button']) {
                    $this->add_render_attribute( 'wadi_video_player', 'controlsList', 'nodownload' );
                }
                $self_hosted_video_url = '';
                
                if('self_hosted' === $video_type && 'local' === $settings['wadi_self_hosted_video']) {
                    $self_hosted_video_url = $settings['self_hosted_url']['url'];
                } elseif('self_hosted' === $video_type && 'remote' === $settings['wadi_self_hosted_video']) {
                    $self_hosted_video_url = $settings['self_hosted_remote_url'];
                }
                if(isset($self_hosted_video_url) && $self_hosted_video_url && !empty($self_hosted_video_url)) {
                    if ( $settings['wadi_video_self_hosted_start'] || $settings['wadi_video_self_hosted_end'] ) {
                        $self_hosted_video_url .= '#t=';
                    }
            
                    if ( $settings['wadi_video_self_hosted_start'] ) {
                        $self_hosted_video_url .= $settings['wadi_video_self_hosted_start'];
                    }
            
                    if ( $settings['wadi_video_self_hosted_end'] ) {
                        $self_hosted_video_url .= ',' . $settings['wadi_video_self_hosted_end'];
                    }
        
                }
        }

        $this->add_render_attribute( 'wadi_video_thumbnail', 'class', 'wadi_video_thumbnail' );

        if ( 'self_hosted' !== $settings['wadi_video_type'] ) {
            $thumb = $this->get_video_thumb( $id );
			$this->add_render_attribute( 'wadi_video_thumbnail', 'src',  $thumb );
		} else {
			if ( 'yes' === $settings['show_image_overlay'] ) {

				$thumb = Group_Control_Image_Size::get_attachment_image_src( $settings['image_overlay']['id'], 'image_overlay', $settings );

			} else {
                if(isset($settings['wadi_video_self_hosted_poster'])) {
                    $thumb = wp_get_attachment_image_src( $settings['wadi_video_self_hosted_poster']['id'], 'full' );
                    if(!empty($thumb)) {
                        $thumb = $thumb[0];
                    }
                }
            }

            // $thumb = $self_hosted_video_url;
			$this->add_render_attribute( 'wadi_video_thumbnail', 'src', $thumb );
		}


        // if ( 'self_hosted' === $settings['wadi_video_type'] && 'yes' !== $settings['show_image_overlay'] && !isset($settings['wadi_video_self_hosted_poster']) ) {
		// 	$custom_tag = 'video';
		// } else {
        //     $custom_tag = 'img';
        // }
        // INline Text Editing
        if('yes' === $settings['wadi_video_text_switch']) {
            $this->add_render_attribute( 'wadi_video_text_text', 'class', 'wadi_video_text' );
    
            $this->add_inline_editing_attributes('wadi_video_text_text');
        }

        ?>
        

        <div <?php echo wp_kses_post($this->get_render_attribute_string('wadi_video_container')); ?>>
            <div <?php echo wp_kses_post($this->get_render_attribute_string('wadi_video_wrapper')); ?>>
                <div class="wadi_video_inner_wrapper wadi_video_inner_wrapper_<?php echo $elem_id; ?>">
                    <div <?php echo wp_kses_post($this->get_render_attribute_string('video_outer_container')) ?>>
                        <?php if('yes' === $settings['wadi_vimeo_video_headers']) : ?>
                            <?php $this->get_viemeo_video_headers( $id ); ?>
                        <?php endif; ?>
                        <div class="wadi_video_thumb">
                            <img <?php echo wp_kses_post($this->get_render_attribute_string('wadi_video_thumbnail')); ?>>
                            <?php if('yes' === $settings['wadi_video_play_icon_switch']) :?>
                                <div class="wadi_video_play_icon">
                                    <i class="fa fa-play"></i>
                                </div>
                            <?php endif; ?>
                            <?php if('yes' === $settings['wadi_video_text_switch']): ?>
                                <div <?php echo wp_kses_post($this->get_render_attribute_string('wadi_video_text_text')); ?>>
                                    <?php echo esc_html($settings['wadi_video_text_text']); ?>
                                </div>
                            <?php endif; ?>
                            </img>
                        </div>
                        <?php if( 'self_hosted' === $settings['wadi_video_type']) : ?>
                            <div class="wadi_self_hosted_video_container">
                            <video src="<?php echo esc_url($self_hosted_video_url); ?>" <?php echo wp_kses_post($this->get_render_attribute_string('wadi_video_player')); ?>>
                            </video>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ( 'yes' === $settings['wadi_video_sticky_switch'] && 'yes' === $settings['wadi_video_close_sticky'] ) { ?>
                        <div class="wadi_close_sticky_container">
                            <i class="fas fa-times wadi_close_sticky_icon"></i>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
	
        
     }

    /**
	 * Get hosted video parameters.
	 *
	 * @since 1.0.3
	 * @access protected
	 */
	private function get_hosted_parameter() {
		$settings = $this->get_settings_for_display();

		$video_params = array();

		foreach ( array( 'autoplay', 'loop', 'controls', 'muted' ) as $option_name ) {
			if ( 'yes' === $settings[ 'wadi_video_self_hosted_'.$option_name ] ) {
				$video_params[ $option_name ] = $option_name;
			}
		}

        if ( ! $settings['wadi_video_self_hosted_download_button'] ) {
			$video_params['controlsList'] = 'nodownload';
		}

        if ( $settings['wadi_video_self_hosted_poster']['url'] ) {
			$video_params['poster'] = $settings['wadi_video_self_hosted_poster']['url'];
		}
		// if ( $settings['muted'] ) {
		// 	$video_params['muted'] = 'muted';
		// }

		return $video_params;
	}


    /**
     * Video Embed Params
     * 
     * @since 1.0.3
     * @access protected
     * 
     */

     protected function get_video_embed_params() {

        $settings = $this->get_settings_for_display();
        $id = $this->get_video_id();

        $params = array();

        if('youtube' === $settings['wadi_video_type']) {
            $youtube_options = ['autoplay', 'loop', 'rel', 'controls', 'mute', 'modestbranding'];

            foreach($youtube_options as $option) {        
                

                    if ( 'autoplay' === $option ) {
                        if ( 'yes' === $settings['wadi_youtube_video_autoplay'] ) {
                            $params[ $option ] = '1';
                        }
                        continue;
                    }

                    $value             = ( 'yes' === $settings[ 'wadi_youtube_video_' . $option ] ) ? '1' : '0';
                    $params['start']   = $settings['wadi_youtube_video_start'];
				    $params['end']     = $settings['wadi_youtube_video_end'];
                    $params['playlist'] = $id; // For Loop to work we have to add to youtube params ID of the video : https://stackoverflow.com/questions/25779966/youtube-iframe-loop-doesnt-work
                    $params[ $option ] = $value;


            }
        }

        if ('vimeo' === $settings['wadi_video_type']) {
            $vimeo_options = ['autoplay', 'loop', 'title', 'byline', 'portrait', 'muted'
            // , 'color'
            ];

            foreach ($vimeo_options as $option) {
                
                if ('autoplay' === $option) {
                    if ('yes' === $settings['wadi_vimeo_video_autoplay']) {
                        $params[ $option ] = '1';
                    }
                    continue;
                }

                $value             = ('yes' === $settings[ 'wadi_vimeo_video_' . $option ]) ? '1' : '0';
                $params[ $option ] = $value;
            }

            $params['color']     = str_replace( '#', '', $settings['color'] );
			$params['autopause'] = '0';
        }

        if('dailymotion' === $settings['wadi_video_type']) {
            $dailymotion_options = ['controls', 'mute', 'showinfo', 'logo', 'autoplay'];

            foreach($dailymotion_options as $option) {

                $value             = ('yes' === $settings[ 'wadi_dailymotion_video_' . $option ]) ? '1' : '0';
			    $params['ui-highlight'] = str_replace( '#', '', $settings['color'] );
                $params['start'] = $settings['wadi_dailymotion_video_start'];
                $params[ $option ] = $value;
            }
        }

		return $params;

        
     }


    /**
	 * Returns Video Thumbnail Image.
	 *
	 * @param string $id Video ID.
	 * @since 1.0.3
	 * @access protected
	 */
	protected function get_video_thumb( $id ) {

		if ( '' === $id ) {
			return '';
		}

		$settings = $this->get_settings_for_display();
		$thumb    = '';

		if ( 'yes' === $settings['show_image_overlay'] ) {

			$thumb = Group_Control_Image_Size::get_attachment_image_src( $settings['image_overlay']['id'], 'image_overlay', $settings );

		} else {

			if ( 'youtube' === $settings['wadi_video_type'] ) {

				$thumb = 'https://i.ytimg.com/vi/' . $id . '/' . apply_filters( 'wadi_video_youtube_image_quality', $settings['wadi_video_youtube_thumbnail_size'] ) . '.jpg';

			} elseif ( 'vimeo' === $settings['wadi_video_type'] ) {

				$response = wp_remote_get( "https://vimeo.com/api/v2/video/$id.php" );

				if ( is_wp_error( $response ) ) {
					return;
				}
				$vimeo = maybe_unserialize( $response['body'] );
				$thumb = str_replace( '_640', '_840', $vimeo[0]['thumbnail_large'] );

			} elseif('dailymotion' === $settings['wadi_video_type']) {

                $thumb = 'https://www.dailymotion.com/thumbnail/video/' . $id;

            }
		}
		return $thumb;
	}


}