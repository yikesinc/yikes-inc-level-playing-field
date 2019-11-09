<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings\Fields;

use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Settings\Setting;

/**
 * Class AdditionalEmailRecipients.
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class BaseSettingsField {

	const NAME = '_base_settings_field';

	/**
	 * The setting instance.
	 *
	 * @since 1.0.0
	 * @var Setting
	 */
	protected $setting;

	/**
	 * The setting's field's value.
	 *
	 * @var mixed $value A value for an setting field.
	 */
	public $value;

	/**
	 * BaseSettingsField constructor.
	 *
	 * @param Setting $setting The setting object for storage.
	 */
	public function __construct( Setting $setting ) {
		$this->setting = $setting;
	}

	/**
	 * Render the field.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		$this->description();
		$this->field();
		$this->help();
	}

	/**
	 * Render the field's HTML.
	 *
	 * @since 1.0.0
	 */
	abstract protected function field();

	/**
	 * Get the name constant for the class.
	 *
	 * @since 1.0.0
	 * @throws MustExtend When the NAME constant has not been extended.
	 */
	protected function get_name() {
		if ( self::NAME === static::NAME ) {
			throw MustExtend::default_name( self::NAME );
		}

		return static::NAME;
	}

	/**
	 * Get the value of the setting. We shouldn't need to instantiate the Settings class every time. We need to find a way to avoid that.
	 *
	 * @since 1.0.0
	 */
	protected function get_value() {
		return $this->setting->get();
	}

	/**
	 * Return the description text for this field, i18n'ed.
	 *
	 * @since 1.0.0
	 *
	 * @return string $description_text The description text for this field.
	 */
	protected function get_description_text() {
		return '';
	}

	/**
	 * Echo the description text for this field wrapped in HTML.
	 *
	 * @since 1.0.0
	 */
	protected function description() {
		$this->maybe_display_text( 'lpf-field-description', $this->get_description_text() );
	}

	/**
	 * Return the help text for this field, i18n'ed.
	 *
	 * @since 1.0.0
	 *
	 * @return string $help_text The help text for this field.
	 */
	protected function get_help_text() {
		return '';
	}

	/**
	 * Echo the help text for this field wrapped in HTML.
	 *
	 * @since 1.0.0
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
		return implode( ' ', array_filter( array_map( 'sanitize_html_class', [ 'settings-field', $this->get_name() ] ) ) );
	}

	/**
	 * Maybe display a paragraph of text with a given class.
	 *
	 * @since 1.0.0
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
