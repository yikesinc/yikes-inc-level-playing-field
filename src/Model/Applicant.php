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
use Yikes\LevelPlayingField\Exception\EmptyArray;
use Yikes\LevelPlayingField\Exception\InvalidKey;
use Yikes\LevelPlayingField\Field\Certifications;
use Yikes\LevelPlayingField\Field\Experience;
use Yikes\LevelPlayingField\Field\Schooling;
use Yikes\LevelPlayingField\Field\Volunteer;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;
use Yikes\LevelPlayingField\RequiredPages\ApplicantMessagingPage;
use Yikes\LevelPlayingField\RequiredPages\BaseRequiredPage;
use Yikes\LevelPlayingField\Email\InterviewConfirmationToApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewConfirmationFromApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewCancellationFromApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewCancellationToApplicantEmail;

/**
 * Class Applicant
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 *
 * @property string email            The Applicant email address.
 * @property int    job              The Job ID.
 * @property string name             The Applicant's name.
 * @property int    application      The Application ID.
 * @property string status           The Applicant status.
 * @property string cover_letter     The Applicant's cover letter.
 * @property array  schooling        The Applicant's schooling details.
 * @property array  certifications   The Applicant's certifications.
 * @property array  skills           The Applicant's skills.
 * @property array  experience       The Applicant's experience.
 * @property array  volunteer        The Applicant's volunteer work.
 * @property string nickname         The Applicant's nickname (for use when their data is anonymous).
 * @property bool   anonymized       Whether the applicant is anonymized.
 * @property int    viewed           User ID who viewed the applicant.
 * @property array  interview        The Applicant's interview details.
 * @property string interview_status The Applicant's interview status.
 * @property string guid             A unique hash for the applicant.
 */
final class Applicant extends CustomPostTypeEntity {

