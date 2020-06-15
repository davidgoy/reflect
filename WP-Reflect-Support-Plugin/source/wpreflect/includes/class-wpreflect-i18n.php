<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/davidgoy/reflect
 * @since      1.0.0-beta.1
 *
 * @package    Wpreflect
 * @subpackage Wpreflect/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0-beta.1
 * @package    Wpreflect
 * @subpackage Wpreflect/includes
 * @author Min Tat Goy <david@davidgoy.dev> 
 */
class Wpreflect_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0-beta.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpreflect',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
