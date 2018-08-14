<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\Model\ApplicationMeta as AMMeta;

/**
 * Class Application
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Application extends CustomPostTypeEntity {

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
		/**
		 * Filter the default enabled fields.
		 *
		 * Fields that are default enabled should be set to true, while default
		 * disabled fields are set to false.
		 *
		 * @param array $fields The array of fields.
		 */
		return apply_filters( 'lpf_application_fields_enabled_defaults', [
			AMMeta::NAME           => true,
			AMMeta::EMAIL          => true,
			AMMeta::PHONE          => false,
			AMMeta::ADDRESS        => false,
			AMMeta::COVER_LETTER   => false,
			AMMeta::EDUCATION      => false,
			AMMeta::CERTIFICATIONS => false,
			AMMeta::SKILLS         => false,
			AMMeta::LANGUAGES      => false,
			AMMeta::EXPERIENCE     => false,
			AMMeta::VOLUNTEER      => false,
		] );
	}

	/**
	 * Get the active fields for this application.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_active_fields() {
		$this->load_lazy_properties();
		return array_keys( array_filter( get_object_vars( $this ), function( $value ) {
			return true === $value;
		} ) );
	}

	/**
	 * Load all lazily-loaded properties.
	 *
	 * After this process, the loaded property should be set within the
	 * object's state, otherwise the load procedure might be triggered multiple
	 * times.
	 *
	 * @since %VERSION%
	 */
	protected function load_lazy_properties() {
		$meta = get_post_meta( $this->get_id() );
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			$this->$key = array_key_exists( AMMeta::META_PREFIX . $key, $meta )
				? (bool) $meta[ AMMeta::META_PREFIX . $key ][0]
				: $default;
		}
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
		if ( isset( $this->$property ) ) {
			return;
		}

		$this->load_lazy_properties();
	}
}
