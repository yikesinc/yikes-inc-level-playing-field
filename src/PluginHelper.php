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
 * Trait PluginHelper.
 *
 * Handle basic WordPress plugin variables like the plugin's path and URL.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait PluginHelper {

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
	 * Get the version of the plugin.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_version() {
		return '2.0.0';
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
	 * Get the filename for the plugin.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_plugin_filename() {
		return 'yikes-level-playing-field.php';
	}

	/**
	 * Get the folder name for the plugin.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_plugin_folder_name() {
		$plugin_path   = rtrim( $this->get_plugin_root(), '/' );
		$plugin_path   = explode( '/', $plugin_path );
		$plugin_folder = end( $plugin_path );
		return $plugin_folder;
	}

	/**
	 * Get the full filepath for the plugin.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_plugin_filepath() {
		return trailingslashit( $this->get_plugin_root() ) . $this->get_plugin_filename();
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
		/**
		 * Filter whether the new editor is enabled.
		 *
		 * There are situations where the new editor is disabled even though our check returns true. This filter allows you to turn on/off our new editor functionality.
		 *
		 * @param bool $is_enabled.
		 *
		 * @return bool True if the new editor is enabled.
		 */
		return apply_filters( 'lpf_is_new_editor_enabled', function_exists( 'register_block_type' ) && ! class_exists( 'Classic_Editor' ) );
	}
}
