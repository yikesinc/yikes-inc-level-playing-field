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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class BaseSetting implements Setting {

	/**
	 * Whether the setting should be autoloaded.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $autoload = true;

	/**
	 * Whether the setting has been loaded.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $loaded = false;

	/**
	 * The setting prefix.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $prefix = 'lpf_settings_';

	/**
	 * The setting value.
	 *
	 * @since 1.0.0
	 * @var mixed
	 */
	protected $value;

	const SANITIZE = FILTER_SANITIZE_STRING;
	const SLUG     = '_baseslug_';

	/**
	 * Get the plugin value.
	 *
	 * @since 1.0.0
	 * @return mixed
	 */
	public function get() {
		$this->maybe_load();

		return $this->value;
	}

	/**
	 * Update the value of the setting.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	public function delete() {
		delete_option( $this->get_setting_name() );
	}

	/**
	 * Maybe load the setting from the DB.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return mixed
	 */
	protected function get_default() {
		return null;
	}

	/**
	 * Sanitize the setting value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to sanitize.
	 *
	 * @return mixed The sanitized value.
	 */
	protected function sanitize( $value ) {
		return filter_var( $value, static::SANITIZE );
	}
}
