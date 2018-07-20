<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Shortcode;

use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Model\ApplicationRepository;
use Yikes\LevelPlayingField\View\FormEscapedView;
use Yikes\LevelPlayingField\View\NoOverrideLocationView;

/**
 * Class Application
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Application extends BaseShortcode {

	const TAG           = 'lpf_application';
	const VIEW_URI      = 'views/job-page-application';
	const SUBMITTED_URI = 'views/job-page-application-completed';

	/**
	 * Get the default array of attributes for the shortcode.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_default_atts() {
		return [
			'id' => 0,
		];
	}

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
	protected function get_context( array $atts ) {
		$application_repository = new ApplicationRepository();

		return [
			'application' => $application_repository->find( $atts['id'] ),
		];
	}

	/**
	 * Get the View URI to use for rendering the shortcode.
	 *
	 * @since %VERSION%
	 *
	 * @return string View URI.
	 */
	protected function get_view_uri() {
		return $this->is_submitting_application() ? self::SUBMITTED_URI : self::VIEW_URI;
	}

	/**
	 * Determine whether an application is currently being submitted.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	protected function is_submitting_application() {
		return ! empty( $_POST );
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
	public function render( array $context = [] ) {
		try {
			$this->enqueue_assets();
			$view = new FormEscapedView( new NoOverrideLocationView( $this->get_view_uri() ) );

			return $view->render( $context );
		} catch ( \Exception $e ) {
			return sprintf(
				/* translators: %s refers to the error message */
				esc_html__( 'There was an error displaying the form: %s', 'yikes-level-playing-field' ),
				$e->getMessage()
			);
		}
	}
}
