<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Options;

use Yikes\LevelPlayingField\Roles\HiringManager;
use Yikes\LevelPlayingField\Roles\HumanResources;
use Yikes\LevelPlayingField\Exception\NoDefault;

/**
 * Class Options.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 *
 * @property string additional_email_recipients Email addresses that should receive applicant message emails.
 * @property array  email_recipient_roles       Roles whose members should receive applicant message emails.
 */
final class Options {

	const FIELDS = [
		OptionFields::ADDITIONAL_EMAIL_RECIPIENTS,
		OptionFields::EMAIL_RECIPIENT_ROLES,
	];

	const SANITIZATION = [
		OptionFields::ADDITIONAL_EMAIL_RECIPIENTS => FILTER_SANITIZE_STRING,
		OptionFields::EMAIL_RECIPIENT_ROLES       => FILTER_VALIDATE_BOOLEAN,
	];

	const DEFAULTS = [
		OptionFields::ADDITIONAL_EMAIL_RECIPIENTS => '',
		OptionFields::EMAIL_RECIPIENT_ROLES       => [
			HiringManager::SLUG  => false,
			HumanResources::SLUG => false,
		],
	];

	/**
	 * Optionally load all options on instantiation.
	 *
	 * @since %VERSION%
	 *
	 * @param bool $autoload True to load all options on instantiation.
	 */
	public function __construct( $autoload = false ) {
		if ( true === $autoload ) {
			$this->load();
		}
	}

	/**
	 * Load all options from the database.
	 *
	 * @since %VERSION%
	 */
	public function load() {
		foreach ( $this->get_options() as $key => $value ) {
			$this->$key = $value;
		}
	}

	/**
	 * Sanitize & save each option.
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
	 * Fetch a specific option from the database.
	 *
	 * @since %VERSION%
	 *
	 * @param string $option_name The name of the option, without the options prefix.
	 *
	 * @return mixed The option's value.
	 * @throws NoDefault When a default option has not been set for the option.
	 */
	public function get_option( $option_name ) {
		if ( ! isset( static::DEFAULTS[ $option_name ] ) ) {
			throw NoDefault::default_value( $option_name );
		}
		return get_option( $this->prefix_field( $option_name ), $this->get_default_value( $option_name ) );
	}

	/**
	 * Fetch all of our options from the database.
	 *
	 * @since %VERSION%
	 *
	 * @return array
	 */
	public function get_options() {
		return array_combine( static::FIELDS, array_map( [ $this, 'get_option' ], static::FIELDS ) );
	}

	/**
	 * Prefix an option name with our options prefix.
	 *
	 * @since %VERSION%
	 *
	 * @param string $option_name The option field name.
	 * @return string The prefixed field.
	 */
	private function prefix_field( $option_name ) {
		return OptionFields::OPTION_PREFIX . $option_name;
	}

	/**
	 * Get a default value for an option.
	 *
	 * @since %VERSION%
	 *
	 * @param string $option_name The option field name.
	 * @return mixed The default for the given option.
	 */
	private function get_default_value( $option_name ) {
		return static::DEFAULTS[ $option_name ];
	}
}
