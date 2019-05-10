<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Settings\AdditionalEmailRecipients as AERSetting;
use Yikes\LevelPlayingField\Settings\ApplicationSuccessMessage as ASMSetting;
use Yikes\LevelPlayingField\Settings\DisableFrontEndCss as DFECSetting;
use Yikes\LevelPlayingField\Settings\EmailRecipientRoles as ERRSetting;
use Yikes\LevelPlayingField\Settings\Fields\AdditionalEmailRecipients;
use Yikes\LevelPlayingField\Settings\Fields\ApplicationSuccessMessage;
use Yikes\LevelPlayingField\Settings\Fields\DisableFrontEndCSS;
use Yikes\LevelPlayingField\Settings\Fields\EmailRecipientRoles;
use Yikes\LevelPlayingField\View\View;

?>
<div id="lpf-settings" class="wrap lpf-page settings-page">
	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Level Playing Field Settings', 'yikes-level-playing-field' ); ?>
	</h1>

	<div id="notice-container"></div>

	<div class="lpf-settings lpf-settings-settings-container">
		<h2>
			<span class="dashicons dashicons-email"></span>
			<?php esc_html_e( 'Email Settings', 'yikes-level-playing-field' ); ?>
		</h2>
		<?php
			( new AdditionalEmailRecipients( new AERSetting() ) )->render();
			( new EmailRecipientRoles( new ERRSetting() ) )->render();
		?>
	</div>

	<hr>

	<div class="lpf-settings lpf-settings-settings-container">
		<h2>
			<span class="dashicons dashicons-feedback"></span>
			<?php esc_html_e( 'Application Settings', 'yikes-level-playing-field' ); ?>
		</h2>
		<?php
			( new ApplicationSuccessMessage( new ASMSetting() ) )->render();
		?>
	</div>

	<hr>

	<div class="lpf-settings lpf-settings-settings-container">
		<h2>
			<span class="dashicons dashicons-art"></span>
			<?php esc_html_e( 'Appearance Settings', 'yikes-level-playing-field' ); ?>
		</h2>
		<?php
			( new DisableFrontEndCSS( new DFECSetting() ) )->render();
		?>
	</div>

	<hr>

	<?php
	/**
	 * Triggered after the built-in settings.
	 *
	 * @param View $view The current view object.
	 */
	do_action( 'lpf_settings_page', $this );
	?>

	<div class="lpf-settings lpf-settings-settings-container">
		<div class="lpf-settings-save">
			<button type="button" class="button button-primary lpf-button-primary" id="lpf-settings-save">
				<?php esc_html_e( 'Save Settings', 'yikes-level-playing-field' ); ?>
			</button>
		</div>
	</div>
</div>
