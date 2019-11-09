<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\Taxonomy\JobStatus;
use Yikes\LevelPlayingField\RequiredPages\ApplicationFormPage;

/**
 * Class Job
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 *
 * @property string status      The Job status.
 * @property string description The Job description.
 * @property string type        The Job type.
 * @property string location    The Job location.
 * @property array  address     The Job location address.
 * @property int    application The Job application ID.
 */
final class Job extends CustomPostTypeEntity {

	use JobMetaDropdowns;
	use JobPrefix;

	/**
	 * Get the job status.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Magic getter method to fetch meta properties only when requested.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property Property that was requested.
	 *
	 * @return mixed
	 */
	public function __get( $property ) {
		// Set the status separately, since it is a taxonomy.
		if ( 'status' === $property ) {
			$this->status = wp_get_object_terms( $this->get_id(), JobStatus::SLUG )[0];
			return $this->status;
		}

		return parent::__get( $property );
	}

	/**
	 * Get the type of the job.
	 *
	 * Possible values are full time, part time, contract, per diem, and other.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_job_type() {
		$job_types = $this->get_job_type_options();
		return isset( $job_types[ $this->{JobMeta::TYPE} ] ) ? $job_types[ $this->{JobMeta::TYPE} ] : '';
	}

	/**
	 * Get the job's location.
	 *
	 * Possible values are address and remote.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function get_location() {
		return $this->{JobMeta::LOCATION};
	}

	/**
	 * Determine if the job is remote.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function is_remote() {
		return 'remote' === $this->get_location();
	}

	/**
	 * Get the job address.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_address() {
		return $this->{JobMeta::ADDRESS};
	}

	/**
	 * Get the application ID to use when displaying this Job.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_application() {
		return (int) $this->{JobMeta::APPLICATION};
	}

	/**
	 * Get the success message to use when an application using this Job is submitted.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_application_success_message() {
		return $this->{JobMeta::APPLICATION_SUCCESS_MESSAGE};
	}

	/**
	 * Get the page ID to use for this Job's application.
	 *
	 * @since 1.0.0
	 *
	 * @return int
	 */
	public function get_application_page() {
		return (int) $this->{JobMeta::APPLICATION_PAGE};
	}

	/**
	 * Set the page ID to use for this Job's application.
	 *
	 * @since 1.0.0
	 *
	 * @param int $page_id A page ID.
	 */
	public function set_application_page( $page_id ) {
		$this->{JobMeta::APPLICATION_PAGE} = (int) $page_id;
	}

	/**
	 * Get the application URL to use when displaying this Job.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_application_url() {

		/**
		 * Filter the application page ID for the given Job.
		 *
		 * @since 1.0.0
		 *
		 * @param int $page_id The page ID of the plugin's application form page.
		 * @param Job $job     The job class.
		 *
		 * @return int The page ID.
		 */
		$app_page_id = (int) apply_filters( 'lpf_single_job_application_page_id', $this->get_application_page(), $this );
		return add_query_arg( [ 'job' => $this->get_id() ], get_permalink( $app_page_id ) );
	}

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since 1.0.0
	 */
	public function persist_properties() {
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			$prefixed_key = $this->meta_prefix( $key );
			if ( $this->$key === $default ) {
				delete_post_meta( $this->get_id(), $prefixed_key );
				continue;
			}

			update_post_meta( $this->get_id(), $prefixed_key, $this->$key );
		}

		// Set the status.
		wp_set_post_terms( $this->get_id(), $this->status, JobStatus::SLUG );
	}

	/**
	 * Return the list of lazily-loaded properties and their default values.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_lazy_properties() {
		$defaults = [
			JobMeta::TYPE                        => '',
			JobMeta::LOCATION                    => '',
			JobMeta::ADDRESS                     => [
				'address-1' => '',
				'address-2' => '',
				'city'      => '',
				'state'     => '',
				'province'  => '',
				'country'   => '',
				'zip'       => '',
			],
			JobMeta::APPLICATION                 => 0,
			JobMeta::APPLICATION_SUCCESS_MESSAGE => '',
			JobMeta::APPLICATION_PAGE            => ( new ApplicationFormPage() )->get_page_id( ApplicationFormPage::PAGE_SLUG ),
		];

		/**
		 * Filter additional job "lazy" properties.
		 *
		 * These are properties that are loaded only when needed.
		 *
		 * @param array $properties The array of applicant properties as keys, with their default
		 *                          setting as values.
		 */
		$additional = (array) apply_filters( 'lpf_job_lazy_properties', [] );

		return array_merge( $additional, $defaults );
	}

	/**
	 * Load a lazily-loaded property.
	 *
	 * After this process, the loaded property should be set within the
	 * object's state, otherwise the load procedure might be triggered multiple
	 * times.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property Name of the property to load.
	 */
	protected function load_lazy_property( $property ) {
		// Load the normal properties from post meta.
		$meta = get_post_meta( $this->get_id() );
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			if ( ! array_key_exists( $key, JobMeta::META_PREFIXES ) ) {
				continue;
			}

			$prefixed_key = JobMeta::META_PREFIXES[ $key ];
			$this->$key   = array_key_exists( $prefixed_key, $meta )
				// Maybe decode, because we grabbed all meta at once instead of individually.
				? $this->maybe_json_decode( $prefixed_key, $meta[ $prefixed_key ][0] )
				: $default;
		}
	}

	/**
	 * Possibly json_decode() a value.
	 *
	 * @since 1.0.0
	 *
	 * @param string $key  The key name.
	 * @param mixed  $data The data.
	 *
	 * @return mixed
	 */
	protected function maybe_json_decode( $key, $data ) {
		if ( array_key_exists( $key, JobMeta::JSON_PROPERTIES ) ) {
			$decoded = json_decode( $data, true );
			$data    = JSON_ERROR_NONE === json_last_error() && is_array( $decoded ) && isset( $decoded[0] )
				? $decoded[0]
				: $data;
		}

		return $data;
	}
}
