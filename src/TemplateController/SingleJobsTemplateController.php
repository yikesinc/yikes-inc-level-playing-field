<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    JP, KU, EB, TL
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\TemplateController;

use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\CustomPostType\JobManager;

/**
 * Class SingleJobsTemplateController.
 *
 * A class to control which template file is used to display the single jobs page.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  JP, KU, EB, TL
 */
class SingleJobsTemplateController extends TemplateController {

	const PRIORITY = 10;
	const VIEW_URI = 'views/job-page-job';

	public function register() {
		parent::register();
	}

	/**
	 * Check if the current request is for this class' object and supply the current post w/ content.
	 *
	 * @since %VERSION%
	 *
	 * @param  string $template The default template file WordPress is handing us.
	 * @return string The text to be used for the menu.
	 */
	public function set_content( $template ) {

		if ( $this->is_template_request() ) {
			global $post;
			$post->post_content = $this->render( $this->get_context( $this->get_context_data() ) );
		}

		return $template;
	}

	/**
	 * Custom logic to determine if the current request should be displayed with your template.
	 *
	 * @return bool True if the current request should use your template.
	 */
	protected function is_template_request() {
		return is_single() && JobManager::SLUG === get_query_var( 'post_type' );
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
	 * @since %VERSION%
	 *
	 * @return array Context to pass onto view.
	 */
	protected function get_context( $id ) {
		$jobs_repository = new JobRepository();

		return apply_filters( 'lpf_single_job_template_data', 
			[
				'job' => $jobs_repository->find( $id ),
			]
		);
	}

}
