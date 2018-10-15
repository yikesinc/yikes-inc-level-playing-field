<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Model\ApplicantRepository;


/**
 * Applicant Status Dashboard Widget.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage Widget
 */
class Status extends BaseWidget implements AssetsAware {

	use AssetsAwareness;
	const SLUG  = 'yikes_lpf_widget';
	const TITLE = 'Applicants';

	// Define the CSS file.
	const CSS_HANDLE = 'lpf-dashboard-widget-css';
	const CSS_URI    = 'assets/css/lpf-dashboard-widget';

	// Define the JavaScript file.
	const JS_HANDLE       = 'lpf-dashboard-widget-script';
	const JS_URI          = 'assets/js/dashboard-widget';
	const JS_DEPENDENCIES = [ 'jquery' ];
	const JS_VERSION      = false;


	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();
		$this->register_assets();
	}

	/**
	 * Output the widget content.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		$this->enqueue_assets();
		// Get job data.
		$job_repo = new JobRepository();
		$applicant_repo = new ApplicantRepository();
		$all_jobs = $job_repo->find_all();
		foreach ( $all_jobs as $job_id => $job ) {
			$tmp = $applicant_repo->get_applicant_count_for_job( $job_id );
		}

		?>
		<table>
			<thead>
				<tr>
					<th>Job Title</th>
					<th>New</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Regional Manager</td>
					<td>2</td>
					<td>32</td>
				</tr>
			</tbody>
		</table>
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
			new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER ),
		];
	}
}
