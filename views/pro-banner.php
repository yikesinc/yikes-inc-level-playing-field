<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Assets\MediaAsset;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>

<!-- Banners -->
<div class="banners-inner">
	<div class="banners-pro-container">

		<a href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank">
			<img src="<?php echo esc_url( ( new MediaAsset() )->get_image( MediaAsset::PROH ) ); ?>" class="lpf-pro-img" alt="Level Playing Field Pro">
		</a>

		<p>
			<?php esc_html_e( 'Upgrade to Level Playing Field Pro for great additional features including:', 'level-playing-field' ); ?>
		</p>

		<ul class="banners-pro-list">
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Unlimited Job Listings.', 'level-playing-field' ); ?>
			</li>
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'More Detailed Job Listings.', 'level-playing-field' ); ?>
			</li>
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Custom Application Form Questions.', 'level-playing-field' ); ?>
			</li>
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Applicant export.', 'level-playing-field' ); ?>
			</li>
		</ul>

		<p>
			<a class="button button-primary" href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank">
				<?php esc_html_e( 'Upgrade to Pro', 'level-playing-field' ); ?>
			</a>
		</p>
	</div>
</div>
