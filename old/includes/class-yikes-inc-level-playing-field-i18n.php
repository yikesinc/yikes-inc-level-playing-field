<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 * @author     YIKES, Inc. <plugins@yikesinc.com>
 */
class Yikes_Inc_Level_Playing_Field_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'yikes-inc-level-playing-field',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
