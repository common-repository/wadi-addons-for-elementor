<?php

/**
 * Class Wadi Admin Helpers
 */
namespace WadiAddons\Admin;

use  Elementor\Modules\Usage\Module ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Class Admin_Helper
 */
class Admin_Helper
{
    /**
     * Class instance
     *
     * @var instance
     */
    public static  $instance ;
    /**
     *
     * Submenus Tabs
     *
     * @var tabs
     *
     * @access public
     *
     * @since 1.0.0
     *
     */
    public static  $tabs ;
    /**
     * Wadi Addons Settings Page Slug
     *
     * @var page_slug
     */
    protected  $page_slug = 'wadi-addons' ;
    /**
     * Elements List
     *
     * @since 1.0.0
     *
     * @package wadi-addons
     * @access public
     *
     * @var wadi_elements_list
     *
     */
    public static  $wadi_elements_list ;
    /**
     * Integrations List
     *
     * @var integrations_list
     */
    public static  $integrations_list = null ;
    /**
     * Elements Names
     *
     * @since 1.0.0
     *
     * @package wadi-addons
     * @access public
     *
     * @var wadi_elements_names
     */
    public static  $wadi_elements_names = null ;
    public function __construct()
    {
        // Insert admin settings submenus.
        $this->wadi_addons_admin_submenus();
        add_action( 'admin_menu', array( $this, 'wadi_admin_menu' ), 100 );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_wadi_elements_settings', array( $this, 'save_settings' ) );
        add_action( 'wp_ajax_wadi_integration_settings', array( $this, 'save_integration_settings' ) );
        add_action( 'wp_ajax_wadi_save_elements_btn', array( $this, 'wadi_save_elements' ) );
    }
    
    public function wadi_admin_menu()
    {
        $plugin_name = self::get_name();
        // Main Menu Page
        call_user_func(
            'add_menu_page',
            $plugin_name,
            $plugin_name,
            'manage_options',
            $this->page_slug,
            array( $this, 'render_wadi_admin_settings' ),
            '',
            100
        );
        foreach ( self::$tabs as $tab ) {
            // Submenu Menu Page
            call_user_func(
                'add_submenu_page',
                $this->page_slug,
                $tab['title'],
                $tab['title'],
                'manage_options',
                $tab['slug'],
                // '__return_null'
                $tab['template'],
                $tab['position']
            );
        }
        remove_submenu_page( $this->page_slug, $this->page_slug );
    }
    
    /**
     * Set Admin Tabs
     *
     * @access private
     * @since 1.0.0
     */
    private function wadi_addons_admin_submenus()
    {
        $slug = $this->page_slug;
        // $plugin_name = self::get_name();
        self::$tabs = array(
            'general'  => array(
            'title'    => __( 'General', 'wadi-addons' ),
            'id'       => 'wadi-general',
            'slug'     => 'wadi-general',
            'template' => array( $this, 'wadi_general' ),
            'position' => 1,
        ),
            'elements' => array(
            'title'    => __( 'Widgets', 'wadi-addons' ),
            'id'       => 'wadi-elements',
            'slug'     => 'wadi-elements',
            'template' => array( $this, 'widets_cotnent' ),
            'position' => 2,
        ),
            'settings' => array(
            'title'    => __( 'Settings', 'wadi-addons' ),
            'id'       => 'wadi-settings',
            'slug'     => 'wadi-settings',
            'template' => array( $this, 'wadi_settings' ),
            'position' => 3,
        ),
        );
        self::$tabs = apply_filters( 'wadi_addons_register_submenus', self::$tabs );
    }
    
