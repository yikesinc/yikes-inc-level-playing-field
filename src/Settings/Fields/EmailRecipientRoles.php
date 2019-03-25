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
use Yikes\LevelPlayingField\Roles\HiringManager;
use Yikes\LevelPlayingField\Roles\HumanResources;

/**
 * Class AdditionalEmailRecipients.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class EmailRecipientRoles extends BaseSettingsField {

	const NAME = 'email-recipient-roles';
	const SLUG = SettingsFields::EMAIL_RECIPIENT_ROLES;

	/**
	 * Render the field's HTML.
	 *
	 * @since %VERSION%
	 */
	protected function field() {
		$roles = [
			new HiringManager(),
			new HumanResources(),
		];
		foreach ( $roles as $role ) {
			?>
			<label for="<?php echo esc_attr( $role::SLUG ); ?>">
				<input type="checkbox"
					class="<?php echo esc_attr( $this->html_classes() ); ?>"
					id="<?php echo esc_attr( $role::SLUG ); ?>"
					name="<?php echo esc_attr( static::NAME ); ?>"
					value="<?php echo esc_attr( $role::SLUG ); ?>"
					<?php checked( isset( $this->get_value()[ $role::SLUG ] ) && true === $this->get_value()[ $role::SLUG ] ); ?>>
				<?php echo esc_html( $role->get_title() ); ?>
			</label>
			<?php
		}
	}

	/**
	 * Return the help text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $description_text The help text for this field.
	 */
	protected function description_text() {
		return __( 'Check off the user roles you want notification emails sent to. All users assigned to those roles will receive notifications.', 'yikes-level-playing-field' );
	}
}
