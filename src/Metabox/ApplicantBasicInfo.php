<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  KU
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantCPT;
use Yikes\LevelPlayingField\View\View;

/**
 * Class ApplicantBasicInfo
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class ApplicantBasicInfo extends BaseMetabox {

	// Base Metabox.
	const BOX_ID   = 'applicant-basic-info';
	const VIEW     = 'views/applicant-basic-info';
	const PRIORITY = 10;

	/**
	 * Do the actual persistence of the changed data.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id ID of the post to persist.
	 */
	protected function persist( $post_id ) {
		// There's no data to save for Applicants, so intentionally do nothing.
	}

	/**
	 * Get the title to use for the metabox.
	 *
	 * @since 1.0.0
	 *
	 * @return string Title to use for the metabox.
	 */
	protected function get_title() {
		return __( 'Basic Applicant Information', 'level-playing-field' );
	}

	/**
	 * Get the context in which to show the metabox.
	 *
	 * @since 1.0.0
	 *
	 * @return string Context to use.
	 */
	protected function get_context() {
		return static::CONTEXT_SIDE;
	}

	/**
	 * Process the metabox attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post      $post The post object.
	 * @param array|string $atts Raw metabox attributes passed into the
	 *                           metabox function.
	 *
	 * @return array Processed metabox attributes.
	 */
	protected function process_attributes( $post, $atts ) {
		$applicant = ( new ApplicantRepository() )->find( $post->ID );
		$job       = ( new JobRepository() )->find( $applicant->get_job_id() );
		return [
			'applicant' => $applicant,
			'job'       => $job,
		];
	}

	/**
	 * Get the screen on which to show the metabox.
	 *
	 * @since 1.0.0
	 *
	 * @return string|array|\WP_Screen Screen on which to show the metabox.
	 */
	protected function get_screen() {
		return $this->get_post_types();
	}

	/**
	 * Get the post types for this metabox..
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_post_types() {
		return [ ApplicantCPT::SLUG ];
	}
}
