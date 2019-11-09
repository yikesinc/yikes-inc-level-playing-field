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
final class ApplicationSuccessMessage extends BaseSettingsField {

	const NAME = SettingsFields::APPLICATION_SUCCESS_MESSAGE;

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
		return __( 'Customize the message shown to applicants after they have successfully submitted an application by entering your own text below.', 'level-playing-field' );
	}

	/**
	 * Return the placeholder text for this field, i18n'ed.
	 *
	 * @since 1.0.0
	 *
	 * @return string $placeholder_text The placeholder text for this field.
	 */
	protected function placeholder_text() {
		return __( 'Thank you, your application has been successfully submitted.', 'level-playing-field' );
	}
}
