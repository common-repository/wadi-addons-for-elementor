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
use Elementor\Plugin;
use WadiAddons;
// Wadi Classes
use WadiAddons\Includes\WadiHelpers;
use WadiAddons\Includes\WadiQueries;
use WadiAddons\Controls\Wadi_Taxonomies;
use WadiAddons\Controls\Wadi_Simple;

if (! defined('ABSPATH')) {
    exit;
}

class Wadi_Posts extends Widget_Base
{
    public function get_name()
    {
        return 'wadi-posts-addon';
    }

    public function get_title()
    {
        return sprintf('%1$s %2$s', 'Wadi', __('Posts', 'wadi-addons'));
    }

    public function get_icon()
    {
        return 'wadi-posts';
    }

    public function get_categories()
    {
        return [ 'wadi-elements' ];
    }

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_register_script('script-wadi_posts_images_loaded', WADI_ADDONS_URL . 'assets/extLib/imagesloaded.pkgd.min.js', ['jquery'], '4.1.4', true);
        wp_register_script('script-wadi_posts_isotope', WADI_ADDONS_URL . 'assets/extLib/isotope.pkgd.js', ['jquery'], '4.1.4', true);
        wp_register_script('script-handle_posts', WADI_ADDONS_URL . 'assets/min/wadi-posts.min.js', [ 'elementor-frontend', 'jquery', 'script-wadi_posts_isotope', 'script-wadi_posts_images_loaded' ], '1.0.0', true);
        if(!is_rtl()) {
            wp_register_style('style-handle_posts', WADI_ADDONS_URL . 'assets/min/wadi-posts.css');
        } else {
            wp_register_style('style-handle_posts', WADI_ADDONS_URL . 'assets/min/wadi-posts.rtl.css');
        }

