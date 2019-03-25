<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    JP, KU, EB, TL
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\TemplateController;

use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Shortcode\Job as JobShortcode;

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

	/**
	 * Check if the current request is for this class' object and supply the current post w/ content.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
	 *
	 * @param int $id The Job ID.
	 *
	 * @return array Context to pass onto view.
	 */
	protected function get_context( $id ) {
		$shortcode_atts        = ( new JobShortcode() )->get_default_atts();
		$shortcode_atts['job'] = ( new JobRepository() )->find( $id );
		return $shortcode_atts;
	}

}
