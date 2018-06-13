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
 * @subpackage Widget
 */
class Status extends BaseWidget {

	const SLUG   = 'yikes_lpf_widget';
	const TITLE  = 'Yikes Level Playing Field Stuff';
	const F_NAME = 'yikes_stats_display_status';

	/**
	 * Output the contents of our Dashboard Widget.
	 */
	public function yikes_stats_display_status() {
		$html = '<h3>Placeholder text.</h3>';
		echo $html;
	}

}
