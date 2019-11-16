<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

use JsonSerializable;
use Yikes\LevelPlayingField\Uninstallable;
use Yikes\LevelPlayingField\Exception\InvalidClass;
use Yikes\LevelPlayingField\Exception\InvalidKey;
use Yikes\LevelPlayingField\Exception\NoDefault;

/**
 * Class Settings.
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class Settings implements JsonSerializable, Uninstallable {

	/**
	 * Array of available settings.
	 *
	 * @since 1.0.0
	 * @var Setting[]
	 */
	private $settings = [];

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		$this->settings = $this->get_available_settings();
	}

	/**
	 * Get available settings to use.
	 *
	 * @todo These should be injected via the constructor rather than hardcoded.
	 *
	 * @since 1.0.0
	 * @return Setting[] Array of setting objects.
	 * @throws InvalidClass When the Setting interface isn't implemented.
	 */
	private function get_available_settings() {
		/**
		 * Filter the available settings.
		 *
		 * @param array $settings Array of setting IDs and their handlers.
		 */
		$settings = (array) apply_filters( 'lpf_available_settings', [
			SettingsFields::ADDITIONAL_EMAIL_RECIPIENTS => AdditionalEmailRecipients::class,
			SettingsFields::EMAIL_RECIPIENT_ROLES       => EmailRecipientRoles::class,
			SettingsFields::APPLICATION_SUCCESS_MESSAGE => ApplicationSuccessMessage::class,
			SettingsFields::DISABLE_FRONT_END_CSS       => DisableFrontEndCss::class,
			SettingsFields::DELETE_ON_UNINSTALL         => DeleteOnUninstall::class,
		] );

		// Instantiate and validate settings.
		foreach ( $settings as $id => $class ) {
			$instance = new $class();
			if ( ! $instance instanceof Setting ) {
				throw InvalidClass::from_interface( $class, Setting::class );
			}

			$settings[ $id ] = $instance;
		}

		return $settings;
	}

	/**
	 * Set one of our settings.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name  The setting name.
	 * @param mixed  $value The setting value.
	 *
	 * @throws InvalidKey When the setting name is not recognized.
	 */
	public function __set( $name, $value ) {
		if ( ! isset( $this->settings[ $name ] ) ) {
			throw InvalidKey::not_found( $name, __METHOD__ );
		}

		$this->settings[ $name ]->update( $value );
	}

	/**
	 * Utilized for reading data from inaccessible members.
	 *
	 * @param string $name The setting name.
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		return $this->get_setting( $name );
	}

	/**
	 * Fetch a specific setting from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param string $setting_name The name of the setting, without the settings prefix.
	 *
	 * @return mixed The setting's value.
	 * @throws NoDefault When a default setting has not been set for the setting.
	 */
	public function get_setting( $setting_name ) {
		if ( ! isset( $this->settings[ $setting_name ] ) ) {
			throw InvalidKey::not_found( $setting_name, __METHOD__ );
		}

		return $this->settings[ $setting_name ]->get();
	}

	/**
	 * Method for serializing the object to JSON.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function jsonSerialize() {
		$return = [];
		foreach ( $this->settings as $id => $instance ) {
			$return[ $id ] = $instance->get();
		}

		return $return;
	}

	/**
	 * Delete all options.
	 */
	public function uninstall() {
		foreach ( $this->settings as $instance ) {
			$instance->delete();
		}
	}
}
