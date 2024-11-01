<?php
/**
 * Class: Wadi_Taxonomies
 * Name:  Wadi Taxonomies
 * Slug:  wadi-tax-filter
 */

namespace WadiAddons\Controls;

use WadiAddons\Includes\WadiQueries;

use Elementor\Control_Select;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Premium Post Filter extended from Elementor Select Control
 *
 * @since 4.3.3
 */
class Wadi_Taxonomies extends Control_Select {

	const TYPE = 'wadi-tax-filter';

	/**
	 * Returns the type of the control
	 */
	public function get_type() {
		return self::TYPE;
	}

	// public static function get_post_type_taxonomies() {
	// 	return WadiQueries::get_taxnomies('post');
	// }



}


