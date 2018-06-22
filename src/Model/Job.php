<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
 * Class Job
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Job extends CustomPostTypeEntity {

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
	 * Get the job description.
	 *
	 * @since %VERSION%
	 *
	 * @return string
	 */
	public function get_description() {
		return $this->{JobMeta::DESCRIPTION};
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
		return $this->{JobMeta::TYPE};
	}

	/**
	 * Determine if the job is remote.
	 *
	 * @since %VERSION%
	 *
	 * @return bool
	 */
	public function is_remote() {
		return 'remote' === $this->{JobMeta::LOCATION};
	}

	/**
	 * Get the job address.
	 *
	 * @since %VERSION%
	 *
	 * @return array
	 */
	public function get_address() {
		return $this->{JobMeta::ADDRESS};
	}

	/**
	 * Get the application ID to use when displaying this Job.
	 *
	 * @since %VERSION%
	 *
	 * @return int
	 */
	public function get_application_id() {
		return 0;
	}

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since %VERSION%
	 */
	public function persist_properties() {
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			if ( $this->$key === $default ) {
				delete_post_meta( $this->get_id(), $key );
				continue;
			}

			update_post_meta( $this->get_id(), $key, $this->maybe_json_encode( $key, $this->$key ) );
		}
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
			$this->$key = array_key_exists( $key, $meta )
				? $this->maybe_json_decode( $key, $meta[ $key ][0] )
				: $default;
		}
	}

	/**
	 * Possibly JSON decode a string.
	 *
	 * @since %VERSION%
	 *
	 * @param string $key   The property key.
	 * @param mixed  $value The property value.
	 *
	 * @return array|string
	 */
	protected function maybe_json_decode( $key, $value ) {
		return isset( $this->get_json_properties()[ $key ] ) && is_string( $value )
			? json_decode( $value, true )
			: $value;
	}

	/**
	 * Get properties that should be stored as JSON.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_json_properties() {
		return [
			JobMeta::ADDRESS => true,
		];
	}

	/**
	 * Possibly JSON encode a value.
	 *
	 * @since %VERSION%
	 *
	 * @param string $key   The property key.
	 * @param mixed  $value The property value.
	 *
	 * @return string
	 */
	protected function maybe_json_encode( $key, $value ) {
		return isset( $this->get_json_properties()[ $key ] ) && ( is_array( $value ) || is_object( $value ) )
			? json_encode( $value )
			: $value;
	}
}
