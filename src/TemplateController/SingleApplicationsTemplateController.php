<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    JP, KU, EB, TL
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\TemplateController;

use Yikes\LevelPlayingField\Model\ApplicationRepository;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\View\FormEscapedView;
use Yikes\LevelPlayingField\View\TemplatedView;

/**
 * Class SingleApplicationsTemplateController.
 *
 * A class to control which template file is used to display the single applications page.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  JP, KU, EB, TL
 */
class SingleApplicationsTemplateController extends TemplateController {

	const PRIORITY = 10;
	const VIEW_URI = 'views/job-page-application';

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
		return is_single() && ApplicationManager::SLUG === get_query_var( 'post_type' );
	}

	/**
	 * Render the current Renderable.
	 *
	 * @since %VERSION%
	 *
	 * @param array $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 */
	public function render( array $context = array() ) {
		try {

			$this->enqueue_assets();
			$view = new FormEscapedView( new TemplatedView( $this->get_view_uri() ) );

			return $view->render( $context );
		} catch ( \Exception $exception ) {

			// Don't let exceptions bubble up. Just render an empty shortcode instead.
			return '';
		}
	}

	/**
	 * Get the data needed for this context, i.e. the $post/application ID.
	 *
	 * @return int The application ID.
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
		$applications_repository = new ApplicationRepository();

		return apply_filters( 'lpf_single_application_template_data', 
			[
				'application' => $applications_repository->find( $id ),
			]
		);
	}

}