	const SANITIZATION = [
		ApplicantMeta::JOB              => FILTER_SANITIZE_NUMBER_INT,
		ApplicantMeta::APPLICATION      => FILTER_SANITIZE_NUMBER_INT,
		ApplicantMeta::EMAIL            => FILTER_SANITIZE_EMAIL,
		ApplicantMeta::NAME             => FILTER_SANITIZE_STRING,
		ApplicantMeta::COVER_LETTER     => FILTER_SANITIZE_STRING,
		ApplicantMeta::STATUS           => FILTER_SANITIZE_STRING,
		ApplicantMeta::SCHOOLING        => [
			ApplicantMeta::INSTITUTION => FILTER_SANITIZE_STRING,
			ApplicantMeta::TYPE        => FILTER_SANITIZE_STRING,
			ApplicantMeta::YEAR        => FILTER_SANITIZE_NUMBER_INT,
			ApplicantMeta::MAJOR       => FILTER_SANITIZE_STRING,
			ApplicantMeta::DEGREE      => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::CERTIFICATIONS   => [
			ApplicantMeta::INSTITUTION => FILTER_SANITIZE_STRING,
			ApplicantMeta::YEAR        => FILTER_SANITIZE_NUMBER_INT,
			ApplicantMeta::TYPE        => FILTER_SANITIZE_STRING,
			ApplicantMeta::STATUS      => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::SKILLS           => [
			ApplicantMeta::SKILL       => FILTER_SANITIZE_STRING,
			ApplicantMeta::PROFICIENCY => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::EXPERIENCE       => [
			// todo: start and end dates.
			ApplicantMeta::ORGANIZATION => FILTER_SANITIZE_STRING,
			ApplicantMeta::INDUSTRY     => FILTER_SANITIZE_STRING,
			ApplicantMeta::POSITION     => FILTER_SANITIZE_NUMBER_INT,
		],
		ApplicantMeta::VOLUNTEER        => [
			// todo: start and end dates.
			ApplicantMeta::ORGANIZATION => FILTER_SANITIZE_STRING,
			ApplicantMeta::INDUSTRY     => FILTER_SANITIZE_STRING,
			ApplicantMeta::POSITION     => FILTER_SANITIZE_NUMBER_INT,
		],
		ApplicantMeta::NICKNAME         => FILTER_SANITIZE_STRING,
		ApplicantMeta::VIEWED           => FILTER_SANITIZE_NUMBER_INT,
		ApplicantMeta::INTERVIEW        => [
			ApplicantMeta::DATE     => FILTER_SANITIZE_STRING,
			ApplicantMeta::TIME     => FILTER_SANITIZE_STRING,
			ApplicantMeta::LOCATION => FILTER_SANITIZE_STRING,
			ApplicantMeta::MESSAGE  => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::INTERVIEW_STATUS => FILTER_SANITIZE_STRING,
		ApplicantMeta::GUID             => FILTER_SANITIZE_STRING,
	];

	/**
	 * Array of changed properties.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	private $changes = [];

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
		$this->changed_property( ApplicantMeta::STATUS );
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
		$this->changed_property( ApplicantMeta::EMAIL );
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
	 * Set the Job ID for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param int $job_id The job ID.
	 */
	public function set_job_id( $job_id ) {
		$this->job = filter_var( $job_id, self::SANITIZATION[ ApplicantMeta::JOB ] );
		$this->changed_property( ApplicantMeta::JOB );
	}

	/**
	 * Get the name of the applicant.
	 *
	 * @since %VERSION%
	 * @return string The applicant name.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Set the name of the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $name The applicant name.
	 */
	public function set_name( $name ) {
		$this->name = filter_var( $name, self::SANITIZATION[ ApplicantMeta::NAME ] );
		$this->changed_property( ApplicantMeta::NAME );
	}

	/**
	 * Get the ID of the application that the Applicant filled out.
	 *
	 * @since %VERSION%
	 * @return int
	 */
	public function get_application_id() {
		return $this->application;
	}

	/**
	 * Set the ID of the application that the Applicant filled out.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id The application ID.
	 */
	public function set_application_id( $id ) {
		$this->application = filter_var( $id, self::SANITIZATION[ ApplicantMeta::APPLICATION ] );
		$this->changed_property( ApplicantMeta::APPLICATION );
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
		$this->changed_property( ApplicantMeta::COVER_LETTER );
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
		$this->schooling[] = $this->filter_and_sanitize( $schooling, ApplicantMeta::SCHOOLING );
		$this->changed_property( ApplicantMeta::SCHOOLING );
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

		// Passing an empty array is a way to remove schooling.
		if ( empty( $schooling ) ) {
			$this->changed_property( ApplicantMeta::SCHOOLING );
			return;
		}

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
		$this->certifications[] = $this->filter_and_sanitize( $certification, ApplicantMeta::CERTIFICATIONS );
		$this->changed_property( ApplicantMeta::CERTIFICATIONS );
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

		// Passing an empty array is a way to remove certifications.
		if ( empty( $certifications ) ) {
			$this->changed_property( ApplicantMeta::CERTIFICATIONS );
			return;
		}

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
	 * Add a skill to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $skill The skill data.
	 */
	public function add_skill( array $skill ) {
		$this->skills[] = $this->filter_and_sanitize( $skill, ApplicantMeta::SKILLS );
		$this->changed_property( ApplicantMeta::SKILLS );
	}

	/**
	 * Set the skills for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $skills The skills for the applicant.
	 */
	public function set_skills( array $skills ) {
		$this->skills = [];

		// Passing an empty array is a way to remove skills.
		if ( empty( $skills ) ) {
			$this->changed_property( ApplicantMeta::SKILLS );
			return;
		}

		foreach ( $skills as $skill ) {
			$this->add_skill( $skill );
		}
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
	 * Add a experience to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $experience The experience data.
	 */
	public function add_experience( array $experience ) {
		$this->experience[] = $this->filter_and_sanitize( $experience, ApplicantMeta::EXPERIENCE );
		$this->changed_property( ApplicantMeta::EXPERIENCE );
	}

	/**
	 * Set the experiences for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $experiences The experiences for the applicant.
	 */
	public function set_experience( array $experiences ) {
		$this->experience = [];

		// Passing an empty array is a way to remove experiences.
		if ( empty( $experiences ) ) {
			$this->changed_property( ApplicantMeta::EXPERIENCE );
			return;
		}

		foreach ( $experiences as $experience ) {
			$this->add_experience( $experience );
		}
	}

	/**
	 * Get the volunteer work for the applicant.
	 *
	 * @see   Volunteer
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_volunteer() {
		return $this->volunteer;
	}

	/**
	 * Add volunteer work to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $volunteer Array of volunteer work.
	 */
	public function add_volunteer( array $volunteer ) {
		$this->volunteer[] = $this->filter_and_sanitize( $volunteer, ApplicantMeta::VOLUNTEER );
		$this->changed_property( ApplicantMeta::VOLUNTEER );
	}

	/**
	 * Set the volunteer work for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $volunteer The volunteer work for the applicant.
	 */
	public function set_volunteer( array $volunteer ) {
		$this->volunteer = [];

		// Passing an empty array will remove volunteer work.
		if ( empty( $volunteer ) ) {
			$this->changed_property( ApplicantMeta::VOLUNTEER );
			return;
		}

		foreach ( $volunteer as $item ) {
			$this->add_volunteer( $item );
		}
	}

	/**
	 * Get the nickname of the applicant.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_nickname() {
		return $this->nickname;
	}

	/**
	 * Set the nickname of the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $nickname The applicant nickname.
	 */
	public function set_nickname( $nickname ) {
		$this->nickname = filter_var( $nickname, self::SANITIZATION[ ApplicantMeta::NICKNAME ] );
		$this->changed_property( ApplicantMeta::NICKNAME );
	}

	/**
	 * Whether this applicant's data is currently anonymized.
	 *
	 * @since %VERSION%
	 * @return mixed
	 */
	public function is_anonymized() {
		return $this->anonymized;
	}

	/**
	 * Get the user ID who viewed the applicant.
	 *
	 * @since %VERSION%
	 * @return int
	 */
	public function viewed_by() {
		return $this->viewed;
	}

	/**
	 * Set the user who viewed the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id The user ID who viewed the applicant.
	 */
	public function set_viewed_by( $id ) {
		$this->viewed = filter_var( $id, self::SANITIZATION[ ApplicantMeta::VIEWED ] );
		$this->changed_property( ApplicantMeta::VIEWED );
	}

	/**
	 * Get the interview details for the applicant.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_interview() {
		return $this->interivew;
	}

	/**
	 * Set the interview details for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $interview The interview details for the applicant.
	 */
	public function set_interview( array $interview ) {
		$this->interview = [];

		// Passing an empty array will remove volunteer work.
		if ( empty( $interview ) ) {
			$this->changed_property( ApplicantMeta::INTERVIEW );
			return;
		}

		$this->interview = $this->filter_and_sanitize( $interview, ApplicantMeta::INTERVIEW );
		$this->changed_property( ApplicantMeta::INTERVIEW );
	}

	/**
	 * Get an interview request status.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_interview_status() {
		return $this->interview_status;
	}

	/**
	 * Set the status of an interview request.
	 *
	 * @since %VERSION%
	 *
	 * Possible values:
	 * - ''
	 * - 'scheduled'
	 * - 'confirmed'
	 * - 'cancelled'
	 *
	 * @param string $interview_status Whether an interview has been scheduled for this applicant.
	 */
	public function set_interview_status( string $interview_status ) {
		$this->interview_status = filter_var( $interview_status, self::SANITIZATION[ ApplicantMeta::INTERVIEW_STATUS ] );
		$this->changed_property( ApplicantMeta::INTERVIEW_STATUS );
	}

	/**
	 * Cancel an interview.
	 *
	 * @since %VERSION%
	 */
	public function cancel_interview() {

		// Don't allow cancellation of an interview that isn't scheduled, is already confirmed, or is already cancelled.
		if ( $this->get_interview_status() !== 'scheduled' ) {
			return;
		}

		// Maybe add a message like 'The applicant has confirmed the interview'?

		$this->set_interview_status( 'cancelled' );
		$this->set_interview( [] );
		$this->persist_properties();

		// Send off canceled interview email to both the applicant and job managers.
		( new InterviewCancellationToApplicantEmail( $this ) )->send();
		( new InterviewCancellationFromApplicantEmail( $this ) )->send();
	}

	/**
	 * Confirm an interview.
	 *
	 * @since %VERSION%
	 */
	public function confirm_interview() {
		$this->set_interview_status( 'confirmed' );
		$this->persist_properties();

		// Unanonymize!

		// Maybe add a message like 'The applicant has confirmed the interview'?

		// Send off confirmed interview email to both the applicant and job managers.
		( new InterviewConfirmationToApplicantEmail( $this ) )->send();
		( new InterviewConfirmationFromApplicantEmail( $this ) )->send();
	}

	/**
	 * Get the URL to the messaging page with the applicant's secret keys appended.
	 *
	 * @since %VERSION%
	 *
	 * @return string $messaging_endpoint The URL to the messaging page with the applicant's secret keys appended.
	 */
	public function get_messaging_endpoint() {
		return add_query_arg( [
			'guid' => $this->get_guid(),
			'post' => $this->get_id(),
		], get_permalink( BaseRequiredPage::get_required_page_id( ApplicantMessagingPage::PAGE_SLUG ) ) );
	}

	/**
	 * Get the endpoint that cancels a scheduled interview.
	 *
	 * @since %VERSION%
	 *
	 * @return string $cancellation_endpoint The endpoint that cancels a scheduled interview.
	 */
	public function get_cancellation_endpoint() {
		return add_query_arg( [
			'cancel' => '1',
		], $this->get_messaging_endpoint() );
	}

	/**
	 * Get the endpoint that confirms a scheduled interview.
	 *
	 * @since %VERSION%
	 *
	 * @return string $confirmation_endpoint The endpoint that confirms a scheduled interview.
	 */
	public function get_confirmation_endpoint() {
		return add_query_arg( [
			'confirm' => '1',
		], $this->get_messaging_endpoint() );
	}

	/**
	 * Create a unique hash/guid.
	 *
	 * @since %VERSION%
	 *
	 * @return string $guid A unique hash/guid.
	 */
	public function create_guid() {

		// wp_generate_uuid4() was added in WP4.7.
		if ( function_exists( 'wp_generate_uuid4' ) ) {
			return wp_generate_uuid4();
		} else {
			return uniqid( '', true );
		}
	}

	/**
	 * Set an applicant's guid.
	 *
	 * @todo throw an error if there is already a guid set for this applicant?
	 *
	 * @since %VERSION%
	 *
	 * @param string $guid A guid.
	 */
	public function set_guid( $guid ) {
		$this->guid = filter_var( $guid, self::SANITIZATION[ ApplicantMeta::GUID ] );
		$this->changed_property( ApplicantMeta::GUID );
	}

	/**
	 * Get the applicant's guid.
	 *
	 * @since %VERSION%
	 *
	 * @return string $guid The applicant's guid.
	 */
	public function get_guid() {
		return $this->guid;
	}

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since %VERSION%
	 */
	public function persist_properties() {
		// Always make sure we have a status set.
		if ( empty( $this->status ) ) {
			$this->set_status( ApplicantStatus::DEFAULT_TERM_SLUG );
		}

		foreach ( $this->get_lazy_properties() as $key => $default ) {
			// Only allow changes via class methods.
			if ( ! array_key_exists( $key, $this->changes ) ) {
				continue;
			}

			// Update the status.
			if ( ApplicantMeta::STATUS === $key ) {
				$this->persist_status();
			} elseif ( $this->$key === $default ) {
				// Default meta value can be deleted from the DB.
				delete_post_meta( $this->post->ID, ApplicantMeta::META_PREFIXES[ $key ] );
			} else {
				update_post_meta( $this->post->ID, ApplicantMeta::META_PREFIXES[ $key ], $this->$key );
			}

			unset( $this->changes[ $key ] );
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
			ApplicantMeta::JOB              => 0,
			ApplicantMeta::APPLICATION      => 0,
			ApplicantMeta::EMAIL            => '',
			ApplicantMeta::NAME             => '',
			ApplicantMeta::COVER_LETTER     => '',
			ApplicantMeta::SCHOOLING        => [],
			ApplicantMeta::CERTIFICATIONS   => [],
			ApplicantMeta::SKILLS           => [],
			ApplicantMeta::EXPERIENCE       => [],
			ApplicantMeta::VOLUNTEER        => [],
			ApplicantMeta::STATUS           => ApplicantStatus::DEFAULT_TERM_SLUG,
			ApplicantMeta::NICKNAME         => (string) $this->post->ID,
			ApplicantMeta::ANONYMIZED       => false,
			ApplicantMeta::VIEWED           => 0,
			ApplicantMeta::INTERVIEW_STATUS => '',
			ApplicantMeta::INTERVIEW        => [],
			ApplicantMeta::GUID             => '',
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
		$meta = $this->new ? [] : get_post_meta( $this->get_id() );
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			// Only include the meta we care about.
			if ( ! array_key_exists( $key, ApplicantMeta::META_PREFIXES ) ) {
				continue;
			}

			// If they key has been changed, don't overwrite the change.
			if ( array_key_exists( $key, $this->changes ) ) {
				continue;
			}

			$prefixed_key = ApplicantMeta::META_PREFIXES[ $key ];
			if ( array_key_exists( $prefixed_key, $meta ) ) {
				$this->$key = $meta[ $prefixed_key ][0];
			} else {
				$this->$key = $default;
				$this->changed_property( $key );
			}
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
			$this->changed_property( ApplicantMeta::STATUS );
			return;
		}

		$this->status = $terms[0]->slug;
	}

	/**
	 * Filter and sanitize data according to the sanitization rules.
	 *
	 * @since %VERSION%
	 *
	 * @param array  $data The data to filter and sanitize.
	 * @param string $key  The sanitization key.
	 *
	 * @return array
	 * @throws InvalidKey When the provided key is not in self::SANITIZATION.
	 * @throws EmptyArray When an empty array of data is provided.
	 */
	private function filter_and_sanitize( $data, $key ) {
		if ( ! array_key_exists( $key, self::SANITIZATION ) ) {
			throw InvalidKey::not_found( $key, __METHOD__ );
		}

		// Remove any extraneous keys.
		$data = array_intersect_key( $data, self::SANITIZATION[ $key ] );
		if ( empty( $data ) ) {
			throw EmptyArray::from_function( __METHOD__ );
		}

		// Sanitize each piece of data.
		foreach ( $data as $index => &$value ) {
			$value = filter_var( $value, self::SANITIZATION[ $key ][ $index ] );
		}

		return $data;
	}

	/**
	 * Persist the status of the applicant.
	 *
	 * @since %VERSION%
	 */
	private function persist_status() {
		wp_set_object_terms( $this->post->ID, $this->status, ApplicantStatus::SLUG );
	}

	/**
	 * Register that a property has been changed.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property that has changed.
	 */
	private function changed_property( $property ) {
		$this->changes[ $property ] = true;
	}
}
