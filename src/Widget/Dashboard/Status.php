<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\StyleAsset;

/**
 * Applicant Status Dashboard Widget.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage Widget
 */
class Status extends BaseWidget {

	use AssetsAwareness;

	const CSS_HANDLE = 'lpf-dashboard-widget-css';
	const CSS_URI    = 'assets/css/lpf-dashboard-widget';
	const SLUG  = 'yikes_lpf_widget';
	const TITLE = 'Applicants';

	/**
	 * Output the widget content.
	 *
	 * @since %VERSION%
	 */
	public function render_widget() {
		$this->enqueue_assets();
		?>
		<h3>Job Title</h3>
		<a href="#">Regional Manager</a>
		<h3>New</h3>
		<a href="#">2</a>
		<h3>Total</h3>
		<a href="#">32</a>
		<?php
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {

		return [
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
		];
	}
}
