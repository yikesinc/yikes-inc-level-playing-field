<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>

<div class="wrap lpf-page support-page">

	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Level Playing Field Support', 'level-playing-field' ); ?>
	</h1>

	<div class="lpf-page-content lpf-support-page-content">
		<div class="cptpro-settings cptpro-settings-support-help-container">
			<p>
				<?php esc_html_e( 'Before submitting a support request, please visit our Knowledge Base where we have step-by-step guides and troubleshooting help.', 'level-playing-field' ); ?> <a href="https://yikesplugins.com/support/knowledge-base/product/level-playing-field/" target="_blank"><?php esc_html_e( 'Visit the Level Playing Field Knowledge Base', 'level-playing-field' ); ?></a>
			</p>

			<p>
				<?php esc_html_e( 'Level Playing Field Pro users qualify for premium support.', 'level-playing-field' ); ?> <a href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank"><?php esc_html_e( 'Check out Level Playing Field Pro!', 'level-playing-field' ); ?></a>
			</p>

			<hr />


			<h2>
				<span class="dashicons dashicons-wordpress-alt"></span> <?php esc_html_e( 'WordPress.org Support Forums', 'level-playing-field' ); ?>
			</h2>

			<p>
				<?php esc_html_e( 'If you need help, please post questions to our support forum on the WordPress Plugin Directory.', 'level-playing-field' ); ?> <a href="https://wordpress.org/support/plugin/level-playing-field#new-post" target="_blank">
					<?php esc_html_e( 'Go to the free Support Forum on WordPress.org', 'level-playing-field' ); ?>
				</a>
			</p>

			<p>
				<a class="button button-primary" href="https://wordpress.org/support/plugin/level-playing-field#new-post" target="_blank">
					<?php esc_html_e( 'Submit a Support Request', 'level-playing-field' ); ?>
				</a>
			</p>
		</div>
	</div>
</div>
