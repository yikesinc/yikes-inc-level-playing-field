<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Form;

use Yikes\LevelPlayingField\Exception\InvalidField;
use Yikes\LevelPlayingField\Field\Field;
use Yikes\LevelPlayingField\Field\Hidden;
use Yikes\LevelPlayingField\Field\Types;
use Yikes\LevelPlayingField\Model\ApplicantMeta as Meta;
use Yikes\LevelPlayingField\Model\Application as AppModel;
use Yikes\LevelPlayingField\Model\ApplicationMeta;

/**
 * Class Application
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 *
 * @property Field[] fields        The array of field objects.
 * @property array   field_classes The array of classes used for field objects.
 * @property array   form_classes  The array of classes used for the main form element.
 */
final class Application {

	/**
	 * The application object.
	 *
	 * @since %VERSION%
	 * @var AppModel
	 */
	private $application;

	/**
	 * Array of classes to use for fields.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	private $field_classes = [];

	/**
	 * Whether the form has any errors.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	private $has_errors = false;

	/**
	 * Whether the form has been submitted.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	private $is_submitted = false;

	/**
	 * The ID of the Job.
	 *
	 * @since %VERSION%
	 * @var int
	 */
	private $job_id = 0;

	/**
	 * The data submitted with this form.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	private $submitted_data = [];

	/**
	 * The validated data for this form.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	private $valid_data = [];

	/**
	 * Application constructor.
	 *
	 * @param int      $job_id        The ID of the job this application is for.
	 * @param AppModel $application   The application object.
	 * @param array    $field_classes Classes to use for the form fields.
	 */
	public function __construct( $job_id, AppModel $application, array $field_classes = [] ) {
		$this->job_id        = $job_id;
		$this->application   = $application;
		$this->field_classes = $field_classes;
		$this->set_default_classes();
	}

	/**
	 * Set the default classes.
	 *
	 * The class fields will only be modified if they are currently empty.
	 *
	 * @since %VERSION%
	 */
	private function set_default_classes() {
		$base_classes = [
			'lpf-application',
			sprintf( 'lpf-application-%s', $this->application->get_id() ),
		];

		if ( empty( $this->field_classes ) ) {
			$this->field_classes = array_merge( [ 'lpf-form-field' ], $base_classes );
		}
	}

	/**
	 * Utilized for reading data from inaccessible members.
	 *
	 * @param string $name The property to retrieve.
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'fields':
				$this->create_fields();
				return $this->fields;

			case 'field_classes':
				return $this->field_classes;

			default:
				$message = sprintf( 'Undefined property: %s::$%s', static::class, $name );
				trigger_error( esc_html( $message ), E_USER_NOTICE );

				return null;
		}
	}

	/**
	 * Create the array of fields.
	 *
	 * @since %VERSION%
	 */
	private function create_fields() {
		$this->fields = [];

		// Manually add the hidden nonce and referrer fields.
		$this->fields[] = new Hidden( 'lpf_nonce', wp_create_nonce( 'lpf_application_submit' ) );
		$this->fields[] = new Hidden( '_wp_http_referer', wp_unslash( $_SERVER['REQUEST_URI'] ) );

		// Manually add the hidden Job ID field.
		$this->fields[] = new Hidden( 'job_id', $this->job_id );

		// Add all of the active fields.
		foreach ( $this->application->get_active_fields() as $field ) {
			//echo '<pre>'; var_dump($field); echo '</pre>';
			$field_name     = ApplicationMeta::FORM_FIELD_PREFIX . $field;
			$field_label    = ucwords( str_replace( [ '-', '_' ], ' ', $field ) );
			$type           = isset( Meta::FIELD_MAP[ $field ] ) ? Meta::FIELD_MAP[ $field ] : Types::TEXT;
			// @todo: Create function to check if active field is set to be a required (non-empty) field.
			echo '<pre>'; var_dump( $field ); echo '</pre>';
			echo '<pre>'; var_dump( $this->is_field_required( $field ) ); echo '</pre>';
			$this->fields[] = new $type( $field_name, $field_label, $this->field_classes, $this->is_field_required( $field ) );
		}
	}

	/**
	 * Check if field input is required for given field.
	 *
	 * @since %VERSION%
	 *
	 * @param string $field_name The name of the field.
	 *
	 * @return bool
	 */
	private function is_field_required( $field_name ) {

		if ( $field_name === ApplicationMeta::NAME || $field_name === ApplicationMeta::EMAIL ) {
			return true;
		}
		$required = get_post_meta( $this->application->get_id(), ApplicationMeta::META_PREFIX . $field_name . ApplicationMeta::REQUIRED_SUFFIX, true );
		if ( empty( $required ) ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Set the array of classes to use for fields.
	 *
	 * @since %VERSION%
	 *
	 * @param array $classes The classes to add to the fields.
	 */
	public function set_field_classes( array $classes ) {
		$this->field_classes = $classes;
	}

	/**
	 * Render the form fields.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		foreach ( $this->fields as $field ) {
			$field->render();
		}
	}

	/**
	 * Set the submission data.
	 *
	 * @since %VERSION%
	 *
	 * @param array $data Submitted data.
	 */
	public function set_submission( array $data ) {
		$this->is_submitted   = true;
		$this->submitted_data = $data;
	}

	/**
	 * Determine whether the form has errors.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function has_errors() {
		return $this->is_submitted && $this->has_errors;
	}

	/**
	 * Validate the submission.
	 *
	 * @since %VERSION%
	 */
	public function validate_submission() {
		$valid = [];
		foreach ( $this->fields as $field ) {
			try {
				$submitted = array_key_exists( $field->get_id(), $this->submitted_data )
					? $this->submitted_data[ $field->get_id() ]
					: '';

				$field->set_submission( $submitted );
				$valid[ $field->get_id() ] = $field->get_sanitized_value();
			} catch ( InvalidField $e ) {
				$this->has_errors = true;
			}
		}

		$this->valid_data = $valid;
	}
}
