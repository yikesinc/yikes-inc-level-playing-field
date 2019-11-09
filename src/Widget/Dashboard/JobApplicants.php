<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Model\MetaLinks;
use Yikes\LevelPlayingField\Comment\ApplicantMessageRepository;

/**
 * Job Applicants Dashboard Widget.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage Widget
 */
class JobApplicants extends BaseWidget {

	const SLUG     = 'yikes_lpf_applicant_widget';
	const VIEW_URI = 'views/job-applicants-widget';

	// Define the CSS file.
	const CSS_HANDLE = 'lpf-dashboard-widget-css';
	const CSS_URI    = 'assets/css/lpf-dashboard-widget';

	/**
	 * Get the context to pass onto the view.
	 *
	 * Override to provide data to the view.
	 *
	 * @since 1.0.0
	 *
	 * @return array Context to pass onto view.
	 * @throws InvalidPostID When the post ID is not valid.
	 */
	protected function get_context() {

		$job_repo       = new JobRepository();
		$applicant_repo = new ApplicantRepository();
		$msg_repo       = new ApplicantMessageRepository();
		$all_jobs       = $job_repo->find_all();
		$records        = [];
		$jobs_url       = ( new JobManager() )->get_list_table_url();
		$app_url        = ( new ApplicantManager() )->get_list_table_url();
		$new_msgs       = $msg_repo->find_new_applicant_messages();

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

		return [
			'records'        => $records,
			'msg_count'      => count( $new_msgs ),
			'jobs_url'       => $jobs_url,
			'applicants_url' => $app_url,
		];
	}

	/**
	 * Get the title of the dashboard widget.
	 *
	 * @since 1.0.0
	 */
	public function get_title() {
		return __( 'Applicants', 'level-playing-field' );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since 1.0.0
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		return [
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
		];
	}
}
