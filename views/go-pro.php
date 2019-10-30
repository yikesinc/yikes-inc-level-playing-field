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
		<?php esc_html_e( 'Level Playing Field Pro', 'level-playing-field' ); ?>
	</h1>

	<div class="lpf-page-content">
		<p>
			<a class="button button-primary" href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank">
				<?php esc_html_e( 'Upgrade to Pro', 'level-playing-field' ); ?>
			</a>
		</p>

		<h2>
			<a href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank">
				<?php esc_html_e( 'Upgrade to Level Playing Field Pro for unlimited job listings and enhanced features.', 'level-playing-field' ); ?>
			</a>
		</h2>

		<a href="https://yikesplugins.com/plugin/level-playing-field-pro/" target="_blank">
			<img src="<?php echo esc_url( ( new MediaAsset() )->get_image( MediaAsset::PRO ) ); ?>" class="lpf-pro-img">
		</a>

		<h3>Level Playing Field Pro Features</h3>

		<ul class="gopro-page-list">
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Unlimited Job Listings.', 'level-playing-field' ); ?>
			</li>

			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'More detailed Job Listings.', 'level-playing-field' ); ?>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Responsibilities', 'level-playing-field' ); ?>
						<ul>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Responsibilities', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Schedule', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Requirements', 'level-playing-field' ); ?></li>
						</ul>
					</li>
				</ul>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Qualifications', 'level-playing-field' ); ?>
						<ul>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Qualifications', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Education', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Experience', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Knowledge, Skills and Abilities', 'level-playing-field' ); ?></li>
						</ul>
					</li>
				</ul>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Compensation', 'level-playing-field' ); ?></li>
				</ul>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Company/Organization Details', 'level-playing-field' ); ?>
						<ul>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Name', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Description', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Logo', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Website', 'level-playing-field' ); ?></li>
							<li><span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Social Links', 'level-playing-field' ); ?></li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'More Robust Applicant Functionality.', 'level-playing-field' ); ?>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Ability to add custom questions to application forms', 'level-playing-field' ); ?></li>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Ability to export applicants to CSV File', 'level-playing-field' ); ?></li>
				</ul>
			</li>
			<li>
				<span class="dashicons dashicons-star-filled"></span> <?php esc_html_e( 'Premium Support.', 'level-playing-field' ); ?>

				<ul>
					<li><span class="dashicons dashicons-arrow-right"></span><?php esc_html_e( 'Prioritized one-on-one support', 'level-playing-field' ); ?></li>
				</ul>
			</li>
		</ul>

	</div>
</div>