    public function render_wadi_admin_settings()
    {
        ?>
		<div class="wadi-settings-wrap">
			<?php 
        do_action( 'wadi_before_render_admin_tabs' );
        ?>
			<div class="wadi-settings-tabs">
				<ul class="wadi-settings-tabs-list">
					<?php 
        foreach ( self::$tabs as $key => $tab ) {
            $link = '<li class="wadi-settings-tab">';
            $link .= '<a id="wadi-tab-link-' . esc_attr( $tab['id'] ) . '"';
            $link .= ' href="' . esc_url( $tab['href'] ) . '">';
            $link .= '<i class="wadi-dash-' . esc_attr( $tab['id'] ) . '"></i>';
            $link .= '<span>' . esc_html( $tab['title'] ) . '</span>';
            $link .= '</a>';
            $link .= '</li>';
            echo  wp_kses_post( $link ) ;
        }
        ?>
				</ul>
			</div> <!-- Settings Tabs -->

			<!-- <div class="wadi-settings-sections"> -->
				<?php 
        // foreach (self::$tabs as $key => $tab) {
        //     echo wp_kses_post('<div id="wadi-section-' . $tab['id'] . '" class="wadi-section wadi-section-' . $key . '">');
        //     include_once $tab['template'] . '.php';
        //     echo '</div>';
        // }
        ?>
			<!-- </div> -->
             <!-- Settings Sections -->
			<?php 
        do_action( 'wadi_after_render_admin_tabs' );
        ?>
		</div> <!-- Settings Wrap -->
		<?php 
    }
    
    /**
     * Get Elements List
     *
     * Get a list of all the widgets available in the plugin
     *
     * @since 1.0.0
     * @access public
     *
     * @return array elements list
     */
    public static function get_wadi_elements_list()
    {
        if ( null === self::$wadi_elements_list ) {
            self::$wadi_elements_list = (require_once WADI_ADDONS_ADMIN_PATH . 'elements-list.php');
        }
        return self::$wadi_elements_list;
    }
    
    /**
     * Get default Elements
     *
     * @since 3.20.9
     * @access private
     *
     * @return $default_keys array keys defaults
     */
    private static function get_default_elements()
    {
        $elements = self::get_wadi_elements_list();
        $keys = array();
        // Now, we need to fill our array with elements keys.
        foreach ( $elements as $cat ) {
            if ( count( $cat['elements'] ) ) {
                foreach ( $cat['elements'] as $elem ) {
                    
                    if ( Admin_Helper::check_wadi_addons_is_free() ) {
                        if ( !$elem['is_pro'] ) {
                            array_push( $keys, $elem['key'] );
                        }
                    } else {
                        array_push( $keys, $elem['key'] );
                    }
                
                }
            }
        }
        $default_keys = array_fill_keys( $keys, true );
        return $default_keys;
    }
    
    /**
     * Get Pro Elements
     *
     * @since 4.5.3
     * @access public
     */
    public static function get_pro_elements()
    {
        $elements = self::get_wadi_elements_list();
        $pro_elements = array();
        $all_elements = $elements['wadi-elements'];
        if ( count( $all_elements['elements'] ) ) {
            foreach ( $all_elements['elements'] as $elem ) {
                if ( isset( $elem['is_pro'] ) && !isset( $elem['is_global'] ) ) {
                    array_push( $pro_elements, $elem );
                }
            }
        }
        return $pro_elements;
    }
    
    /**
     * Check if is Premium (Wadi Addons for Elementor Pro) is active
     *
     * @since 1.0.9
     * @access public
     * 
     */
    public static function check_wadi_addons_pro()
    {
    }
    
    public static function check_wadi_addons_is_free()
    {
        if ( wa_fs()->is_free_plan() ) {
            return true;
        }
    }
    
    /**
     * Get used widgets.
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public static function wadi_used_widgets()
    {
        $used_widgets = array();
        $tracker_allowed = ( 'yes' === get_option( 'elementor_allow_tracking' ) ? true : false );
        if ( !$tracker_allowed ) {
            return false;
        }
        $module = Module::instance();
        $elements = $module->get_formatted_usage( 'raw' );
        $wadi_elements = self::get_wadi_elements_names();
        if ( is_array( $elements ) || is_object( $elements ) ) {
            foreach ( $elements as $post_type => $data ) {
                foreach ( $data['elements'] as $element => $count ) {
                    if ( in_array( $element, $wadi_elements, true ) ) {
                        
                        if ( isset( $used_widgets[$element] ) ) {
                            $used_widgets[$element] += $count;
                        } else {
                            $used_widgets[$element] = $count;
                        }
                    
                    }
                }
            }
        }
        return $used_widgets;
    }
    
    /**
     * Get Wadi Elements names.
     *
     * @access public
     * @since 1.0.0
     *
     * @return array
     */
    public static function get_wadi_elements_names()
    {
        $names = self::$wadi_elements_names;
        
        if ( null === $names ) {
            $names = array_map( function ( $item ) {
                return ( isset( $item['name'] ) ? $item['name'] : 'global' );
            }, self::get_wadi_elements_list()['wadi-elements']['elements'] );
            $names = array_filter( $names, function ( $name ) {
                return 'global' !== $name;
            } );
        }
        
        return $names;
    }
    
