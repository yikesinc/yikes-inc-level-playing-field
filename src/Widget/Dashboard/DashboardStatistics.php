<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

/**
 * Applicant Manager CPT.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage CustomPostType
 */
class DashboardStatistics extends BaseWidget {

	const PREFIX = 'yikes_stats_';

	/**
	 * Add a widget to the dashboard.
	 * This function is hooked into the 'wp_dashboard_setup' action above.
	 *
	 * @return void
	 */
	public function yikes_stats_add_widget() {
		wp_add_dashboard_widget(
			'yikes_lpf_widget',         // Widget slug.
			'Yikes Level Playing Field',         // Title.
			array( $this, 'yikes_stats_display_status' ) // Display function.
		);
	}

	/**
	 * Output the contents of our Dashboard Widget.
	 *
	 * @return void
	 */
	public function yikes_stats_display_status() {
		$html = '<h3>Placeholder text.</h3>';
		echo $html;
	}

}
