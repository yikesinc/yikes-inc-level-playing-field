<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Shortcode;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Exception\InvalidURI;
use Yikes\LevelPlayingField\Form\Application as ApplicationForm;
use Yikes\LevelPlayingField\Model\ApplicationRepository;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\View\FormEscapedView;
use Yikes\LevelPlayingField\View\NoOverrideLocationView;
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\StyleAsset;

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
	const CSS_HANDLE    = 'lpf-apps-css';
	const CSS_URI       = 'assets/css/lpf-app-frontend';

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

	/**
	 * The view URI to use.
	 *
	 * This property is used so that the view can be switched dynamically
	 * as needed.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $view_uri = self::VIEW_URI;

	/**
	 * Get the default array of attributes for the shortcode.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_default_atts() {
		return [
			'job_id' => 0,
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
		$job         = ( new JobRepository() )->find( $atts['job_id'] );
		$application = ( new ApplicationRepository() )->find( $job->get_application_id() );

		/**
		 * Set up the classes we'll use for the form and the individual fields.
		 */
		$base_classes  = [ 'lpf-application', sprintf( 'lpf-application-%s', $application->get_id() ) ];
		$form_classes  = array_merge( [ 'lpf-form' ], $base_classes );
		$field_classes = array_merge( [ 'lpf-form-field' ], $base_classes );

		return [
			'application'      => $application,
			'application_form' => new ApplicationForm( $job->get_id(), $application, $field_classes ),
			'form_classes'     => $form_classes,
		];
	}

	/**
	 * Process the shortcode attributes and prepare rendering.
	 *
	 * @since %VERSION%
	 *
	 * @param array|string $atts Attributes as passed to the shortcode.
	 *
	 * @return string Rendered HTML of the shortcode.
	 */
	public function process_shortcode( $atts ) {
		$atts = $this->process_attributes( $atts );

		return $this->render( array_merge( $atts, $this->get_context( $atts ) ) );
	}

	/**
	 * Get the View URI to use for rendering the shortcode.
	 *
	 * @since %VERSION%
	 *
	 * @return string View URI.
	 */
	protected function get_view_uri() {
		return $this->view_uri;
	}

	/**
	 * Set the view URI.
	 *
	 * Must be either the VIEW_URI or SUBMITTED_URI.
	 *
	 * @since %VERSION%
	 *
	 * @param string $uri The URI to use.
	 *
	 * @throws InvalidURI When the URI is not one of the valid values.
	 */
	protected function set_view_uri( $uri ) {
		if ( self::VIEW_URI !== $uri && self::SUBMITTED_URI !== $uri ) {
			throw InvalidURI::from_list( $uri, [ self::VIEW_URI, self::SUBMITTED_URI ] );
		}

		$this->view_uri = $uri;
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

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		$repeater = new ScriptAsset( 'lpf-repeater', 'assets/js/fields/repeater', [ 'jquery' ] );
		$repeater->add_localization( 'lpfRepeater', [
			'addNew' => _x( 'Add New', 'button for adding section in application', 'yikes-level-playing-field' ),
		] );

		return [
			$repeater,
		];
	}
}
