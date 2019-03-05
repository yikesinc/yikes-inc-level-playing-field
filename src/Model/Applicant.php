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
use Yikes\LevelPlayingField\Anonymizer\AnonymizerInterface;
use Yikes\LevelPlayingField\Email\InterviewCancellationFromApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewCancellationToApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewConfirmationFromApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewConfirmationToApplicantEmail;
use Yikes\LevelPlayingField\Exception\EmptyArray;
use Yikes\LevelPlayingField\Exception\FailedToUnanonymize;
use Yikes\LevelPlayingField\Exception\InvalidApplicantValue;
use Yikes\LevelPlayingField\Exception\InvalidClass;
use Yikes\LevelPlayingField\Exception\InvalidKey;
use Yikes\LevelPlayingField\Exception\InvalidMethod;
use Yikes\LevelPlayingField\Field\Certifications;
use Yikes\LevelPlayingField\Field\Experience;
use Yikes\LevelPlayingField\Field\Schooling;
use Yikes\LevelPlayingField\Field\Volunteer;
use Yikes\LevelPlayingField\RequiredPages\ApplicantMessagingPage;
use Yikes\LevelPlayingField\Roles\Capabilities;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

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
		ApplicantMeta::COVER_LETTER     => FILTER_UNSAFE_RAW,
		ApplicantMeta::PHONE            => FILTER_SANITIZE_NUMBER_INT,
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
			ApplicantMeta::TYPE        => FILTER_SANITIZE_STRING,
			ApplicantMeta::CERT_TYPE   => FILTER_SANITIZE_STRING,
			ApplicantMeta::YEAR        => FILTER_SANITIZE_NUMBER_INT,
			ApplicantMeta::STATUS      => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::SKILLS           => [
			ApplicantMeta::SKILL       => FILTER_SANITIZE_STRING,
			ApplicantMeta::PROFICIENCY => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::EXPERIENCE       => [
			ApplicantMeta::ORGANIZATION     => FILTER_SANITIZE_STRING,
			ApplicantMeta::INDUSTRY         => FILTER_SANITIZE_STRING,
			ApplicantMeta::POSITION         => FILTER_SANITIZE_STRING,
			ApplicantMeta::START_DATE       => FILTER_SANITIZE_STRING,
			ApplicantMeta::END_DATE         => FILTER_SANITIZE_STRING,
			ApplicantMeta::PRESENT_POSITION => FILTER_SANITIZE_NUMBER_INT,
			ApplicantMeta::YEAR_DURATION    => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::VOLUNTEER        => [
			ApplicantMeta::ORGANIZATION     => FILTER_SANITIZE_STRING,
			ApplicantMeta::INDUSTRY         => FILTER_SANITIZE_STRING,
			ApplicantMeta::POSITION         => FILTER_SANITIZE_STRING,
			ApplicantMeta::START_DATE       => FILTER_SANITIZE_STRING,
			ApplicantMeta::END_DATE         => FILTER_SANITIZE_STRING,
			ApplicantMeta::PRESENT_POSITION => FILTER_SANITIZE_NUMBER_INT,
			ApplicantMeta::YEAR_DURATION    => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::NICKNAME         => FILTER_SANITIZE_STRING,
		ApplicantMeta::VIEWED           => FILTER_SANITIZE_NUMBER_INT,
		ApplicantMeta::ADDRESS          => [
			ApplicantMeta::LINE_1  => FILTER_SANITIZE_STRING,
			ApplicantMeta::LINE_2  => FILTER_SANITIZE_STRING,
			ApplicantMeta::CITY    => FILTER_SANITIZE_STRING,
			ApplicantMeta::STATE   => FILTER_SANITIZE_STRING,
			ApplicantMeta::COUNTRY => FILTER_SANITIZE_STRING,
			ApplicantMeta::ZIP     => FILTER_SANITIZE_NUMBER_INT,
		],
		ApplicantMeta::INTERVIEW        => [
			ApplicantMeta::DATE     => FILTER_SANITIZE_STRING,
			ApplicantMeta::TIME     => FILTER_SANITIZE_STRING,
			ApplicantMeta::LOCATION => FILTER_SANITIZE_STRING,
			ApplicantMeta::MESSAGE  => FILTER_SANITIZE_STRING,
		],
		ApplicantMeta::INTERVIEW_STATUS => FILTER_SANITIZE_STRING,
		ApplicantMeta::GUID             => FILTER_SANITIZE_STRING,
		ApplicantMeta::LANGUAGES        => [
			ApplicantMeta::LANGUAGE    => FILTER_SANITIZE_STRING,
			ApplicantMeta::PROFICIENCY => FILTER_SANITIZE_STRING,
		],
	];

	/**
	 * The anonymizer class used for anonymization.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	private $anonymizer = '';

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
		return $this->{ApplicantMeta::STATUS};
	}

	/**
	 * Set the status of the current Applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $status The status.
	 */
	public function set_status( $status ) {
		$this->set_property( ApplicantMeta::STATUS, $status );
	}

	/**
	 * Get the email address of the applicant.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_email() {
		return $this->{ApplicantMeta::EMAIL};
	}

	/**
	 * Set the email for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $email The applicant's email address.
	 */
	public function set_email( $email ) {
		$this->set_property( ApplicantMeta::EMAIL, $email );
	}

	/**
	 * Get the Job ID for the applicant.
	 *
	 * @since %VERSION%
	 * @return int
	 */
	public function get_job_id() {
		return (int) $this->{ApplicantMeta::JOB};
	}

	/**
	 * Set the Job ID for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param int $job_id The job ID.
	 */
	public function set_job_id( $job_id ) {
		$this->set_property( ApplicantMeta::JOB, $job_id );
	}

	/**
	 * Get the name of the applicant.
	 *
	 * @since %VERSION%
	 * @return string The applicant name.
	 */
	public function get_name() {
		return $this->{ApplicantMeta::NAME};
	}

	/**
	 * Set the name of the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $name The applicant name.
	 */
	public function set_name( $name ) {
		$this->set_property( ApplicantMeta::NAME, $name );
	}

	/**
	 * Get the phone of the applicant.
	 *
	 * @since %VERSION%
	 * @return int The applicant phone.
	 */
	public function get_phone() {
		return $this->{ApplicantMeta::PHONE};
	}

	/**
	 * Set the phone of the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param int $phone The applicant's phone.
	 */
	public function set_phone( $phone ) {
		$this->set_property( ApplicantMeta::PHONE, $phone );
	}

	/**
	 * Get the ID of the application that the Applicant filled out.
	 *
	 * @since %VERSION%
	 * @return int
	 */
	public function get_application_id() {
		return (int) $this->{ApplicantMeta::APPLICATION};
	}

	/**
	 * Set the ID of the application that the Applicant filled out.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id The application ID.
	 */
	public function set_application_id( $id ) {
		$this->set_property( ApplicantMeta::APPLICATION, $id );
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
		/*
		 * Use different arguments depending on whether the applicant is anonymized.
		 * Non-anonymized applicants are allowed to show their regular avatars.
		 */
		$avatar = get_avatar( $this->get_email(), $size, 'identicon', '', [
			'force_default' => $this->is_anonymized(),
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
		return $this->{ApplicantMeta::COVER_LETTER};
	}

	/**
	 * Set the cover letter for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $cover_letter The cover letter text.
	 */
	public function set_cover_letter( $cover_letter ) {
		$this->set_property( ApplicantMeta::COVER_LETTER, $cover_letter );
	}

	/**
	 * Get the schooling details for the applicant.
	 *
	 * @see   Schooling
	 * @since %VERSION%
	 * @return array
	 */
	public function get_schooling() {
		return $this->{ApplicantMeta::SCHOOLING};
	}

	/**
	 * Add a schooling to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $schooling Array of schooling data.
	 */
	public function add_schooling( array $schooling ) {
		$this->{ApplicantMeta::SCHOOLING}[] = $this->filter_and_sanitize( $schooling, ApplicantMeta::SCHOOLING );
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
		$this->{ApplicantMeta::SCHOOLING} = [];

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
		return $this->{ApplicantMeta::CERTIFICATIONS};
	}

	/**
	 * Add a certification to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $certification The certification data.
	 */
	public function add_certification( array $certification ) {
		$this->{ApplicantMeta::CERTIFICATIONS}[] = $this->filter_and_sanitize(
			$certification,
			ApplicantMeta::CERTIFICATIONS
		);
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
		$this->{ApplicantMeta::CERTIFICATIONS} = [];

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
		return $this->{ApplicantMeta::SKILLS};
	}

	/**
	 * Add a skill to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $skill The skill data.
	 */
	public function add_skill( array $skill ) {
		$this->{ApplicantMeta::SKILLS}[] = $this->filter_and_sanitize( $skill, ApplicantMeta::SKILLS );
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
		$this->{ApplicantMeta::SKILLS} = [];

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
	public function get_experience() {
		return $this->{ApplicantMeta::EXPERIENCE};
	}

	/**
	 * Add a experience to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $experience The experience data.
	 */
	public function add_experience( array $experience ) {
		// Calculate duration between start and end dates.
		$start = date_create( $experience[ ApplicantMeta::START_DATE ] );
		$end   = date_create( $experience[ ApplicantMeta::END_DATE ] );
		$diff  = date_diff( $start, $end );
		$still = ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) ? __( '(actively working here)', 'yikes-level-playing-field' ) : '';

		// Add calculated duration to experience and save.
		$experience[ ApplicantMeta::YEAR_DURATION ] = $diff instanceof DateInterval
			? $diff->format( "%y Year(s) %m Month(s) %d Days {$still}" )
			: '';
		$this->{ApplicantMeta::EXPERIENCE}[]        = $this->filter_and_sanitize( $experience, ApplicantMeta::EXPERIENCE );
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
		$this->{ApplicantMeta::EXPERIENCE} = [];

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
		return $this->{ApplicantMeta::VOLUNTEER};
	}

	/**
	 * Add volunteer work to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $volunteer Array of volunteer work.
	 */
	public function add_volunteer( array $volunteer ) {
		// Calculate duration between start and end dates.
		$start = date_create( $volunteer[ ApplicantMeta::START_DATE ] );
		$end   = date_create( $volunteer[ ApplicantMeta::END_DATE ] );
		$diff  = date_diff( $start, $end );
		$still = ! empty( $volunteer[ ApplicantMeta::PRESENT_POSITION ] ) ? __( '(actively volunteering here)', 'yikes-level-playing-field' ) : '';

		// Add calculated duration to volunteer experience and save.
		$volunteer[ ApplicantMeta::YEAR_DURATION ] = $diff instanceof DateInterval
			? $diff->format( "%y Year(s) %m Month(s) %d Days {$still}" )
			: '';
		$this->{ApplicantMeta::VOLUNTEER}[]        = $this->filter_and_sanitize( $volunteer, ApplicantMeta::VOLUNTEER );
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
		$this->{ApplicantMeta::VOLUNTEER} = [];

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
		return $this->{ApplicantMeta::NICKNAME};
	}

	/**
	 * Set the nickname of the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $nickname The applicant nickname.
	 */
	public function set_nickname( $nickname ) {
		$this->set_property( ApplicantMeta::NICKNAME, $nickname );
	}

	/**
	 * Get the address of the applicant.
	 *
	 * When the applicant is anonymized, only the City and State will be returned.
	 *
	 * @since %VERSION%
	 * @return array The address data.
	 */
	public function get_address() {
		return $this->is_anonymized()
			? array_intersect_key(
				$this->{ApplicantMeta::ADDRESS},
				[
					ApplicantMeta::CITY  => 1,
					ApplicantMeta::STATE => 1,
				]
			)
			: $this->{ApplicantMeta::ADDRESS};
	}

	/**
	 * Set the address of the Applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $address The array of address data.
	 */
	public function set_address( $address ) {
		$this->{ApplicantMeta::ADDRESS} = $this->filter_and_sanitize( $address, ApplicantMeta::ADDRESS );
		$this->changed_property( ApplicantMeta::ADDRESS );
	}

	/**
	 * Whether this applicant's data is currently anonymized.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_anonymized() {
		return (bool) $this->{ApplicantMeta::ANONYMIZED};
	}

	/**
	 * Get the user ID who viewed the applicant.
	 *
	 * @since %VERSION%
	 * @return int
	 */
	public function viewed_by() {
		return (int) $this->{ApplicantMeta::VIEWED};
	}

	/**
	 * Set the user who viewed the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id The user ID who viewed the applicant.
	 */
	public function set_viewed_by( $id ) {
		$this->set_property( ApplicantMeta::VIEWED, $id );
	}

	/**
	 * Get the languages and proficiency.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_languages() {
		return $this->{ApplicantMeta::LANGUAGES};
	}

	/**
	 * Add a language to the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $language Array of language data.
	 */
	public function add_language( array $language ) {
		$this->{ApplicantMeta::LANGUAGES}[] = $this->filter_and_sanitize( $language, ApplicantMeta::LANGUAGES );
		$this->changed_property( ApplicantMeta::LANGUAGES );
	}

	/**
	 * Set the languages of the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $languages Array of language arrays.
	 */
	public function set_languages( array $languages ) {
		$this->{ApplicantMeta::LANGUAGES} = [];

		if ( empty( $languages ) ) {
			$this->changed_property( ApplicantMeta::LANGUAGES );
			return;
		}

		foreach ( $languages as $language ) {
			$this->add_language( $language );
		}
	}

	/**
	 * Get the interview details for the applicant.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	public function get_interview() {
		return $this->{ApplicantMeta::INTERVIEW};
	}

	/**
	 * Set the interview details for the applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param array $interview The interview details for the applicant.
	 */
	public function set_interview( array $interview ) {
		$this->{ApplicantMeta::INTERVIEW} = [];

		// Passing an empty array will remove volunteer work.
		if ( empty( $interview ) ) {
			$this->changed_property( ApplicantMeta::INTERVIEW );
			return;
		}

		$this->{ApplicantMeta::INTERVIEW} = $this->filter_and_sanitize( $interview, ApplicantMeta::INTERVIEW );
		$this->changed_property( ApplicantMeta::INTERVIEW );
	}

	/**
	 * Get an interview request status.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_interview_status() {
		return $this->{ApplicantMeta::INTERVIEW_STATUS};
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
		$this->{ApplicantMeta::INTERVIEW_STATUS} = filter_var( $interview_status, self::SANITIZATION[ ApplicantMeta::INTERVIEW_STATUS ] );
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

		// todo: Maybe add a message like 'The applicant has confirmed the interview'?
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

		/*
		 * todo: Unanonymize!
		 * todo: Maybe add a message like 'The applicant has confirmed the interview'?
		 */

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
		], get_permalink( ( new ApplicantMessagingPage() )->get_page_id( ApplicantMessagingPage::PAGE_SLUG ) ) );
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
		if ( empty( $this->{ApplicantMeta::STATUS} ) ) {
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
	 * Anonymize this applicant's data.
	 *
	 * @since %VERSION%
	 *
	 * @param AnonymizerInterface $anonymizer The anonymizer object.
	 */
	public function anonymize( AnonymizerInterface $anonymizer ) {
		// Don't anonymize multiple times.
		if ( $this->is_anonymized() ) {
			return;
		}

		// Walk through the object properties, anonymizing them.
		$properties = array_diff_key( get_object_vars( $this ), $this->get_excluded_properties() );
		array_walk_recursive( $properties, $this->get_anonymizer_callback( $anonymizer, 'anonymize' ) );

		// Manually set anonymizer properties.
		$properties[ ApplicantMeta::ANONYMIZED ] = true;
		$properties[ ApplicantMeta::ANONYMIZER ] = get_class( $anonymizer );

		// Copy the changed properties back.
		$this->update_properties( $properties );
	}

	/**
	 * Get the callback for anonymizing.
	 *
	 * The Closure that is returned by this method is expected to be compatible with array_walk_recursive().
	 *
	 * @since %VERSION%
	 *
	 * @param AnonymizerInterface $anonymizer The anonymizer object.
	 * @param string              $method     The method to use from the anonymizer class.
	 *
	 * @return \Closure
	 * @throws InvalidMethod When the method provided doesn't exist for the anonymizer object.
	 */
	private function get_anonymizer_callback( AnonymizerInterface $anonymizer, $method ) {
		// Sanity check: make sure the method exists.
		if ( ! method_exists( $anonymizer, $method ) ) {
			throw InvalidMethod::from_method( $anonymizer, $method );
		}

		$defaults = $this->get_lazy_properties();
		return function( &$value, $key ) use ( $anonymizer, $defaults, $method ) {
			if ( ! array_key_exists( $key, ApplicantMeta::ANONYMOUS_FIELDS ) ) {
				return;
			}

			if ( isset( $defaults[ $key ] ) && $value === $defaults[ $key ] ) {
				return;
			}

			$value = $anonymizer->{$method}( $value );
		};
	}

	/**
	 * Unanonymize this Applicant's data.
	 *
	 * Role checking should be handled outside of this function.
	 *
	 * @since %VERSION%
	 *
	 * @param AnonymizerInterface $anonymizer The anonymizer object.
	 *
	 * @throws InvalidClass When the passed anonymizer object does not match the type used to anonymize the applicant.
	 * @throws FailedToUnanonymize When the current user is not capable of unanonyming.
	 */
	public function unanonymize( AnonymizerInterface $anonymizer ) {
		// Nothing to do if this isn't anonymized.
		if ( ! $this->is_anonymized() ) {
			return;
		}

		// Don't allow unanonymizing without the proper role.
		if ( ! current_user_can( Capabilities::UNANONYMIZE, $this ) ) {
			throw FailedToUnanonymize::not_capable();
		}

		// Ensure the unanonymizer class is the same that was used to anonymize.
		if ( get_class( $anonymizer ) !== $this->anonymizer ) {
			throw InvalidClass::mismatch( get_class( $anonymizer ), $this->anonymizer );
		}

		// Walk through the object properties, unanonymizing them.
		$properties = array_diff_key( get_object_vars( $this ), $this->get_excluded_properties() );
		array_walk_recursive( $properties, $this->get_anonymizer_callback( $anonymizer, 'reveal' ) );

		// Set the anonymized property.
		$properties[ ApplicantMeta::ANONYMIZED ] = false;
		$properties[ ApplicantMeta::ANONYMIZER ] = '';

		// Copy the changed properties back.
		$this->update_properties( $properties );
	}

	/**
	 * Copy changed properties back to the object.
	 *
	 * @since %VERSION%
	 *
	 * @param array $properties Properties changed by anonymization process.
	 */
	private function update_properties( $properties ) {
		foreach ( $properties as $key => $value ) {
			if ( $value !== $this->$key ) {
				$this->$key = $value;
				$this->changed_property( $key );
			}
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
			ApplicantMeta::PHONE            => '',
			ApplicantMeta::COVER_LETTER     => '',
			ApplicantMeta::SCHOOLING        => [],
			ApplicantMeta::CERTIFICATIONS   => [],
			ApplicantMeta::SKILLS           => [],
			ApplicantMeta::EXPERIENCE       => [],
			ApplicantMeta::VOLUNTEER        => [],
			ApplicantMeta::STATUS           => ApplicantStatus::DEFAULT_TERM_SLUG,
			ApplicantMeta::NICKNAME         => (string) $this->post->ID,
			ApplicantMeta::ANONYMIZER       => '',
			ApplicantMeta::ANONYMIZED       => false,
			ApplicantMeta::VIEWED           => 0,
			ApplicantMeta::ADDRESS          => [],
			ApplicantMeta::INTERVIEW_STATUS => '',
			ApplicantMeta::INTERVIEW        => [],
			ApplicantMeta::GUID             => '',
			ApplicantMeta::LANGUAGES        => [],
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
				$this->$key = maybe_unserialize( $meta[ $prefixed_key ][0] );
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
			$this->{ApplicantMeta::STATUS} = $this->get_lazy_properties()[ ApplicantMeta::STATUS ];
			$this->changed_property( ApplicantMeta::STATUS );
			return;
		}

		$this->{ApplicantMeta::STATUS} = $terms[0]->slug;
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
	 * Set a property for this Applicant.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property to set.
	 * @param string $value    The value of that property.
	 *
	 * @throws InvalidApplicantValue When the value is not valid, or when there is no sanitization setting.
	 */
	private function set_property( $property, $value ) {
		if ( ! array_key_exists( $property, self::SANITIZATION ) ) {
			throw InvalidApplicantValue::no_sanitization( $property );
		}

		$filtered = filter_var( $value, self::SANITIZATION[ $property ] );
		if ( false === $value ) {
			throw InvalidApplicantValue::property_value( $property, $value );
		}

		$this->$property = $filtered;
		$this->changed_property( $property );
	}

	/**
	 * Persist the status of the applicant.
	 *
	 * @since %VERSION%
	 */
	private function persist_status() {
		wp_set_object_terms( $this->post->ID, $this->{ApplicantMeta::STATUS}, ApplicantStatus::SLUG );
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

	/**
	 * Get properties to exclude from anonymizer.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	private function get_excluded_properties() {
		static $properties = [];
		if ( empty( $properties ) ) {
			$properties = [
				ApplicantMeta::ANONYMIZER => 1,
				'changes'                 => 1,
				'post'                    => 1,
				'new'                     => 1,
				'post_changed'            => 1,
			];
		}

		return $properties;
	}
}
