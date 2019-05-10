<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

use Yikes\LevelPlayingField\Exception\MustExtend;

/**
 * Class BaseSetting
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseSetting implements Setting {

	/**
	 * Whether the setting should be autoloaded.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $autoload = true;

	/**
	 * Whether the setting has been loaded.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $loaded = false;

	/**
	 * The setting prefix.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $prefix = 'lpf_settings_';

	/**
	 * The setting value.
	 *
	 * @since %VERSION%
	 * @var mixed
	 */
	protected $value;

	const SANITIZE = FILTER_SANITIZE_STRING;
	const SLUG     = '_baseslug_';

	/**
	 * Get the plugin value.
	 *
	 * @since %VERSION%
	 * @return mixed
	 */
	public function get() {
		$this->maybe_load();

		return $this->value;
	}

	/**
	 * Update the value of the setting.
	 *
	 * @since %VERSION%
	 *
	 * @param mixed $value The new value for the setting.
	 */
	public function update( $value ) {
		$new_value = $this->sanitize( $value );
		if ( $new_value === $this->get() ) {
			return;
		}

		update_option( $this->get_setting_name(), $new_value );
	}

	/**
	 * Delete the setting from the DB.
	 *
	 * @since %VERSION%
	 */
	public function delete() {
		delete_option( $this->get_setting_name() );
	}

	/**
	 * Maybe load the setting from the DB.
	 *
	 * @since %VERSION%
	 */
	protected function maybe_load() {
		if ( ! $this->loaded ) {
			$this->value  = get_option( $this->get_setting_name(), $this->get_default() );
			$this->loaded = true;
		}
	}

	/**
	 * Get the name of the setting from the DB.
	 *
	 * @since %VERSION%
	 * @return string
	 * @throws MustExtend When the default slug is not overridden.
	 */
	public function get_setting_name() {
		if ( static::SLUG === self::SLUG ) {
			throw MustExtend::default_slug( self::SLUG );
		}

		return $this->prefix . static::SLUG;
	}

	/**
	 * Get the default value for the setting.
	 *
	 * @since %VERSION%
	 * @return mixed
	 */
	protected function get_default() {
		return null;
	}

	/**
	 * Sanitize the setting value.
	 *
	 * @since %VERSION%
	 *
	 * @param mixed $value The value to sanitize.
	 *
	 * @return mixed The sanitized value.
	 */
	protected function sanitize( $value ) {
		return filter_var( $value, static::SANITIZE );
	}
}
