<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Options\Fields;

use Yikes\LevelPlayingField\Options\OptionFields;

/**
 * Class AdditionalEmailRecipients.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class AdditionalEmailRecipients extends BaseOptionsField {

	const ID   = 'additional-email-recipients';
	const NAME = 'additional-email-recipients';
	const SLUG = OptionFields::ADDITIONAL_EMAIL_RECIPIENTS;

	/**
	 * Render the field's HTML.
	 *
	 * @since %VERSION%
	 */
	protected function field() {
		?>
		<textarea cols="80" rows="4" class="settings-field" id="<?php echo esc_attr( static::ID ); ?>" name="<?php echo esc_attr( static::NAME ); ?>" placeholder="<?php echo esc_attr( $this->placeholder_text() ); ?>"><?php echo esc_html( $this->get_value() ); ?></textarea>
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
		return __( 'These email addresses will receive notifications when an applicant sends a message.', 'yikes-level-playing-field' );
	}

	/**
	 * Return the help text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $help_text The help text for this field.
	 */
	protected function help_text() {
		return __( 'Please separate email addresses with a comma.', 'yikes-level-playing-field' );
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
