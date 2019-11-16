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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
trait PluginHelper {

	/**
	 * Get the main plugin file.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_main_file() {
		return "{$this->get_root_dir()}/{$this->get_main_filename()}";
	}

	/**
	 * Get the root directory for the plugin.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_root_dir() {
		return dirname( __DIR__ );
	}

	/**
	 * Get the version of the plugin.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_version() {
		return '1.0.1';
	}

	/**
	 * Get the url for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $path The URL path.
	 *
	 * @return string
	 */
	protected function get_plugin_url( $path = '' ) {
		return plugins_url( $path, $this->get_main_file() );
	}

	/**
	 * Get the filename for the plugin.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_main_filename() {
		return 'level-playing-field.php';
	}

	/**
	 * Get the WordPress plugin name for this plugin.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_basename() {
		return plugin_basename( $this->get_main_file() );
	}

	/**
	 * Is the new editor is enabled?
	 *
	 * Check if the register_block_type function exists to see if the new editor is available. Then check if the classic editor plugin is enabled to see if the editor is being disabled.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	protected function is_new_editor_enabled() {
		/**
		 * Filter whether the new editor is enabled.
		 *
		 * There are situations where the new editor is disabled even though our check returns true. This filter allows you to turn on/off our new editor functionality.
		 *
		 * @param bool $is_enabled.
		 *
		 * @return bool True if the new editor is enabled.
		 */
		return apply_filters(
			'lpf_is_new_editor_enabled',
			function_exists( 'register_block_type' ) && ! class_exists( 'Classic_Editor' )
		);
	}
}
