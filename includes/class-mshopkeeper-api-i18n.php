<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link        https://thangdangblog.com/
 * @since      1.0.0
 *
 * @package    Mshopkeeper_Api
 * @subpackage Mshopkeeper_Api/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mshopkeeper_Api
 * @subpackage Mshopkeeper_Api/includes
 * @author     Đặng Quốc Thắng <thangdangblog@gmail.com>
 */
class Mshopkeeper_Api_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mshopkeeper-api',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
