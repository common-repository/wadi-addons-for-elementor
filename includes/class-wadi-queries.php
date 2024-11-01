<?php

namespace WadiAddons\Includes;

use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Icons_Manager;
use WP_Query;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WadiQueries
{
    /**
     * Class instance
     *
     * @var instance
     */
    protected static $instance;

    /**
     * $options is option field of select
     *
     * @since 1.0.0
     * @var integer $page_limit
     */
    protected $options;


    /**
     * $options is option field of select
     *
     * @since 1.0.0
     * @var integer $page_limit
     */
    protected static $page_limit;

    /**
     *
     * $settings is settings for queries
     *
     * @since 1.0.0
     *
     */

    protected static $settings;

    /**
     *
     * $page_num is settings for queries
     *
     * @since 1.0.0
     *
     */

    protected static $page_num;

    /**
     *
     * $total_num is settings for queries
     *
     * @since 1.0.0
     *
     */

    protected static $total_num;

    /**
     *
     * $total_posts is settings for queries
     *
     * @since 1.0.0
     *
     */

    protected static $total_posts;

    /**
	 * Filter
	 *
	 * @since 1.0.0
	 * @var object $filter
	 */
	public static $filter = '';
    
    
    /**
     * Class contructor
     * 
     * @since 1.0.0
     */
    public function __construct() {
        add_action( 'pre_get_posts', [ $this, 'fix_query_offset' ], 1 );
        add_filter( 'found_posts', [ $this, 'fix_query_found_posts' ], 1, 2 );

        add_action( 'wp_ajax_wadi_get_posts_query', array( $this, 'wadi_get_posts_query' ) );
        add_action( 'wp_ajax_nopriv_wadi_get_posts_query', array( $this, 'wadi_get_posts_query' ) );

        add_action( 'wp_ajax_wadi_get_taxonomies', [ $this, 'wadi_get_taxonomies' ] );
    }

    /**
     * Get instance of this class
     */
    public static function getInstance()
    {
        if (! static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * wadi_get_taxonomies function
     * 
     * Get list of taxonomies
     *
     * @return void
     */
    public function wadi_get_taxonomies() {
        check_ajax_referer( 'wadi-nonce', 'nonce' );

        $post_type = isset( $_POST['post_type'] ) ? sanitize_text_field( wp_unslash( $_POST['post_type'] ) ) : '';

        if ( empty( $post_type ) ) {
			wp_send_json_error( __( 'Empty Post Type.', 'wadi-addons' ) );
		}
        $taxonomy = self::get_taxnomies( $post_type );

		$related_tax = array();

		if ( ! empty( $taxonomy ) ) {

			foreach ( $taxonomy as $index => $tax ) {
				$related_tax[ $index ] = $tax->label;
			}
		}

		wp_send_json_success( wp_json_encode( $related_tax ) );
    }


    /**
     * Get All Posts
     *
     * Returns an array of posts/pages
     *
     * @since 1.0.0
     * @access public
     *
     * @return $options array posts/pages query
     */
    public function get_all_posts()
    {
        $all_posts = get_posts(
            array(
                'posts_per_page'         => -1,
                'post_type'              => array( 'page', 'post' ),
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'fields'                 => array( 'ids' ),
            )
        );

        if (! empty($all_posts) && ! is_wp_error($all_posts)) {
            foreach ($all_posts as $post) {
                $this->options[ $post->ID ] = strlen($post->post_title) > 20 ? substr($post->post_title, 0, 20) . '...' : $post->post_title;
            }
        }
        return $this->options;
    }

    /**
     * Get All Posts (including Elementor Templates)
     *
     * Returns an array of posts/pages
     *
     * @since 1.0.0
     * @access public
     *
     * @return $options array posts/pages query
     */
    public static function get_all_posts_only()
    {

        $self = new self();
        $all_posts = get_posts(
            array(
                'posts_per_page'         => -1,
                'post_type'              => array( 'post' ),
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'fields'                 => array( 'ids' ),
            )
        );

        if (! empty($all_posts) && ! is_wp_error($all_posts)) {
            foreach ($all_posts as $post) {
                $self->options[ $post->ID ] = strlen($post->post_title) > 20 ? substr($post->post_title, 0, 20) . '...' : $post->post_title;
            }
        }
        return $self->options;
    }

    /**
     * Get Elementor Templates
     *
     * Returns an array of Elementor Templates
     *
     * @since 1.0.0
     * @access public
     *
     * @return $options array Elementor Templates
     */
    public function get_elementor_templates()
    {
        $templatesList = get_posts(
            array(
                'post_type' => 'elementor_library',
                'numberposts' => -1,
            )
        );

        if (! empty($templatesList) && ! is_wp_error($templatesList)) {
            foreach ($templatesList as $post) {
                $options[ $post->post_title ] = $post->post_title;
            }

            update_option('templates_count', $options);

            return $options;
        }
    }

    /**
     * Get ID By Title
     *
     * Get Elementor Template ID by title
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $title template title.
     *
     * @return string $template_id template ID.
     */
    public function get_id_by_title($title)
    {
        $template = get_page_by_title($title, OBJECT, 'elementor_library');

        $template_id = isset($template->ID) ? $template->ID : $title;

        return $template_id;
    }

    /**
     * Get Elementor Template HTML Content
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $title Template Title.
     *
     * @return $template_content string HTML Markup of the selected template.
     */
    public function get_elementor_template_content($title)
    {
        $frontend = Plugin::$instance->frontend;

        $id = $this->get_id_by_title($title);

        $id = apply_filters('wpml_object_id', $id, 'elementor_library', true);

        $template_content = $frontend->get_builder_content_for_display($id, true);

        return $template_content;
    }

    /**
     * Get Elementor Templates List
     *
     * @since 1.0.0
     *
     * @access public
     *
     */

    public function elementor_templates_list()
    {
        $args  = [
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
        ];
        $query = new \WP_Query($args);

        $posts = $query->posts;
        foreach ($posts as $post) {
            $items[$post->ID] = $post->post_title;
        }

        if (empty($items)) {
            $items = [];
        }

        return $items;
    }
    /**
     * Get Elementor Templates List
     *
     * @since 1.0.0
     *
     * @access public
     *
     */
    
    public function elementor_templates_list_title()
    {
        $args  = [
            'post_type'      => 'elementor_library',
            'posts_per_page' => -1,
        ];
        $query =  new \WP_Query($args);
    
        $posts = $query->posts;
        foreach ($posts as $post) {
            $items[$post->post_title] = $post->post_title;
        }
    
        if (empty($items)) {
            $items = [];
        }

        update_option('temp_count', $items);

    
        return $items;
    }

    /**
     * 
     * Get Wadi Paged
     * 
	 * Returns the paged number for the query.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public static function get_wadi_paged() {

		global $wp_the_query, $paged;
        if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'wadi-nonce')) {
            if (isset($_POST['page_number']) && '' !== $_POST['page_number']) {
                return sanitize_text_field(wp_unslash($_POST['page_number']));
            }
        }

		// Check the 'paged' query var.
		$paged_qv = $wp_the_query->get( 'paged' );

		if ( is_numeric( $paged_qv ) ) {
			return $paged_qv;
		}

		// Check the 'page' query var.
		$page_qv = $wp_the_query->get( 'page' );

		if ( is_numeric( $page_qv ) ) {
			return $page_qv;
		}

		// Check the $paged global?
		if ( is_numeric( $paged ) ) {
			return $paged;
		}

		return 0;
	}


    /**
     * Get Posts Query
     * 
     * @since 1.0.7
     * 
     * @access public
     * 
     */

     public function wadi_get_query() {
        // The Query
        $self = new self();

        $settings = self::$settings;
        $paged     = self::get_wadi_paged();

        $post_type = ( isset( $settings['wadi_posts_query_content_type_filter'] ) && '' !== $settings['wadi_posts_query_content_type_filter'] ) ? $settings['wadi_posts_query_content_type_filter'] : 'post';

        $args = [
            'post_type' => $post_type,
            'posts_per_page'   => empty( $settings['wadi_posts_per_page'] ) ? 9999 : $settings['wadi_posts_per_page'],
            'paged'            => $paged,
            'post_status'      => 'publish',
            'suppress_filters' => false,
        ];

        // get Posts by Author List
        if ( ! empty( $settings['wadi_get_list_users'] ) ) {

            $args[ $settings['wadi_content_filter_by_author_rule'] ] = $settings['wadi_get_list_users'];
        }

        // Get all the taxanomies associated with the post type.
        $taxonomy = self::get_taxnomies($post_type);

        if (! empty($taxonomy) && ! is_wp_error($taxonomy)) {

            // Get all taxonomy values under the taxonomy.

            $tax_count = 0;
            foreach ($taxonomy as $index => $tax) {
                if (! empty($settings[ 'tax_' . $index . '_' . $post_type . '_filter' ])) {
                    $operator = $settings[ $index . '_' . $post_type . '_filter_rule' ];

                    $args['tax_query'][] = array(
                        'taxonomy' => $index,
                        'field'    => 'slug',
                        'terms'    => $settings[ 'tax_' . $index . '_' . $post_type . '_filter' ],
                        'operator' => $operator,
                    );
                    $tax_count++;
                }
            }
        }

        $posts_by_post_type = self::get_posts_by_post_types($post_type);


        if (! empty($posts_by_post_type) && ! is_wp_error($posts_by_post_type)) {

                if (! empty($settings[ $post_type . '_filter_post_type' ])) {

                    $args[ $settings['wadi_posts_filter_rule'] ]  = $settings[$post_type . '_filter_post_type' ];
                }
        }

        if ( ! empty( $settings['wadi_posts_select_filter'] ) ) {
            $args[ $settings['wadi_posts_filter_rule'] ] = $settings['wadi_posts_select_filter'];
        }
        if ( ! empty( $settings['wadi_posts_select_filter_extra'] ) ) {
            $args[ $settings['wadi_posts_filter_rule'] ] = $settings['wadi_posts_select_filter_extra'];
        }
        
        $args['wadi_posts_sticky'] = ( isset( $settings['wadi_posts_sticky'] ) && 'yes' === $settings['wadi_posts_sticky'] ) ? 1 : 0;

        if ( 0 < $settings['wadi_posts_offset'] ) {

            /**
             * Offset break the pagination. Using WordPress's work around
             *
             * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
             */
            $args['offset_to_fix'] = $settings['wadi_posts_offset'];
        }

        if ( '' !== self::$filter && '*' !== self::$filter ) {
            $args['offset_to_fix'] = 0;
        }


        // Exclude current post.
        if ( 'yes' === $settings['wadi_query_exclude_current'] ) {
            $args['post__not_in'][] = get_the_id();
        }

        // Ordering Filters
        $args['orderby'] = $settings['wadi_order_by_filter'];
        $args['order'] = $settings['wadi_order_filter'];

        if(! empty($settings['active_cat'] )) {
            
            if ( '' !== $settings['active_cat'] && '*' !== $settings['active_cat'] ) {
                

                $filter_type = $settings['wadi_posts_filters_by_'.$post_type.'_taxonomies'];
    
                if ( 'tag' ===  $settings['wadi_posts_filters_by_'.$post_type.'_taxonomies'] ) {
                    $filter_type = 'post_tag';
                }
    
                $args['tax_query'][] = array(
                    'taxonomy' => $filter_type,
                    'field'    => 'slug',
                    'terms'    => $settings['active_cat'],
                    'operator' => 'IN',
                );
    
            }
        }


        
        $the_query = new \WP_Query($args);
        $total_pages = $the_query->max_num_pages;
        $total_posts_num = $the_query->found_posts;
        

        self::$total_num = $total_pages;

        self::$total_posts = $total_posts_num;
        $this->wadi_page_limit( $total_pages );


        // if( 0 === self::$total_posts) {

        //     $query_notice = $settings['wadi_not_found_text'];
    
        //     $self->get_empty_query_message( $query_notice );
        //     $self->render_search();
        // }

        return $the_query;
     }
    /**
     *
     * Get Posts
     *
     * @since 1.0.0
     *
     * @access public
     *
     */

    protected function wadi_get_posts()
    {
        // The Query

        $the_query = $this->wadi_get_query();

        self::get_post_layout($the_query);

    }

    

    
    
    /**
     * Get Other Posts From Other Post Types
     *
     * Returns an array of posts from Post Types
     *
     * @since 1.0.0
     * @access public
     *
     * @return $options array posts
     */
    public static function get_all_posts_types()
    {

        $self = new self();

        $settings = self::$settings;

        $wadi_all_post_types = $self->list_post_types();

        $wadi_get_post_types = array();

        foreach($wadi_all_post_types as $key => $value) {
            array_push($wadi_get_post_types, $key);
        }

        $index = array_search('post', $wadi_get_post_types);
        if($index !== FALSE){
            unset($wadi_get_post_types[$index]);
        }
        
        
        $all_posts = get_posts(
            array(
                'posts_per_page'         => -1,
                'post_type'              => $wadi_get_post_types,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'fields'                 => array( 'ids' ),
            )
        );

        
        if (! empty($all_posts) && ! is_wp_error($all_posts)) {
            foreach ($all_posts as $post) {
                $self->options[ $post->ID ] = strlen($post->post_title) > 20 ? substr($post->post_title, 0, 20) . '...' : $post->post_title;
            }
        }
        
                
        return $self->options;
        
    }
    
    /**
     * Get Other Posts From Other Post Types
     *
     * Returns an array of posts from Post Types
     *
     * @since 1.0.0
     * @access public
     *
     * @return $options array posts
     */
    public static function get_posts_by_post_types($type)
    {

        $self = new self();

        $settings = self::$settings;

        // $wadi_all_post_types = $self->list_post_types();

        // $wadi_get_post_types = array();

        // foreach($wadi_all_post_types as $key => $value) {
        //     array_push($wadi_get_post_types, $key);
        // }

        // $index = array_search('post', $wadi_get_post_types);
        // if($index !== FALSE){
        //     unset($wadi_get_post_types[$index]);
        // }
        
        
        $all_posts = get_posts(
            array(
                'posts_per_page'         => -1,
                'post_type'              => $type,
                'update_post_term_cache' => false,
                'update_post_meta_cache' => false,
                'fields'                 => array( 'ids' ),
            )
        );

        
        if (! empty($all_posts) && ! is_wp_error($all_posts)) {
            foreach ($all_posts as $post) {
                $self->options[ $post->ID ] = strlen($post->post_title) > 20 ? substr($post->post_title, 0, 20) . '...' : $post->post_title;
            }
        }
        
                
        return $self->options;
        
    }

    public static function get_current_terms_tax() {
        $settings = self::$settings;
        $self = new self;
        $post_type = $settings['wadi_posts_query_content_type_filter'];


        $current_tax = $settings['wadi_posts_filters_by_'.$post_type.'_taxonomies'];

        $selectedValues = isset($settings['tax_' . $current_tax . '_' . $post_type . '_filter']) ? $settings['tax_' . $current_tax . '_' . $post_type . '_filter'] : null;
        $filterRule = isset($settings[$current_tax . '_' . $post_type . '_filter_rule']) ? $settings[$current_tax . '_' . $post_type . '_filter_rule'] : null;
        

        $included_terms_slugs = array();
        if (isset($selectedValues) && is_array($selectedValues)) {
            foreach ($selectedValues as $index => $term_selected) {
                array_push($included_terms_slugs, $term_selected);
            }
        }

        $ids_to_exclude = array();
        $get_terms_to_exclude =  get_terms(
            array(
                'fields'  => 'ids',
                'slug'    =>  $included_terms_slugs,
                'taxonomy' => $current_tax,
            )
        );

        if (!is_wp_error($get_terms_to_exclude) && count($get_terms_to_exclude) > 0) {
            $ids_to_exclude = $get_terms_to_exclude;
        }


        if ( isset($selectedValues) && $filterRule === 'IN' && !is_wp_error($selectedValues) && is_array($selectedValues) && count($selectedValues) > 0) {
            $current_filter_taxonomy_terms = get_terms([
                'slug' => $included_terms_slugs,
                'taxonomy' => $current_tax,
                'hide_empty' => false,
            ]);
        } else if ( isset($selectedValues) && $filterRule === 'NOT IN' && !is_wp_error($selectedValues) && count($selectedValues) > 0) {
            $current_filter_taxonomy_terms = get_terms([
                'exclude' => $ids_to_exclude,
                'taxonomy' => $current_tax,
                'hide_empty' => false,
            ]);
        } else {
            $current_filter_taxonomy_terms = get_terms([
                'taxonomy' => $current_tax,
                'hide_empty' => false,
            ]);
                    
        }

        
        ?>
        <?php if (!is_wp_error($current_filter_taxonomy_terms) && count($current_filter_taxonomy_terms) > 0 && 'yes' === $settings['wadi_posts_filters_switcher']) : ?>
        <div class="wadi_tax_filters">
            <ul class="wadi_posts_filters_container">
            <?php if(!empty($settings['wadi_posts_first_tab'])) :
                $first_tab_filter = $settings['wadi_posts_first_tab'];
            ?>
            <li class="wadi_posts_filter_item">
                <a href="javascript:void(0);" class="data_filter filter_active" data-filter="*"><?php echo wp_kses_post($first_tab_filter); ?></a>
            </li>
            <?php endif; ?>
                <?php
        foreach($current_filter_taxonomy_terms as $index => $val) {

            $term_name = $val->name;
            $term_slug = $val->slug;
            $term_id = $val->term_id;
            $term_taxonomy = $val->taxonomy;
            $key = 'wadi_filter_' . $index;

                $self->add_render_attribute( $key, [
                    'class' => 'data_filter',
                    'data-taxonomy' => $term_taxonomy,
                    'data-tax-id' => $term_id,
                    'data-filter' => "." . $term_slug,

                ] );

            if ( empty( $settings['wadi_posts_first_tab'] ) && 0 === $index ) {
                $self->add_render_attribute( $key, 'class', 'filter_active' );
            }
            ?>
            <li  class="wadi_posts_filter_item">
                <a href="javascript:void(0);" <?php echo wp_kses_post( $self->get_render_attribute_string( $key ) ); ?>><?php echo wp_kses_post($term_name); ?></a>
            </li>
            <?php
            
        }
        ?>
        </ul>
    </div>
    <?php endif; ?>

    <?php
    }



    public static function get_post_layout($query) {
        $self = new self();
		$settings = self::$settings;
        $total = self::$page_limit;
        $total_post_count = $query->found_posts;
        $posts_per_page = $settings['wadi_posts_per_page'];

        $wadi_post_swiper_slide =  'carousel' === $settings['wadi_posts_layout'] ? 'swiper-slide' : '';
        

        // The Loop
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $post = $query->the_post(); ?>
                <div class="wadi_single_post_container <?php echo wp_kses_post($wadi_post_swiper_slide); ?> <?php wp_kses_post($self->get_post_filter_taxonomy($post)); ?>" data-posts-per-page="<?php echo wp_kses_post($posts_per_page); ?>" data-total-posts-count="<?php echo wp_kses_post($total_post_count); ?>" data-total-page-num="<?php echo wp_kses_post($total); ?>">
                        <div class="wadi_single_bg_wrap">
                            <div class="wadi_inner_post_wrap <?php echo wp_kses_post($self->get_no_image_class()); ?>">
                                <?php echo wp_kses_post($self->get_post_featured_image($post)); ?>
                                <div class="wadi_posts_text_content_container">
                                    <a href="<?php echo wp_kses_post(get_permalink($post)); ?>">
                                        <<?php echo wp_kses_post($settings['wadi_posts_posts_title_tag']); ?> class="wadi_post_title"><?php echo wp_kses_post(get_the_title()); ?></<?php echo wp_kses_post($settings['wadi_posts_posts_title_tag']); ?>>
                                    </a>
                                    <?php if( 'yes' === $settings['wadi_post_content_switcher']) : ?>
                                        <?php if ('post_content' === $settings['wadi_post_content_type']) : ?>
                                            <div class="wadi_post_content">
                                                <?php echo wp_kses_post(get_the_content('Continue reading ' . get_the_title() . ' ...' )); ?>
                                            </div>
                                        <?php elseif ('excerpt' === $settings['wadi_post_content_type']) : ?>
                                            <div class="wadi_post_excerpt">
                                                <?php echo wp_kses_post($self->excerpt_articles($post, $settings['wadi_post_excerpt_length'])); ?>
                                                <?php if ('excerpt_dots' === $settings['wadi_post_excerpt_link_type_switcher'] ) : ?>
                                                    <a href="<?php echo wp_kses_post(get_permalink($post)); ?>">
                                                       ...
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ('excerpt_link' === $settings['wadi_post_excerpt_link_type_switcher'] ) : ?>
                                                    <div class="wadi_post_read_more_call_to_action">
                                                        <a href="<?php echo wp_kses_post(get_permalink($post)); ?>">
                                                            <div class="wadi_excerpt_read_more"><?php echo wp_kses_post($settings['wadi_post_excerpt_link_text']); ?></div>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                        <?php  endif ;?>
                                    <?php endif; ?>
                                    <div class="wadi_single_post_meta">
                                        <?php if('yes' === $settings['wadi_post_date_meta']) : ?>
                                            <div class="wadi_post_date"><?php echo wp_kses_post(get_the_date('', $post)); ?></div>
                                        <?php endif; ?>
                                        <?php if('yes' === $settings['wadi_post_author_meta']) : ?>
                                            <div class="wadi_single_post_author">
                                                <?php if('yes' === $settings['wadi_post_author_avatar_meta'] ) : ?>
                                                    <div class="wadi_post_author_avatar"><?php echo wp_kses_post($self->get_post_author_avatar($post)); ?></div>
                                                <?php endif; ?>
                                                <?php if('yes' === $settings['wadi_post_author_display_name_meta']) : ?>
                                                <div class="wadi_post_author_name"><?php echo wp_kses_post($self->get_post_author_name($post)); ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        <?php if('yes' === $settings['wadi_post_categories_meta']) : ?>
                                            <div class="wadi_post_list_categories"><?php echo wp_kses_post($self->list_categories($post)); ?></div>
                                        <?php endif; ?>
                                        <?php if('yes' === $settings['wadi_post_tags_meta']) : ?>
                                            <div class="wadi_post_list_tags"><?php echo wp_kses_post($self->list_tags($post)); ?></div>
                                        <?php endif; ?>
                                        <?php if('yes' === $settings['wadi_post_comments_meta']) : ?>
                                            <?php
                                                $post_comments = get_comments_number($post);
                                                
                                                if(  0 !== (int)$post_comments) : ?>
                                                <a  class="wadi_posts_comments" href="<?php echo wp_kses_post(get_permalink($post)) . '#respond'; ?>">
                                                    <div><?php comments_number(); ?></div>
                                                </a>
                                                <?php else: ?>
                                                    <div class="wadi_posts_no_comments"><?php comments_number(); ?></div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <?php
            }
        }

        /* Restore original Post Data */
        wp_reset_postdata();
        
    }

    public function wadi_render_posts()
    {
        ob_start();

        $this->wadi_get_posts();

        echo ob_get_clean();
    }

    public function wadi_render_posts_terms_filter(){
        self::get_current_terms_tax();
    }


    /**
	 * Set Widget Settings
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param object $settings widget settings.
	 * @param string $active_cat active category.
	 */
	public function set_widget_settings( $settings, $active_cat = '' ) {

		$settings['active_cat'] = $active_cat;
		self::$settings         = $settings;
	}

    /**
     * 
     * Get Post Type Taxonomies List
     * 
     * @since 1.0.0
     * 
     * @access public
     * 
     */

    public static function get_post_type_taxonomies($post_type) {

        $taxonomies = self::get_taxnomies( $post_type );


		$related_tax = array();

		if ( ! empty( $taxonomies ) ) {

			foreach ( $taxonomies as $key => $tax ) {
                
				$related_tax[ $key ] = $tax->label;
			}
            return $related_tax;
		}


     }

    /**
    *
    * List Categories
    *
    */
    public function list_categories($post_id)
    {
        $category_detail = get_the_category($post_id);

        if (!empty($category_detail)) :

            count(array($category_detail));

        foreach ($category_detail as $cd) {
            ?>
                <a href="<?php echo esc_url(get_category_link($cd->term_id)); ?>"><?php echo esc_attr($cd->cat_name); ?></a>
                <?php
                if (next($category_detail)) {
                    echo ", ";
                }
        }

        endif;
    }

    /**
    *
    * List Categories
    *
    */
    public function get_post_filter_taxonomy($post_id)
    {

        $settings = self::$settings;

        $current_post_type = $settings['wadi_posts_query_content_type_filter'];
        $filter_rule = $settings['wadi_posts_filters_by_'.$current_post_type.'_taxonomies'];

		$taxonomies = 'category' === $filter_rule ? get_the_category( $post_id ) : get_the_tags( $post_id );

        if ( ! empty( $taxonomies ) ) {
            count(array($taxonomies));

            foreach ( $taxonomies as $index => $taxonomy ) {

                echo 'category' === esc_html($filter_rule) ? esc_html($taxonomy->slug) : esc_attr($taxonomy->name);

                if (next($taxonomies)) {
                    echo " ";
                }
            }
        }

    }

    /**
    *
    * List Tags
    *
    */
    public function list_tags($post_id)
    {
        $tags_detail = get_the_tags($post_id);

        if (!empty($tags_detail)) :
            if (count(array($tags_detail))) :


                foreach ($tags_detail as $tag) {
                    ?>
                    <a href="<?php echo esc_url(get_category_link($tag->term_id)); ?>"><?php echo esc_attr($tag->name); ?></a>
                    <?php
                    if (next($tags_detail)) {
                        echo ", ";
                    }
                }
                
        endif;
        endif;
    }

    /**
     *
     * Post Excerpt
     *
     */

    
    public function excerpt_articles($post, $length)
    {
        get_the_excerpt($post);
        
        if (! empty($the_excerpt_field)) {
            echo wp_kses_post($the_excerpt_field);
        } else {
            $field_content = get_post($post);

            if (is_object($field_content)) :
            $the_content_excerpt = $field_content->post_content;
            endif;
            
            if (!empty($the_content_excerpt) && strlen($the_content_excerpt) > $length):
                $trimmed_text = wp_html_excerpt($the_content_excerpt, $length);
            $last_space = strrpos($trimmed_text, ' ');
            $modified_trimmed_text = substr($trimmed_text, 0, $last_space);
            echo ''. wp_kses_post($modified_trimmed_text);
            endif;
        }
    }

    /**
     *
     * Post Author Avatar By Post
     *
     */
    public function get_post_author_avatar($post)
    {
        $author_id=$post; ?>
        <div class="wadi_author_avatar">
        <?php
        echo get_avatar(get_the_author_meta('ID')); ?>
        </div>
        <?php
    }
    /**
     *
     * Post Author By Post
     *
     */
    public function get_post_author_name($post)
    {
        $author_id=$post; ?>
                <div class="wadi_author_user_nicename"><?php the_author_meta('user_nicename', $author_id); ?></div>
        <?php
    }

    /**
     *
     * Post Date by Post ID
     *
     */
    public function get_post_date($post_id)
    {
        $post_object = get_post($post_id);
        $post_date = date('F j, Y', strtotime($post_object->post_date));

        echo wp_kses_post($post_date);
    }


    /**
     * Post featured Image
     *
     *
     *
     */

    public function get_post_featured_image($post)
    {
		$settings = self::$settings;

        $settings['wadi_single_post_featured_image_thumb'] = array(
			'id' => get_post_thumbnail_id($post),
		);

		$thumbnail= \Elementor\Group_Control_Image_Size::get_attachment_image_src($settings['wadi_single_post_featured_image_thumb']['id'], 'wadi_single_post_featured_image_thumb', $settings);

        if ( empty( $thumbnail ) ) {
			return;
		}

        if('yes' === $settings['wadi_single_post_featured_image']) :

            if (has_post_thumbnail($post)): ?>
                <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($post), 'single-post-thumbnail');
                if ('yes' === $settings['wadi_single_post_featured_image_link']) {
                    echo '<a class="wadi_featured_image_post_link" href="'.esc_url(get_permalink($post)).'">';
                }
                ?>
                <div class="wadi_featured_image">
                    <img id="wadi_post_image_thumbnail" src="<?php echo wp_kses_post($thumbnail); ?>" />
                </div>
            <?php
                if ('yes' === $settings['wadi_single_post_featured_image_link']) {
                    echo '</a>';
                }
            endif;

        endif;
    }


    /**
     *
     * Get List of All Post Types
     *
     *
     */

    public static function list_post_types()
    {
        $args = array(
            'public'   => true,
         );

        $post_types = get_post_types($args, 'objects');
           
        $wadi_post_types = [];

        if (!empty($post_types)) { // If there are any custom public post types.
 
            foreach ($post_types  as $post_type) {
                $wadi_post_types[ $post_type->name ] = $post_type->label;
            }
            
            $key = array_search('Media', $wadi_post_types, true);

            if ('attachment' === $key) {
                unset($wadi_post_types[ $key ]);
            }
            
            return $wadi_post_types;
        }
    }


     
    /**
     * Get taxnomies.
     *
     * Get post taxnomies for post type
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $type Post type.
     */
    public static function get_taxnomies($type)
    {
        $taxonomies = get_object_taxonomies($type, 'objects');
        $data       = array();

        foreach ($taxonomies as $tax_slug => $tax) {
            if (! $tax->public || ! $tax->show_ui) {
                continue;
            }

            $data[ $tax_slug ] = $tax;
        }

        return $data;
    }


    /**
     * Get list of users.
     *
     * @uses   get_users()
     *
     * @since 1.0.0
     * @return array $users Data for all users.
     */

    public static function list_authors()
    {
        $users = get_users();

        $authors_list = [];

        if (empty($users)) {
            return $authors_list;
        }

        foreach ($users as $user) {
            if ('wp_update_service' !== $user->display_name) {
                $authors_list[ $user->ID ] = $user->display_name;
            }
        }

        return apply_filters('wadi_post_loop_authors_list', $authors_list);
    }

    	/**
	 * Get Empty Query Message
	 *
	 * Written in PHP and used to generate the final HTML when the query is empty
	 *
	 * @since 3.20.3
	 * @access protected
	 *
	 * @param string $notice empty query notice.
	 */
	protected function get_empty_query_message( $notice ) {

        $settings = self::$settings;

        if ( 'yes' === $settings['wadi_not_found_switcher'] ) {
            if ( empty( $notice ) ) {
                $notice = __( 'Current query has no items. Please make sure you have published items matching your query.', 'wadi-addons' );
            }

            ?>
            <div class="wadi_error_notice">
                <?php echo wp_kses_post( $notice ); ?>
            </div>
            <?php
        }

	}

    /**
	 * Get Search Box HTML.
	 *
	 * Returns the Search Box HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render_search() {
		$settings = self::$settings;

        if ( 'yes' === $settings['wadi_show_search_box'] &&  'yes' === $settings['wadi_not_found_switcher'] ) { ?>
		    <div class="wadi_posts_search_box">
				<?php get_search_form(); ?>
            </div>
		<?php } ?>
		<?php
	}

    
	/**
	 * @deprecated 2.5.0
	 * @param \WP_Query &$query
	 */
	public function fix_query_offset( &$query ) {
		if ( ! empty( $query->query_vars['offset_to_fix'] ) ) {
			if ( $query->is_paged ) {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'] + ( ( $query->query_vars['paged'] - 1 ) * $query->query_vars['posts_per_page'] );
			} else {
				$query->query_vars['offset'] = $query->query_vars['offset_to_fix'];
			}
		}
	}

	/**
	 * @deprecated 2.5.0
	 *
	 * @param int       $found_posts
	 * @param \WP_Query $query
	 *
	 * @return mixed
	 */
	public static function fix_query_found_posts( $found_posts, $query ) {
		$offset_to_fix = $query->get( 'offset_to_fix' );

		if ( $offset_to_fix ) {
			$found_posts -= $offset_to_fix;
		}

		return $found_posts;
	}

    public function wadi_render_posts_ajax($widget, $active_cat)
    {
        ob_start();

        $settings = $widget->get_settings();

		$settings['widget_id'] = $widget->get_id();

		$this->set_widget_settings( $settings, $active_cat );

        $this->wadi_get_posts();

        return ob_get_clean();
    }

    /**
	 * Get Posts Query
	 *
	 * Get posts using AJAX
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wadi_get_posts_query() {

		check_ajax_referer( 'wadi-nonce', 'nonce' );

		if ( ! isset( $_POST['page_id'] ) || ! isset( $_POST['widget_id'] ) ) {
			return;
		}


		$post_id     = isset( $_POST['page_id'] ) ? sanitize_text_field( wp_unslash( $_POST['page_id'] ) ) : '';
		$widget_id    = isset( $_POST['widget_id'] ) ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : '';
		$active_cat = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '';

		$elementor = Plugin::$instance;
		$meta      = $elementor->documents->get( $post_id )->get_elements_data();

		$widget_data = $this->find_element_recursive( $meta, $widget_id );

		$data = array(
			'ID'     => '',
			'posts'  => '',
			'paging' => '',
		);

		if ( null !== $widget_data ) {

			$widget = $elementor->elements_manager->create_element_instance( (array)$widget_data );

			$posts = $this->wadi_render_posts_ajax( $widget, $active_cat );

            $pagination = $this->inner_pagination_render();

			$data['ID']     = $widget->get_id();
			$data['posts']  = $posts;
			$data['paging'] = $pagination;
		}

        // $data = "hellio How are you Nice one";

		wp_send_json_success( $data );

	}

    
	/**
	 * Inner Pagination Render
	 *
	 * Used to generate the pagination to be used with the AJAX call
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function inner_pagination_render() {

		ob_start();

		$this->render_pagination();

		return ob_get_clean();

	}

    /**
	 * Set Wadi Pagination Limit
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param integer $pages pages number.
	 */
	public function wadi_page_limit( $pages ) {
		self::$page_limit = $pages;
	}


    /**
     * 
     * Render Pagination
     * 
     * @since 1.0.0
     * 
     * @access protected
     * 
     */

     protected function render_pagination() {
         $settings = self::$settings;

         if('yes' !== $settings['wadi_posts_enable_pagination']) {
            return;
         }

         $pages = self::$page_limit;
         $paged = self::get_wadi_paged();

            $current_page = $paged;
            if ( ! $current_page ) {
                $current_page = 1;
            }

            $next_page = intval($current_page) + 1;

            $this->add_render_attribute( 'wadi_pagination',
            [
                'class' => ['wadi_posts_pagination_container', 'wadi_posts_pagination_container_'.$settings['widget_id']],
                'data-current' => $current_page,
                'data-next' => $next_page,
                'data-max-pages' => self::$total_num,
                'data-total-posts' => self::$total_posts,
            ] );

        if($settings['wadi_posts_pagination_select'] === 'numbers') {
            $nav_links = paginate_links(
                array(
                    'current'   => $current_page,
                    'total'     => $pages,
                    'prev_next' => 'numbers' === $settings['wadi_posts_pagination_select'] ? true : false,
                    'prev_text' => sprintf( '« %s', $settings['wadi_posts_prev_text'] ),
                    'next_text' => sprintf( '%s »', $settings['wadi_posts_next_text'] ),
                    'type'      => 'array',
                )
            );

            ?>
            <div  class='wadi_posts_pagination_container wadi_posts_pagination_container_<?php echo wp_kses_post($settings['widget_id']); ?>' data-current="<?php echo wp_kses_post($current_page); ?>" data-next="<?php echo wp_kses_post($next_page); ?>" data-max-pages="<?php echo wp_kses_post(self::$total_num); ?>" data-total-posts="<?php echo wp_kses_post(self::$total_posts) ?>">
                <?php
                if(!empty($nav_links)) {
                    echo wp_kses_post(implode(' ', $nav_links));
                }
                ?>
            </div>

            <?php
        } elseif($settings['wadi_posts_pagination_select'] === 'infinite' && $settings['wadi_posts_pagination_infinite_select'] === 'wadi_infinite_button') {
            // if( 0 !== self::$total_num ){
            ?>

            <div  class='wadi_posts_pagination_container wadi_posts_pagination_container_<?php echo wp_kses_post($settings['widget_id']); ?>' data-current="<?php echo wp_kses_post($current_page)?>" data-next="<?php echo wp_kses_post($next_page); ?>" data-max-pages="<?php echo wp_kses_post(self::$total_num); ?>" data-total-posts="<?php echo wp_kses_post(self::$total_posts) ?>">
                <a class="wadi_loadmore_infinite" href="javascript:void(0);"><?php echo esc_attr(__("Load More", 'wadi-addons')); ?></a>
            </div>

            <?php
            // }

        } elseif($settings['wadi_posts_pagination_select'] === 'infinite' && $settings['wadi_posts_pagination_infinite_select'] === 'wadi_infinite_scroll') {
            // if (0 !== self::$total_num ) { // Yoda Condition
                ?>
                <input type="hidden"  class='wadi_posts_pagination_container wadi_posts_pagination_container_<?php echo wp_kses_post($settings['widget_id']); ?> wadi_infinite_scroll_posts wadi_infinite_scroll_posts_<?php echo wp_kses_post($settings['widget_id']); ?>' data-current="<?php echo wp_kses_post($current_page); ?>" data-next="<?php echo wp_kses_post($next_page); ?>" data-max-pages="<?php echo wp_kses_post(self::$total_num); ?>"  data-total-posts="<?php echo wp_kses_post(self::$total_posts) ?>">
                </input>

            <?php
            // }
        }

     }

    /**
	 * Get no image class.
	 *
	 * Returns the no image class.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function get_no_image_class() {
        $settings = self::$settings;

        if( 'yes' !== $settings['wadi_single_post_featured_image']) {
            return 'wadi_post_no_thumbnail_image';
        }

		return ( ! get_the_post_thumbnail_url() ) ? 'wadi_post_no_thumbnail_image' : '';
	}


    public function render_not_found_section_content(){
        $settings = self::$settings;
        
        ?>
        <div class="wadi_not_found_wrapper">
                    <?php
                    $query_notice = $settings['wadi_not_found_text'];
                    $query_notice = ! empty( $query_notice ) ? $query_notice : __( 'No posts found.', 'wadi-addons' );            
                    $this->get_empty_query_message( $query_notice );
                    $this->render_search();
                    ?>
                </div>

                <?php
    }


    /**
     * 
     * Wadi Posts Wrapper
     * 
     * @since 1.0.7
     * @access public
     * 
     * 
     */

     public function wadi_posts_wrapper(){

        $settings = self::$settings;

        $query = $this->wadi_get_query();

        if ( ! $query->have_posts() ) {

            ?>
                <div class="wadi_not_found_wrapper">
                    <?php
                    $query_notice = $settings['wadi_not_found_text'];
                    $query_notice = ! empty( $query_notice ) ? $query_notice : __( 'No posts found.', 'wadi-addons' );            
                    $this->get_empty_query_message( $query_notice );
                    $this->render_search();
                    ?>
                </div>
            <?php
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
        $paged = self::get_wadi_paged();

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
                



        ?>
        <div <?php $this->print_render_attribute_string('wadi_posts_content_wrapper'); ?>>
            <?php 
            if( 'carousel' !==  $settings['wadi_posts_layout']|| 'yes' !== $settings['wadi_posts_filters_switcher'] ) { ?>
                    <?php
                        $this->wadi_render_posts_terms_filter();
                    ?>
            <?php
                }
            ?>
            <div <?php $this->print_render_attribute_string('wadi_posts_container'); ?> >
                <?php 
                    $this->wadi_render_posts();
                ?>
            </div>
                <?php echo $this->inner_pagination_render(); ?>
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
     }

    /**
	 * Get Widget Setting data.
	 *
	 * @since 1.7.0
	 * @access public
	 * @param array  $elements Element array.
	 * @param string $form_id Element ID.
	 * @return Boolean True/False.
	 */
	public function find_element_recursive( $elements, $form_id ) {

		foreach ( $elements as $element ) {
			if ( $form_id === $element['id'] ) {
				return $element;
			}

			if ( ! empty( $element['elements'] ) ) {
				$element = $this->find_element_recursive( $element['elements'], $form_id );

				if ( $element ) {
					return $element;
				}
			}
		}

		return false;
	}


	/**
	 * Add render attribute.
	 *
	 * Used to add attributes to a specific HTML element.
	 *
	 * The HTML tag is represented by the element parameter, then you need to
	 * define the attribute key and the attribute key. The final result will be:
	 * `<element attribute_key="attribute_value">`.
	 *
	 * Example usage:
	 *
	 * `$this->add_render_attribute( 'wrapper', 'class', 'custom-widget-wrapper-class' );`
	 * `$this->add_render_attribute( 'widget', 'id', 'custom-widget-id' );`
	 * `$this->add_render_attribute( 'button', [ 'class' => 'custom-button-class', 'id' => 'custom-button-id' ] );`
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array|string $element   The HTML element.
	 * @param array|string $key       Optional. Attribute key. Default is null.
	 * @param array|string $value     Optional. Attribute value. Default is null.
	 * @param bool         $overwrite Optional. Whether to overwrite existing
	 *                                attribute. Default is false, not to overwrite.
	 *
	 * @return self Current instance of the element.
	 */
	public function add_render_attribute( $element, $key = null, $value = null, $overwrite = false ) {
		if ( is_array( $element ) ) {
			foreach ( $element as $element_key => $attributes ) {
				$this->add_render_attribute( $element_key, $attributes, null, $overwrite );
			}

			return $this;
		}

		if ( is_array( $key ) ) {
			foreach ( $key as $attribute_key => $attributes ) {
				$this->add_render_attribute( $element, $attribute_key, $attributes, $overwrite );
			}

			return $this;
		}

		if ( empty( $this->render_attributes[ $element ][ $key ] ) ) {
			$this->render_attributes[ $element ][ $key ] = [];
		}

		settype( $value, 'array' );

		if ( $overwrite ) {
			$this->render_attributes[ $element ][ $key ] = $value;
		} else {
			$this->render_attributes[ $element ][ $key ] = array_merge( $this->render_attributes[ $element ][ $key ], $value );
		}

		return $this;
	}

	/**
	 * Get Render Attributes
	 *
	 * Used to retrieve render attribute.
	 *
	 * The returned array is either all elements and their attributes if no `$element` is specified, an array of all
	 * attributes of a specific element or a specific attribute properties if `$key` is specified.
	 *
	 * Returns null if one of the requested parameters isn't set.
	 *
	 * @since 2.2.6
	 * @access public
	 * @param string $element
	 * @param string $key
	 *
	 * @return array
	 */
	public function get_render_attributes( $element = '', $key = '' ) {
		$attributes = $this->render_attributes;

		if ( $element ) {
			if ( ! isset( $attributes[ $element ] ) ) {
				return null;
			}

			$attributes = $attributes[ $element ];

			if ( $key ) {
				if ( ! isset( $attributes[ $key ] ) ) {
					return null;
				}

				$attributes = $attributes[ $key ];
			}
		}

		return $attributes;
	}

    	/**
	 * Get render attribute string.
	 *
	 * Used to retrieve the value of the render attribute.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $element The element.
	 *
	 * @return string Render attribute string, or an empty string if the attribute
	 *                is empty or not exist.
	 */
	public function get_render_attribute_string( $element ) {
		if ( empty( $this->render_attributes[ $element ] ) ) {
			return '';
		}

		return Utils::render_html_attributes( $this->render_attributes[ $element ] );
	}

    /**
	 * Print render attribute string.
	 *
	 * Used to output the rendered attribute.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array|string $element The element.
	 */
	public function print_render_attribute_string( $element ) {
		echo wp_kses_post($this->get_render_attribute_string( $element )); // XSS ok.
	}
    /**
     * Set Widget Settings
     *
     * @since 1.0.0
     * @access public
     *
     * @param object $settings widget settings.
     */
    public static function wadi_set_settings($settings)
    {
        self::$settings         = $settings;
    }
}
