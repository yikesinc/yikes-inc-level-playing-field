<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings\Fields;

use Yikes\LevelPlayingField\Settings\Settings;

/**
 * Class AdditionalEmailRecipients.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseSettingsField {

	/**
	 * The setting's field's value.
	 *
	 * @var mixed $value A value for an setting field.
	 */
	public $value;

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 * @param mixed $value Settingally pass the setting field's value when rendering the field.
	 */
	public function render( $value = false ) {

		// If we have the value already, store it. Otherwise we'll fetch it when we need it.
		if ( false !== $value ) {
			$this->value = $value;
		}

		$this->description();
		$this->field();
		$this->help();
	}

	/**
	 * Render the field's HTML.
	 *
	 * @since %VERSION%
	 */
	abstract protected function field();

	/**
	 * Get the value of the setting. We shouldn't need to instantiate the Settings class every time. We need to find a way to avoid that.
	 *
	 * @since %VERSION%
	 */
	protected function get_value() {
		return $this->value ? $this->value : ( new Settings() )->get_setting( static::SLUG );
	}

	/**
	 * Return the description text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $description_text The description text for this field.
	 */
	protected function get_description_text() {
		return '';
	}

	/**
	 * Echo the description text for this field wrapped in HTML.
	 *
	 * @since %VERSION%
	 */
	protected function description() {
		$this->maybe_display_text( 'lpf-field-description', $this->get_description_text() );
	}

	/**
	 * Return the help text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $help_text The help text for this field.
	 */
	protected function get_help_text() {
		return '';
	}

	/**
	 * Echo the help text for this field wrapped in HTML.
	 *
	 * @since %VERSION%
	 */
	protected function help() {
		$this->maybe_display_text( 'lpf-field-help', $this->get_help_text() );
	}

	/**
	 * Get the default class names, i.e. `settings-field` and the field's ID.
	 *
	 * @return string $class_names The classes, sanitized.
	 */
	protected function html_classes() {
		// Sanitize class names, remove empties, join each with a space, and then remove the trailing space.
		return rtrim( implode( ' ', array_filter( array_map( 'sanitize_html_class', [ 'settings-field', static::NAME ] ) ) ) );
	}

	/**
	 * Maybe display a paragraph of text with a given class.
	 *
	 * @since %VERSION%
	 *
	 * @param string $class HTML class for the text.
	 * @param string $text  The text to display.
	 */
	protected function maybe_display_text( $class, $text ) {
		if ( empty( $text ) ) {
			return;
		}

		printf( '<p class="%1$s">%2$s</p>', esc_attr( $class ), esc_html( $text ) );
	}
}
