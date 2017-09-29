<?php
/**
 * AlainSchlesser.com Speaking Page Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      https://www.alainschlesser.com/
 * @copyright 2017 Alain Schlesser
 */

namespace Yikes\LevelPlayingField;

/**
 * Class PluginFactory
 *
 * @since   0.2.7
 *
 * @package Yikes\LevelPlayingField
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
final class PluginFactory {

	/**
	 * Create and return an instance of the plugin.
	 *
	 * This always returns a shared instance.
	 *
	 * @since 0.2.7
	 *
	 * @return Plugin Plugin instance.
	 */
	public static function create() {
		static $plugin = null;

		if ( null === $plugin ) {
			$plugin = new Plugin();
		}

		return $plugin;
	}
}
