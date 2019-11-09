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
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Model\ApplicationRepository;
use Yikes\LevelPlayingField\View\FormEscapedView;
use Yikes\LevelPlayingField\View\TemplatedView;
use Yikes\LevelPlayingField\RequiredPages\ApplicationFormPage;
use Yikes\LevelPlayingField\Shortcode\Application as ApplicationShortcode;

/**
 * Class SingleApplicationsTemplateController.
 *
 * A class to control which template file is used to display the single applications page.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  JP, KU, EB, TL
 */
class SingleApplicationsTemplateController extends TemplateController {

	const PRIORITY = 10;
	const VIEW_URI = 'views/job-page-application-shortcode';

	/**
	 * Get the array of known assets.
	 *
	 * @since 1.0.0
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		return [
			new StyleAsset( ApplicationShortcode::CSS_HANDLE, ApplicationShortcode::CSS_URI, StyleAsset::DEPENDENCIES, StyleAsset::VERSION, StyleAsset::MEDIA_ALL, true ),
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
	 * Custom logic to determine if the current request should be displayed with your template.
	 *
	 * @return bool True if the current request should use your template.
	 */
	protected function is_template_request() {
		return in_the_loop() && ( new ApplicationFormPage() )->get_page_id( ApplicationFormPage::PAGE_SLUG ) === get_queried_object_id();
	}

	/**
	 * Render the current Renderable.
	 *
	 * @since 1.0.0
	 *
	 * @param array $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 */
	public function render( array $context = [] ) {
		try {
			$this->enqueue_assets();
			$view = new FormEscapedView( new TemplatedView( $this->get_view_uri() ) );

			return $view->render( $context );
		} catch ( \Exception $e ) {
			return sprintf(
				/* translators: %s refers to the error message */
				esc_html__( 'There was an error displaying the form: %s', 'level-playing-field' ),
				$e->getMessage()
			);
		}
	}

	/**
	 * Get the data needed for this context, i.e. the $post/job ID.
	 *
	 * @return int The job ID.
	 */
	protected function get_context_data() {
		$job_id = isset( $_GET['job'] ) ? filter_var( wp_unslash( $_GET['job'] ), FILTER_SANITIZE_NUMBER_INT ) : 0;
		return $job_id;
	}

	/**
	 * Get the data to pass onto the view.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The Job ID.
	 *
	 * @return array Context to pass onto view.
	 * @throws InvalidPostID When the post ID cannot be found as an Application.
	 */
	protected function get_context( $id ) {
		return [
			'shortcode_args' => [ 'job_id' => $id ],
		];
	}
}