    /**
     * Get enabled widgets
     *
     * @since 1.0.0
     * @access public
     *
     * @return array $enabled_keys enabled elements
     */
    public static function get_wadi_enabled_elements()
    {
        $defaults = self::get_default_elements();
        $enabled_keys = get_option( 'wadi_save_settings', $defaults );
        foreach ( $defaults as $key => $value ) {
            if ( !isset( $enabled_keys[$key] ) ) {
                $defaults[$key] = 0;
            }
        }
        return $defaults;
    }
    
    /**
     * Save Settings
     *
     * Save elements settings using AJAX
     *
     * @access public
     * @since 3.20.8
     */
    public function save_settings()
    {
        check_ajax_referer( 'wadi-admin-settings', 'security' );
        if ( !isset( $_POST['fields'] ) ) {
            return;
        }
        parse_str( sanitize_text_field( $_POST['fields'] ), $settings );
        $defaults = self::get_default_elements();
        $elements = array_fill_keys( array_keys( array_intersect_key( $settings, $defaults ) ), true );
        update_option( 'wadi_save_settings', $elements );
        wp_send_json_success( $elements, 200 );
    }
    
    /**
     * 
     * Get all elements Wadi
     * 
     * @since 1.0.0
     * 
     * @access public
     * 
     */
    public function wadi_save_elements()
    {
        check_ajax_referer( 'wadi-admin-settings', 'security' );
        if ( !isset( $_POST['isGlobalOn'] ) ) {
            wp_send_json_error();
        }
        $global_btn_value = sanitize_text_field( $_POST['isGlobalOn'] );
        update_option( 'wadi_elements_btn_value', $global_btn_value );
        wp_send_json_success();
    }
    
    /**
     * Check if white labeling - Free version name field is set
     *
     * @since 1.0.0
     * @access public
     *
     * @return string
     */
    public static function get_name()
    {
        $name = 'Wadi Addons For Elementor';
        return ( '' !== $name ? $name : 'Wadi Addons For Elementor' );
    }
    
