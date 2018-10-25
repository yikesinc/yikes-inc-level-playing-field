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
use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;


/**
 * Applicant Status Dashboard Widget.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage Widget
 */
class Status extends BaseWidget implements AssetsAware {

	use AssetsAwareness;
	const SLUG  = 'yikes_lpf_applicant_widget';
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
		$records = array();
		foreach ( $all_jobs as $job_id => $job ) {
			$total = $applicant_repo->get_applicant_count_for_job( $job_id );
			$new = $applicant_repo->get_new_applicant_count_for_job( $job_id );
			$name = $job->get_title();

			$records[] = [
				'job_name'         => $name,
				'job_link'         => get_the_permalink( $job_id ),
				'new_applicants'   => $new,
				// @todo: call function to get link to filtered list of applicants.
				'new_link'         => add_query_arg( array( ApplicantMeta::VIEWED => 'none', 'post_type' => ApplicantManager::SLUG ), admin_url( 'edit.php' ) ),
				'total_applicants' => $total,
				// @todo: call function to get link to filtered list of applicants.
				'total_link'       => admin_url('edit.php?post_type=applicants'),
			];
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
			<?php
			foreach ( $records as $record ) {
				echo '<tr>';
				echo '<td><a href="' . esc_attr( $record['job_link'] ) . '">' . esc_html__( $record['job_name'], 'yikes-level-playing-field' ) . '</a></td>';
				echo '<td><a href="' . esc_attr( $record['new_link'] ) . '">' . esc_html__( $record['new_applicants'], 'yikes-level-playing-field' ) . '</a></td>';
				echo '<td><a href="' . esc_attr( $record['total_link'] ) . '">' . esc_html__( $record['total_applicants'], 'yikes-level-playing-field' ) . '</a></td>';
				echo '</tr>';
			}
			?>
			</tbody>
		</table>
		<a href="<?php echo admin_url('edit.php?post_type=jobs'); ?>" class="button">View All Job Listings</a>
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
