<?php
/**
 * Class: Wadi_Posts
 * Name:  Wadi Posts
 * Slug:  wadi-posts-filter
 */

namespace WadiAddons\Controls;

use WadiAddons\Includes\WadiQueries;

use Elementor\Control_Select2;
use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Premium Post Filter extended from Elementor Select Control
 *
 * @since 4.3.3
 */
class Wadi_Posts extends Control_Select2 {

    const TYPE = 'wadi-posts-filter';

    /**
	 * Returns the type of the control
	 */
    public function get_type()
    {
        return self::TYPE;
    }


    	/**
	 * Enqueue control scripts and styles.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue() {

        wp_register_script(
            'wadi-posts-editor-script',
            WADI_ADDONS_URL . 'assets/src/posts-editor.js',
            array( 'jquery'),
            WADI_ADDONS_VERSION
        );
		wp_enqueue_script( 'wadi-posts-editor-script' );

        // wp_localize_script(
        //     'wadi-posts-editor-script',
        //     'WadiAddons',
        //     array(
        //         'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
        //         'nonce'   => wp_create_nonce( 'wadi-nonce' ),
        //     )
        // );

	}


}
