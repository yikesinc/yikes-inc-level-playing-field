<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    JP, KU, EB, TL
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\TemplateController;

use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Shortcode\BaseJobs;
use Yikes\LevelPlayingField\Shortcode\Job as JobShortcode;

/**
 * Class SingleJobsTemplateController.
 *
 * A class to control which template file is used to display the single jobs page.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  JP, KU, EB, TL
 */
class SingleJobsTemplateController extends TemplateController {

	const PRIORITY = 10;
	const VIEW_URI = 'views/job-page-job';

	/**
	 * Get the array of known assets.
	 *
	 * @since 1.0.0
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		return [
			new StyleAsset( JobShortcode::CSS_HANDLE, JobShortcode::CSS_URI ),
		];
	}

	/**
	 * Check if the current request is for this class' object and supply the current post w/ content.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $content The default post content.
	 *
	 * @return string The post's content, maybe overridden.
	 */
	public function set_content( $content ) {

		if ( $this->is_template_request() ) {
			$content = $this->render( $this->get_context( $this->get_context_data() ) );
		}

		return $content;
	}

	/**
	 * Filters the path of the current template before including it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template The path of the template to include.
	 */
	public function set_template( $template ) {
		if ( is_singular( JobManager::SLUG ) ) {

			// @todo Modifying query directly is not optimal. Alternative solution would be preferred.
			global $wp_query;
			$wp_query->is_page = true;

			$new_template = locate_template( [ 'page.php' ] );
			if ( ! empty( $new_template ) ) {
				return $new_template;
			}
		}

		return $template;
	}


	/**
	 * Custom logic to determine if the current request should be displayed with your template.
	 *
	 * @return bool True if the current request should use your template.
	 */
	protected function is_template_request() {
		return in_the_loop() && is_single() && JobManager::SLUG === get_query_var( 'post_type' );
	}

	/**
	 * Get the data needed for this context, i.e. the $post/job ID.
	 *
	 * @return int The job ID.
	 */
	protected function get_context_data() {
		return get_queried_object_id();
	}

	/**
	 * Get the data to pass onto the view.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The Job ID.
	 *
	 * @return array Context to pass onto view.
	 */
	protected function get_context( $id ) {
		$shortcode_atts               = ( new JobShortcode() )->get_default_atts();
		$shortcode_atts['job']        = ( new JobRepository() )->find( $id );
		$shortcode_atts['show_title'] = false;
		$shortcode_atts['partials']   = [
			'job_details'      => BaseJobs::JOB_DETAILS_PARTIAL,
			'job_apply_button' => BaseJobs::JOB_APPLY_PARTIAL,
		];
		return $shortcode_atts;
	}
}
