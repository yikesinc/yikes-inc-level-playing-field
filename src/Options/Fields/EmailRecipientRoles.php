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
use Yikes\LevelPlayingField\Roles\HiringManager;
use Yikes\LevelPlayingField\Roles\HumanResources;

/**
 * Class AdditionalEmailRecipients.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class EmailRecipientRoles extends BaseOptionsField {

	const NAME = 'email-recipient-roles';
	const SLUG = OptionFields::EMAIL_RECIPIENT_ROLES;

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
	 * @return string $help_text The help text for this field.
	 */
	protected function help_text() {
		return __( 'The email will be sent to all users with the selected role.', 'yikes-level-playing-field' );
	}
}