        wp_localize_script(
			'script-handle_posts',
			'WadiAddons',
			array(
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'   => wp_create_nonce( 'wadi-nonce' ),
			)
		);

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
        return [ 'wadi-addons','posts','blog', 'query', 'posts grid', 'posts carousel' ];
    }


    public function get_script_depends()
    {
        return [ 'script-handle_posts' ];
    }

    public function get_style_depends()
    {
        return [ 'style-handle_posts' ];
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
            'wadi_posts_content_layout_settings_section',
            [
                'label' => esc_html__('Layout Settings', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        
        $this->add_control(
            'wadi_posts_skins',
            [
                'label' => esc_html__('Skins', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'classic' => esc_html__('Classic', 'wadi-addons'),
                    'card' => esc_html__('Card', 'wadi-addons'),
                    'side' => esc_html__('On Side', 'wadi-addons'),
                ],
                'default'     => 'classic',
				'label_block' => true,
                'frontend_available' => true,
            ]
        );
       

        $this->add_responsive_control(
            'wadi_posts_columns',
            [
                'label' => esc_html__('Columns', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => '33.330%',
                'tablet_default'     => '50%',
                'mobile_default'     => '100%',
				'options'         => [
					'100%'    => __( '1 Column', 'wadi-addons' ),
					'50%'     => __( '2 Columns', 'wadi-addons' ),
					'33.330%' => __( '3 Columns', 'wadi-addons' ),
					'25%'     => __( '4 Columns', 'wadi-addons' ),
					'20%'     => __( '5 Columns', 'wadi-addons' ),
					'16.660%'  => __( '6 Columns', 'wadi-addons' ),
                ],
				'selectors'       => [
					'{{WRAPPER}} .wadi_single_post_container' => 'width: {{VALUE}};',
                ],
				'render_type'     => 'template',
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                ],
				'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_posts_per_page',
            [
                'label' => esc_html__('Posts Per Page', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'description' => __('Set Posts per Page, <b>Note: Posts per page should be more than columns number</b>.'),
                'min' => 1,
				'step' => 1,
				'default' => 4,
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_posts_layout',
            [
                'label' => esc_html__('Layout', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'grid' => esc_html__('Grid', 'wadi-addons'),
                    'masonry' => esc_html__('Masonry', 'wadi-addons'),
                    'fitRows' => esc_html__('Even', 'wadi-addons'),
                    'carousel' => esc_html__('Carousel', 'wadi-addons'),
                ],
                'condition' => [
                    'wadi_posts_skins!' => 'side',
                ],
                'frontend_available' => true,
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'wadi_posts_enable_pagination',
            [
                'label' => esc_html__('Enable Pagination', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'separator' => 'before',
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_posts_pagination_select',
            [
                'label' => esc_html__('Pagingation', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'numbers' => esc_html__('Numbers', 'wadi-addons'),
                    'infinite' => esc_html__('Infinite', 'wadi-addons'),
                ],
                'default' => 'numbers',
                'condition' => [
                    'wadi_posts_enable_pagination' => 'yes',
                    'wadi_posts_layout!' => ['carousel'],
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
			'wadi_posts_prev_text',
			array(
				'label'     => __( 'Previous Page Text', 'wadi-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Previous', 'wadi-addons' ),
				'condition' => array(
					'wadi_posts_enable_pagination' => 'yes',
					'wadi_posts_pagination_select!'  => ['infinite'],
                    'wadi_posts_layout!' => ['carousel'],
				),
			)
		);

		$this->add_control(
			'wadi_posts_next_text',
			array(
				'label'     => __( 'Next Page Text', 'wadi-addons' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'Next', 'wadi-addons' ),
				'condition' => array(
					'wadi_posts_enable_pagination' => 'yes',
					'wadi_posts_pagination_select!'  => ['infinite'],
                    'wadi_posts_layout!' => ['carousel'],
				),
			)
		);

        $this->add_control(
            'wadi_posts_pagination_infinite_select',
            [
                'label' => esc_html__('Infinite Load Event', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'wadi_infinite_button' => esc_html__('Load Button Click', 'wadi-addons'),
                    'wadi_infinite_scroll' => esc_html__('Load on Scroll', 'wadi-addons'),
                ],
                'default' => 'wadi_infinite_button',
                'condition' => [
                    'wadi_posts_enable_pagination' => 'yes',
                    'wadi_posts_pagination_select' => 'infinite',
                    'wadi_posts_layout!' => ['carousel'],
                ],
                'frontend_available' => true,
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_posts_query_content_section',
            [
                'label' => esc_html__('Query', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$post_types = WadiQueries::list_post_types();

        $this->add_control(
            'wadi_posts_query_content_type_filter',
            [
                'label' => esc_html__('Content Type', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => $post_types,
                'default'     => 'post',
            ]
        );

        foreach ( $post_types as $key => $type ) {

			// Get all the taxanomies associated with the selected post type.
			$taxonomy = WadiQueries::get_taxnomies( $key );

			if ( ! empty( $taxonomy ) ) {

				// Get all taxonomy values under the taxonomy.
				foreach ( $taxonomy as $index => $tax ) {

					$terms = get_terms( $index );

					$related_tax = array();

					if ( ! empty( $terms ) ) {

						foreach ( $terms as $t_index => $t_obj ) {

							$related_tax[ $t_obj->slug ] = $t_obj->name;
						}

						// Add filter rule for the each taxonomy.
						$this->add_control(
							$index . '_' . $key . '_filter_rule',
							array(
								/* translators: %s Taxnomy Label */
								'label'       => sprintf( __( '%s Filter Rule', 'wadi-addons' ), $tax->label ),
								'type'        => Controls_Manager::SELECT,
								'default'     => 'IN',
								'label_block' => true,
								'options'     => array(
									/* translators: %s: Taxnomy Label */
									'IN'     => sprintf( __( 'Match %s', 'wadi-addons' ), $tax->label ),
									/* translators: %s: Taxnomy Label */
									'NOT IN' => sprintf( __( 'Exclude %s', 'wadi-addons' ), $tax->label ),
								),
                                'separator' => 'before',
								'condition'   => array(
									'wadi_posts_query_content_type_filter' => $key,
								),
							)
						);

						// Add select control for each taxonomy.
						$this->add_control(
							'tax_' . $index . '_' . $key . '_filter',
							array(
								/* translators: %s Taxnomy Label */
								'label'       => sprintf( __( '%s Filter', 'wadi-addons' ), $tax->label ),
								'type'        => Controls_Manager::SELECT2,
								'default'     => '',
								'multiple'    => true,
								'label_block' => true,
								'options'     => $related_tax,
								'condition'   => array(
									'wadi_posts_query_content_type_filter' => $key,
								),
							)
						);

					}
				}
			}
		}


        $this->add_control(
            'wadi_content_filter_by_author_rule',
            array(
                'label'       => __( 'Filter By Author Rule', 'wadi-addons' ),
                'type'        => Controls_Manager::SELECT,
                'separator'   => 'before',
                'default'     => 'author__in',
                'label_block' => true,
                'options'     => array(
                    'author__in'     => __( 'Match Author', 'wadi-addons' ),
                    'author__not_in' => __( 'Exclude Author', 'wadi-addons' ),
                ),
            )
        );

        $listAuthors = WadiQueries::list_authors();
        $this->add_control(
			'wadi_get_list_users',
			array(
				'label'       => __( 'Authors', 'wadi-addons' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $listAuthors,
                'separator'   => 'after',
			)
		);

        $this->add_control(
            'wadi_posts_filter_rule',
            [
                'label' => esc_html__('Posts Filter Rule', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'default' => 'post__in',
                'label_block' => true,
                'options' => [
                    'post__in' => esc_html__('Match Posts', 'wadi-addons'),
                    'post__not_in' => esc_html__('Exclude Posts', 'wadi-addons'),
                ],
            ]
        );


        foreach ( $post_types as $key => $type ) {

			// Get all the taxanomies associated with the selected post type.
			$posts_by_post_type = WadiQueries::get_posts_by_post_types( $key );

			if ( ! empty( $posts_by_post_type ) ) {

						// Add filter rule for the each taxonomy.
						$this->add_control(
							$key . '_filter_post_type',
							array(
								/* translators: %s Taxnomy Label */
								'label'       => sprintf( __( 'Post Type Filter ['.$type.']', 'wadi-addons' ) ),
								'type'        => Controls_Manager::SELECT2,
                                'options' => $posts_by_post_type,
                                'multiple' => true,
                                'label_block' => true,
                                'separator' => 'before',
								'condition'   => array(
									'wadi_posts_query_content_type_filter' => $key,
								),
							)
						);

			}
		}

        $this->add_control(
            'wadi_posts_sticky',
            [
                'label' => esc_html__('Ignore Sticky Posts', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'default' => 'yes',
                'separator' => 'before',
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                ],
            ]
        );

        $this->add_control(
            'wadi_posts_offset',
            [
                'label' => esc_html__('Offset', 'wadi-addons'),
                'type'        => Controls_Manager::NUMBER,
				'default'     => 0,
				'description' => esc_html__( 'Set number of posts to offset (Not to include).', 'wadi-addons' ),
            ]
        );

        $this->add_control(
			'wadi_query_exclude_current',
			[
				'label'       => esc_html__( 'Exclude Current Post', 'wadi-addons' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => esc_html__( 'This option will remove the current post from the query.', 'wadi-addons' ),
				'label_on'    => esc_html__( 'Yes', 'wadi-addons' ),
				'label_off'   => esc_html__( 'No', 'wadi-addons' ),
            ]
		);

        $this->add_control(
            'wadi_order_posts_heading',
            [
                'label' => esc_html__('Items Order', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'wadi_order_by_filter',
            [
                'label' => esc_html__('Items Order', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
				'label_block' => true,
				'options'     => [
					'none'          => esc_html__( 'None', 'wadi-addons' ),
					'ID'            => esc_html__( 'ID', 'wadi-addons' ),
					'author'        => esc_html__( 'Author', 'wadi-addons' ),
					'title'         => esc_html__( 'Title', 'wadi-addons' ),
					'name'          => esc_html__( 'Name', 'wadi-addons' ),
					'date'          => esc_html__( 'Date', 'wadi-addons' ),
					'modified'      => esc_html__( 'Last Modified', 'wadi-addons' ),
					'rand'          => esc_html__( 'Random', 'wadi-addons' ),
					'comment_count' => esc_html__( 'Comments Count', 'wadi-addons' ),
                ],
				'default'     => 'date',
            ]
        );

        $this->add_control(
            'wadi_order_filter',
            [
                'label'     => esc_html__( 'Order', 'wadi-addons' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'DESC',
                'options'   => array(
                    'DESC' => esc_html__( 'Descending', 'wadi-addons' ),
                    'ASC'  => esc_html__( 'Ascending', 'wadi-addons' ),
                ),
                'condition' => [
                    'wadi_order_by_filter!' => 'none', 
                ]
            ]
        );

        $this->add_control(
            'wadi_not_found_switcher',
            [
                'label' => __('<b>Not Found & No More Posts</b>', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Show Not found Text in case of query does not contain any further posts.', 'wadi-addons' ),
				'label_on'    => esc_html__( 'Yes', 'wadi-addons' ),
				'label_off'   => esc_html__( 'No', 'wadi-addons' ),
            ]
        );


        $this->add_control(
            'wadi_not_found_text',
            [
                'label' => esc_html__('Not Found Items Text', 'wadi-addons'),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '<h1>No Posts Found</h1>',
				'label_block' => true,
                'condition' => [
                    'wadi_not_found_switcher' => 'yes'
                ]
            ]
        );

        $this->add_control(
            'wadi_show_search_box',
            [
                'label'        => __( 'Display Search Box', 'wadi-addons' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wadi-addons' ),
                'label_off'    => __( 'No', 'wadi-addons' ),
                'default'      => 'yes',
                'description'  => __( 'Display search box if Nothing was found.', 'wadi-addons' ),
                'condition' => [
                    'wadi_not_found_switcher' => 'yes'
                ]
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_posts_display_options_section',
            [
                'label' => esc_html__('Display Options', 'wadi-addons'),
                'type' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_posts_posts_title_tag',
            [
                'label' => esc_html__('Title Tag', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'description' => esc_html__('HTML tag for Title (Default is H2).', 'wadi-addons'),
                'options' => [
                    'div' => esc_html__('div', 'wadi-addons'),
                    'span' => esc_html__('span', 'wadi-addons'),
                    'p' => esc_html__('P', 'wadi-addons'),
                    'h1' => esc_html__('H1', 'wadi-addons'),
                    'h2' => esc_html__('H2', 'wadi-addons'),
                    'h3' => esc_html__('H3', 'wadi-addons'),
                    'h4' => esc_html__('H4', 'wadi-addons'),
                    'h5' => esc_html__('H5', 'wadi-addons'),
                    'h6' => esc_html__('H6', 'wadi-addons'),
                ],
                'default' => 'h2',
            ]
        );

        $this->add_responsive_control(
            'wadi_posts_rows_spacing',
            [
                'label' => esc_html__('Rows Spacing', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_single_post_container' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
            ]
        );

        $this->add_responsive_control(
            'wadi_posts_columns_spacing',
            [
                'label' => esc_html__('Columns Spacing', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'rem' => [
                        'min' => 0,
						'max' => 20,
					],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
					'em' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_single_post_container' => 'padding-right: calc({{SIZE}}{{UNIT}}/ 2);padding-left: calc({{SIZE}}{{UNIT}}/ 2);',
					'{{WRAPPER}} .wadi_posts_container' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
            ]
        );

        $postTextAlingment = [
            'left'    => [
                'title' => __( 'Left', 'wadi-addons' ),
                'icon'  => 'eicon-text-align-left',
            ],
            'center'  => [
                'title' => __( 'Center', 'wadi-addons' ),
                'icon'  => 'eicon-text-align-center',
            ],
            'right'   => [
                'title' => __( 'Right', 'wadi-addons' ),
                'icon'  => 'eicon-text-align-right',
            ],
            'justify' => [
                'title' => __( 'Justify', 'wadi-addons' ),
                'icon'  => 'eicon-text-align-justify',
            ],
        ];

        $rtl_postTextAlingment = array_reverse($postTextAlingment);

        $thePostTextAlingment = !is_rtl() ? $postTextAlingment : $rtl_postTextAlingment;

        $this->add_responsive_control(
			'wadi_posts_text_align',
			[
				'label'        => __( 'Alignment', 'wadi-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => $thePostTextAlingment,
				'toggle'       => true,
				'default'      => 'left',
				'prefix_class' => 'wadi_posts_text_content_align_',
				'selectors'    => array(
					'{{WRAPPER}} .wadi_posts_text_content_container, {{WRAPPER}} .wadi_excerpt_read_more' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wadi_posts_text_content_container .wadi_single_post_meta, {{WRAPPER}} .wadi_posts_text_content_container .wadi_single_post_meta .wadi_single_post_author' => 'justify-content: {{VALUE}};',
				),
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_single_post_featured_image_section',
            [
                'label' => esc_html__('Featured Image', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_single_post_featured_image',
            [
                'label' => esc_html__('Featured Image', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'wadi-addons' ),
                'label_off'    => __( 'No', 'wadi-addons' ),
                'default'      => 'yes',
            ]
        );

        // Link Featured Image Switch
        $this->add_control(
            'wadi_single_post_featured_image_link',
            [
                'label' => esc_html__('Link Featured Image', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__('Link the featured image to the post.', 'wadi-addons'),
                'label_on'     => __( 'Yes', 'wadi-addons' ),
                'label_off'    => __( 'No', 'wadi-addons' ),
                'default'      => 'yes',
                'condition' => [
                    'wadi_single_post_featured_image' => 'yes',
                ],
            ]
        );

        // Featured Image Direction
        $this->add_responsive_control(
            'wadi_single_post_featured_image_direction',
            [
                'label' => esc_html__('Featured Image Position', 'wadi-addons'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'top'    => array(
                        'title' => __( 'Top', 'wadi-addons' ),
                        'icon'  => 'eicon-v-align-top',
                    ),
                    'left'    => array(
                        'title' => __( 'Left', 'wadi-addons' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'bottom'    => array(
                        'title' => __( 'Bottom', 'wadi-addons' ),
                        'icon'  => 'eicon-v-align-bottom',
                    ),
                    'right' => array(
                        'title' => __( 'Right', 'wadi-addons' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ],
                'selectors_dictionary' => array(
                    'top'   => 'column',
                    'left'   => 'row',
                    'bottom'   => 'column-reverse',
                    'right'  => 'row-reverse',
                ),
                'default' => 'right',
                'condition' => [
                    'wadi_single_post_featured_image' => 'yes',
                    'wadi_posts_skins' => 'side',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap .wadi_inner_post_wrap:not(.wadi_post_no_thumbnail_image)' => 'flex-direction: {{VALUE}};',
                    '(tablet) {{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap .wadi_inner_post_wrap:not(.wadi_post_no_thumbnail_image)' => 'flex-direction: {{VALUE}};',
                    '(mobile) {{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap .wadi_inner_post_wrap:not(.wadi_post_no_thumbnail_image)' => 'flex-direction: {{VALUE}};',
                    '(mobile) {{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap .wadi_inner_post_wrap.wadi_post_no_thumbnail_image' => 'flex-direction: column!important;',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'wadi_single_post_featured_image_thumb', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `wadi_testimonial_image_size` and `wadi_testimonial_image_custom_dimension`.
				'default' => 'full',
                'condition' => [
                    'wadi_single_post_featured_image' => 'yes',
                ],
			]
		);

        $this->add_responsive_control(
            'wadi_single_post_thumb_height',
            [
                'label' => esc_html__('Image Height', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
					'size' => 350,
				],
				'selectors' => [
                    '(desktop){{WRAPPER}} .wadi_single_post_container .wadi_featured_image' => 'height: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}} .wadi_single_post_container .wadi_featured_image' => 'height: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .wadi_single_post_container .wadi_featured_image' => 'height: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_single_post_thumb_width',
            [
                'label' => esc_html__('Image Width', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
					'size' => 250,
				],
				'selectors' => [
                    '(desktop){{WRAPPER}} .wadi_single_post_container .wadi_featured_image' => 'width: {{SIZE}}{{UNIT}};',
                    '(tablet){{WRAPPER}} .wadi_single_post_container .wadi_featured_image' => 'width: {{SIZE}}{{UNIT}};',
                    '(mobile){{WRAPPER}} .wadi_single_post_container .wadi_featured_image' => 'width: {{SIZE}}{{UNIT}};',
				],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_skins' => ['side'],
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_single_post_thumb_carousel_height',
            [
                'label' => esc_html__('Image Height', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'rem','%', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
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
					'size' => 350,
				],
				'selectors' => [
					'{{WRAPPER}} .wadi_single_post_container .wadi_featured_image img#wadi_post_image_thumbnail' => 'height: {{SIZE}}{{UNIT}}; width: 100%;',
				],
                'condition' => [
                    'wadi_posts_layout' => ['carousel'],
                    'wadi_posts_skins!' => ['side'],
                ]
            ]
        );

        $this->add_control(
            'wadi_single_post_image_size_fit',
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
                    '{{WRAPPER}} .wadi_single_post_container .wadi_featured_image img' => 'object-fit: {{VALUE}};',
                ],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_single_post_featured_image' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'wadi_single_post_image_size_fit_hover',
            [
                'label' => esc_html__('Images Scale', 'wadi-addons'),
                'type' => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 3,
                        'step' => 0.01,
                    ),
                ),
                'selectors' => [
                    '{{WRAPPER}} .wadi_single_post_container .wadi_featured_image img:hover' => 'transform: scale({{SIZE}});',
                ],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_single_post_featured_image' => 'yes',
                ]
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_single_post_display_options',
            [
                'label' => esc_html__('Post Display', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'wadi_post_content_switcher',
            [
                'label' => esc_html__('Post Content', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_post_content_type',
            [
                'label' => esc_html__( 'Content Type', 'wadi-addons' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'excerpt' => esc_html__('Excerpt', 'wadi-addons'),
                    'post_content' => esc_html__("Post Content", 'wadi-addons'),
                ],
                'default' => 'excerpt',
                'condition' => [
                    'wadi_post_content_switcher' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_post_excerpt_length',
            [
                'label' => esc_html__('Excerpt Length', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 120,
                'description' => esc_html__('Excerpt length by number of letter if default is 120 letters excerpt.', 'wadi-addons'),
                'condition' => [
                    'wadi_post_content_type' => 'excerpt',
                ],
            ]
        );

        $this->add_control(
            'wadi_post_excerpt_link_type_switcher',
            [
                'label' => esc_html__('Excerpt Link Type', 'wadi-addons'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__('None', 'wadi-addons'),
                    'excerpt_dots' => esc_html__('Dots', 'wadi-addons'),
                    'excerpt_link' => esc_html__("Link", 'wadi-addons'),
                ],
                'default' => 'excerpt_link',
                'condition' => [
                    'wadi_post_content_switcher' => 'yes',
                    'wadi_post_content_type' => 'excerpt',
                ],
            ]
        );
        
        $this->add_control(
            'wadi_post_excerpt_link_text',
            [
                'label' => esc_html__('Excerpt Link Text', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Read More →', 'wadi-addons'),
                'condition' => [
                    'wadi_post_excerpt_link_type_switcher' => 'excerpt_link',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_post_meta',
            [
                'label' => esc_html__('Post Meta', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'wadi_post_author_meta',
            [
                'label' => esc_html__('Post Author', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'condition' => [
                    'wadi_posts_skins!' => 'side',
                ]
            ]
        );

        $this->add_control(
            'wadi_post_author_avatar_meta',
            [
                'label' => esc_html__('Avatar', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'condition' => [
                    'wadi_post_author_meta' => 'yes',
                    'wadi_posts_skins!' => 'side',
                ],
            ]
        );

        $this->add_control(
            'wadi_post_author_display_name_meta',
            [
                'label' => esc_html__('Author Name', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'condition' => [
                    'wadi_post_author_meta' => 'yes',
                    'wadi_posts_skins!' => 'side',
                ],
            ]
        );
        
        $this->add_control(
            'wadi_post_date_meta',
            [
                'label' => esc_html__('Post Date', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'wadi_post_comments_meta',
            [
                'label' => esc_html__('Post Comments', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'wadi_post_categories_meta',
            [
                'label' => esc_html__('Post Categories', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'wadi_post_tags_meta',
            [
                'label' => esc_html__('Post Tags', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
            ]
        );
        
        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_posts_carousel_layout_settings_section',
            [
                'label' => esc_html__('Carousel Settings', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'wadi_posts_layout' => ['carousel'],
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_posts_carousel_breakpoints',
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


        $this->add_responsive_control(
            'wadi_posts_carousel_slides_per_view',
            [
                'label' => esc_html__('Slides Per View', 'wadi-addons'),
                'description' => esc_html__('Set Posts per view on Carousel, Please note that Carousel Effects only works for Slide and Coverflow effects.', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
				'max' => 20,
				'step' => 1,
                'frontend_available' => true,
                'condition' => [
                    'wadi_posts_layout' => ['carousel'],
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_posts_carousel_slides_per_group',
            [
                'label' => esc_html__('Slides to Scroll', 'wadi-addons'),
                'description' => esc_html__('Set numbers of slides per swipe. Useful to use with Slides Per View more than 1. Please note that Carousel Effects only works for (Slide) and (Coverflow) effects.', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
				'max' => 20,
				'step' => 1,
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'wadi_posts_carousel_space_between',
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

        $this->add_control(
            'wadi_posts_carousel_speed_transition',
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


        $this->add_control(
            'wadi_posts_carousel_auto_height',
            [
                'label' => esc_html__('Posts Auto Height', 'wadi-addons'),
                'description' => esc_html__('Make the carousel change height based on the height of the current post instead of Static Height for the carousel. Currently works only on Carousel Horizontal Direction.', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('yes', 'wadi-addons'),
                'label_off' => esc_html__('no', 'wadi-addons'),
                'default' => 'no',
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'wadi_posts_carousel_loop',
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
            'wadi_posts_carousel_mousewheel',
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
            'wadi_posts_carousel_autoplay',
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
            'wadi_posts_carousel_autoplay_delay',
            [
                'label' => esc_html__('Autoplay Delay', 'wadi-addons'),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
				'max' => 20000,
				'step' => 100,
                'default' => 5000,
                'description' => esc_html__('Delay between slides on autoplay of Carousel in ms (milliseconds).', 'wadi-addons'),
                'frontend_available' => true,
                'condition' => [
                    'wadi_posts_carousel_autoplay' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_posts_carousel_pagination_heading',
            [
                'label' => esc_html__('Pagination', 'wadi-addons'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'wadi_posts_carousel_dots_navigation',
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
            'wadi_posts_carousel_pagination_type',
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
                    'wadi_posts_carousel_dots_navigation' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'wadi_posts_carousel_dots_clickable',
            [
                'label' => esc_html__( 'Navigation Dots Clickable', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'yes',
                'frontend_available' => true,
                'condition' => [
                    'wadi_posts_carousel_dots_navigation' => 'yes',
                    'wadi_posts_carousel_pagination_type' => 'bullets',
                ]
            ]
        );

        $this->add_control(
            'wadi_posts_carousel_dots_pagination_hide_on_click',
            [
                'label' => esc_html__( 'Hide Navigation Dots on Click', 'wadi-addons' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'wadi-addons' ),
                'label_off' => esc_html__( 'No', 'wadi-addons' ),
                'default' => 'no',
                'frontend_available' => true,
                'condition' => [
                    'wadi_posts_carousel_dots_navigation' => 'yes',
                    'wadi_posts_carousel_pagination_type' => 'bullets',
                ]
            ]
        );


        $this->add_control(
            'wadi_posts_carousel_arrow_navigation',
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
			'wadi_selected_posts_carousel_next_icon',
			[
				'label' => esc_html__( 'Next Icon', 'wadi-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wadi_posts_carousel_next_icon',
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
					'wadi_selected_posts_carousel_prev_icon[value]!' => '',
                    'wadi_posts_carousel_arrow_navigation' => 'yes',
				],
			]
		);

        $this->add_control(
			'wadi_selected_posts_carousel_prev_icon',
			[
				'label' => esc_html__( 'Previous Icon', 'wadi-addons' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'wadi_posts_carousel_prev_icon',
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
					'wadi_selected_posts_carousel_next_icon[value]!' => '',
                    'wadi_posts_carousel_arrow_navigation' => 'yes',
				],
			]
		);




        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_posts_filters_section',
            [
                'label' => esc_html__('Filter Tabs', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                ],
            ]
        );

        $this->add_control(
            'wadi_posts_filters_switcher',
            [
                'label' => esc_html__('Show Filters', 'wadi-addons'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'wadi-addons'),
                'label_off' => esc_html__('No', 'wadi-addons'),
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                ],
                'default' => 'no',
            ]
        );


        foreach ($post_types as $key => $type) {

            // Get all the taxanomies associated with the selected post type.
            $taxomomies_by_post_type = WadiQueries::get_post_type_taxonomies($key);

            // if (! empty($taxomomies_by_post_type)) {

                        // Add filter rule for the each taxonomy.
                $this->add_control(
                    'wadi_posts_filters_by_'.$key.'_taxonomies',
                    [
                                'label' => esc_html__('Filters By', 'wadi-addons'),
                                'type' => Controls_Manager::SELECT2,
                                'options' => $taxomomies_by_post_type,
                                // 'options' => [
                                //     'categories' => esc_html__('Categories', 'wadi-addons'),
                                //     'tags' => esc_html__('Tags', 'wadi-addons'),
                                // ],
                                // 'default' => 'category',
                                'condition' => [
                                    'wadi_posts_filters_switcher' => 'yes',
                                    'wadi_posts_query_content_type_filter' => $key,
                                    'wadi_posts_layout!' => ['carousel'],
                                ],
                            ]
                );
            // }
        }

        

        $this->add_control(
            'wadi_posts_first_tab',
            [
                'label' => esc_html__('First Tab', 'wadi-addons'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('All', 'wadi-addons'),
                'condition' => [
                    'wadi_posts_filters_switcher' => 'yes',
                    'wadi_posts_layout!' => ['carousel'],
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->end_controls_section();

        // Styling Options

        $this->start_controls_section(
            'wadi_posts_filter_styling_section',
            [
                'label' => esc_html__('Filter', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_filter_content_typogrpahy',
				'selector' => '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item',
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ],
			]
		);

        $start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';

        
        $wadi_posts_filter_alignment_options =  [
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

        $wadi_posts_filter_alignment_options_rtl = array_reverse($wadi_posts_filter_alignment_options);

        $the_wadi_posts_filter_alignment_options = is_rtl() ? $wadi_posts_filter_alignment_options_rtl :$wadi_posts_filter_alignment_options;


        
        $this->add_responsive_control(
			'wadi_posts_filter_alignments',
			[
				'label' => esc_html__( 'Alignment', 'wadi-addons' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => $the_wadi_posts_filter_alignment_options,
                'selectors_dictionary' => array(
                    'left'   => 'flex-start',
                    'center' => 'center',
                    'right'  => 'flex-end',
                ),
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .wadi_posts_filters_container' => 'justify-content: {{VALUE}};',
				],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ]
			]
		);

        $this->start_controls_tabs(
            'wadi_posts_style_tabs'
        );
        
        $this->start_controls_tab(
            'wadi_posts_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
            ]
        ); 
            $this->add_control(
                'wadi_posts_filter_text_color',
                [
                    'label' => esc_html__( 'Text Color', 'wadi-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ]
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'wadi_posts_filter_background',
                    'label' => esc_html__( 'Background', 'wadi-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter:not(.filter_active)',
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ]
                ]
            );



            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'wadi_posts_normal_border',
                    'label' => esc_html__( 'Border', 'wadi-addons' ),
                    'selector' => '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter:not(.filter_active)',
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ]
                ]
            );

            $this->add_responsive_control(
                'wadi_posts_filter_border_radius',
                [
                     'label' => esc_attr__('Border Radius', 'wadi-addons'),
                     'type' => Controls_Manager::DIMENSIONS,
                     'size_units' => ['px','rem' ,'em', '%'],
                     'selectors' => [
                         '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                     ],
                     'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ]
                 ]
            );
    

        $this->end_controls_tab();

        
        $this->start_controls_tab(
            'wadi_posts_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
            ]
        );
        
            $this->add_control(
                'wadi_posts_filter_text_color_hover',
                [
                    'label' => esc_html__( 'Text Color', 'wadi-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter:hover' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ]
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'wadi_posts_filter_background_hover',
                    'label' => esc_html__( 'Background', 'wadi-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter:hover',
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ],
                ]
            );


            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'wadi_posts_hover_border',
                    'label' => esc_html__( 'Border', 'wadi-addons' ),
                    'selector' => '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter:hover',
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ],
                ]
            );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'wadi_posts_style_active_tab',
            [
                'label' => esc_html__( 'Active', 'wadi-addons' ),
            ]
        );

            $this->add_control(
                'wadi_posts_filter_text_color_active',
                [
                    'label' => esc_html__( 'Text Color', 'wadi-addons' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter.filter_active' => 'color: {{VALUE}}',
                    ],
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ]
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Background::get_type(),
                [
                    'name' => 'wadi_posts_filter_background_active',
                    'label' => esc_html__( 'Background', 'wadi-addons' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter.filter_active',
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ]
                ]
            );



            $this->add_group_control(
                \Elementor\Group_Control_Border::get_type(),
                [
                    'name' => 'wadi_posts_active_border',
                    'label' => esc_html__( 'Border', 'wadi-addons' ),
                    'selector' => '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter.filter_active',
                    'condition' => [
                        'wadi_posts_layout!' => ['carousel'],
                        'wadi_posts_filters_switcher' => 'yes',
                    ]
                ]
            );


        $this->end_controls_tab();

        
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'wadi_posts_filter_padding',
            [
                'label' => esc_html__( 'Filter Padding', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item a.data_filter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'wadi_posts_filter_items_spacing',
            [
                'label' => esc_html__( 'Filter Items Spacing', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_posts_filters_container .wadi_posts_filter_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ],
            ]
        );

        // Wadi Posts Filter Container
        $this->add_control(
            'wadi_posts_filter_container_heading',
            [
                'label' => esc_html__( 'Filter Container', 'wadi-addons' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ],
            ]
        );

        // Wadi Posts Filter Container Background Color
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'wadi_posts_filter_container_background',
                'label' => esc_html__( 'Background', 'wadi-addons' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .wadi_posts_content_wrapper .wadi_tax_filters',
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_posts_filter_container_padding',
            [
                'label' => esc_html__( 'Padding', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_posts_content_wrapper .wadi_tax_filters' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'wadi_posts_filter_container_margin',
            [
                'label' => esc_html__( 'Margin', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_posts_content_wrapper .wadi_tax_filters' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'wadi_posts_layout!' => ['carousel'],
                    'wadi_posts_filters_switcher' => 'yes',
                ],
            ]
        );


        $this->end_controls_section();

                
        $this->start_controls_section(
            'wadi_posts_blog_post_styling_section',
            [
                'label' => esc_html__('Blog Post', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_post_blog_background',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap, {{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap .wadi_inner_post_wrap',
			]
		);

        $this->add_responsive_control(
            'wadi_posts_blog_padding',
            [
                 'label' => esc_attr__('Post Padding', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','rem' ,'em', '%'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_responsive_control(
            'wadi_posts_text_box_blog_padding',
            [
                 'label' => esc_attr__('Text Box Padding', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','rem' ,'em', '%'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_posts_text_content_container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );


        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap',
			]
		);

        $this->add_responsive_control(
            'wadi_posts_blog_border_radius',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','rem' ,'em', '%'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap, {{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap .wadi_inner_post_wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'wadi_posts_blog_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_single_post_container .wadi_single_bg_wrap',
			]
		);

        
        
        $this->end_controls_section();
        
        $wadi_pagination_styling_conditions =  [
            'relation' => 'or',
            'terms' => [
                [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'wadi_posts_layout',
                            'operator' => '!=',
                            'value' => 'carousel'
                        ],
                        [
                            'name' => 'wadi_posts_enable_pagination',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'wadi_posts_pagination_select',
                            'operator' => '==',
                            'value' => 'infinite'
                        ],
                        [
                            'name' => 'wadi_posts_pagination_infinite_select',
                            'operator' => '==',
                            'value' => 'wadi_infinite_button'
                        ],
                    ]
                ],
                [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'wadi_posts_layout',
                            'operator' => '!=',
                            'value' => 'carousel'
                        ],
                        [
                            'name' => 'wadi_posts_enable_pagination',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'wadi_posts_pagination_select',
                            'operator' => '==',
                            'value' => 'numbers'
                        ],
                    ]
                ]
            ]
        ];

        $wadi_load_more_width =  [
            'terms' => [
                [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'wadi_posts_layout',
                            'operator' => '!=',
                            'value' => 'carousel'
                        ],
                        [
                            'name' => 'wadi_posts_enable_pagination',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'wadi_posts_pagination_select',
                            'operator' => '==',
                            'value' => 'infinite'
                        ],
                        [
                            'name' => 'wadi_posts_pagination_infinite_select',
                            'operator' => '==',
                            'value' => 'wadi_infinite_button'
                        ],
                    ]
                ],
            ]
        ];
        $this->start_controls_section(
            'wadi_posts_pagination_styling_section',
            [
                'label' => esc_html__('Pagination', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
                'conditions' => $wadi_pagination_styling_conditions
            ]
        );

        $this->add_responsive_control(
			'wadi_posts_pagination_numbers_align',
			array(
				'label'        => __( 'Alignment', 'wadi-addons' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => array(
					'left'    => array(
						'title' => __( 'Left', 'wadi-addons' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center'  => array(
						'title' => __( 'Center', 'wadi-addons' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'   => array(
						'title' => __( 'Right', 'wadi-addons' ),
						'icon'  => 'eicon-text-align-right',
					)
				),
				'toggle'       => false,
				'default'      => 'left',
				'prefix_class' => 'wadi_posts_pagination_numbers_align_',
				'selectors'    => array(
					'{{WRAPPER}} .wadi_posts_pagination_container' => 'text-align: {{VALUE}};',
				),
                'conditions' => $wadi_pagination_styling_conditions
			)
		);

        // Load more Button Width

        $this->add_responsive_control(
            'wadi_posts_pagination_load_more_width',
            [
                 'label' => esc_attr__('Width', 'wadi-addons'),
                 'type' => Controls_Manager::SLIDER,
                 'size_units' => ['px','rem' ,'%', 'em'],
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
                    '%' => [
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
                    'size' => '100',
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_posts_pagination_container a.wadi_loadmore_infinite' => 'width: {{SIZE}}{{UNIT}}; display: inline-block;',
                ],
                'conditions' => $wadi_load_more_width,

            ]
        );

        $this->start_controls_tabs(
            'wadi_posts_pagination_style_tabs',
            [
                'conditions' => $wadi_pagination_styling_conditions
            ]
        );
        
        $this->start_controls_tab(
            'wadi_posts_pagination_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
                'conditions' => $wadi_pagination_styling_conditions

            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_pagination_background_color',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_posts_pagination_container .page-numbers,{{WRAPPER}} .wadi_loadmore_infinite',
                'conditions' => $wadi_pagination_styling_conditions
			]
		);

        $this->add_control(
			'wadi_pagination_text_color',
			[
				'label' => esc_html__( 'Text Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_posts_pagination_container .page-numbers,{{WRAPPER}} .wadi_loadmore_infinite' => 'color: {{VALUE}}',
				],
                'conditions' => $wadi_pagination_styling_conditions
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_posts_pagination_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_posts_pagination_container .page-numbers,{{WRAPPER}} .wadi_loadmore_infinite',
                'conditions' => $wadi_pagination_styling_conditions
			]
		);
        
        $this->end_controls_tab();
        $this->start_controls_tab(
            'wadi_posts_pagination_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
                'conditions' => $wadi_pagination_styling_conditions
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_pagination_hover_background_color',
				'label' => esc_html__( 'Hover Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_posts_pagination_container .page-numbers:hover,{{WRAPPER}} .wadi_loadmore_infinite:hover',
                'conditions' => $wadi_pagination_styling_conditions
			]
		);

        $this->add_control(
			'wadi_pagination_hover_text_color',
			[
				'label' => esc_html__( 'Hover Text Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_posts_pagination_container .page-numbers:hover,{{WRAPPER}} .wadi_loadmore_infinite:hover' => 'color: {{VALUE}}',
				],
                'conditions' => $wadi_pagination_styling_conditions
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_posts_pagination_border_hover',
				'label' => esc_html__( 'Border Hover', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_posts_pagination_container .page-numbers:hover,{{WRAPPER}} .wadi_loadmore_infinite:hover',
                'conditions' => $wadi_pagination_styling_conditions
			]
		);
        
        $this->end_controls_tab();
        $this->start_controls_tab(
            'wadi_posts_pagination_style_active_tab',
            [
                'label' => esc_html__( 'Active', 'wadi-addons' ),
                'conditions' => $wadi_pagination_styling_conditions
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_pagination_active_background_color',
				'label' => esc_html__( 'Active Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_posts_pagination_container .page-numbers.current',
                'conditions' => $wadi_pagination_styling_conditions
			]
		);

        $this->add_control(
			'wadi_pagination_active_text_color',
			[
				'label' => esc_html__( 'Active Text Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_posts_pagination_container .page-numbers.current' => 'color: {{VALUE}}',
				],
                'conditions' => $wadi_pagination_styling_conditions

			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_posts_pagination_border_active',
				'label' => esc_html__( 'Border Active', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_posts_pagination_container .page-numbers.current',
                'conditions' => $wadi_pagination_styling_conditions
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_control(
			'wadi_posts_pagination_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_posts_pagination_container .page-numbers,{{WRAPPER}} .wadi_loadmore_infinite' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'separator' => 'before',
                'conditions' => $wadi_pagination_styling_conditions
			]
		);
        $this->add_responsive_control(
			'wadi_posts_pagination_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_posts_pagination_container .page-numbers,{{WRAPPER}} .wadi_loadmore_infinite' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'conditions' => $wadi_pagination_styling_conditions
			]
		);
        $this->add_responsive_control(
			'wadi_posts_pagination_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_posts_pagination_container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'conditions' => $wadi_pagination_styling_conditions
			]
		);
        $this->add_control(
			'wadi_posts_pagination_spacing',
			[
				'label' => esc_html__( 'Pagination Spacing', 'wadi-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_posts_pagination_container .page-numbers' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wadi_posts_pagination_container .page-numbers' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
                'conditions' => $wadi_pagination_styling_conditions

			]
		);


        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_posts_title_style',
            [
                'label' => esc_html__('Title', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]

        );


        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_posts_title_typograhpy',
				'selector' => '{{WRAPPER}} .wadi_post_title',
			]
		);


        $this->add_control(
			'wadi_posts_title_color',
			[
				'label' => esc_html__( 'Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_post_title' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'wadi_posts_title_hover_color',
			[
				'label' => esc_html__( 'Hover Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_post_title:hover' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_responsive_control(
			'wadi_posts_title_margin',
			[
				'label' => esc_html__( 'Margin', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_post_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
		);
        $this->add_responsive_control(
			'wadi_posts_title_padding',
			[
				'label' => esc_html__( 'Padding', 'wadi-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wadi_post_title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
            ]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_posts_meta_styling_section',
            [
                'label' => esc_html__('Meta', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_posts_meta_typograhpy',
				'selector' => '{{WRAPPER}} .wadi_single_post_meta > *, {{WRAPPER}} .wadi_single_post_meta > * > a',
			]
		);

        $this->add_control(
            'wadi_post_meta_color',
            [
                'label' => esc_html__( 'Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_single_post_meta > *, {{WRAPPER}} .wadi_single_post_meta > * > a' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_control(
            'wadi_post_meta_hover_color',
            [
                'label' => esc_html__( 'Links Hover Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_single_post_meta > * > a:hover' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_responsive_control(
			'wadi_post_meta_bottom_spacing',
			[
				'label' => esc_html__( 'Bottom Spacing', 'wadi-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
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
					'{{WRAPPER}} .wadi_single_post_meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'wadi_post_meta_inner_spacing',
			array(
				'label'     => __( 'Inner Meta Spacing', 'wadi-addons' ),                
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
				'range'     => array(
					'px' => array(
						'max' => 100,
					),
				),
				'default'   => array(
					'size' => 10,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .wadi_single_post_meta > div' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wadi_single_post_meta > div:last-child' => 'margin-right: 0;',
				),
			)
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_psots_excerpt_styling_section',
            [
                'label' => esc_html__('Excerpt', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_posts_excerpt_typography',
				'selector' => '{{WRAPPER}} .wadi_post_excerpt',
			]
		);

        $this->add_control(
            'wadi_posts_excerpt_color',
            [
                'label' => esc_html__( 'Color', 'wadi-addons' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_post_excerpt' => 'color: {{VALUE}}',
				],
            ]
        );

        $this->add_responsive_control(
			'wadi_posts_excerpt_bottom_spacing',
			[
				'label' => esc_html__( 'Bottom Spacing', 'wadi-addons' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'rem' ,'%', 'em' ],
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
					'{{WRAPPER}} .wadi_post_excerpt' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        $this->start_controls_section(
            'wadi_posts_cta_style_section',
            [
                'label' => esc_html__('Call To Action', 'wadi-addons'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        
        $postCTATextAlingment = [
            'left'    => [
                'title' => __( 'Left', 'wadi-addons' ),
                'icon'  => 'eicon-text-align-left',
            ],
            'center'  => [
                'title' => __( 'Center', 'wadi-addons' ),
                'icon'  => 'eicon-text-align-center',
            ],
            'right'   => [
                'title' => __( 'Right', 'wadi-addons' ),
                'icon'  => 'eicon-text-align-right',
            ],
        ];

        $rtl_postCTATextAlingment = array_reverse($postCTATextAlingment);

        $thePostCTATextAlingment = !is_rtl() ? $postCTATextAlingment : $rtl_postCTATextAlingment;


        $this->add_responsive_control(
			'wadi_posts_cta_text_alignments',
			[
				'label' => esc_html__( 'Alignment', 'wadi-addons' ),
				'type' => Controls_Manager::CHOOSE,
                'toggle' => true,
				'options' => $thePostCTATextAlingment,
				'selectors' => [
					'{{WRAPPER}} .wadi_inner_post_wrap .wadi_excerpt_read_more' => 'text-align: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'wadi_post_cta_text_typography',
				'selector' => '{{WRAPPER}} .wadi_excerpt_read_more',
			]
		);

        $this->start_controls_tabs(
            'wadi_posts_cta_style_tabs'
        );
        
        $this->start_controls_tab(
            'wadi_post_cta_style_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'wadi-addons' ),
            ]
        );
        
        
        $this->add_control(
            'wadi_post_cta_text_color',
            [
				'label' => esc_html__( 'Text Color', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_excerpt_read_more' => 'color: {{VALUE}}',
				],
			]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_post_cta_bg_color',
				'label' => esc_html__( 'Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_excerpt_read_more',
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_post_cta_border',
				'label' => esc_html__( 'Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_excerpt_read_more',
			]
		);


        $this->end_controls_tab();
        
        $this->start_controls_tab(
            'wadi_post_cta_style_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'wadi-addons' ),
            ]
        );

        $this->add_control(
            'wadi_post_cta_text_color_hover',
            [
				'label' => esc_html__( 'Text Hover Color', 'wadi-addons' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wadi_excerpt_read_more:hover' => 'color: {{VALUE}}',
				],
			]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wadi_post_cta_bg_hover_color',
				'label' => esc_html__( 'Hover Background', 'wadi-addons' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wadi_excerpt_read_more:hover',
			]
		);


        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'wadi_post_cta_hover_border',
				'label' => esc_html__( 'Hover Border', 'wadi-addons' ),
				'selector' => '{{WRAPPER}} .wadi_excerpt_read_more:hover',
			]
		);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();

        $this->add_responsive_control(
            'wadi_post_cta_border_radius',
            [
                 'label' => esc_attr__('Border Radius', 'wadi-addons'),
                 'type' => Controls_Manager::DIMENSIONS,
                 'size_units' => ['px','rem' ,'em', '%'],
                 'selectors' => [
                     '{{WRAPPER}} .wadi_excerpt_read_more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_responsive_control(
            'wadi_post_cta_width',
            [
                 'label' => esc_attr__('Width', 'wadi-addons'),
                 'type' => Controls_Manager::SLIDER,
                 'size_units' => ['px','rem' ,'em', '%'],
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
                     '{{WRAPPER}} .wadi_excerpt_read_more' => 'width: {{SIZE}}{{UNIT}};',
                 ],
             ]
        );

        $this->add_responsive_control(
            'wadi_post_cta_padding',
            [
                'label' => esc_html__( 'Padding', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_excerpt_read_more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'wadi_post_cta_margin',
            [
                'label' => esc_html__( 'Margin', 'wadi-addons' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'rem','%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} .wadi_excerpt_read_more' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();




        }

    protected function render_posts_with_wrapper() {
        return $this->getTemplateInstance()->wadi_posts_wrapper();
    }
    protected function render_posts_query() {
        return $this->getTemplateInstance()->wadi_get_query();
    }
    protected function render_not_found_section() {
        return $this->getTemplateInstance()->render_not_found_section_content();
    }

    protected function render_posts() {
        $this->getTemplateInstance()->wadi_render_posts();
    }
    protected function render_posts_terms_filer() {
        $this->getTemplateInstance()->wadi_render_posts_terms_filter();
    }

    protected function render_posts_pagination() {
        return $this->getTemplateInstance()->inner_pagination_render();
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $layoutModeSettings = array("layoutMode"=> $settings['wadi_posts_layout']);

		$settings['widget_id'] = $this->get_id();


        WadiQueries::wadi_set_settings($settings);

        // $this->render_posts_with_wrapper();
        
        $query = $this->render_posts_query();

        

        if ( ! $query->have_posts() ) {

            $this->render_not_found_section();
			return;
		}


        $posts_data_settings = [
            'columns' => $settings['wadi_posts_columns'],
            'columns_columns' => $settings['wadi_posts_columns_tablet'],
            'columns_mobile' => $settings['wadi_posts_columns_mobile'],
            'posts_skin' => $settings['wadi_posts_skins'],
            'posts_layout' =>  'side' !== $settings['wadi_posts_skins'] ? $settings['wadi_posts_layout'] : 'grid',
        ];
        

        // Add page ID to be used later to get posts by AJAX.   
		$page_id = '';
		if ( null !== Plugin::$instance->documents->get_current() ) {
			$page_id = Plugin::$instance->documents->get_current()->get_main_id();
		}

        $wadi_post_swiper_wrapper =  'carousel' === $settings['wadi_posts_layout'] ? 'swiper-wrapper' : '';

		$this->add_render_attribute( 'wadi_posts_container', [
            'class' => [
                $wadi_post_swiper_wrapper,
                'carousel' !== $settings['wadi_posts_layout'] ? 'wadi_posts_container' : '',
                'carousel' === $settings['wadi_posts_layout'] ? 'wadi_carousel_posts_container': '',
                'wadi_posts_container_' . esc_attr( $settings['widget_id']  ),
                'carousel' !== $settings['wadi_posts_layout'] ? 'wadi_posts_columns_' . esc_attr($settings['wadi_posts_columns']) : '',
                'carousel' !== $settings['wadi_posts_layout'] ? 'wadi_posts_columns_tablet_' . esc_attr($settings['wadi_posts_columns_tablet']) : '',
                'carousel' !== $settings['wadi_posts_layout'] ? 'wadi_posts_columns_mobile_' . esc_attr($settings['wadi_posts_columns_mobile']) : '',
                'wadi_posts_skin_' .  esc_attr($settings['wadi_posts_skins']),
                'side' !== $settings['wadi_posts_skins'] ? 'wadi_posts_layout_' .  esc_attr($settings['wadi_posts_layout']) : 'wadi_posts_layout_grid',
                ],
                'data-page' => $page_id,
                'data-settings' => wp_json_encode($posts_data_settings),
                'data-id' => esc_attr( $settings['widget_id']  )
            ]
        );

        $wadi_post_swiper =  'carousel' === $settings['wadi_posts_layout'] ? 'swiper' : '';

		$this->add_render_attribute( 'wadi_posts_content_wrapper', [
            'class' => [
                $wadi_post_swiper,
                'carousel' !== $settings['wadi_posts_layout'] ? 'wadi_posts_content_wrapper' : '',
                'carousel' === $settings['wadi_posts_layout'] ? 'wadi_carousel_posts_content_wrapper': '',
                'wadi_posts_content_wrapper_' . esc_attr( $settings['widget_id']  ),
                'carousel' !== $settings['wadi_posts_layout'] ? 'wadi_posts_columns_' . esc_attr($settings['wadi_posts_columns']) : '',
                'carousel' !== $settings['wadi_posts_layout'] ? 'wadi_posts_columns_tablet_' . esc_attr($settings['wadi_posts_columns_tablet']) : '',
                'carousel' !== $settings['wadi_posts_layout'] ? 'wadi_posts_columns_mobile_' . esc_attr($settings['wadi_posts_columns_mobile']) : '',
                'wadi_posts_skin_' .  esc_attr($settings['wadi_posts_skins']),
                'side' !== $settings['wadi_posts_skins'] ? 'wadi_posts_layout_' .  esc_attr($settings['wadi_posts_layout']) : 'wadi_posts_layout_grid',
                ],
                'data-page' => $page_id,
                'data-settings' => wp_json_encode($posts_data_settings),
                'data-id' => esc_attr( $settings['widget_id']  )
            ]
        );
        $paged = WadiQueries::get_wadi_paged();

        $current_page = $paged;
		if ( ! $current_page ) {
			$current_page = 1;
		}

        $next_page = intval($current_page) + 1;

        $this->add_render_attribute( 'wadi_pagination', [
            'class' => 'wadi_posts_pagination_container',
            'data-current' => $current_page,
            'data-next' => $next_page,
            // 'data-max-pages' => self::$total_num,
        ] );



        // Horizontal

        $migrated_next = isset( $settings['__fa4_migrated']['wadi_selected_posts_carousel_next_icon'] );
        $is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
        
        $has_icon_next = ( ! $is_new || ! empty( $settings['wadi_posts_carousel_next_icon']['value'] ) );
        
        $migrated_prev = isset( $settings['__fa4_migrated']['wadi_selected_posts_carousel_prev_icon'] );
        $is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
        
        $has_icon_prev = ( ! $is_new || ! empty( $settings['wadi_selected_posts_carousel_prev_icon']['value'] ) );
                


        // echo '<pre>';
        // print_r($settings);
        // echo '</pre>';
        

        ?>
        <div <?php $this->print_render_attribute_string('wadi_posts_content_wrapper'); ?>>
            <?php 
            if( 'carousel' !==  $settings['wadi_posts_layout']|| 'yes' !== $settings['wadi_posts_filters_switcher'] ) { ?>
                    <?php
                        $this->render_posts_terms_filer();
                    ?>
            <?php
                }
            ?>
            <div <?php $this->print_render_attribute_string('wadi_posts_container'); ?> >
                <?php 
                    $this->render_posts();
                ?>
            </div>
                <?php echo $this->render_posts_pagination(); ?>
                <?php
                if( $settings['wadi_posts_pagination_infinite_select'] === 'wadi_infinite_scroll' ) { ?>
                <div class='wadi_infinite_loader'>Loading...</div>
                <?php
                }

                if ('carousel' ===  $settings['wadi_posts_layout']) {

                    /**
                     * Posts Dots Navigation
                     */
                    ?>
                    <?php if($settings['wadi_posts_carousel_dots_navigation'] === 'yes') : ?>
                        <div class="swiper-pagination"></div>
                    <?php endif; ?>

                    <?php
                        /**
                         * 
                         * Wadi Carousel Posts Arrows Navigation
                         * 
                         */

                        if($settings['wadi_posts_carousel_arrow_navigation'] === 'yes') : ?>

                            <div class="wadi-swiper-button wadi-posts_carousel_swiper-button-prev">
                            <?php
                                if ( $is_new || $migrated_prev ) { ?>
                                    <?php Icons_Manager::render_icon( $settings['wadi_selected_posts_carousel_prev_icon'] ); ?>
                                <?php } else { ?>
                                    <i class="wadi_posts_carousel_icon_prev <?php echo esc_attr( $settings['wadi_posts_carousel_prev_icon'] ); ?>"></i>
                                <?php } ?>
                            </div>
                            <div class="wadi-swiper-button wadi-posts_carousel_swiper-button-next">
                            <?php
                            if ( $is_new || $migrated_next ) { ?>
                                <?php Icons_Manager::render_icon( $settings['wadi_selected_posts_carousel_next_icon'] ); ?>
                            <?php } else { ?>
                                <i class="wadi_posts_carousel_icon_next <?php echo esc_attr( $settings['wadi_posts_carousel_next_icon'] ); ?>"></i>
                            <?php } ?>
                            </div>
                            <?php

                        endif;
                    ?>
                
                    <!-- If we need scrollbar -->
                    <!-- <div class="swiper-scrollbar"></div> -->
                <?php
                }
                ?>
        </div>
        <?php
        

        if ( \Elementor\Plugin::instance()->editor->is_edit_mode() ) {

                $this->render_editor_script();
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

				$( '.wadi_posts_container' ).each( function(index, item) {

					const $node_id 	= '<?php echo esc_attr( $this->get_id() ); ?>';
					const scope 	= $( '[data-id="' + $node_id + '"]' );
					const settings  = $(this).parent().data("settings");
					const selector 	= $(this);


					if ( selector.closest( scope ).length < 1 ) {
						return;
					}

                    if(settings.posts_layout === 'masonry' || settings.posts_layout === 'fitRows') {
                        var masonryArgs = {
                            // set itemSelector so .grid-sizer is not used in layout
                            filter 			: localStorage.getItem('wadiPostActiveFilter'),
                            itemSelector	: '.wadi_single_post_container',
                            percentPosition : true,
                            layoutMode		: settings.posts_layout,
                        };

                        var $isotopeObj = {};
                        $isotopeObj = selector.isotope( masonryArgs );
                        $isotopeObj.isotope( 'reloadItems' );

                        selector.imagesLoaded( function() {
                            $isotopeObj = selector.isotope( masonryArgs );
                            selector.isotope( 'reloadItems' );
                            selector.find('.wadi_gallery_grid_item').resize( function() {
                                $isotopeObj.isotope( 'layout' );
                            });
                        });
                    }

				});
                
			});
		</script>
		<?php
	}
}