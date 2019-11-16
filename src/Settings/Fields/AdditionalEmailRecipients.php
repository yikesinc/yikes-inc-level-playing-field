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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class AdditionalEmailRecipients extends BaseSettingsField {

	const NAME = SettingsFields::ADDITIONAL_EMAIL_RECIPIENTS;

	/**
	 * Render the field's HTML.
	 *
	 * @since 1.0.0
	 */
	protected function field() {
		?>
		<textarea cols="80"
			rows="4"
			class="<?php echo esc_attr( $this->html_classes() ); ?>"
			id="<?php echo esc_attr( $this->get_name() ); ?>"
			name="<?php echo esc_attr( $this->get_name() ); ?>"
			placeholder="<?php echo esc_attr( $this->placeholder_text() ); ?>"
		><?php echo esc_html( $this->get_value() ); ?></textarea>
		<?php
	}

	/**
	 * Return the description text for this field, i18n'ed.
	 *
	 * @since 1.0.0
	 *
	 * @return string $description_text The description text for this field.
	 */
	protected function get_description_text() {
		return __( 'Enter email addresses below of people to receive email notifications when an applicant sends a message. Separate multiple email addresses with commas.', 'level-playing-field' );
	}

	/**
	 * Return the placeholder text for this field, i18n'ed.
	 *
	 * @since 1.0.0
	 *
	 * @return string $placeholder_text The placeholder text for this field.
	 */
	protected function placeholder_text() {
		return __( 'admin@example.com, jobmanager@example.com', 'level-playing-field' );
	}
}
