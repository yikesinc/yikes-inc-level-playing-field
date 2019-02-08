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

use Yikes\LevelPlayingField\Options\Fields\AdditionalEmailRecipients;
use Yikes\LevelPlayingField\Options\Fields\EmailRecipientRoles;
use Yikes\LevelPlayingField\AdminPage\TabbedPage;

$options = $this->options;

?>
<div id="lpf-options">
	<h2><?php esc_html_e( 'Options', 'yikes-level-playing-field' ); ?></h2>

	<div id="notice-container"></div>

	<h3><?php esc_html_e( 'Email', 'yikes-level-playing-field' ); ?></h3>

	<div class="lpf-options lpf-options-options-container">

		<?php
			( new AdditionalEmailRecipients() )->render( $options->get_option( AdditionalEmailRecipients::SLUG ) );
			( new EmailRecipientRoles() )->render( $options->get_option( EmailRecipientRoles::SLUG ) );
		?>

		<div class="lpf-options-save">
			<button type="button" class="button button-primary lpf-button-primary" id="lpf-options-save"><?php esc_html_e( 'Save Options', 'yikes-level-playing-field' ); ?></button>
		</div>
	</div>	
</div>
