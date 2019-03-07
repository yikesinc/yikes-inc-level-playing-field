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
use Yikes\LevelPlayingField\Model\MetaLinks;

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
	 * Override to provide data to the view that is not part of the shortcode
	 * attributes.
	 *
	 * @since %VERSION%
	 *
	 * @param array $atts Array of shortcode attributes.
	 *
	 * @return array Context to pass onto view.
	 * @throws InvalidPostID When the post ID is not valid.
	 */
	protected function get_context() {
		$job                = ( new JobRepository() )->find( $atts['job_id'] );
		$application        = ( new ApplicationRepository() )->find( $job->get_application_id() );
		$this->is_submitted = $this->is_submitting_application();

		// Set up the classes we'll use for the form and the individual fields.
		$base_classes  = [ 'lpf-application', sprintf( 'lpf-application-%s', $application->get_id() ) ];
		$form_classes  = array_merge( [ 'lpf-form' ], $base_classes );
		$field_classes = array_merge( [ 'lpf-form-field' ], $base_classes );

		// Set up the form object.
		$form = $this->get_application_form( $job->get_id(), $application, $field_classes );

		return [
			'application'      => $application,
			'application_form' => $form,
			'form_classes'     => $form_classes,
			'job_id'           => $job->get_id(),
			'submitted'        => $this->is_submitted,
		];
	}

	/**
	 * Get the title of the dashboard widget.
	 *
	 * @since %VERSION%
	 */
	public function get_title() {
		return __( 'Applicants', 'yikes-level-playing-field' );
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
