<?php

namespace WadiAddons\Includes;

use WadiAddons;
use WadiAddons\Includes\WadiQueries;
use WadiAddons\Admin\Admin_Helper;
use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

class WadiIntegation
{
    /**
     * Class instance
     *
     * @var instance
     */
    private static $instance = null;


    /**
     * Template Instance
     *
     * @var template_instance
     */
    protected $template_instance;

    /**
     * Modules
     *
     * @var modules
     */
    private static $modules = null;

    /**
     * wadi_free
     *
     * @var wadi_free_mobudles
     */
    private static $wadi_free_mobudles = null;


    public function __construct()
    {

		self::$modules = \WadiAddons\Admin\Admin_Helper::get_wadi_enabled_elements();
		$pro_elements = \WadiAddons\Admin\Admin_Helper::get_pro_elements();

        
        $this->template_instance = WadiQueries::getInstance();

        /**
            * Register Widgets
            */
        add_action('elementor/widgets/register', [ $this, 'widgets_area' ]);

        /**
         * Register Category
         */
        add_action('elementor/elements/categories_registered', [ $this, 'add_wadi_addons_categories']);

        /**
         *
         * Register Assets CSS
         *
         */

        add_action('elementor/editor/before_enqueue_styles', array( $this, 'enqueue_editor_styles' ));
        /**
         *
         * Register Assets JS
         *
         */

        add_action('elementor/editor/after_enqueue_scripts', array( $this, 'after_enqueue_scripts' ));

        // New things

        add_action('elementor/preview/enqueue_styles', array( $this, 'enqueue_preview_styles' ));

        add_action('elementor/frontend/after_register_styles', array( $this, 'register_frontend_styles' ));

        add_action('elementor/frontend/after_register_scripts', array( $this, 'register_frontend_scripts' ));

        add_action('elementor/controls/register', array( $this, 'add_wadi_controls' ));
    }

    /**
         *
         * Register Wadi Custom Controls
         *
         * @since 1.0.0
         *
         * @access public
         *
         */

    public function add_wadi_controls($control_manager)
    {
        require_once WADI_ADDONS_PATH . 'controls/class-wadi-taxonomies.php';
        require_once WADI_ADDONS_PATH . 'controls/class-wadi-currency.php';

        $control_manager->register(new WadiAddons\Controls\Wadi_Taxonomies());
        $control_manager->register(new \WadiAddons\Controls\Wadi_Currency());
    }

    /**
     * Register Frontend CSS files
     *
     * @since 2.9.0
     * @access public
     */
    public function register_frontend_styles()
    {
        // wp_register_style('pricing_table_css', WADI_ADDONS_URL . 'assets/dist/wadi-pricing-table.css', array(), time(), 'all');
    }

    /**
     * Registers required JS files
     *
     * @since 1.0.0
     * @access public
     */
    public function register_frontend_scripts()
    {
        // wp_register_script('pricing_table_js', WADI_ADDONS_URL . 'assets/dist/wadi-pricing-table.js', [ 'elementor-frontend', 'jquery', 'jquery-ui-draggable' ], '1.0.0', true);
    }

    // All Enqueue Things

    /**
     * Enqueue Preview CSS files
     *
     * @since 2.9.0
     * @access public
     */
    public function enqueue_preview_styles()
    {
        // wp_enqueue_style('pricing_table_css');

        // wp_enqueue_style( 'premium-woocommerce' );
    }

    	/**
	 * Load widgets require function
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function widgets_area() {
		$this->init_widgets();
	}


    public function init_widgets()
    {
        $enabled_elements = self::$modules;
        
        foreach ($enabled_elements as $key => $value) {


            $enabled = isset($enabled_elements[ $key ]) ? $enabled_elements[ $key ] : '';


            if (filter_var($enabled, FILTER_VALIDATE_BOOLEAN) || ! $enabled_elements) {
                $this->register_wadi_widget($key);
            }
        }
    }
    /**
     *
     * Register addon by file name.
     *
     * @access public
     *
     * @param  string $file File name.
     *
     * @return void
     */
    public function register_wadi_widget($base)
    {
        $widget_manager = Plugin::instance()->widgets_manager;

        // $base  = basename(str_replace('.php', '', $file));
        $class = ucwords(str_replace('-', ' ', $base));
        $class = str_replace(' ', '_', $class);
        $class = sprintf('WadiAddons\Widgets\%s', $class);

        if (class_exists($class, true)) {
            $widget_manager->register(new $class());
        }
    }


    /**
     * Register Widgets Category
     *
     * Register a new category for Wadi Addons widgets
     *
     * @since 1.0.0
     * @access public
     *
     * @param object $elements_manager elements manager.
     */
    public function add_wadi_addons_categories($elements_manager)
    {
        $elements_manager->add_category(
            'wadi-elements',
            array(
                    'title' => __('Wadi Addons', 'wadi-addons'),
                ),
            1
        );
    }
    /**
     * Loads plugin icons font
     *
     * @since 1.0.0
     * @access public
     * @return void
     */
    public function enqueue_editor_styles()
    {
        wp_enqueue_style(
            'wadi-editor-style',
            WADI_ADDONS_URL . 'assets/dist/main.css',
            array(),
            WADI_ADDONS_VERSION
        );

        // Wadi Editor Styling
        wp_enqueue_style(
			'pa-editor',
			WADI_ADDONS_URL . 'assets/dist/wadi-editor.css',
			array(),
			WADI_ADDONS_VERSION
		);
    }

    /**
    * Enqueue editor scripts
    *
    * @since 3.2.5
    * @access public
    */
    public function after_enqueue_scripts()
    {
        wp_enqueue_script(
            'wadi-editor-script',
            WADI_ADDONS_URL . 'assets/dist/main.js',
            array(),
            WADI_ADDONS_VERSION,
            true
        );

        wp_enqueue_script(
			'wadi-editor-alert-script',
			WADI_ADDONS_URL . 'assets/dist/wadi-editor.js',
			['elementor-editor', 'jquery'],
			WADI_ADDONS_VERSION,
			true
		);

        wp_localize_script(
			'wadi-editor-alert-script',
			'WadiProAlert',
			// 'PremiumPanelSettings',
			array(
				'wadiPro_installed' => Admin_Helper::check_wadi_addons_pro(),
				'wadiPro_widgets'   => Admin_Helper::get_pro_elements(),
			)
		);
    }


    /**
         *
         * Creates and returns an instance of the class
         *
         * @since 1.0.0
         * @access public
         *
         * @return object
         */
    public static function get_instance()
    {
        if (! isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
