<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\Model\MetaLinks;

/**
 * Job Applicants Dashboard Widget.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage Widget
 */
class JobApplicants extends BaseWidget implements AssetsAware {

	use AssetsAwareness;

	const SLUG     = 'yikes_lpf_applicant_widget';
	const VIEW_URI = 'views/job-applicants-widget';

	// Define the CSS file.
	const CSS_HANDLE = 'lpf-dashboard-widget-css';
	const CSS_URI    = 'assets/css/lpf-dashboard-widget';

	/**
	 * Get the title of the dashboard widget.
	 *
	 * @since %VERSION%
	 */
	public function get_title() {
		return __( 'Applicants', 'yikes-level-playing-field' );
	}

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
		$job_repo       = new JobRepository();
		$applicant_repo = new ApplicantRepository();
		$all_jobs       = $job_repo->find_all();
		$records        = [];

		foreach ( $all_jobs as $job_id => $job ) {

			$records[] = [
				'job_name'         => $job->get_title(),
				'job_link'         => get_the_permalink( $job_id ),
				'new_applicants'   => $applicant_repo->get_new_applicant_count_for_job( $job_id ),
				'new_link'         => add_query_arg( [
					ApplicantMeta::VIEWED => 'none',
					MetaLinks::JOB        => $job_id,
					'post_type'           => ApplicantManager::SLUG,
				], admin_url( 'edit.php' ) ),
				'total_applicants' => $applicant_repo->get_applicant_count_for_job( $job_id ),
				'total_link'       => add_query_arg( [
					MetaLinks::JOB => $job_id,
					'post_type'    => ApplicantManager::SLUG,
				], admin_url( 'edit.php' ) ),
			];
		}

		?>
		<table>
			<thead>
				<tr>
					<th><?php esc_html_e( 'Job Title', 'yikes-level-playing-field' ); ?></th>
					<th><?php esc_html_e( 'New', 'yikes-level-playing-field' ); ?></th>
					<th><?php esc_html_e( 'Total', 'yikes-level-playing-field' ); ?></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $records as $record ) {
				?>
				<tr>
					<td>
						<a href="<?php echo esc_attr( $record['job_link'] ); ?>"><?php echo esc_html( $record['job_name'] ); ?></a>
					</td>
					<td>
						<a href="<?php echo esc_attr( $record['new_link'] ); ?>"><?php echo esc_html( $record['new_applicants'] ); ?></a>
					</td>
					<td>
						<a href="<?php echo esc_attr( $record['total_link'] ); ?>"><?php echo esc_html( $record['total_applicants'] ); ?></a>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=jobs' ) ); ?>" class="button"><?php esc_html_e( 'View All Job Listings', 'yikes-level-playing-field' ); ?></a>
		<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=applicants' ) ); ?>" class="button"><?php esc_html_e( 'View All Applicants', 'yikes-level-playing-field' ); ?></a>
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
