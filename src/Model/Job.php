<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\Model\JobMeta as JMMeta;

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
		return '';
	}

	/**
	 * Get the job description.
	 *
	 * @since %VERSION%
	 *
	 * @return string
	 */
	public function get_description() {
		return '';
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
		return '';
	}

	/**
	 * Determine if the job is remote.
	 *
	 * @since %VERSION%
	 *
	 * @return bool
	 */
	public function is_remote() {
		return true;
	}

	/**
	 * Get the job address.
	 *
	 * @since %VERSION%
	 *
	 * @return array
	 */
	public function get_address() {
		return array();
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
				delete_post_meta( $this->get_id(), JMMeta::META_PREFIX . $key );
				continue;
			}

			update_post_meta( $this->get_id(), JMMeta::META_PREFIX . $key, $this->$key );
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
		return array();
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
		$meta = get_post_meta( $this->get_id() );

		foreach ( $this->get_lazy_properties() as $key => $default ) {
			$this->$key = array_key_exists( JMMeta::META_PREFIX . $key, $meta )
				? $meta[ JMMeta::META_PREFIX . $key ][0]
				: $default;
		}
	}
}