    /**
     *
     * Enqueue Admin Scripts Wadi Addons
     *
     * @since 1.0.0
     *
     * @access public
     *
     */
    public function enqueue_scripts( $hook )
    {
        // Wadi Addons Admin Widgets Page
        
        if ( 'wadi-addons-for-elementor_page_wadi-elements' === $hook ) {
            wp_enqueue_script(
                'wadi_swal',
                WADI_ADDONS_URL . 'assets/extLib/sweetalert2.all.min.js',
                array(),
                '11.4.4',
                true
            );
            wp_register_script(
                'wadi_addons_admin_settings',
                WADI_ADDONS_URL . 'assets/dist/wadi-admin-widgets.js',
                array( 'jquery', 'wadi_swal' ),
                WADI_ADDONS_VERSION,
                true
            );
            $theme_slug = self::get_installed_theme();
            $localized_data = array(
                'settings' => array(
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'nonce'            => wp_create_nonce( 'wadi-admin-settings' ),
                'theme'            => $theme_slug,
                'isTrackerAllowed' => ( 'yes' === get_option( 'elementor_allow_tracking', 'no' ) ? true : false ),
            ),
            );
            wp_localize_script( 'wadi_addons_admin_settings', 'wadiAddonsSettings', $localized_data );
            wp_enqueue_script( 'wadi_addons_admin_settings' );
            
            if ( !is_rtl() ) {
                wp_enqueue_style(
                    'wadi_addons_admin_settings-css',
                    WADI_ADDONS_URL . 'assets/dist/wadi-admin-widgets.css',
                    array(),
                    WADI_ADDONS_VERSION,
                    'all'
                );
            } else {
                wp_enqueue_style(
                    'wadi_addons_admin_settings-css',
                    WADI_ADDONS_URL . 'assets/dist/wadi-admin-widgets.rtl.css',
                    array(),
                    WADI_ADDONS_VERSION,
                    'all'
                );
            }
        
        }
        
        
        if ( 'wadi-addons-for-elementor_page_wadi-settings' === $hook ) {
            $theme_slug = self::get_installed_theme();
            wp_register_script(
                'wadi_admin_settings_integrations',
                WADI_ADDONS_URL . 'assets/dist/wadi-admin-settings-integrations.js',
                array( 'jquery' ),
                WADI_ADDONS_VERSION,
                true
            );
            wp_register_script(
                'wadi_admin_settings_tabs',
                WADI_ADDONS_URL . 'assets/dist/wadi-settings-tabs.js',
                array( 'jquery' ),
                WADI_ADDONS_VERSION,
                true
            );
            wp_enqueue_script( 'wadi_admin_settings_tabs' );
            
            if ( !is_rtl() ) {
                wp_enqueue_style(
                    'wadi_addons_admin_general-css',
                    WADI_ADDONS_URL . 'assets/dist/wadi-settings-tabs.css',
                    array(),
                    WADI_ADDONS_VERSION,
                    'all'
                );
            } else {
                wp_enqueue_style(
                    'wadi_addons_admin_general-css',
                    WADI_ADDONS_URL . 'assets/dist/wadi-settings-tabs.rtl.css',
                    array(),
                    WADI_ADDONS_VERSION,
                    'all'
                );
            }
            
            $localized_data = array(
                'settings' => array(
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'nonce'            => wp_create_nonce( 'wadi-admin-integration-settings' ),
                'theme'            => $theme_slug,
                'isTrackerAllowed' => ( 'yes' === get_option( 'elementor_allow_tracking', 'no' ) ? true : false ),
            ),
            );
            wp_localize_script( 'wadi_admin_settings_integrations', 'wadiAddonsIntegrationsSettings', $localized_data );
            wp_enqueue_script( 'wadi_admin_settings_integrations' );
        }
        
        // Wadi Addons Admin General Page
        
        if ( 'wadi-addons-for-elementor_page_wadi-general' === $hook ) {
            wp_register_script(
                'wadi_addons_admin_general',
                WADI_ADDONS_URL . 'assets/dist/wadi-admin-main.js',
                array( 'jquery' ),
                WADI_ADDONS_VERSION,
                true
            );
            $theme_slug = self::get_installed_theme();
            $localized_data = array(
                'settings' => array(
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'nonce'            => wp_create_nonce( 'wadi-admin-general' ),
                'theme'            => $theme_slug,
                'isTrackerAllowed' => ( 'yes' === get_option( 'elementor_allow_tracking', 'no' ) ? true : false ),
            ),
            );
            wp_localize_script( 'wadi_addons_admin_general', 'wadiAddonsGeneral', $localized_data );
            wp_enqueue_script( 'wadi_addons_admin_general' );
            wp_enqueue_style(
                'wadi_addons_admin_general-css',
                WADI_ADDONS_URL . 'assets/dist/wadi-admin-main.css',
                array(),
                WADI_ADDONS_VERSION,
                'all'
            );
        }
    
    }
    
    /**
     * 
     * Settings Page Wadi Addons
     * @since 1.0.0
     * 
     * 
     */
    public function wadi_general()
    {
        require_once WADI_ADDONS_ADMIN_PATH . 'templates/general.php';
    }
    
    /**
     * 
     * Widgets Management Wadi Elements
     * @since 1.0.0
     * 
     * 
     */
    public function widets_cotnent()
    {
        require_once WADI_ADDONS_ADMIN_PATH . 'templates/widgets.php';
    }
    
    /**
     * 
     * Widgets Management Wadi Elements
     * @since 1.0.0
     * 
     * 
     */
    public function wadi_settings()
    {
        require_once WADI_ADDONS_ADMIN_PATH . 'templates/settings.php';
    }
    
