<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

/**
 * Trait PluginHelpers.
 *
 * Handle basic WordPress plugin variables like the plugin's path and URL.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait PluginHelpers {

	/**
	 * Get the root directory for the plugin.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_plugin_root() {
		return dirname( __DIR__ );
	}

	/**
	 * Get the url for the plugin.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_plugin_url() {
		return plugins_url( '', dirname( __FILE__ ) );
	}

	/**
	 * Is the new editor is enabled?
	 *
	 * Check if the register_block_type function exists to see if the new editor is available. Then check if the classic editor plugin is enabled to see if the editor is being disabled.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_new_editor_enabled() {
		return apply_filters( 'lpf_is_new_editor_enabled', function_exists( 'register_block_type' ) && ! class_exists( 'Classic_Editor' ) );
	}
}
