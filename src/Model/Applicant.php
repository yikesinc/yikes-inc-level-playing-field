<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

/**
 * Class Applicant
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 *
 * @property string email  The Applicant email address.
 * @property int    job    The Job ID.
 * @property string status The Applicant status.
 */
final class Applicant extends CustomPostTypeEntity {

	/**
	 * The applicant status.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	private $status;

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
			$this->status = wp_get_object_terms( $this->get_id(), ApplicantStatus::SLUG )[0];

			return $this->status;
		}

		return parent::__get( $property );
	}

	/**
	 * Get the status of the applicant.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * Get the email address of the applicant.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_email() {
		return $this->email;
	}

	/**
	 * Get the Job ID for the applicant.
	 *
	 * @since %VERSION%
	 * @return int
	 */
	public function get_job_id() {
		return (int) $this->job;
	}

	/**
	 * Get the avatar image tag.
	 *
	 * @since %VERSION%
	 *
	 * @param int $size The image size.
	 *
	 * @return string The avatar image tag, or an empty string.
	 */
	public function get_avatar_img( $size = 32 ) {
		$avatar = get_avatar( $this->get_email(), $size, 'identicon', '', [
			'force_default' => true,
			'force_display' => true,
		] );

		return $avatar ?: '';
	}

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since %VERSION%
	 */
	public function persist_properties() {
		// TODO: Implement persist_properties() method.
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
			JobManager::SINGULAR_SLUG         => 0,
			ApplicationManager::SINGULAR_SLUG => 0,
			ApplicantMeta::EMAIL              => '',
			ApplicantMeta::NAME               => '',

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
		// Load properties from post meta.
		$meta = get_post_meta( $this->get_id() );
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			if ( ! array_key_exists( $key, ApplicantMeta::META_PREFIXES ) ) {
				continue;
			}

			$prefixed_key = ApplicantMeta::META_PREFIXES[ $key ];
			$this->$key   = array_key_exists( $prefixed_key, $meta )
				? $meta[ $prefixed_key ][0]
				: $default;
		}
	}
}
