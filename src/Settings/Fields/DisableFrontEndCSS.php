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
use Yikes\LevelPlayingField\Shortcode\Application;
use Yikes\LevelPlayingField\Shortcode\BaseJobs;

/**
 * Class AdditionalEmailRecipients.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class DisableFrontEndCSS extends BaseSettingsField {

	const NAME = 'disable-front-end-css';
	const SLUG = SettingsFields::DISABLE_FRONT_END_CSS;

	/**
	 * Render the field's HTML.
	 *
	 * @since %VERSION%
	 */
	protected function field() {
		$disableable_css_files = [
			Application::CSS_HANDLE => __( 'Turn off Application Form Styles', 'yikes-level-playing-field' ),
			BaseJobs::CSS_HANDLE    => __( 'Turn off Job Listings Styles', 'yikes-level-playing-field' ),
		];
		foreach ( $disableable_css_files as $file_handle => $file_label ) :
			?>
			<label for="<?php echo esc_attr( $file_handle ); ?>">
				<input type="checkbox"
					class="<?php echo esc_attr( $this->html_classes() ); ?>"
					id="<?php echo esc_attr( $file_handle ); ?>"
					name="<?php echo esc_attr( static::NAME ); ?>"
					value="<?php echo esc_attr( $file_handle ); ?>"
					<?php checked( isset( $this->get_value()[ $file_handle ] ) && true === $this->get_value()[ $file_handle ] ); ?>
				/>
				<?php echo esc_html( $file_label ); ?>
			</label>
			<?php
		endforeach;
	}

	/**
	 * Return the description text for this field, i18n'ed.
	 *
	 * @since %VERSION%
	 *
	 * @return string $description_text The description text for this field.
	 */
	protected function description_text() {
		return __( "Turn off plugin styles to give your theme full control over the way forms and job listings look on your site.", 'yikes-level-playing-field' );
	}
}
