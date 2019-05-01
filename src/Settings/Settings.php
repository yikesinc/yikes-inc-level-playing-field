<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

use Yikes\LevelPlayingField\Roles\HiringManager;
use Yikes\LevelPlayingField\Roles\HumanResources;
use Yikes\LevelPlayingField\Exception\NoDefault;
use Yikes\LevelPlayingField\Shortcode\Application;
use Yikes\LevelPlayingField\Shortcode\BaseJobs;

/**
 * Class Settings.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 *
 * @property string additional_email_recipients Email addresses that should receive applicant message emails.
 * @property array  email_recipient_roles       Roles whose members should receive applicant message emails.
 */
final class Settings {

	const FIELDS = [
		SettingsFields::ADDITIONAL_EMAIL_RECIPIENTS,
		SettingsFields::EMAIL_RECIPIENT_ROLES,
		SettingsFields::APPLICATION_SUCCESS_MESSAGE,
		SettingsFields::DISABLE_FRONT_END_CSS,
	];

	const SANITIZATION = [
		SettingsFields::ADDITIONAL_EMAIL_RECIPIENTS => FILTER_SANITIZE_STRING,
		SettingsFields::EMAIL_RECIPIENT_ROLES       => FILTER_VALIDATE_BOOLEAN,
		SettingsFields::APPLICATION_SUCCESS_MESSAGE => FILTER_SANITIZE_STRING,
		SettingsFields::DISABLE_FRONT_END_CSS       => FILTER_VALIDATE_BOOLEAN,
	];

	const DEFAULTS = [
		SettingsFields::ADDITIONAL_EMAIL_RECIPIENTS => '',
		SettingsFields::EMAIL_RECIPIENT_ROLES       => [
			HiringManager::SLUG  => false,
			HumanResources::SLUG => false,
		],
		SettingsFields::APPLICATION_SUCCESS_MESSAGE => 'Thank you, your application has been successfully submitted.',
		SettingsFields::DISABLE_FRONT_END_CSS       => [
			Application::CSS_HANDLE => false,
			BaseJobs::CSS_HANDLE    => false,
		],
	];

	/**
	 * Settingally load all settings on instantiation.
	 *
	 * @since %VERSION%
	 *
	 * @param bool $autoload True to load all settings on instantiation.
	 */
	public function __construct( $autoload = false ) {
		if ( true === $autoload ) {
			$this->load();
		}
	}

	/**
	 * Load all settings from the database.
	 *
	 * @since %VERSION%
	 */
	public function load() {
		foreach ( $this->get_settings() as $key => $value ) {
			$this->$key = $value;
		}
	}

	/**
	 * Sanitize & save each setting.
	 *
	 * @since %VERSION%
	 */
	public function save() {
		foreach ( static::FIELDS as $field_name ) {
			update_option(
				$this->prefix_field( $field_name ),
				is_array( $this->$field_name )
					? filter_var_array( $this->$field_name, static::SANITIZATION[ $field_name ] )
					: filter_var( $this->$field_name, static::SANITIZATION[ $field_name ] )
			);
		}
	}

	/**
	 * Fetch a specific setting from the database.
	 *
	 * @since %VERSION%
	 *
	 * @param string $setting_name The name of the setting, without the settings prefix.
	 *
	 * @return mixed The setting's value.
	 * @throws NoDefault When a default setting has not been set for the setting.
	 */
	public function get_setting( $setting_name ) {
		if ( ! isset( static::DEFAULTS[ $setting_name ] ) ) {
			throw NoDefault::default_value( $setting_name );
		}
		return get_option( $this->prefix_field( $setting_name ), $this->get_default_value( $setting_name ) );
	}

	/**
	 * Fetch all of our settings from the database.
	 *
	 * @since %VERSION%
	 *
	 * @return array
	 */
	public function get_settings() {
		return array_combine( static::FIELDS, array_map( [ $this, 'get_setting' ], static::FIELDS ) );
	}

	/**
	 * Prefix an setting name with our settings prefix.
	 *
	 * @since %VERSION%
	 *
	 * @param string $setting_name The setting field name.
	 * @return string The prefixed field.
	 */
	private function prefix_field( $setting_name ) {
		return SettingsFields::OPTION_PREFIX . $setting_name;
	}

	/**
	 * Get a default value for an setting.
	 *
	 * @since %VERSION%
	 *
	 * @param string $setting_name The setting field name.
	 * @return mixed The default for the given setting.
	 */
	private function get_default_value( $setting_name ) {
		return static::DEFAULTS[ $setting_name ];
	}
}
