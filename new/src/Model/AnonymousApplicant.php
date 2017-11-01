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
 * Class AnonymousApplicant
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class AnonymousApplicant extends AnonymousCustomPostTypeEntity {

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
		return array(
			'basic' => array(
				'name'         => true,
				'email'        => true,
				'phone'        => true,
				'address'      => array(
					// address fields
				),

				// Make it clear to NOT add name to body of letter
				'cover_letter' => true,
			),

			'education' => array(
				array(
					// Repeating
					'schooling' => array(
						'institution'      => true,
						'institution_type' => true, // selection options
						'year'             => true,
						'major'            => true,
						'degree'           => true,
					),
				),
				'certifications' => array(
					// Repeating
					array(
						'institution'      => true,
						'institution_type' => true,
						'year'             => true,
						'certification'    => true,
						'status'           => true,
					),
				),
			),

			'skills' => array(
				// Repeating
				array(
					'skill'       => true,
					'proficiency' => true,
				),
			),

			'languages'  => array(
				// Repeating
				array(
					'language'    => true,
					'proficiency' => true, // selection options
				),
			),

			// Hiring manager will only see "<industry> for <years> years"
			'experience' => array(
				// Repeating
				array(
					'organization' => true,
					'industry'     => true,
					'dates'        => true,
					'position'     => true,
				),
			),

			'volunteer' => array(
				array(
					'organization'      => true,
					'organization_type' => true,
					'dates'             => true,
					'position'          => true,
				),
			),
		);
	}

	/**
	 * Get an array of properties that need to be anonymized.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_anonymous_properties() {
		return array(
			'basic' => array(
				'name'    => true,
				'email'   => true,
				'phone'   => true,
				'address' => true,
			),

			'education' => array(
				'schooling'      => array(
					'institution' => true,
					'year'        => true,
				),
				'certifications' => array(
					'institution' => true,
					'year'        => true,
				),
			),

			'experience' => array(
				'organization' => true,
				'dates'        => true,
			),

			'volunteer' => array(
				'organization' => true,
				'dates'        => true,
			),
		);
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
}