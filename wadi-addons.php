<?php

/**
 * Plugin Name:       Wadi Addons for Elementor
 * Plugin URI:        https://www.wadiweb.com/wadi-addons-for-elementor/
 * Description:       Wadi Addons is a great collection of high quality widgets and addons for Elementor
 * Version:           1.0.10
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Wadi Web
 * Author URI:        https://www.wadiweb.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wadi-addons
 * Domain Path:       /languages
 * Elementor tested up to: 3.7.8
 * Elementor Pro tested up to: 3.7.8
 * 
 * 
 */
/*
Wadi Addons for Elementor is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Wadi Addons for Elementor is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Wadi Addons for Elementor. If not, see https://www.gnu.org/licenses/gpl-3.0.en.html.
*/
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'wa_fs' ) ) {
    wa_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    if ( !function_exists( 'wa_fs' ) ) {
        // ... Freemius integration snippet ...
        
        if ( !function_exists( 'wa_fs' ) ) {
            // Create a helper function for easy SDK access.
            function wa_fs()
            {
                global  $wa_fs ;
                
                if ( !isset( $wa_fs ) ) {
                    // Include Freemius SDK.
                    require_once dirname( __FILE__ ) . '/freemius/start.php';
                    $wa_fs = fs_dynamic_init( array(
                        'id'              => '9971',
                        'slug'            => 'wadi-addons',
                        'premium_slug'    => 'wadi-addons-pro',
                        'type'            => 'plugin',
                        'public_key'      => 'pk_d6c4cb36c4f63b0f65e5d7db6dfd7',
                        'is_premium'      => false,
                        'has_addons'      => false,
                        'has_paid_plans'  => true,
                        'has_affiliation' => 'all',
                        'menu'            => array(
                        'slug'       => 'wadi-addons',
                        'first-path' => 'admin.php?page=wadi-elements',
                        'support'    => false,
                    ),
                        'is_live'         => true,
                    ) );
                }
                
                return $wa_fs;
            }
            
            // Init Freemius.
            wa_fs();
            // Signal that SDK was initiated.
            do_action( 'wa_fs_loaded' );
        }
    
    }
    class WadiAddons
    {
        /**
         * Version
         *
         * @var string
         */
        public  $version = '1.0.10' ;
        /**
         * Full name
         *
         * @var string
         */
        public  $name = 'Wadi Addons for Elementor' ;
        /**
         * Slug
         *
         * @var string
         */
        public  $slug = 'wadi-addons' ;
        /**
         * Variable to hold settings
         *
         * @var array|null
         */
        public  $settings = null ;
        /**
         *
         * Variable to hold settings framework
         *
         * @var object
         */
        public  $settings_framework = null ;
        /**
         * Absolute path to this plugin folder, with trailing slash
         *
         * @var string
         */
        public  $plugin_path ;
        /**
         * Path to Languages folder
         *
         * @var string
         */
        public  $plugin_languages ;
        /**
         * Plugin URL for this plugin folder, with no trailing slash
         *
         * @var string
         */
        public  $plugin_url ;
        /**
         * Nonce name for ajax requests
         *
         * @var string
         */
        public  $ajax_nonce ;
        /**
         * Active plugin list
         *
         * @var array
         */
        public  $active_plugins_list ;
        /**
         * Construct
         */
        public function __construct()
        {
            $this->plugin_path = plugin_dir_path( __FILE__ );
            $this->plugin_languages = basename( dirname( __FILE__ ) ) . '/languages';
            $this->plugin_url = plugin_dir_url( __FILE__ );
            $this->ajax_nonce = $this->slug . '_ajax';
            $this->active_plugins_list = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
            $this->wadi_constants();
            $this->init_wadi_autoloader();
        }
        
        private function wadi_constants()
        {
            define( 'WADI_ADDONS_PATH', $this->plugin_path );
            define( 'WADI_ADDONS_PATH_LANGUAGES', $this->plugin_languages );
            define( 'WADI_ADDONS_URL', $this->plugin_url );
            define( 'WADI_ADDONS_CONTROLS_PATH', WADI_ADDONS_PATH . 'controls/' );
            define( 'WADI_ADDONS_INC_PATH', WADI_ADDONS_PATH . 'includes/' );
            define( 'WADI_ADDONS_ADMIN_PATH', WADI_ADDONS_PATH . 'admin/' );
            define( 'WADI_ADDONS_ADMIN_TEMPLATES_PATH', WADI_ADDONS_ADMIN_PATH . 'templates/' );
            define( 'WADI_ADDONS_VERNDOR_PATH', WADI_ADDONS_PATH . 'vendor/' );
            define( 'WADI_ADDONS_FILE', __FILE__ );
            define( 'WADI_ADDONS_BASENAME', plugin_basename( WADI_ADDONS_FILE ) );
            define( 'WADI_ADDONS_VERSION', $this->version );
        }
        
        private function init_wadi_autoloader()
        {
            require_once WADI_ADDONS_INC_PATH . 'class-core-autoloader.php';
        }
    
    }
    $WadiAddonsClass = new WadiAddons();
    // ... Your plugin's main file logic ...
}
