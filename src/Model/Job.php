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

/**
 * Class Job
 *
 * @since   %VERSION%
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

	/**
	 * Get the job status.
	 *
	 * @since %VERSION%
	 *
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Magic getter method to fetch meta properties only when requested.
	 *
	 * @since %VERSION%
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
	 * Get the job description.
	 *
	 * @since %VERSION%
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->description;
	}

	/**
	 * Get the type of the job.
	 *
	 * Possible values are full time, part time, contract, per diem, and other.
	 *
	 * @since %VERSION%
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Determine if the job is remote.
	 *
	 * @since %VERSION%
	 *
	 * @return bool
	 */
	public function is_remote() {
		return 'remote' === $this->location;
	}

	/**
	 * Get the job address.
	 *
	 * @since %VERSION%
	 *
	 * @return array
	 */
	public function get_address() {
		return $this->address;
	}

	/**
	 * Get the application ID to use when displaying this Job.
	 *
	 * @since %VERSION%
	 *
	 * @return int
	 */
	public function get_application_id() {
		return $this->application;
	}

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since %VERSION%
	 */
	public function persist_properties() {
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			$prefixed_key = JobMeta::META_PREFIX . $key;
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
	 * @since %VERSION%
	 *
	 * @return array
	 */
	protected function get_lazy_properties() {
		return [
			JobMeta::DESCRIPTION => '',
			JobMeta::TYPE        => '',
			JobMeta::LOCATION    => '',
			JobMeta::ADDRESS     => [
				'address-1' => '',
				'address-2' => '',
				'city'      => '',
				'state'     => '',
				'province'  => '',
				'country'   => '',
				'zip'       => '',
			],
			JobMeta::APPLICATION => 0,
		];
	}

	/**
	 * Load a lazily-loaded property.
	 *
	 * After this process, the loaded property should be set within the
	 * object's state, otherwise the load procedure might be triggered multiple
	 * times.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
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
