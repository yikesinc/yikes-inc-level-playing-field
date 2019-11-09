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
final class DeleteOnUninstall extends BaseSettingsField {

	const NAME = SettingsFields::DELETE_ON_UNINSTALL;

	/**
	 * Render the field's HTML.
	 *
	 * @since 1.0.0
	 */
	protected function field() {
		?>
		<label for="<?php echo esc_attr( $this->get_name() ); ?>">
			<input type="checkbox"
				class="<?php echo esc_attr( $this->html_classes() ); ?>"
				id="<?php echo esc_attr( $this->get_name() ); ?>"
				name="<?php echo esc_attr( $this->get_name() ); ?>"
				value="1"
				<?php checked( true === boolval( $this->get_value() ) ); ?>
			/>
			<?php echo esc_html__( 'Delete Data on Uninstall', 'level-playing-field' ); ?>
		</label>
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
		return __( 'Delete plugin data, such as pages, messages, applications, applicants, and jobs, when the plugin is uninstalled.', 'level-playing-field' );
	}
}
