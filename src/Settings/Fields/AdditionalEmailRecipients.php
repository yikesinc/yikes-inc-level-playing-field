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
final class AdditionalEmailRecipients extends BaseSettingsField {

	const NAME = 'additional-email-recipients';
	const SLUG = SettingsFields::ADDITIONAL_EMAIL_RECIPIENTS;

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
	protected function description_text() {
		return __( 'Enter email addresses below of people to receive email notifications when an applicant sends a message. Separate multiple email addresses with commas.', 'yikes-level-playing-field' );
	}

	/**
	 * Return the placeholder text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $placeholder_text The placeholder text for this field.
	 */
	protected function placeholder_text() {
		return __( 'admin@example.com, jobmanager@example.com', 'yikes-level-playing-field' );
	}
}
