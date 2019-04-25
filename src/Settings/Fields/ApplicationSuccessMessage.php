<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings\Fields;

use Yikes\LevelPlayingField\Settings\SettingsFields;

/**
 * Class AdditionalEmailRecipients.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class ApplicationSuccessMessage extends BaseSettingsField {

	const NAME = 'application-success-message';
	const SLUG = SettingsFields::APPLICATION_SUCCESS_MESSAGE;

	/**
	 * Render the field's HTML.
	 *
	 * @since %VERSION%
	 */
	protected function field() {
		?>
		<textarea cols="80"
			rows="4"
			class="<?php echo esc_attr( $this->html_classes() ); ?>"
			id="<?php echo esc_attr( static::NAME ); ?>"
			name="<?php echo esc_attr( static::NAME ); ?>"
			placeholder="<?php echo esc_attr( $this->placeholder_text() ); ?>"
		><?php echo esc_html( $this->get_value() ); ?></textarea>
		<?php
	}

	/**
	 * Return the description text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $description_text The description text for this field.
	 */
	protected function get_description_text() {
		return __( 'Customize the message shown to applicants after they have submitted an application by entering your own text below.', 'yikes-level-playing-field' );
	}

	/**
	 * Return the placeholder text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $placeholder_text The placeholder text for this field.
	 */
	protected function placeholder_text() {
		return __( 'Thank you. Your application form has been successfully submitted.', 'yikes-level-playing-field' );
	}
}
