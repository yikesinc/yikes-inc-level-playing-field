<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Form;

use Yikes\LevelPlayingField\Field\Field;
use Yikes\LevelPlayingField\Field\Hidden;
use Yikes\LevelPlayingField\Field\Types;
use Yikes\LevelPlayingField\Model\ApplicantMeta as Meta;
use Yikes\LevelPlayingField\Model\Application as AppModel;
use Yikes\LevelPlayingField\Model\ApplicationMeta;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\StyleAsset;

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
class Application {

	use AssetsAwareness;

	const CSS_HANDLE = 'lpf-app-css';
	const CSS_URI    = 'assets/css/lpf-app-frontend';

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
	 * The application object.
	 *
	 * @since %VERSION%
	 * @var AppModel
	 */
	protected $application;

	/**
	 * Array of classes to use for fields.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $field_classes = [];

	/**
	 * Application constructor.
	 *
	 * @param AppModel $application   The application object.
	 * @param array    $field_classes Classes to use for the form fields.
	 */
	public function __construct( AppModel $application, array $field_classes = [] ) {
		$this->application   = $application;
		$this->field_classes = $field_classes;
		$this->set_default_classes();
		$this->register_assets();
	}

	/**
	 * Set the default classes.
	 *
	 * The class fields will only be modified if they are currently empty.
	 *
	 * @since %VERSION%
	 */
	protected function set_default_classes() {
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
				return $this->$name;

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
	protected function create_fields() {
		foreach ( $this->application->get_active_fields() as $field ) {
			$name           = str_replace( ApplicationMeta::META_PREFIX, '', $field );
			$field_name     = ApplicationMeta::FORM_FIELD_PREFIX . $name;
			$field_label    = ucwords( str_replace( [ '-', '_' ], ' ', $name ) );
			$type           = isset( Meta::FIELD_MAP[ $name ] ) ? Meta::FIELD_MAP[ $name ] : Types::TEXT;
			$this->fields[] = new $type( $field_name, $field_label, $this->field_classes );
		}

		// Manually add the hidden Job ID field.
		$this->fields[] = new Hidden( 'job_id', $this->application->get_id() );
	}

	/**
	 * Set the array of classes to use for fields.
	 *
	 * @since %VERSION%
	 *
	 * @param array $classes
	 */
	public function set_field_classes( array $classes ) {
		$this->field_classes = $classes;
	}
}
