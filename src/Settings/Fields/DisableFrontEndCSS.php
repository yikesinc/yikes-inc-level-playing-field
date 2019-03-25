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
final class DisableFrontEndCSS extends BaseSettingsField {

	const NAME  = 'disable-front-end-css';
	const SLUG  = SettingsFields::DISABLE_FRONT_END_CSS;
	const LABEL = 'Turn off CSS for front-end forms';

	/**
	 * Render the field's HTML.
	 *
	 * @since %VERSION%
	 */
	protected function field() {
		?>
		<label for="<?php echo esc_attr( static::SLUG ); ?>">
			<input type="checkbox"
				class="<?php echo esc_attr( $this->html_classes() ); ?>"
				id="<?php echo esc_attr( static::SLUG ); ?>"
				name="<?php echo esc_attr( static::NAME ); ?>"
				value="1"
				<?php checked( $this->get_value(), '1' ); ?>>
			<?php echo esc_html( static::LABEL ); ?>
		</label>
		<?php
	}

	/**
	 * Return the description text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $description_text The help text for this field.
	 */
	protected function description_text() {
		return __( "Turn off plugin styles to give your theme full control over the styling of forms.", 'yikes-level-playing-field' );
	}
}
