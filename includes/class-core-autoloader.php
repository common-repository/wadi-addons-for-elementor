<?php
/**
 * WA Core.
 */

namespace WadiAddons\Includes;

use Elementor\Plugin;
use WadiAddons\Includes\WadiIntegation;
use WadiAddons\Admin\Admin_Helper;
use WadiAddons\Admin\Wadi_Notices;

if (! defined('ABSPATH')) {
    exit;
}

if (! class_exists('WA_Core')) {
    final class WA_Core
    {

            /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
        const VERSION = '1.0.0';

        /**
         * Minimum Elementor Version
         *
         * @since 1.0.0
         *
         * @var string Minimum Elementor version required to run the plugin.
         */
        const MINIMUM_ELEMENTOR_VERSION = '3.2.0';

        /**
         * Minimum PHP Version
         *
         * @since 1.0.0
         *
         * @var string Minimum PHP version required to run the plugin.
         */
        const MINIMUM_PHP_VERSION = '7.0';

        /**
         * Instance
         *
         * @since 1.0.0
         *
         * @access private
         * @static
         *
         * @var WA_Core The single instance of the class.
         */
        private static $_instance = null;
    


        public static function instance()
        {
            if (!isset(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct()
        {
            add_action('plugins_loaded', [ $this, 'on_plugins_loaded' ]);
            
            add_action('elementor/init', [ $this, 'init' ]);
            // Autoloader.
            spl_autoload_register(array( $this, 'autoload' ));
        }

        /**
         * AutoLoad
         *
         * @since 1.0.0
         * @param string $class class.
         */
        public function autoload($class)
        {
            if (0 !== strpos($class, 'WadiAddons')) {
                return;
            }

            $class_to_load = $class;

            if (! class_exists($class_to_load)) {
                $filename = strtolower(
                    preg_replace(
                        array( '/^WadiAddons\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ),
                        array( '', '$1-$2', '-', DIRECTORY_SEPARATOR ),
                        $class_to_load
                    )
                );

                $filename = WADI_ADDONS_PATH . $filename . '.php';
                if (is_readable($filename)) {
                    include $filename;
                }
            }
        }

        public function on_plugins_loaded()
        {
            $this->i18n();

            $this->wadi_addons_admin_settings();
        }

        protected function wadi_addons_admin_settings()
        {
            
            
            // if (file_exists(WADI_ADDONS_ADMIN_PATH . 'class-admin-helper.php')) {
            //     require_once WADI_ADDONS_ADMIN_PATH . 'class-admin-helper.php';
            // }
            Admin_Helper::get_instance();
            Wadi_Notices::get_instance();
        }

        public function is_compatible()
        {

            // Check if Elementor installed and activated
            if (! did_action('elementor/loaded')) {
                add_action('admin_notices', [ $this, 'admin_notice_missing_main_plugin' ]);
                return false;
            }

            // Check for required Elementor version
            if (! version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
                add_action('admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ]);
                return false;
            }

            // Check for required PHP version
            if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
                add_action('admin_notices', [ $this, 'admin_notice_minimum_php_version' ]);
                return false;
            }
    
            return true;
        }

        
        /**
        * Load Textdomain
        *
        * Load plugin localization files.
        *
        * Fired by `init` action hook.
        *
        * @since 1.0.0
        *
        * @access public
        */
        public function i18n()
        {   
            load_plugin_textdomain('wadi-addons', false, WADI_ADDONS_PATH_LANGUAGES );
        }

        public function admin_notice_missing_main_plugin()
        {
            if (isset($_GET['activate'])) {
                unset($_GET['activate']); // phpcs:ignore CSRF ok.
            }
    
            $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor */
                esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'wadi-addons'),
                '<strong>' . esc_html__('Wadi Addons', 'wadi-addons') . '</strong>',
                '<strong>' . esc_html__('Elementor', 'wadi-addons') . '</strong>'
            );
    
            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post($message));
        }
        
        public function admin_notice_minimum_elementor_version()
        {
            if (isset($_GET['activate'])) {
                unset($_GET['activate']); // phpcs:ignore CSRF ok.
            }
    
            $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
                esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'wadi-addons'),
                '<strong>' . esc_html__('Wadi Addons', 'wadi-addons') . '</strong>',
                '<strong>' . esc_html__('Elementor', 'wadi-addons') . '</strong>',
                self::MINIMUM_ELEMENTOR_VERSION
            );
    
            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post($message));
        }

        /**
        * Admin notice
        *
        * Warning when the site doesn't have a minimum required PHP version.
        *
        * @since 1.0.0
        *
        * @access public
        */
        public function admin_notice_minimum_php_version()
        {
            if (isset($_GET['activate'])) {
                unset($_GET['activate']); // phpcs:ignore CSRF ok.
            }

            $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'wadi-addons'),
                '<strong>' . esc_html__('Wadi Addons', 'wadi-addons') . '</strong>',
                '<strong>' . esc_html__('PHP', 'wadi-addons') . '</strong>',
                self::MINIMUM_PHP_VERSION
            );

            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', wp_kses_post($message));
        }

    
        public function init()
        {

            require_once WADI_ADDONS_PATH . 'includes/class-helper-functions.php';
            require_once WADI_ADDONS_PATH . 'includes/class-wadi-queries.php';
            require_once WADI_ADDONS_PATH . 'includes/class-wadi-integration.php';


            WadiIntegation::get_instance();
        }
    }
}


if (! function_exists('wadi_loader')) {

    /**
     * Returns an instance of the plugin class.
     *
     * @since  1.0.0
     * @return object
     */
    function wadi_loader()
    {
        return WA_Core::instance();
    }
}

wadi_loader();
