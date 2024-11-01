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
use Elementor\Modules\DynamicTags\Module as TagsModule;

// Wadi Classes
use WadiAddons\Includes\WadiHelpers;
use WadiAddons\Includes\WadiQueries;

if (! defined('ABSPATH')) {
    exit;
}

class Wadi_Gallery_Grid extends Widget_Base
{

    public function get_name()
    {
        return 'wadi-gallery-grid-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Gallery Grid', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-gallery-grid';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_register_script('script-wadi_images_loaded', WADI_ADDONS_URL . 'assets/extLib/imagesloaded.pkgd.min.js', ['jquery'], '4.1.4', true);
        wp_register_script('script-wadi_isotope', WADI_ADDONS_URL . 'assets/extLib/isotope.pkgd.js', ['jquery'], '4.1.4', true);
        wp_register_script('script-handle_gallery_grid', WADI_ADDONS_URL . 'assets/min/wadi-gallery-grid.min.js', [ 'elementor-frontend', 'jquery','script-wadi_isotope' ,'script-wadi_images_loaded' ], '1.0.0', true);
        if(!is_rtl()) {
            wp_register_style('style-handle_gallery_grid', WADI_ADDONS_URL . 'assets/min/wadi-gallery-grid.css');
        } else {
            wp_register_style('style-handle_gallery_grid', WADI_ADDONS_URL . 'assets/min/wadi-gallery-grid.rtl.css');
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
        return [ 'wadi-addons','media','gallery grid', 'media grid', 'grid', 'image', 'gallery', 'media gallery' ];
    }


    public function get_script_depends()
    {
        return [ 'script-handle_gallery_grid' ];
    }

    public function get_style_depends()
    {
        return [ 'style-handle_gallery_grid' ];
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
            'wadi_gallery_grid_settings',
            [
                'label' => esc_html__('Layout Settings', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_skins_stylings',
            [
                'label' => esc_html__('Skins','wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('default', 'wadi-addons'),
                    'style_1' => esc_html__('Style 1', 'wadi-addons'),
                    'style_2' => esc_html__('Style 2', 'wadi-addons'),
                    'style_3' => esc_html__('Style 3', 'wadi-addons'),
                    'style_4' => esc_html__('Style 4', 'wadi-addons'),
                ],
                'default' => 'default',
                'prefix_class' => 'wadi_gallery_grid_skin_',
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_layout_mode',
            [
                'label' => esc_html__('Layout Mode', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'masonry' => esc_html__('Masonry', 'wadi-addons'),
                    'fitRows' => esc_html__('Even', 'wadi-addons'),
                    'packery' => esc_html__('Packery', 'wadi-addons'),
                ],
                'default' => 'masonry',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
			'wadi_gallery_grid_column_width',
			array(
				'label'           => __( 'Columns', 'wadi-addons' ),
				'type'            => Controls_Manager::SELECT,
				'default' => '50%',
				'options'         => array(
					'100%'    => __( '1 Column', 'wadi-addons' ),
					'50%'     => __( '2 Columns', 'wadi-addons' ),
					'33.330%' => __( '3 Columns', 'wadi-addons' ),
					'25%'     => __( '4 Columns', 'wadi-addons' ),
					'20%'     => __( '5 Columns', 'wadi-addons' ),
					'16.660%'  => __( '6 Columns', 'wadi-addons' ),
					'14.280%'  => __( '7 Columns', 'wadi-addons' ),
					'12.50%'  => __( '8 Columns', 'wadi-addons' ),
					'11.110%'  => __( '9 Columns', 'wadi-addons' ),
					'10%'  => __( '10 Columns', 'wadi-addons' ),
					'9.09%'  => __( '11 Columns', 'wadi-addons' ),
					'8.330%'   => __( '12 Columns', 'wadi-addons' ),
				),
				'selectors'       => array(
					'{{WRAPPER}} .wadi_gallery_grid_item' => 'width: {{VALUE}};',
				),
				'render_type'     => 'template',
                'frontend_available' => true,
			)
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_items_gap',
            [
                'label' => esc_html__('Gap', 'wadi-addons'),
                'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'rem' ,'%', 'em'],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 300,
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
				'selectors'  => array(
					'{{WRAPPER}} .wadi_gallery_grid_item' => 'padding: {{SIZE}}{{UNIT}}',
				),
            ]
        );



        $this->end_controls_section();


        $this->start_controls_section(
            'wadi_gallery_grid_content_section',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'wadi_gallery_grid_content_type',
            [
                'label' => esc_html__('Gallery Item Type', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'media_image',
				'options' => [
					'media_image' => [
						'title' => esc_html__( 'Image', 'wadi-addons' ),
						'icon' => 'eicon-image-bold',
					],
					'media_video' => [
						'title' => esc_html__( 'Video', 'wadi-addons' ),
						'icon' => 'eicon-video-camera',
					],
				],
				'toggle' => false,
            ]
        );

        $repeater->add_control(
            'wadi_gallery_item_categories',
            [
                'label' => esc_html__('Categories', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Write category names here, separate them by commas. Please note that you have to create the categories in category section in order to filter', 'wadi-addons'),
            ]
        );

        $repeater->add_control(
			'wadi_gallery_grid_title',
			[
				'label' => esc_html__( 'Title', 'wadi-addons' ),
				'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Title #1', 'wadi-addons'),
			]
		);

        $repeater->add_control(
			'wadi_gallery_grid_description',
			[
				'label' => esc_html__( 'Description', 'wadi-addons' ),
				'type' => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('Description #1', 'wadi-addons'),
			]
		);

        $repeater->add_control(
			'wadi_gallery_grid_image',
			[
				'label' => esc_html__( 'Image', 'wadi-addons' ),
				'type' => Controls_Manager::MEDIA,
                'default' =>  [
                    'url' => Utils::get_placeholder_image_src(),
                 ],
			]
		);

        
        $repeater->add_responsive_control(
            'wadi_gallery_grid_image_height',
            [
                'label' => esc_attr__('Height', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', '%', 'em' ],
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
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid {{CURRENT_ITEM}}.wadi_gallery_grid_item' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
            ]
        );


		$repeater->add_control(
			'wadi_gallery_grid_image_link_to_type',
			[
				'label' => esc_html__( 'Link', 'wadi-addons' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'wadi-addons' ),
					'file' => esc_html__( 'Media File', 'wadi-addons' ),
					'custom' => esc_html__( 'Custom URL', 'wadi-addons' ),
				],
				'condition' => [
					'wadi_gallery_grid_content_type' => 'media_image',
				],
                'default' => 'file',
			]
		);

		$repeater->add_control(
			'wadi_gallery_grid_image_link_to',
			[
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'wadi-addons' ),
				'show_external' => 'true',
				'condition' => [
					'wadi_gallery_grid_content_type' => 'media_image',
					'wadi_gallery_grid_image_link_to_type' => 'custom',
				],
				'separator' => 'none',
				'show_label' => false,
			]
		);
        
        $repeater->add_control(
            'wadi_grid_gallery_lightbox',
            [
                'label' => __('Video Lightbox', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type!' => ['dailymotion', 'self_hosted'],
                ],
            ]
        );

        $repeater->add_control(
            'wadi_grid_gallery_video_type',
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
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video'
                ],
                'label_block' => true,
            ]
        );


        $repeater->add_control(
            'youtube_url',
            [
                'label' => esc_html__('YouTube URL', 'wadi-addons'),
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
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                ],
            ]
        );

        $repeater->add_control(
            'vimeo_url',
            [
                'label' => esc_html__('Vimeo URL', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => 'https://vimeo.com/235215203',
                'placeholder' => __('Enter your Vimeo url', 'wadi-addons'),
                'label_block' => true,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'vimeo',
                ],
                'dynamic' => [
                    'active' => true,
                    'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
                ],
            ]
        );

        $repeater->add_control(
			'dailymotion_url',
			[
				'label' => esc_html__( 'Dailymotion URL', 'wadi-addons' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
					'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
				],
				'placeholder' => esc_html__( 'Enter your URL', 'wadi-addons' ) . ' (Dailymotion)',
				'default' => 'https://www.dailymotion.com/video/x6tqhqb',
				'label_block' => true,
				'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
					'wadi_grid_gallery_video_type' => 'dailymotion'
				]
			]
		);

        $repeater->add_control(
            'wadi_grid_gallery_self_hosted_video',
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
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted'
                ],
            ]
        );

        $repeater->add_control(
            'self_hosted_url',
            [
                'label' => esc_html__('Self Hosted URL', 'wadi-addons'),
                'type' => Controls_Manager::MEDIA,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                    'wadi_grid_gallery_self_hosted_video' => 'local',
                ],
                'dynamic' => [
                    'active' => true,
                    'categories' => array(
						TagsModule::MEDIA_CATEGORY,
					),
                ],
                'media_type' => 'video',
            ]
        );

        $repeater->add_control(
            'self_hosted_remote_url',
            [
                'label' => esc_html__('Self Hosted Remote URL', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Enter your self hosted remote url', 'wadi-addons'),
                'label_block' => true,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                    'wadi_grid_gallery_self_hosted_video' => 'remote',
                ],
                'dynamic' => [
                    'active' => true,
                    'categories' => [
						TagsModule::POST_META_CATEGORY,
						TagsModule::URL_CATEGORY,
					],
                ],
            ]
        );

        $repeater->add_control(
            'wadi_youtube_privacy_switch',
            [
                'label' => __('Youtube Privacy', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                    'wadi_grid_gallery_lightbox!' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );


        $repeater->add_control(
            'wadi_youtube_video_start',
            [
                'label' => __('Video Start Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );

        
        $repeater->add_control(
            'wadi_youtube_video_end',
            [
                'label' => __('Video End Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );


        $repeater->add_control(
            'wadi_youtube_video_modestbranding',
            [
                'label' => __('Modest Branding', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'wadi_youtube_video_autoplay',
            [
                'label' => __('Youtube Autoplay', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                    'wadi_grid_gallery_lightbox!' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
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
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                ],
            ]
        );

        $repeater->add_control(
            'wadi_youtube_video_loop',
            [
                'label' => __('Loop', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'wadi_youtube_video_mute',
            [
                'label' => __('Mute', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'wadi_youtube_video_controls',
            [
                'label' => __('Controls', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'youtube',
                ],
                'frontend_available' => true,
            ]
        );

        
        $repeater->add_control(
            'wadi_vimeo_video_autoplay',
            [
                'label' => __('Vimeo Autoplay', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'vimeo',
                    'wadi_grid_gallery_lightbox!' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'wadi_vimeo_video_loop',
            [
                'label' => __('Vimeo Loop', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'vimeo',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'wadi_vimeo_video_muted',
            [
                'label' => __('Vimeo Mute', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'vimeo',
                ],
                'frontend_available' => true,
            ]
        );

        
        $repeater->add_control(
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
                            'name' => 'wadi_gallery_grid_content_type',
                            'operator' => '==',
                            'value' => 'media_video',
                        ],
                        [
                            'name' => 'wadi_grid_gallery_video_type',
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


        $repeater->add_control(
            'wadi_vimeo_video_title',
            array(
                'label'     => __( 'Intro Title', 'wadi-addons' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'wadi-addons' ),
                'label_on'  => __( 'Show', 'wadi-addons' ),
                'default'   => 'yes',
                'condition' => array(
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'vimeo',
                ),
            )
        );

        // $repeater->add_control(
        //     'wadi_vimeo_video_controls',
        //     [
        //         'label' => __('Vimeo Controls', 'wadi-addons'),
        //         'type' => Controls_Manager::SWITCHER,
        //         'default' => 'no',
        //         'label_on' => __('Yes', 'wadi-addons'),
        //         'label_off' => __('No', 'wadi-addons'),
        //         'return_value' => 'yes',
        //         'condition' => [
        //             'wadi_grid_gallery_video_type' => 'vimeo',
        //         ],
        //         'frontend_available' => true,
        //     ]
        // );

        $repeater->add_control(
            'wadi_vimeo_video_portrait',
            array(
                'label'     => __( 'Intro Portrait', 'wadi-addons' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'wadi-addons' ),
                'label_on'  => __( 'Show', 'wadi-addons' ),
                'default'   => 'yes',
                'condition' => array(
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'vimeo',
                ),
                'frontend_available' => true,
            )
        );

        $repeater->add_control(
            'wadi_vimeo_video_byline',
            array(
                'label'     => __( 'Intro Byline', 'wadi-addons' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_off' => __( 'Hide', 'wadi-addons' ),
                'label_on'  => __( 'Show', 'wadi-addons' ),
                'default'   => 'yes',
                'condition' => array(
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'vimeo',
                ),
                'frontend_available' => true,
            )
        );

        $repeater->add_control(
            'wadi_vimeo_video_start',
            [
                'label' => __('Vimeo Start Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'vimeo',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );

        
        $repeater->add_control(
            'wadi_dailymotion_video_autoplay',
            [
                'label' => __('Dailymotion Autoplay', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'label_on' => __('Yes', 'wadi-addons'),
                'label_off' => __('No', 'wadi-addons'),
                'return_value' => 'yes',
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'dailymotion',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'wadi_dailymotion_video_start',
            [
                'label' => __('Dailymotion Start Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'dailymotion',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
			'wadi_dailymotion_video_controls',
			[
				'label' => esc_html__( 'Dailymotion Controls', 'wadi-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
				'label_on' => esc_html__( 'Show', 'wadi-addons' ),
				'default' => 'yes',
				'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
					'wadi_grid_gallery_video_type' => [ 'dailymotion' ],
				],
			]
		);
        $repeater->add_control(
			'wadi_dailymotion_video_mute',
			[
				'label' => esc_html__( 'Dailymotion Mute', 'wadi-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Yes', 'wadi-addons' ),
				'label_on' => esc_html__( 'No', 'wadi-addons' ),
				'default' => 'no',
				'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
					'wadi_grid_gallery_video_type' => [ 'dailymotion' ],
				],
			]
		);

        $repeater->add_control(
			'wadi_dailymotion_video_showinfo',
			[
				'label' => esc_html__( 'Dailymotion Info', 'wadi-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
				'label_on' => esc_html__( 'Show', 'wadi-addons' ),
				'default' => 'yes',
				'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
					'wadi_grid_gallery_video_type' => [ 'dailymotion' ],
				],
			]
		);

        $repeater->add_control(
			'wadi_dailymotion_video_logo',
			[
				'label' => esc_html__( 'Dailymotion Logo', 'wadi-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
				'label_on' => esc_html__( 'Show', 'wadi-addons' ),
				'default' => 'yes',
				'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
					'wadi_grid_gallery_video_type' => [ 'dailymotion' ],
				],
			]
		);

        $this->add_control(
			'color',
			[
				'label' => esc_html__( 'Controls Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
					'wadi_grid_gallery_video_type' => [ 'vimeo', 'dailymotion' ],
				],
			]
		);

        
        $repeater->add_control(
            'wadi_video_self_hosted_start',
            [
                'label' => esc_html__('Start Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
            'wadi_video_self_hosted_end',
            [
                'label' => esc_html__('End Time', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater->add_control(
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
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                ],
            ]
        );

        $repeater->add_control(
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
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                ],
            ]
        );

        $repeater->add_control(
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
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                ],
            ]
        );
        
        $repeater->add_control(
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
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                ],
            ]
        );

        $repeater->add_control(
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
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                ],
            ]
        );

        $repeater->add_control(
			'wadi_video_self_hosted_download_button',
			[
				'label' => esc_html__( 'Download Button', 'wadi-addons' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Hide', 'wadi-addons' ),
				'label_on' => esc_html__( 'Show', 'wadi-addons' ),
                'default' => 'Show',
                'return_value' => 'yes',
				'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
					'wadi_grid_gallery_video_type' => 'self_hosted',
				],
			]
		);

        $repeater->add_control(
            'wadi_video_self_hosted_poster',
            [
                'label' => __('Video Image (Poster)', 'wadi-addons'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'wadi_gallery_grid_content_type' => 'media_video',
                    'wadi_grid_gallery_video_type' => 'self_hosted',
                ],
                'dynamic' => [
                    'active' => true,
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_content_repeater',
            [
                'label' => esc_html__('Media Items', 'wadi-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
					[
						'wadi_gallery_grid_title' => esc_html__( 'Title #1', 'wadi-addons' ),
						'wadi_gallery_grid_image' => Utils::get_placeholder_image_src(),
                        'wadi_gallery_grid_content_type' => 'media_image',
                        'wadi_gallery_grid_image_link_to_type' => 'file',
                    ],
					[
						'wadi_gallery_grid_title' => esc_html__( 'Title #2', 'wadi-addons' ),
						'wadi_gallery_grid_image' => Utils::get_placeholder_image_src(),
                        'wadi_gallery_grid_content_type' => 'media_image',
                        'wadi_gallery_grid_image_link_to_type' => 'file',
					],
				],
				'title_field' => '{{{ wadi_gallery_grid_title }}}',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_image_item_height',
            [
                'label' => esc_attr__('Item Height', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', '%', 'em' ],
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
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item' => 'height: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'wadi_gallery_grid_image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `wadi_testimonial_image_size` and `wadi_testimonial_image_custom_dimension`.
				'default' => 'full',
				'separator' => 'none',
			]
		);

        $this->add_control(
            'wadi_gallery_grid_extra_class',
            [
                'label' => esc_html__('Extra Class', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'custom_class',
                'description' => esc_html__( 'Add extra class name that will be applied to the grid container, and you can use this class for your customizations.', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_image_size_fit',
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
                    '{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item  img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

                        
        $galleryGridTitleAlignment = [
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

        $rtl_galleryGridTitleAlignment = array_reverse($galleryGridTitleAlignment);

        $theGalleryGridTitleAlignment = !is_rtl()? $galleryGridTitleAlignment :  $rtl_galleryGridTitleAlignment;



        $this->add_responsive_control(
            'wadi_gallery_grid_title_alignment',
            [
                'label' => esc_html__('Text Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => $theGalleryGridTitleAlignment,
                'selectors_dictionary' => array(
                    'left'   => 'left',
                    'center' => 'center',
                    'right'  => 'right',
                ),
                'toggle'               => false,
                'selectors'            => array(
                    '{{WRAPPER}} .wadi_gallery_item_text_content' => 'text-align: {{VALUE}}',
                ),
                'label_block' => false,
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_items_shuffle_switecher',
            [
                'label' => esc_html__('Shuffle on Load', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_gallery_grid_categories_section',
            [
                'label' => esc_html__('Categories', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_categories_filter_switcher',
            [
                'label' => esc_html__('Categories Filter', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_categories_first_tab',
            [
                'label' => esc_html__('First Category', 'wadi-addons' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('All', 'wadi-addons'),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'wadi_gallery_grid_categories_filter_switcher' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $repeater_cat = new REPEATER();

        $repeater_cat->add_control(
            'wadi_gallery_grid_category_label',
            [
                'label' => esc_html__('Category Label', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Nature', 'wadi-addons'),
                'description' => esc_html__('Add label names for instance: Nature, Birds or any category name that fit for some of your media', 'wadi-addons'),
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_categories_repeater',
            [
                'label' => esc_html__('Categories', 'wadi-addons'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_cat->get_controls(),
                'default' => [
					[
						'wadi_gallery_grid_category_label' => esc_html__( 'Category 1', 'wadi-addons' ),
                    ],
					[
						'wadi_gallery_grid_category_label' => esc_html__( 'Category 2', 'wadi-addons' ),
					],
				],
                'condition' => [
                    'wadi_gallery_grid_categories_filter_switcher' => 'yes',
                ],
				'title_field' => '{{{ wadi_gallery_grid_category_label }}}',
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_active_category',
            [
                'label' => esc_html__('Active Category', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Set Active category by default from the existing categories. If empty it will get the default settings. If the category has a typo it will not activate set any active category.'),
                'condition' => [
                    'wadi_gallery_grid_categories_filter_switcher' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_filter_param',
            [
                'label' => esc_html__('Filter URL Parameter', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'description' => esc_html__('Category filter using url query parameter categrory name.'),
                'condition' => [
                    'wadi_gallery_grid_categories_filter_switcher' => 'yes',
                ],
                'frontend_available' => true,
            ]
        );
        $this->add_control(
			'wadi_active_cat_notice',
			array(
				'raw'             => __( 'Please note, when writing URL query parameter, category names if they have space replace them with underscore ( _ ).', 'wadi-addons' ),
				'type'            => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
				'condition' => [
                    'wadi_gallery_grid_categories_filter_switcher' => 'yes',
                ],
			)
		);

        $galleryGridFiltersAlignment = [
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

        $rtl_galleryGridFiltersAlignment = array_reverse($galleryGridFiltersAlignment);

        $theGalleryGridFiltersAlignment = !is_rtl()? $galleryGridFiltersAlignment :  $rtl_galleryGridFiltersAlignment;


        $this->add_responsive_control(
            'wadi_gallery_grid_filters_alignement',
            [
                'label' => esc_html__('Filters Alignment', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => $theGalleryGridFiltersAlignment,
                'selectors_dictionary' => array(
                    'left'   => 'left',
                    'center' => 'center',
                    'right'  => 'right',
                ),
                'toggle'               => false,
                'selectors'            => array(
                    '{{WRAPPER}} .wadi_gallery_grid_filter' => 'justify-content: {{VALUE}}',
                ),
                'label_block' => false,
                'condition' => [
                    'wadi_gallery_grid_categories_filter_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_items_filter_shuffle_switecher',
            [
                'label' => esc_html__('Shuffle on Filter', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->end_controls_section();

 
        $this->start_controls_section(
            'wadi_gallery_grid_styling_box_section',
            [
                'label' => esc_html__('Container', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_gallery_grid_container_backgrund',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_container',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_gallery_grid_container_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_container',
			]
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_container_border_radius',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_gallery_grid_container_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_container',
			]
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_container_margin',
            [
                 'label' => esc_attr__('Margin', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_container_padding',
            [
                 'label' => esc_attr__('Padding', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

       
        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_gallery_grid_content_styling_section',
            [
                'label' => esc_html__('Content', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_gallery_grid_content_item_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_item .wadi_gallery_grid_image, {{WRAPPER}} .wadi_gallery_grid_item video.wadi_gallery_grid_self_hosted_video, {{WRAPPER}} .wadi_gallery_grid_item iframe.wadi_gallery_grid_video_iframe',
			]
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_content_item_border_radius',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_item .wadi_gallery_grid_image, {{WRAPPER}} .wadi_gallery_grid_item video, {{WRAPPER}} .wadi_gallery_grid_item iframe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_gallery_grid_content_item_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_item .wadi_gallery_grid_image, {{WRAPPER}} .wadi_gallery_grid_item video, {{WRAPPER}} .wadi_gallery_grid_item iframe',
			]
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_content_item_margin',
            [
                 'label' => esc_attr__('Margin', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_item .wadi_gallery_grid_image, {{WRAPPER}} .wadi_gallery_grid_item video, {{WRAPPER}} .wadi_gallery_grid_item iframe' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_content_item_padding',
            [
                 'label' => esc_attr__('Padding', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_item .wadi_gallery_grid_image, {{WRAPPER}} .wadi_gallery_grid_item video, {{WRAPPER}} .wadi_gallery_grid_item iframe' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );
        
        $this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
                'label' => esc_html__('CSS Filters', 'wadi-addons'),
				'name' => 'content_item_custom_css_filters',
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_item .wadi_gallery_grid_image',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Css_Filter::get_type(),
			[
                'label' => esc_html__('CSS Filters Hover', 'wadi-addons'),
				'name' => 'content_item_custom_css_filters_hover',
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_item:hover .wadi_gallery_grid_image',
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_gallery_grid_categories_styling_section',
            [
                'label' => esc_html__('Categories', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'wadi_gallery_grid_categories_style_tabs'
        );
        
        $this->start_controls_tab(
            'wadi_gallery_grid_categories_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_categories_styling_color',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter',
			]
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_categories_items_border_radius',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter',
			]
		);
        
        $this->end_controls_tab();
        $this->start_controls_tab(
            'wadi_gallery_grid_categories_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_gallery_grid_categories_styling_color_hover',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_gallery_grid_filter_item:hover .data_filter' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_background_hover',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item:hover .data_filter',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_border_hover',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item:hover .data_filter',
			]
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_categories_items_border_radius_hover',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_filter_item:hover .data_filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_box_shadow_hover',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item:hover .data_filter',
			]
		);
        
        $this->end_controls_tab();
        $this->start_controls_tab(
            'wadi_gallery_grid_categories_style_active_tab',
            [
                'label' => esc_html__( 'Active', 'wadi-addons' ),
            ]
        );


        $this->add_control(
            'wadi_gallery_grid_categories_styling_color_active',
            [
                'label' => esc_html__('Color', 'wadi-addons'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter.filter_active' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_background_active',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter.filter_active',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_border_active',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter.filter_active',
			]
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_categories_items_border_radius_active',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter.filter_active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_items_box_shadow_active',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter.filter_active',
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_gallery_grid_categories_content_typography',
				'selector' => '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter',
			]
		);

        $this->add_responsive_control(
            'wadi_gallery_grid_categories_margin',
            [
                 'label' => esc_attr__('Margin', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_filter .wadi_gallery_grid_filters_list .wadi_gallery_grid_filter_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_categories_padding',
            [
                 'label' => esc_attr__('Padding', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','em', '%', 'rem'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_gallery_grid_filter_item .data_filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

         // Wadi Gallery Grid Categories Container Styling

        $this->add_control(
            'wadi_gallery_grid_categories_filter_container_heading',
            [
                'label' => esc_html__( 'Categories Container', 'wadi-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
         $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wadi_gallery_grid_categories_filter_container_background',
                'label' => esc_html__( 'Background', 'wadi-addons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid_filter',
            ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_categories_filter_container_padding',
            [
                'label' => esc_html__( 'Padding', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid_filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_categories_filter_container_margin',
            [
                'label' => esc_html__( 'Margin', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid_filter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );



        $this->end_controls_section();

        // Gallery Grid Text Styling Section

        $this->start_controls_section(
            'wadi_gallery_grid_text_styling_section',
            [
                'label' => esc_html__('Text', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
			'wadi_gallery_grid_text_title_heading',
			[
				'label' => esc_html__( 'Title', 'wadi-addons' ),
				'type' => Controls_Manager::HEADING,
			]
		);

        // Title Color

        $this->add_control(
			'wadi_gallery_grid_text_title_color',
			[
				'label' => esc_html__( 'Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text .wadi_gallery_item_text_title' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_gallery_grid_text_title_typography',
				'selector' => '{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text .wadi_gallery_item_text_title',
			]
		);

        $this->add_control(
			'wadi_gallery_grid_text_description_heading',
			[
				'label' => esc_html__( 'Description', 'wadi-addons' ),
				'type' => Controls_Manager::HEADING,
                'separator' => 'before',
			]
		);

        // Description Color

        $this->add_control(
			'wadi_gallery_grid_text_description_color',
			[
				'label' => esc_html__( 'Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text .wadi_gallery_item_text_description' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_gallery_grid_text_description_typography',
				'selector' => '{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text .wadi_gallery_item_text_description',
			]
		);


        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_gallery_grid_text_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
                'separator' => 'before',
				'selector' => '{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text',
			]
		);

        
		$this->add_control(
			'wadi_gallery_grid_text_color',
			[
				'label' => esc_html__( 'Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'wadi_gallery_grid_text_shadow',
				'label' => esc_html__( 'Text Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text',
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'wadi_gallery_grid_text_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text',
			]
		);

        // Border Radius
        $this->add_responsive_control(
            'wadi_gallery_grid_text_border_radius',
                [
                    'label' => esc_attr__('Border Radius', 'wadi-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px','em', '%', 'rem'],
                    'selectors' => [
                        '{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        // Padding
        $this->add_responsive_control(
            'wadi_gallery_grid_text_padding',
                [
                    'label' => esc_attr__('Padding', 'wadi-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px','em', '%', 'rem'],
                    'selectors' => [
                        '{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );
        // Margin
        $this->add_responsive_control(
            'wadi_gallery_grid_text_margin',
                [
                    'label' => esc_attr__('Margin', 'wadi-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px','em', '%', 'rem'],
                    'selectors' => [
                        '{{WRAPPER}} .wadi_gallery_item_text_content .wadi_gallery_item_the_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );


        $this->end_controls_section();



        $this->start_controls_section(
            'wadi_gallery_grid_video_icon_styling_section',
            [
                'label' => esc_html__('Video Icon', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_video_icon_vertical_position',
            [
                'label' => esc_html__('Vertical Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', '%', 'em' ],
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
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item' => 'top: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_video_icon_horizontal_position',
            [
                'label' => esc_html__('Horizontal Position', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', '%', 'em' ],
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
					'unit' => '%',
					'size' => 50,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item' => 'left: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'wadi_gallery_grid_video_icon_size',
            [
                'label' => esc_html__('Size', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem', 'em' ],
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
					'em' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item i.fa-play' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->add_control(
			'wadi_gallery_grid_video_icon_color',
			[
				'label' => esc_html__( 'Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item i.fa-play' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item svg' => 'fill: {{VALUE}}',
				],
			]
		);

        // Background Color
        $this->add_control(
            'wadi_gallery_grid_video_icon_background_color',
			[
				'label' => esc_html__( 'Background Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item' => 'background-color: {{VALUE}}',
				],
			]
        );
        
        // Border
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'wadi_gallery_grid_video_icon_border',
                'label' => esc_html__( 'Border', 'wadi-addons' ),
                'selector' => '{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item',
            ]    
        );    
            
            
        // Border Radius
        $this->add_responsive_control(
            'wadi_gallery_grid_video_icon_border_radius',
                [
                    'label' => esc_attr__('Border Radius', 'wadi-addons'),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => ['px','em', '%', 'rem'],
                    'selectors' => [
                    '{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
        );

        // Padding 
        $this->add_responsive_control(
            'wadi_gallery_grid_video_icon_padding',
            [
                'label' => esc_html__( 'Padding', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],    
            ]    
        );            
        // Margin 
        $this->add_responsive_control(
            'wadi_gallery_grid_video_icon_margin',
            [
                'label' => esc_html__( 'Margin', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_gallery_grid_container .wadi_gallery_grid .wadi_gallery_grid_item .wadi_icon_video_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],    
            ]    
        );            

        $this->end_controls_section();


        $this->start_controls_section(
			'wadi_gallery_grid_section_lightbox_style',
			[
				'label' => esc_html__( 'Lightbox', 'wadi-addons' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'wadi_gallery_grid_lightbox_background_color',
			[
				'label' => esc_html__( 'Lightbox Background Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wadi_gallery_grid_lightbox_elements_color',
			[
				'label' => esc_html__( 'Lightbox Elements Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'wadi_gallery_grid_lightbox_elements_hover_color',
			[
				'label' => esc_html__( 'Lightbox Elements Hover Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .dialog-lightbox-close-button:hover, #elementor-lightbox-slideshow-{{ID}} .elementor-swiper-button:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'wadi_gallery_grid_lightbox_video_width',
			[
				'label' => esc_html__( 'Lightbox Video Width (%)', 'wadi-addons' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 50,
					],
				],
				'selectors' => [
					'#elementor-lightbox-slideshow-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
    }


    protected function get_item_image_url( $item, array $settings ) {
		$image_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $item['wadi_gallery_grid_image']['id'], 'wadi_gallery_grid_image', $settings );

		if ( ! $image_url ) {
			$image_url = $item['wadi_gallery_grid_image']['url'];
		}

		return $image_url;
	}

    protected function render(){

        $settings = $this->get_settings_for_display();

        $repeaterGalleryGrid = $settings['wadi_gallery_grid_content_repeater'];
        $extra_class = ! empty( $settings['wadi_gallery_grid_extra_class'] ) ? ' ' . $settings['wadi_gallery_grid_extra_class'] : '';
       
        $layoutModeSettings = array("layoutMode"=> $settings['wadi_gallery_grid_layout_mode']);
        


        $this->add_render_attribute(
			'wadi_gallery_grid_container', [
                'class' =>  [
                    'wadi_gallery_grid_container',
                    'wadi_gallery_grid_container_' . esc_attr( $this->get_id() ),
                    $extra_class,
                ],
                'data-settings' => wp_json_encode($layoutModeSettings)
            ]
		);


        $this->add_render_attribute(
			'wadi_gallery_grid_filter',
			'class',
			array(
				'wadi_gallery_grid_filter',
				'wadi_gallery_grid_filter_' . esc_attr( $this->get_id() ),
			)
		);

        $this->add_render_attribute(
			'wadi_gallery_grid_filters_list',
			'class',
			array(
				'wadi_gallery_grid_filters_list',
				'wadi_gallery_grid_filters_list_' . esc_attr( $this->get_id() ),
			)
		);
        


        $this->add_render_attribute(
			'wadi_gallery_grid', [
                'class' => [
                    'wadi_gallery_grid',
                    'wadi_gallery_grid_' . esc_attr( $this->get_id() ),
                ],
            ]
		);

        


        ?>

        <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_gallery_grid_container' ) ); ?>>
            <?php if ($settings['wadi_gallery_grid_categories_filter_switcher'] === 'yes') : ?>
                <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_gallery_grid_filter' ) ); ?>>
                    <ul <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_gallery_grid_filters_list' ) ); ?>>
                        <?php if(!empty($settings['wadi_gallery_grid_categories_first_tab'])) :
                            $first_tab_filter = strtolower($settings['wadi_gallery_grid_categories_first_tab']);
                            ?>
                        <li class="wadi_gallery_grid_filter_item">
                            <a href="javascript:void(0);" class="data_filter" data-filter="*"><?php echo wp_kses_post($first_tab_filter); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php 
                            $repeaterCategories = $settings['wadi_gallery_grid_categories_repeater'];

                            foreach($repeaterCategories as $index => $cat ) { 

                                $cat_label = strtolower($cat['wadi_gallery_grid_category_label']);
                                $cat_label_u = str_replace(' ', '_', $cat_label);

                                ?>
                                <li class="wadi_gallery_grid_filter_item">
                                    <a href="javascript:void(0);" class="data_filter" data-filter=".<?php echo wp_kses_post($cat_label_u); ?>"><?php echo wp_kses_post($cat_label); ?></a>
                                </li>
                                <?php
                            }
                        ?>
                    </ul>
                </div>

            <?php endif; ?>

            <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'wadi_gallery_grid' ) ); ?>>
                <div class="wadi_grid_sizer"></div>
                <?php

                    foreach ($repeaterGalleryGrid as $key => $item) {
                        # code...
                        $lightbox_vid = 'wadi_gallery_grid_vid_lightbox_' . $key;
                        $lightbox_key = 'wadi_gallery_grid_image_lightbox_' . $key;
                        $self_hosted_video_key = 'wadi_gallery_grid_vid_self_hosted_' . $key;
				        $gg_count = $key + 1;

                        $wadi_gallery_grid_setting_key = $this->get_repeater_setting_key( 'wadi_gallery_item_categories', 'wadi_gallery_grid_content_repeater', $key );
                        
                        $categories = ! empty( $item['wadi_gallery_item_categories'] ) ? ' ' . $item['wadi_gallery_item_categories'] : '';



                        $myArray = explode(',', $categories);

                        $cleanCategories = array();

                        foreach($myArray as $catItem) {

                            $catItem = strtolower($catItem);
                            $catItem = ltrim($catItem);
                            $catItem = rtrim($catItem);
                            $catItem = str_replace(' ', '_', $catItem);

                            array_push($cleanCategories, $catItem);
 
                        }

                        
                        
                        $this->add_render_attribute(
                            'wadi_gallery_grid_item_render' . $item['_id'],
                            'class',
                            array(
                                'wadi_gallery_grid_item',
                                'wadi_gallery_grid_item_' . $item['_id'],
                                'elementor-repeater-item-' . esc_attr($item['_id']),
                                'wadi_gallery_grid_item_repeater_' . $key,
                                // $categories
                               implode(' ',$cleanCategories)
                            )
                        );

                            
                            if('media_video' == $item['wadi_gallery_grid_content_type']) {
                                $item_id = $this->get_video_id($item);
                                $embed_params = $this->get_video_embed_params($item);
                                $src = $this->get_url( $item, $embed_params, $item_id );
                                $video_type = $item['wadi_grid_gallery_video_type'];


                                $video_url = '';
    
                                if('vimeo' == $item['wadi_grid_gallery_video_type'] && isset($item['vimeo_url']) ) {
                                    $video_url = $src;
                                    $lightbox_video_url = $video_url;

                                } elseif('youtube' == $item['wadi_grid_gallery_video_type'] && isset($item['youtube_url']) ) {
                                    $video_url = $src;
                                    $lightbox_video_url = $video_url;
                                } elseif('self_hosted' ==  $item['wadi_grid_gallery_video_type'] ) {
                                    
                                    if ( 'local' == $item['wadi_grid_gallery_self_hosted_video'] && isset($item['self_hosted_url']) ) {
                                        $video_url = $item['self_hosted_url']['url'];
                                    } elseif( 'remote' == $item['wadi_grid_gallery_self_hosted_video'] && isset($item['self_hosted_remote_url'])) {
                                        $video_url = $item['self_hosted_remote_url'];
                                    }
                                    
  
                                    if('yes' === $item['wadi_video_self_hosted_autoplay']) {
                                        $this->add_render_attribute( 'wadi_gallery_grid_item_render' . $item['_id'], 'autoplay' );
                                    }
                                    if('yes' === $item['wadi_video_self_hosted_loop']) {
                                        $this->add_render_attribute( 'wadi_gallery_grid_item_render' . $item['_id'],  'loop' );
                                    }
                                    if('yes' === $item['wadi_video_self_hosted_muted']) {
                                        $this->add_render_attribute( 'wadi_gallery_grid_item_render' . $item['_id'], 'muted' );
                                    }
                                    if('yes' === $item['wadi_video_self_hosted_controls']) {
                                        $this->add_render_attribute( 'wadi_gallery_grid_item_render' . $item['_id'],'controls' );
                                    }
                                    if('yes' === $item['wadi_video_self_hosted_playsinline']) {
                                        $this->add_render_attribute( 'wadi_gallery_grid_item_render' . $item['_id'], 'playsinline' );
                                    }
                                    if($item['wadi_video_self_hosted_poster']) {
                                        $this->add_render_attribute( 'wadi_gallery_grid_item_render' . $item['_id'], 'poster', $item['wadi_video_self_hosted_poster'] );
                                    }
                                    if('yes' === $item['wadi_video_self_hosted_download_button']) {
                                        $this->add_render_attribute( 'wadi_gallery_grid_item_render' . $item['_id'], 'controlsList', 'nodownload' );
                                    }


                                    if(isset($video_url) && $video_url && !empty($video_url)) {
                                        if ( $item['wadi_video_self_hosted_start'] || $item['wadi_video_self_hosted_end'] ) {
                                            $video_url .= '#t=';
                                        }
                                
                                        if ( $item['wadi_video_self_hosted_start'] ) {
                                            $video_url .= $item['wadi_video_self_hosted_start'];
                                        }
                                
                                        if ( $item['wadi_video_self_hosted_end'] ) {
                                            $video_url .= ',' . $item['wadi_video_self_hosted_end'];
                                        }
                            
                                    }

                                    $this->add_render_attribute(
                                        'wadi_gallery_grid_item_render' . $item['_id'], 
                                        [
                                            'data-self-hosted-type' => $item['wadi_grid_gallery_self_hosted_video'],
                                            'data-self-hosted-video-link' => $video_url,
                                        ]
                                    );

                                    $this->add_render_attribute(
                                        $self_hosted_video_key,
                                        [
                                            'class' => ['wadi_gallery_grid_video'],
                                            'href' => wp_kses_post($this->get_item_image_url($item, $settings)),
                                            'data-video-type' => esc_attr($item['wadi_grid_gallery_video_type']),
                                            'data-video-src' => wp_kses_post($video_url),
                                            'data-self-hosted-settings' => wp_json_encode($this->get_hosted_parameter($item)),
                                        ]
                                    );
                                }

                                
                                
                                $lightbox_options = [
                                    'type' => 'video',
                                    'videoType' => 'youtube' === $video_type ? 'youtube' : $video_type,
                                    'url' => $lightbox_video_url,
                                    'modalOptions' => [
                                        'id' => 'elementor-lightbox-' . $this->get_id(),
                                        'videoAspectRatio' => 169,
                                    ],
                                ];

                                $this->add_render_attribute(
                                    $lightbox_vid,
                                    [
                                        'data-elementor-lightbox'  => wp_json_encode( $lightbox_options ),
                                    ]
                                );
    
    
                                $this->add_render_attribute(
                                    $lightbox_vid,
                                    [
                                        'data-elementor-lightbox-slideshow' => count($repeaterGalleryGrid) > 1 ? $this->get_id() : false,
                                        'data-elementor-open-lightbox' =>"yes" 
                                    ]
                                );


                                if ('self_hosted' === $video_type) {
                                    $lightbox_options['videoParams'] = $this->get_hosted_parameter($item);
                                }
                            }



                        if(!empty($item['wadi_gallery_grid_image']['url']) && 'file' == $item['wadi_gallery_grid_image_link_to_type']) :
                            $this->add_render_attribute(
                                $lightbox_key,
                                [
                                    'data-elementor-lightbox-slideshow' => count($repeaterGalleryGrid) > 1 ? $this->get_id() : false,
                                    'data-elementor-open-lightbox' => "yes", 
                                ]
                            );

                        endif;

                            // Content Type is Image
                            if( 'media_image' == $item['wadi_gallery_grid_content_type']) {
                                // Image Link is None
                                if('' == $item['wadi_gallery_grid_image_link_to_type']) :?>
                                    <div <?php $this->print_render_attribute_string( 'wadi_gallery_grid_item_render' . $item['_id'] ); ?>>
                                        <img class="wadi_gallery_grid_image" src="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" >
                                    </div>
                                <?php
                                // Image Link is Media File
                                elseif('file' == $item['wadi_gallery_grid_image_link_to_type']) : ?>
                                    <div <?php $this->print_render_attribute_string( 'wadi_gallery_grid_item_render' . $item['_id'] ); ?>>
                                        <a class="wadi_gallery_grid_lightbox_link" href="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" <?php echo wp_kses_post($this->get_render_attribute_string( $lightbox_key ) ) ?> >
                                        </a>
                                        <img class="wadi_gallery_grid_image" src="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" >
                                        <?php if (!empty($item['wadi_gallery_grid_title']) || !empty($item['wadi_gallery_grid_description'])) : ?>
                                        <div class="wadi_gallery_item_text_content">
                                            <div class="wadi_gallery_item_the_text">
                                                <?php if(!empty($item['wadi_gallery_grid_title'])) :?>
                                                    <div class="wadi_gallery_item_text_title"><?php echo wp_kses_post($item['wadi_gallery_grid_title']); ?></div>
                                                <?php endif; ?>
                                                <?php if(!empty($item['wadi_gallery_grid_description'])) :?>
                                                    <div class="wadi_gallery_item_text_description"><?php echo wp_kses_post($item['wadi_gallery_grid_description']); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                <?php
                                elseif('custom' == $item['wadi_gallery_grid_image_link_to_type']) : ?>
                                    <div <?php $this->print_render_attribute_string( 'wadi_gallery_grid_item_render' . $item['_id'] ); ?>>
                                        <a  class="wadi_gallery_grid_lightbox_link" href="<?php echo wp_kses_post( $item['wadi_gallery_grid_image_link_to']['url'] ); ?>" target="_blank">
                                        </a>
                                        <img class="wadi_gallery_grid_image" src="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" >
                                    </div>
                                <?php
                                
                                endif;

                            // Content Type is Video
                            } elseif('media_video' == $item['wadi_gallery_grid_content_type']) {
                                // Video Display on Lightbox
                                if('yes' == $item['wadi_grid_gallery_lightbox']){
                                    //  Video of Type YouTube
                                    if ('youtube' == $item['wadi_grid_gallery_video_type'] && isset($item['youtube_url'])) { ?>
                                        <div <?php $this->print_render_attribute_string( 'wadi_gallery_grid_item_render' . $item['_id'] ); ?>>
                                            <a class="wadi_gallery_grid_lightbox_link" href="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" data-elementor-lightbox-video="<?php echo $src ?>" <?php echo wp_kses_post($this->get_render_attribute_string( $lightbox_vid ) ) ?>>
                                            </a>
                                            <?php // Video Icon ?>
                                            <span class="wadi_icon_video_item">
                                                <?php $this->render_overlay_icon(); ?>
                                            </span>

                                            <img class="wadi_gallery_grid_image" src="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" >
                                            <?php if (!empty($item['wadi_gallery_grid_title']) || !empty($item['wadi_gallery_grid_description'])) : ?>
                                            <div class="wadi_gallery_item_text_content">
                                                <div class="wadi_gallery_item_the_text">
                                                    <?php if(!empty($item['wadi_gallery_grid_title'])) :?>
                                                        <div class="wadi_gallery_item_text_title"><?php echo wp_kses_post($item['wadi_gallery_grid_title']); ?></div>
                                                    <?php endif; ?>
                                                    <?php if(!empty($item['wadi_gallery_grid_description'])) :?>
                                                        <div class="wadi_gallery_item_text_description"><?php echo wp_kses_post($item['wadi_gallery_grid_description']); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                        //  Video of Type Vimeo
                                    } elseif('vimeo' == $item['wadi_grid_gallery_video_type'] && isset($item['vimeo_url'])) { ?>
                                        <div <?php $this->print_render_attribute_string( 'wadi_gallery_grid_item_render' . $item['_id'] ); ?>>
                                            <a class="wadi_gallery_grid_lightbox_link" href="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" data-elementor-lightbox-video="<?php echo $src ?>" <?php echo wp_kses_post($this->get_render_attribute_string( $lightbox_vid ) ) ?>>
                                            </a>
                                            <?php // Video Icon ?>
                                            <span class="wadi_icon_video_item">
                                                <?php $this->render_overlay_icon(); ?>
                                            </span>

                                            <img class="wadi_gallery_grid_image" src="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" >
                                            <?php if (!empty($item['wadi_gallery_grid_title']) || !empty($item['wadi_gallery_grid_description'])) : ?>
                                            <div class="wadi_gallery_item_text_content">
                                                <div class="wadi_gallery_item_the_text">
                                                    <?php if(!empty($item['wadi_gallery_grid_title'])) :?>
                                                        <div class="wadi_gallery_item_text_title"><?php echo wp_kses_post($item['wadi_gallery_grid_title']); ?></div>
                                                    <?php endif; ?>
                                                    <?php if(!empty($item['wadi_gallery_grid_description'])) :?>
                                                        <div class="wadi_gallery_item_text_description"><?php echo wp_kses_post($item['wadi_gallery_grid_description']); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php
                                    }
                                    // Lightbox is Off for YouTube and Vimeo or video type is Dailymotion, Self Hosted 
                                } else {
                                    // Video Type Yotube Inline
                                    $this->add_render_attribute(
                                        'wadi_gallery_grid_item_render' . $item['_id'],
                                        'class',
                                        array(
                                            'wadi_gallery_grid_item_video_inline',
                                        )
                                    );
                                    
                                    if ('youtube' == $item['wadi_grid_gallery_video_type'] && isset($item['youtube_url']) || 'vimeo' == $item['wadi_grid_gallery_video_type'] && isset($item['vimeo_url']) || 'dailymotion' == $item['wadi_grid_gallery_video_type'] && isset($item['dailymotion_url'])) { 
                                        ?>

                                        <div <?php $this->print_render_attribute_string( 'wadi_gallery_grid_item_render' . $item['_id'] ); ?>>
                                            <div class="wadi_gallery_grid_video" href="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" data-video-type="<?php echo esc_attr($item['wadi_grid_gallery_video_type']); ?>" data-video-src="<?php echo wp_kses_post($src); ?>">
                                            </div>
                                            <?php // Video Icon ?>
                                            <span class="wadi_icon_video_item">
                                                <?php $this->render_overlay_icon(); ?>
                                            </span>

                                            <img class="wadi_gallery_grid_image" src="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" >
                                            <?php if (!empty($item['wadi_gallery_grid_title']) || !empty($item['wadi_gallery_grid_description'])) : ?>
                                            <div class="wadi_gallery_item_text_content">
                                                <div class="wadi_gallery_item_the_text">
                                                    <?php if(!empty($item['wadi_gallery_grid_title'])) :?>
                                                        <div class="wadi_gallery_item_text_title"><?php echo wp_kses_post($item['wadi_gallery_grid_title']); ?></div>
                                                    <?php endif; ?>
                                                    <?php if(!empty($item['wadi_gallery_grid_description'])) :?>
                                                        <div class="wadi_gallery_item_text_description"><?php echo wp_kses_post($item['wadi_gallery_grid_description']); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php
                                    } elseif('self_hosted' == $item['wadi_grid_gallery_video_type']) { 
                                        // Video Type Self Hosted Inline
                                            $this->add_render_attribute(
                                                'wadi_gallery_grid_item_render' . $item['_id'],
                                                'class',
                                                array(
                                                    'wadi_gallery_grid_item_video_inline',
                                                )
                                            );
                                        ?>
                                        
                                        <div <?php $this->print_render_attribute_string( 'wadi_gallery_grid_item_render' . $item['_id'] ); ?>>
                                            <div <?php $this->print_render_attribute_string( $self_hosted_video_key ); ?>>
                                            </div>
                                            <?php // Video Icon ?>
                                            <span class="wadi_icon_video_item">
                                                <?php $this->render_overlay_icon(); ?>
                                            </span>

                                            <img class="wadi_gallery_grid_image" src="<?php echo wp_kses_post($this->get_item_image_url($item, $settings)); ?>" >
                                            <?php if (!empty($item['wadi_gallery_grid_title']) || !empty($item['wadi_gallery_grid_description'])) : ?>
                                            <div class="wadi_gallery_item_text_content">
                                                <div class="wadi_gallery_item_the_text">
                                                    <?php if(!empty($item['wadi_gallery_grid_title'])) :?>
                                                        <div class="wadi_gallery_item_text_title"><?php echo wp_kses_post($item['wadi_gallery_grid_title']); ?></div>
                                                    <?php endif; ?>
                                                    <?php if(!empty($item['wadi_gallery_grid_description'])) :?>
                                                        <div class="wadi_gallery_item_text_description"><?php echo wp_kses_post($item['wadi_gallery_grid_description']); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                        </div>

                                        
                                        <?php
                                    }

                                }
                            }


                        
                    }
                
                ?>
                
            </div>
        </div>
        <?php
		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {

				$this->render_editor_script();
		}
        
    }


    /**
	 * Returns Video ID.
	 *
	 * @since 1.0.9
	 * @access protected
	 */
	protected function get_video_id($item) {

		// $settings = $this->get_settings_for_display();
        if( 'media_video' === $item['wadi_gallery_grid_content_type']) {
            $id       = '';
            $url      = $item[ $item['wadi_grid_gallery_video_type'] . '_url' ];

            if ( 'youtube' === $item['wadi_grid_gallery_video_type'] ) {

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

            } elseif ( 'vimeo' === $item['wadi_grid_gallery_video_type'] ) {

                $id = preg_replace( '/[^\/]+[^0-9]|(\/)/', '', rtrim( $url, '/' ) );

            } elseif( 'dailymotion' === $item['wadi_grid_gallery_video_type'] ) {
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
	}


    
    /**
     * Video Embed Params
     * 
     * @since 1.0.9
     * @access protected
     * 
     */

    protected function get_video_embed_params( $item ) {

        if( 'media_video' === $item['wadi_gallery_grid_content_type']) {

            $id = $this->get_video_id($item);

            $params = array();

            if('youtube' === $item['wadi_grid_gallery_video_type']) {
                $youtube_options = ['autoplay', 'loop', 'rel', 'controls', 'mute', 'modestbranding'];

                foreach($youtube_options as $option) {        
                    

                        if ( 'autoplay' === $option ) {
                            if ( 'yes' === $item['wadi_youtube_video_autoplay'] ) {
                                $params[ $option ] = '1';
                            }
                            continue;
                        }

                        $value             = ( 'yes' === $item[ 'wadi_youtube_video_' . $option ] ) ? '1' : '0';
                        $params['start']   = $item['wadi_youtube_video_start'];
                        $params['end']     = $item['wadi_youtube_video_end'];
                        $params['playlist'] = $id; // For Loop to work we have to add to youtube params ID of the video : https://stackoverflow.com/questions/25779966/youtube-iframe-loop-doesnt-work
                        $params[ $option ] = $value;


                }
            }

            if ('vimeo' === $item['wadi_grid_gallery_video_type']) {
                $vimeo_options = ['autoplay', 'loop', 'title', 'byline', 'portrait', 'muted'
                // , 'color'
                ];

                foreach ($vimeo_options as $option) {
                    
                    if ('autoplay' === $option) {
                        if ('yes' === $item['wadi_vimeo_video_autoplay']) {
                            $params[ $option ] = '1';
                        }
                        continue;
                    }

                    $value             = ('yes' === $item[ 'wadi_vimeo_video_' . $option ]) ? '1' : '0';
                    $params[ $option ] = $value;
                }

                $params['color']     = isset($item['color']) ?? str_replace( '#', '', $item['color'] );
                $params['autopause'] = '0';
            }

            if('dailymotion' === $item['wadi_grid_gallery_video_type']) {
                $dailymotion_options = ['controls', 'mute', 'showinfo', 'logo', 'autoplay'];

                foreach($dailymotion_options as $option) {

                    $value             = ('yes' === $item[ 'wadi_dailymotion_video_' . $option ]) ? '1' : '0';
                    $params['ui-highlight'] = isset( $item['color']) ?? str_replace( '#', '', $item['color'] );
                    $params['start'] = $item['wadi_dailymotion_video_start'];
                    $params[ $option ] = $value;
                }
            }

            return $params;
        }

        
     }


     
    /**
	 * Returns Video URL.
	 * @param string $item Video Item in Gallery Grid repeater.
	 * @param array  $params Video Param array.
	 * @param string $id Video ID.
	 * @since 1.0.9
	 * @access protected
	 */
	protected function get_url( $item, $params, $id ) {

		// $settings = $this->get_settings_for_display();
        if( 'media_video' === $item['wadi_gallery_grid_content_type']) {

            $url      = '';

            if ( 'vimeo' === $item['wadi_grid_gallery_video_type'] ) {

                $url = 'https://player.vimeo.com/video/';

            } elseif ( 'youtube' === $item['wadi_grid_gallery_video_type'] ) {

                if( 'yes' != $item['wadi_grid_gallery_lightbox']) {
                    $cookie = '';
    
                    if ( 'yes' === $item['wadi_youtube_privacy_switch'] ) {
                        $cookie = '-nocookie';
                    }
                    $url = 'https://www.youtube' . $cookie . '.com/embed/';
                }
                
                $url = 'https://www.youtube.com/embed/';

            } elseif('dailymotion' === $item['wadi_grid_gallery_video_type']) {
                $url = 'https://www.dailymotion.com/embed/video/';
            }

            $url = add_query_arg( $params, $url . $id );
            
            
            $url .= ( empty( $params ) ) ? '?' : '&';
            
            if( 'yes' === $item['wadi_grid_gallery_lightbox']) {
                $url .= 'autoplay=1';
            }
            
            if ( 'vimeo' === $item['wadi_grid_gallery_video_type'] && '' !== $item['wadi_vimeo_video_start'] ) {

                $time = gmdate( 'H\hi\ms\s', $item['wadi_vimeo_video_start'] );
                $url .= '#t=' . $time;
            } elseif ( 'vimeo' === $item['wadi_grid_gallery_video_type'] ) {

                $url .= '#t=';
            }

            $url = apply_filters( 'wadi_gallery_grid_video_url_filter', $url, $id );

            return $url;
        }
	}


        /**
	 * Get hosted video parameters.
	 *
	 * @since 1.0.3
	 * @access protected
	 */
	private function get_hosted_parameter($item) {
		// $settings = $this->get_settings_for_display();
        if( 'media_video' === $item['wadi_gallery_grid_content_type']) {

            if ( 'self_hosted' === $item['wadi_grid_gallery_video_type'] ) {

                $video_params = array();

                foreach ( array( 'autoplay', 'loop', 'controls', 'muted', 'playsinline' ) as $option_name ) {
                    if ( 'yes' === $item[ 'wadi_video_self_hosted_'.$option_name ] ) {
                        $video_params[ $option_name ] = $option_name;
                    }
                }

                if ( ! $item['wadi_video_self_hosted_download_button'] ) {
                    $video_params['controlsList'] = 'nodownload';
                }

                if ( $item['wadi_video_self_hosted_poster']['url'] ) {
                    $video_params['poster'] = $item['wadi_video_self_hosted_poster']['url'];
                }
                if ( $item['wadi_video_self_hosted_muted'] ) {
                	$video_params['muted'] = 'muted';
                }

                return $video_params;
            }
        }
	}


	/**
	 * Render Editor Masonry Script.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render_editor_script() {

		?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {

				$( '.wadi_gallery_grid' ).each( function(index, item) {

					const $node_id 	= '<?php echo esc_attr( $this->get_id() ); ?>';
					const scope 	= $( '[data-id="' + $node_id + '"]' );
					const settings  = $(this).parent().data("settings");
					const selector 	= $(this);


					if ( selector.closest( scope ).length < 1 ) {
						return;
					}

					var masonryArgs = {
						// set itemSelector so .grid-sizer is not used in layout
						filter 			: localStorage.getItem('wadiActiveFilter'),
						itemSelector	: '.wadi_gallery_grid_item',
						percentPosition : true,
						layoutMode		: settings.layoutMode,
					};

					var $isotopeObj = {};
                    $isotopeObj = selector.isotope( masonryArgs );
                    $isotopeObj.isotope( 'layout' );

					selector.imagesLoaded( function() {
						$isotopeObj = selector.isotope( masonryArgs );
                        selector.isotope( 'layout' );
						selector.find('.wadi_gallery_grid_item').resize( function() {
							$isotopeObj.isotope( 'layout' );
						});
					});

				});
			});
		</script>
		<?php
	}

    private function render_overlay_icon() {
		$icon_value = 'fas fa-play';

		$icon = [
			'library' => 'fa-solid',
			'value' => $icon_value,
		];

		Icons_Manager::render_icon( $icon );
	}

}