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

<div class="wrap lpf-page gopro-page">
	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'Level Playing Field Pro', 'yikes-level-playing-field' ); ?>
	</h1>

	<div class="lpf-page-content">
		<p>
			<a class="button button-primary" href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank">
				<?php esc_html_e( 'Upgrade to Pro', 'yikes-level-playing-field' ); ?>
			</a>
		</p>

		<h2>
			<a href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank">
				<?php esc_html_e( 'Upgrade to Level Playing Field Pro for unlimited job listings and enhanced features.', 'yikes-level-playing-field' ); ?>
			</a>
		</h2>

		<a href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank">
			<img src="<?php echo esc_url( ( new MediaAsset() )->get_image( MediaAsset::PRO ) ); ?>" class="lpf-pro-img">
		</a>

		<h3>Level Playing Field Pro Features</h3>

		<ul class="gopro-page-list">
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Unlimited Job Listings.', 'yikes-level-playing-field' ); ?>
			</li>

			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'More detailed Job Listings.', 'yikes-level-playing-field' ); ?>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Responsibilities', 'yikes-level-playing-field' ); ?>
						<ul>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Responsibilities', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Schedule', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Requirements', 'yikes-level-playing-field' ); ?></li>
						</ul>
					</li>
				</ul>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Qualifications', 'yikes-level-playing-field' ); ?>
						<ul>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Qualifications', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Education', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Experience', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Knowledge, Skills and Abilities', 'yikes-level-playing-field' ); ?></li>
						</ul>
					</li>
				</ul>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Compensation', 'yikes-level-playing-field' ); ?></li>
				</ul>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Company/Organization Details', 'yikes-level-playing-field' ); ?>
						<ul>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Name', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Description', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Logo', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Website', 'yikes-level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Social Links', 'yikes-level-playing-field' ); ?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'More Robust Applicant Functionality.', 'yikes-level-playing-field' ); ?>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Ability to add custom questions to application forms', 'yikes-level-playing-field' ); ?></li>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Ability to export applicants to CSV File', 'yikes-level-playing-field' ); ?></li>
				</ul>
			</li>
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Premium Support.', 'yikes-level-playing-field' ); ?>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Prioritized one-on-one support', 'yikes-level-playing-field' ); ?></li>
				</ul>
			</li>
		</ul>

	</div>
</div>
