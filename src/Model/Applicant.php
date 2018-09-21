<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use WP_Term;
use Yikes\LevelPlayingField\Field\Certifications;
use Yikes\LevelPlayingField\Field\Experience;
use Yikes\LevelPlayingField\Field\Schooling;
use Yikes\LevelPlayingField\Field\Volunteer;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

/**
 * Class Applicant
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 *
 * @property string email          The Applicant email address.
 * @property int    job            The Job ID.
 * @property string status         The Applicant status.
 * @property string cover_letter   The Applicant's cover letter.
 * @property array  schooling      The Applicant's schooling details.
 * @property array  certifications The Applicant's certifications.
 * @property array  skills         The Applicant's skills.
 * @property array  experience     The Applicant's experience.
 * @property array  volunteer      The Applicant's volunteer work.
 */
final class Applicant extends CustomPostTypeEntity {

	const SANITIZATION = [
		ApplicantMeta::JOB            => FILTER_SANITIZE_NUMBER_INT,
		ApplicantMeta::APPLICATION    => FILTER_SANITIZE_NUMBER_INT,
		ApplicantMeta::EMAIL          => FILTER_SANITIZE_EMAIL,
		ApplicantMeta::NAME           => FILTER_SANITIZE_STRING,
		ApplicantMeta::COVER_LETTER   => FILTER_SANITIZE_STRING,
		ApplicantMeta::STATUS         => FILTER_SANITIZE_STRING,
		ApplicantMeta::SCHOOLING      => [
			ApplicantMeta::INSTITUTION => FILTER_SANITIZE_STRING,
			ApplicantMeta::TYPE        => FILTER_SANITIZE_STRING,
			ApplicantMeta::YEAR        => FILTER_SANITIZE_NUMBER_INT,
			ApplicantMeta::MAJOR       => FILTER_SANITIZE_STRING,
			ApplicantMeta::DEGREE      => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::CERTIFICATIONS => [
			ApplicantMeta::INSTITUTION => FILTER_SANITIZE_STRING,
			ApplicantMeta::YEAR        => FILTER_SANITIZE_NUMBER_INT,
			ApplicantMeta::TYPE        => FILTER_SANITIZE_STRING,
			ApplicantMeta::STATUS      => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::SKILLS         => [
			ApplicantMeta::SKILL       => FILTER_SANITIZE_STRING,
			ApplicantMeta::PROFICIENCY => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::EXPERIENCE     => [
			// todo: start and end dates.
			ApplicantMeta::ORGANIZATION => FILTER_SANITIZE_STRING,
			ApplicantMeta::INDUSTRY     => FILTER_SANITIZE_STRING,
			ApplicantMeta::POSITION     => FILTER_SANITIZE_NUMBER_INT,
		],
		ApplicantMeta::VOLUNTEER      => [
			// todo: start and end dates.
			ApplicantMeta::ORGANIZATION => FILTER_SANITIZE_STRING,
			ApplicantMeta::INDUSTRY     => FILTER_SANITIZE_STRING,
			ApplicantMeta::POSITION     => FILTER_SANITIZE_NUMBER_INT,
		],
	];

	/**
	 * The applicant status.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	private $status;

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
	 * Set the status of the current Applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $status The status.
	 */
	public function set_status( $status ) {
		$this->status = filter_var( $status, self::SANITIZATION[ ApplicantMeta::STATUS ] );
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
	 * Set the email for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $email The applicant's email address.
	 */
	public function set_email( $email ) {
		$this->email = filter_var( $email, self::SANITIZATION[ ApplicantMeta::EMAIL ] );
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
	 * Get the cover letter for the applicant.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_cover_letter() {
		return $this->cover_letter;
	}

	/**
	 * Set the cover letter for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $cover_letter The cover letter text.
	 */
	public function set_cover_letter( $cover_letter ) {
		$this->cover_letter = filter_var( $cover_letter, self::SANITIZATION[ ApplicantMeta::COVER_LETTER ] );
	}

	/**
	 * Get the schooling details for the applicant.
	 *
	 * @see   Schooling
	 * @since %VERSION%
	 * @return array
	 */
	public function get_schooling() {
		return $this->schooling;
	}

	/**
	 * Add a schooling to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $schooling Array of schooling data.
	 */
	public function add_schooling( array $schooling ) {
		// Remove any extraneous keys.
		$schooling = array_intersect_key( $schooling, self::SANITIZATION[ ApplicantMeta::SCHOOLING ] );

		// Sanitize each piece of data.
		foreach ( $schooling as $key => &$value ) {
			$value = filter_var( $value, self::SANITIZATION[ ApplicantMeta::SCHOOLING ][ $key ] );
		}

		$this->schooling[] = $schooling;
	}

	/**
	 * Set the schooling for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $schooling All schooling data.
	 */
	public function set_schooling( array $schooling ) {
		// Reset current schooling to empty array.
		$this->schooling = [];

		// Add each individual schooling.
		foreach ( $schooling as $item ) {
			$this->add_schooling( $item );
		}
	}

	/**
	 * Get the certifications for the applicant.
	 *
	 * @see   Certifications
	 * @since %VERSION%
	 * @return array
	 */
	public function get_certifications() {
		return $this->certifications;
	}

	/**
	 * Add a certification to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $certification The certification data.
	 */
	public function add_certification( array $certification ) {
		// Remove any extraneous keys.
		$certification = array_intersect_key( $certification, self::SANITIZATION[ ApplicantMeta::CERTIFICATIONS ] );

		// Sanitize each piece of data.
		foreach ( $certification as $key => &$value ) {
			$value = filter_var( $value, self::SANITIZATION[ ApplicantMeta::CERTIFICATIONS ][ $key ] );
		}

		$this->certifications[] = $certification;
	}

	/**
	 * Set the certification data for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $certifications All certification data.
	 */
	public function set_certifications( array $certifications ) {
		$this->certifications = [];

		foreach ( $certifications as $certification ) {
			$this->add_certification( $certification );
		}
	}

	/**
	 * Get the skills of the applicant.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_skills() {
		return $this->skills;
	}

	/**
	 * Get the job experience of the applicant.
	 *
	 * @see   Experience
	 * @since %VERSION%
	 * @return array
	 */
	public function get_job_experience() {
		return $this->experience;
	}

	/**
	 * Get the volunteer work for the applicant.
	 *
	 * @see   Volunteer
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_volunteer_work() {
		return $this->volunteer;
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
			ApplicantMeta::JOB            => 0,
			ApplicantMeta::APPLICATION    => 0,
			ApplicantMeta::EMAIL          => '',
			ApplicantMeta::NAME           => '',
			ApplicantMeta::COVER_LETTER   => '',
			ApplicantMeta::SCHOOLING      => [],
			ApplicantMeta::CERTIFICATIONS => [],
			ApplicantMeta::SKILLS         => [],
			ApplicantMeta::EXPERIENCE     => [],
			ApplicantMeta::VOLUNTEER      => [],
			ApplicantMeta::STATUS         => ApplicantStatus::DEFAULT_TERM_NAME,
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
		if ( ApplicantMeta::STATUS === $property ) {
			$this->load_status();
			return;
		}

		// Load other properties from post meta.
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

	/**
	 * Load the status of the Applicant.
	 *
	 * @since %VERSION%
	 */
	private function load_status() {
		/** @var WP_Term[] $terms */
		$terms = wp_get_object_terms( $this->get_id(), ApplicantStatus::SLUG );
		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			$this->status = $this->get_lazy_properties()[ ApplicantMeta::STATUS ];
			return;
		}

		$this->status = $terms[0]->name;
	}
}
