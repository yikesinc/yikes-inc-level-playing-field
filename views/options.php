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

use Yikes\LevelPlayingField\Roles\HiringManager;
use Yikes\LevelPlayingField\Roles\HumanResources;
use Yikes\LevelPlayingField\Options\Fields\AdditionalEmailRecipients;
use Yikes\LevelPlayingField\Options\Fields\EmailRecipientRoles;

$options = $this->options;

?>
<div id="lpf-options">
	<h1><?php esc_html_e( 'Level Playing Field | Options', 'yikes-level-playing-field' ); ?></h1>

	<div id="notice-container"></div>

	<h2><?php esc_html_e( 'Email', 'yikes-level-playing-field' ); ?></h2>

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
