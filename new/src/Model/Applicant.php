<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\Model\ApplicantMeta as AMMeta;

/**
 * Class Applicant
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Applicant extends AnonymousCustomPostTypeEntity {

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since %VERSION%
	 */
	public function persist_properties() {
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			if ( $this->$key === $default ) {
				delete_post_meta( $this->get_id(), AMMeta::META_PREFIX . $key );
				continue;
			}

			update_post_meta( $this->get_id(), AMMeta::META_PREFIX . $key, $this->$key );
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
			$this->$key = array_key_exists( AMMeta::META_PREFIX . $key, $meta )
				? $meta[ AMMeta::META_PREFIX . $key ][0]
				: $default;
		}
	}

	protected function get_anonymous_properties() {
		// TODO: Implement get_anonymous_properties() method.
	}
}
