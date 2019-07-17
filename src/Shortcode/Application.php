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
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\Exception\Exception;
use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Exception\InvalidURI;
use Yikes\LevelPlayingField\Form\Application as ApplicationForm;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Model\Application as ApplicationModel;
use Yikes\LevelPlayingField\Model\ApplicationRepository;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\View\FormEscapedView;
use Yikes\LevelPlayingField\View\NoOverrideLocationView;

/**
 * Class Application
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class Application extends BaseShortcode {

	const TAG           = 'lpf_application';
	const VIEW_URI      = 'views/job-page-application';
	const SUBMITTED_URI = 'views/job-page-application-completed';
	const CSS_HANDLE    = 'lpf-apps-css';
	const CSS_URI       = 'assets/css/lpf-app-frontend';

	/**
	 * Whether a form has been submitted.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	private $is_submitted = false;

	/**
	 * The view URI to use.
	 *
	 * This property is used so that the view can be switched dynamically
	 * as needed.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	private $view_uri = self::VIEW_URI;

	/**
	 * Register the Shortcode.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();

		add_action( 'lpf_do_applicant_shortcode', function( $atts ) {
			echo $this->process_shortcode( $atts ); // phpcs:ignore WordPress.Security.EscapeOutput
		} );
	}

	/**
	 * Get the default array of attributes for the shortcode.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_default_atts() {
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
		$job                = ( new JobRepository() )->find( $atts['job_id'] );
		$application        = ( new ApplicationRepository() )->find( $job->get_application() );
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
	 * Process the shortcode attributes and prepare rendering.
	 *
	 * @since %VERSION%
	 *
	 * @param array|string $atts Attributes as passed to the shortcode.
	 *
	 * @return string Rendered HTML of the shortcode.
	 */
	public function process_shortcode( $atts ) {
		try {
			// Determine if the form has been submitted.
			$this->is_submitted = $this->is_submitting_application();

			// Process the shortcode attributes.
			$atts    = $this->process_attributes( $atts );
			$context = $this->get_context( $atts );

			return $this->render( $context );
		} catch ( Exception $e ) {
			return $this->exception_to_string( $e );
		}
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
	private function set_view_uri( $uri ) {
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
	private function is_submitting_application() {
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
			return $this->exception_to_string( $e );
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
		$repeater = new ScriptAsset( 'lpf-repeater', 'assets/js/fields/repeater', [ 'jquery', 'jquery-ui-datepicker' ] );
		$repeater->add_localization( 'lpfRepeater', [
			'addNew' => _x( 'Add New', 'button for adding section in application', 'yikes-level-playing-field' ),
		] );

		$input_validation = new ScriptAsset(
			'lpf-input-validation',
			'assets/js/fields/input-validation',
			[ 'jquery' ]
		);
		$input_validation->add_localization( 'lpfInputValidation', [
			'errors' => [
				'empty'   => __( 'This field is required.', 'yikes-level-playing-field' ),
				/* translators: %TYPE% should not be translated. It is a placeholder for a field name. */
				'invalid' => __( '%TYPE% is invalid.', 'yikes-level-playing-field' ),
			],
		] );

		$jquery_datepicker_styles = new StyleAsset( 'lpf-jquery-ui-datepicker-styles', 'assets/vendor/datepicker/jquery-ui', StyleAsset::DEPENDENCIES, StyleAsset::VERSION, StyleAsset::MEDIA_ALL );

		$styles = new StyleAsset( self::CSS_HANDLE, self::CSS_URI, StyleAsset::DEPENDENCIES, StyleAsset::VERSION, StyleAsset::MEDIA_ALL, true );

		return [
			$repeater,
			$styles,
			$input_validation,
			$jquery_datepicker_styles,
		];
	}

	/**
	 * Get the Application form object.
	 *
	 * @since %VERSION%
	 *
	 * @param int              $job_id        The Job ID for the form.
	 * @param ApplicationModel $application   The application Object.
	 * @param array            $field_classes The classes for fields in the form.
	 *
	 * @return ApplicationForm
	 */
	private function get_application_form( $job_id, $application, $field_classes ) {
		$form = new ApplicationForm( $job_id, $application, $field_classes );

		if ( $this->is_submitted ) {
			$this->handle_submission( $form );
		}

		return $form;
	}

	/**
	 * Handle the form submission.
	 *
	 * @since %VERSION%
	 *
	 * @param ApplicationForm $form The form object.
	 *
	 * @return Applicant|null Returns a new Appliant object, or null if one was not created.
	 * @throws InvalidURI When an invalid URI is set for the view.
	 */
	private function handle_submission( ApplicationForm $form ) {
		$form->set_submission( $_POST );
		$form->validate_submission();

		// Maybe update the view URI.
		if ( ! $form->has_errors() ) {
			$this->set_view_uri( self::SUBMITTED_URI );
			$applicant = ( new ApplicantRepository() )->create_from_form( $form );
		}

		return isset( $applicant ) ? $applicant : null;
	}

	/**
	 * Convert an exception to a string.
	 *
	 * @since %VERSION%
	 *
	 * @param \Exception $e The exception object.
	 *
	 * @return string
	 */
	private function exception_to_string( \Exception $e ) {
		$error_message = "If you see this message, something's wrong! To view an application for a specific job, do one of the following:<br/>";
		$error_message .= sprintf(
		/* translators: %s refers to the error message */
			__( 'There was an error displaying the form: %s', 'yikes-level-playing-field' ),
			$e->getMessage()
		);
		return $error_message;
	}
}