    /**
     * Checks user credentials for specific action
     *
     * @since 1.0.0
     *
     * @return boolean
     */
    public static function check_user_can( $action )
    {
        return current_user_can( $action );
    }
    
    /**
     * Get Campaign Link
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $link page link.
     * @param string $source source.
     * @param string $medium  media.
     * @param string $campaign campaign name.
     *
     * @return string $link campaign URL
     */
    public static function get_campaign_link(
        $link,
        $source,
        $medium,
        $campaign = ''
    )
    {
        $theme = self::get_installed_theme();
        $url = add_query_arg( array(
            'utm_source'   => $source,
            'utm_medium'   => $medium,
            'utm_campaign' => $campaign,
            'utm_term'     => $theme,
        ), $link );
        return $url;
    }
    
    /**
     * Get Installed Theme
     *
     * Returns the active theme slug
     *
     * @access public
     *
     * @return string theme slug
     */
    public static function get_installed_theme()
    {
        $theme = wp_get_theme();
        
        if ( $theme->parent() ) {
            $theme_name = sanitize_key( $theme->parent()->get( 'Name' ) );
            return $theme_name;
        }
        
        $theme_name = $theme->get( 'Name' );
        $theme_name = sanitize_key( $theme_name );
        return $theme_name;
    }
    
    /**
     * Checks if a plugin is installed
     *
     * @since 1.0.0
     * @access public
     *
     * @param string $plugin_path plugin path.
     *
     * @return boolean
     */
    public static function is_plugin_installed( $plugin_path )
    {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugins = get_plugins();
        return isset( $plugins[$plugin_path] );
    }
    
    /**
     * Check Plugin Active
     *
     * @since 4.2.5
     * @access public
     *
     * @param string $slug plugin slug.
     *
     * @return boolean $is_active plugin active.
     */
    public static function check_plugin_active( $slug = '' )
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $is_active = is_plugin_active( $slug );
        return $is_active;
    }
    
    /**
     * Get Integrations List
     *
     * Get a list of all the integrations available in the plugin
     *
     * @since 3.20.9
     * @access private
     *
     * @return array integrations_list
     */
    private static function get_integrations_list()
    {
        if ( null === self::$integrations_list ) {
            self::$integrations_list = array( 'wadi_paypal_client_id', 'wadi_paypal_currency' );
        }
        return self::$integrations_list;
    }
    
    /**
     * Save Integrations Control Settings
     *
     * Stores integration and version control settings
     *
     * @since 3.20.8
     * @access public
     */
    public function save_integration_settings()
    {
        check_ajax_referer( 'wadi-admin-integration-settings', 'security' );
        if ( !isset( $_POST['fields'] ) ) {
            return;
        }
        parse_str( sanitize_text_field( wp_unslash( $_POST['fields'] ) ), $settings );
        $new_settings = array(
            'wadi_paypal_client_id' => sanitize_text_field( $settings['wadi_paypal_client_id'] ),
            'wadi_paypal_currency'  => sanitize_text_field( $settings['wadi_paypal_currency'] ),
        );
        update_option( 'wadi_integration_settings_options', $new_settings );
        wp_send_json_success( $settings );
    }
    
    /**
     * Get Integrations Settings
     *
     * Get plugin integrations settings
     *
     * @since 3.20.9
     * @access public
     *
     * @return array $settings integrations settings
     */
    public static function get_integrations_settings()
    {
        $enabled_keys = get_option( 'wadi_integration_settings_options', self::get_default_integrations() );
        return $enabled_keys;
    }
    
    /**
     * Get Default Interations
     *
     * @since 3.20.9
     * @access private
     *
     * @return $default_keys array default keys
     */
    private static function get_default_integrations()
    {
        $settings = self::get_integrations_list();
        $default_keys = array_fill_keys( $settings, true );
        return $default_keys;
    }
    
    /**
     * Creates and returns an instance of the class
     *
     * @since 1.0.0
     * @access public
     *
     * @return object
     */
    public static function get_instance()
    {
        if ( !isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}