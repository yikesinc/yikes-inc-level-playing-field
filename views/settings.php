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

use Yikes\LevelPlayingField\Settings\Fields\AdditionalEmailRecipients;
use Yikes\LevelPlayingField\Settings\Fields\EmailRecipientRoles;

$settings = $this->settings;

?>
<div id="lpf-settings">
	<h2><?php esc_html_e( 'Settings', 'yikes-level-playing-field' ); ?></h2>

	<div id="notice-container"></div>

	<h3><?php esc_html_e( 'Email', 'yikes-level-playing-field' ); ?></h3>

	<div class="lpf-settings lpf-settings-settings-container">

		<?php
			( new AdditionalEmailRecipients() )->render( $settings->get_setting( AdditionalEmailRecipients::SLUG ) );
			( new EmailRecipientRoles() )->render( $settings->get_setting( EmailRecipientRoles::SLUG ) );
		?>

		<div class="lpf-settings-save">
			<button type="button" class="button button-primary lpf-button-primary" id="lpf-settings-save"><?php esc_html_e( 'Save Settings', 'yikes-level-playing-field' ); ?></button>
		</div>
	</div>	
</div>
