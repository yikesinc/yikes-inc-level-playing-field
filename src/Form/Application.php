<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Form;

use Yikes\LevelPlayingField\Exception\InvalidClass;
use Yikes\LevelPlayingField\Exception\InvalidField;
use Yikes\LevelPlayingField\Field\Field;
use Yikes\LevelPlayingField\Field\Hidden;
use Yikes\LevelPlayingField\Field\Types;
use Yikes\LevelPlayingField\Model\ApplicantMeta as Meta;
use Yikes\LevelPlayingField\Model\Application as AppModel;
use Yikes\LevelPlayingField\Model\ApplicationPrefix;

/**
 * Class Application
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 *
 * @property Field[] fields        The array of field objects.
 * @property array   field_classes The array of classes used for field objects.
 * @property array   form_classes  The array of classes used for the main form element.
 */
final class Application {

	use ApplicationPrefix;

	/**
	 * The application object.
	 *
	 * @since 1.0.0
	 * @var AppModel
	 */
	private $application;

	/**
	 * Array of classes to use for fields.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $field_classes = [];

	/**
	 * Whether the form has any errors.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	private $has_errors = false;

	/**
	 * Whether the form has been submitted.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	private $is_submitted = false;

	/**
	 * The ID of the Job.
	 *
	 * @since 1.0.0
	 * @var int
	 */
	private $job_id = 0;

	/**
	 * The data submitted with this form.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private $submitted_data = [];

	/**
	 * The validated data for this form.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
	 * @since 1.0.0
	 */
	private function create_fields() {
		$fields = [];

		// Manually add the hidden nonce and referrer fields.
		$fields[] = new Hidden( 'lpf_nonce', wp_create_nonce( 'lpf_application_submit' ) );
		$fields[] = new Hidden( '_wp_http_referer', wp_unslash( $_SERVER['REQUEST_URI'] ) );

		// Manually add the hidden Job ID field.
		$fields[] = new Hidden( 'job_id', $this->job_id );

		// Add all of the active fields.
		foreach ( $this->application->get_active_fields() as $field ) {
			$fields = array_merge( $fields, $this->instantiate_field( $field ) );
		}

		$this->fields = $fields;
	}

	/**
	 * Get the label for the form field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $field The field name.
	 *
	 * @return string
	 */
	private function get_field_label( $field ) {
		$field_label = ucwords( str_replace( [ '-', '_' ], ' ', $field ) );

		/**
		 * Filter the label for the form field.
		 *
		 * @param string   $field_label The field label.
		 * @param string   $field       The field name.
		 * @param AppModel $application The application object.
		 */
		return apply_filters( 'lpf_application_form_field_label', $field_label, $field, $this->application );
	}

	/**
	 * Get the class type for a particular field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $field The field name.
	 *
	 * @return string The class name to instantiate that field.
	 * @throws InvalidClass When a field type is returned to the filter that doesn't implement Field.
	 */
	private function get_field_type( $field ) {
		$type = array_key_exists( $field, Meta::FIELD_MAP ) ? Meta::FIELD_MAP[ $field ] : Types::TEXT;

		/**
		 * Filter the class used to instantiate the field.
		 *
		 * @param string $type  The field class name. Must extend implment the Field interface.
		 * @param string $field The field name.
		 */
		$type = apply_filters( 'lpf_application_form_field_type', $type, $field );

		// Ensure that the field implements the Field interface..
		$implements = class_implements( $type );
		if ( ! isset( $implements[ Field::class ] ) ) {
			throw InvalidClass::from_interface( $type, Field::class );
		}

		return $type;
	}

	/**
	 * Instantiate a field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $field The raw field name.
	 *
	 * @return Field[] Array of Field objects.
	 */
	private function instantiate_field( $field ) {
		/**
		 * Short-circuit the instantiation of a field object.
		 *
		 * To effectively short-circuit normal instantiation, an array of Field objects must be returned.
		 *
		 * @param array|null $pre         Array of Field objects or null.
		 * @param string     $field       The raw field name.
		 * @param AppModel   $application The application object.
		 */
		$pre = apply_filters( 'lpf_application_instantiate_field', null, $field, $this->application );
		if ( is_array( $pre ) ) {
			foreach ( $pre as $object ) {
				$this->validate_is_field( $object );
			}

			return $pre;
		}

		$field_name  = $this->form_prefix( $field );
		$field_label = $this->get_field_label( $field );
		$type        = $this->get_field_type( $field );

		return [
			new $type(
				$field_name,
				$field_label,
				$this->field_classes,
				$this->application->is_required( $field )
			),
		];
	}

	/**
	 * Set the array of classes to use for fields.
	 *
	 * @since 1.0.0
	 *
	 * @param array $classes The classes to add to the fields.
	 */
	public function set_field_classes( array $classes ) {
		$this->field_classes = $classes;
	}

	/**
	 * Render the form fields.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		foreach ( $this->fields as $field ) {
			$field->render();
		}
	}

	/**
	 * Set the submission data.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return bool
	 */
	public function has_errors() {
		return $this->is_submitted && $this->has_errors;
	}

	/**
	 * Validate the submission.
	 *
	 * @since 1.0.0
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

	/**
	 * Validate that the given object is a Field.
	 *
	 * @since 1.0.0
	 *
	 * @param object $maybe_field The object to validate.
	 *
	 * @throws InvalidClass When the object isn't a Field object.
	 */
	private function validate_is_field( $maybe_field ) {
		if ( ! $maybe_field instanceof Field ) {
			throw InvalidClass::from_interface( get_class( $maybe_field ), Field::class );
		}
	}
}
