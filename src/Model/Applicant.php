<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use DateInterval;
use WP_Term;
use Yikes\LevelPlayingField\Anonymizer\AnonymizerInterface;
use Yikes\LevelPlayingField\Comment\ApplicantMessage;
use Yikes\LevelPlayingField\Email\InterviewCancellationFromApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewCancellationToApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewConfirmationFromApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewConfirmationToApplicantEmail;
use Yikes\LevelPlayingField\Exception\EmptyArray;
use Yikes\LevelPlayingField\Exception\FailedToUnanonymize;
use Yikes\LevelPlayingField\Exception\InvalidApplicantValue;
use Yikes\LevelPlayingField\Exception\InvalidClass;
use Yikes\LevelPlayingField\Exception\InvalidMethod;
use Yikes\LevelPlayingField\Exception\InvalidProperty;
use Yikes\LevelPlayingField\Field\Certifications;
use Yikes\LevelPlayingField\Field\Experience;
use Yikes\LevelPlayingField\Field\Schooling;
use Yikes\LevelPlayingField\Field\Volunteer;
use Yikes\LevelPlayingField\RequiredPages\ApplicantMessagingPage;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

/**
 * Class Applicant
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class Applicant extends CustomPostTypeEntity {

	use ApplicantMetaDropdowns;

	/**
	 * The anonymizer class used for anonymization.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $anonymizer = '';

	/**
	 * Get the status of the applicant.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_status() {
		return $this->get_property( ApplicantMeta::STATUS );
	}

	/**
	 * Set the status of the current Applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param string $status The status.
	 */
	public function set_status( $status ) {
		$this->set_property( ApplicantMeta::STATUS, $status );
	}

	/**
	 * Get the email address of the applicant.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_email() {
		return $this->get_property( ApplicantMeta::EMAIL );
	}

	/**
	 * Get the email address of the applicant for email communication.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_email_for_send() {
		return parent::get_property( ApplicantMeta::EMAIL );
	}

	/**
	 * Set the email for the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param string $email The applicant's email address.
	 */
	public function set_email( $email ) {
		$this->set_property( ApplicantMeta::EMAIL, $email );
	}

	/**
	 * Get the Job ID for the applicant.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function get_job_id() {
		return (int) $this->get_property( ApplicantMeta::JOB );
	}

	/**
	 * Set the Job ID for the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param int $job_id The job ID.
	 */
	public function set_job_id( $job_id ) {
		$this->set_property( ApplicantMeta::JOB, $job_id );
	}

	/**
	 * Get the name of the applicant.
	 *
	 * @since 1.0.0
	 * @return string The applicant name.
	 */
	public function get_name() {
		return $this->get_property( ApplicantMeta::NAME );
	}

	/**
	 * Set the name of the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The applicant name.
	 */
	public function set_name( $name ) {
		$this->set_property( ApplicantMeta::NAME, $name );
	}

	/**
	 * Get the phone of the applicant.
	 *
	 * @since 1.0.0
	 * @return int The applicant phone.
	 */
	public function get_phone() {
		return $this->get_property( ApplicantMeta::PHONE );
	}

	/**
	 * Set the phone of the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param int $phone The applicant's phone.
	 */
	public function set_phone( $phone ) {
		$this->set_property( ApplicantMeta::PHONE, $phone );
	}

	/**
	 * Get the ID of the application that the Applicant filled out.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function get_application_id() {
		return (int) $this->get_property( ApplicantMeta::APPLICATION );
	}

	/**
	 * Set the ID of the application that the Applicant filled out.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The application ID.
	 */
	public function set_application_id( $id ) {
		$this->set_property( ApplicantMeta::APPLICATION, $id );
	}

	/**
	 * Get the avatar image tag.
	 *
	 * @since 1.0.0
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
		$avatar = get_avatar(
			$this->get_email_for_send(),
			$size,
			'identicon',
			'',
			[
				'force_default' => $this->is_anonymized(),
				'force_display' => true,
			]
		);

		return $avatar ?: '';
	}

	/**
	 * Get the cover letter for the applicant.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_cover_letter() {
		return $this->get_property( ApplicantMeta::COVER_LETTER );
	}

	/**
	 * Set the cover letter for the applicant.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return array
	 */
	public function get_schooling() {
		$schooling       = [];
		$type_selections = $this->get_schooling_options();
		foreach ( $this->{ApplicantMeta::SCHOOLING} as $school ) {
			if ( $this->is_anonymized() ) {
				if ( 'high_school' === $school['type'] ) {
					$schooling[] = esc_html__(
						'Graduated from High School or High School equivalent',
						'level-playing-field'
					);
				} else {
					$schooling[] = sprintf(
						/* translators: 1 is the degree name, 2 is the school type, 3 is the major name */
						'Graduated with a %1$s from %2$s with a major in %3$s',
						esc_html( $school[ ApplicantMeta::DEGREE ] ),
						esc_html( $type_selections[ $school['type'] ] ),
						esc_html( $school[ ApplicantMeta::MAJOR ] )
					);
				}
			} else {
				if ( 'high_school' === $school['type'] ) {
					$schooling[] = sprintf(
						/* translators: 1 is the school name, 2 is the year */
						'Graduated from %s (High School or High School equivalent) in %s',
						esc_html( $school[ ApplicantMeta::INSTITUTION ] ),
						esc_html( $school[ ApplicantMeta::YEAR ] )
					);
				} else {
					$schooling[] = sprintf(
						/* translators: 1 is the year, 2 is the degree, 3 is the school name, 4 is the major */
						'Graduated in %s with a %s from %s with a major in %s',
						esc_html( $school[ ApplicantMeta::YEAR ] ),
						esc_html( $school[ ApplicantMeta::DEGREE ] ),
						esc_html( $school[ ApplicantMeta::INSTITUTION ] ),
						esc_html( $school[ ApplicantMeta::MAJOR ] )
					);
				}
			}
		}

		return $schooling;
	}

	/**
	 * Add a schooling to the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param array $schooling Array of schooling data.
	 */
	public function add_schooling( array $schooling ) {
		$this->add_property( ApplicantMeta::SCHOOLING, $schooling );
	}

	/**
	 * Set the schooling for the applicant.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return array
	 */
	public function get_certifications() {
		$certifications = [];
		foreach ( $this->{ApplicantMeta::CERTIFICATIONS} as $certification ) {
			if ( $this->is_anonymized() ) {
				$certifications[] = sprintf(
					/* translators: %1$s: certification type. %2$s: institution type. %3$s: status. */
					__( 'Certified in %1$s from %2$s. Status: %3$s', 'level-playing-field' ),
					esc_html( $certification[ ApplicantMeta::CERT_TYPE ] ),
					esc_html( $certification[ ApplicantMeta::TYPE ] ),
					esc_html( $certification[ ApplicantMeta::STATUS ] )
				);
			} else {
				$certifications[] = sprintf(
					/* translators: %1$s: certification type. %2$s: institution. %3$s: status. %4$s: year. */
					__( 'Certified in %1$s from %2$s. Status: %3$s. Year: %4$s.', 'level-playing-field' ),
					esc_html( $certification[ ApplicantMeta::CERT_TYPE ] ),
					esc_html( $certification[ ApplicantMeta::INSTITUTION ] ),
					esc_html( $certification[ ApplicantMeta::STATUS ] ),
					esc_html( $certification[ ApplicantMeta::YEAR ] )
				);
			}
		}

		return $certifications;
	}

	/**
	 * Add a certification to the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param array $certification The certification data.
	 */
	public function add_certification( array $certification ) {
		$this->add_property( ApplicantMeta::CERTIFICATIONS, $certification );
	}

	/**
	 * Set the certification data for the applicant.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return array
	 */
	public function get_skills() {
		return $this->get_property( ApplicantMeta::SKILLS );
	}

	/**
	 * Add a skill to the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param array $skill The skill data.
	 */
	public function add_skill( array $skill ) {
		$this->add_property( ApplicantMeta::SKILLS, $skill );
	}

	/**
	 * Set the skills for the applicant.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return array
	 */
	public function get_experience() {
		$experiences = [];
		foreach ( $this->{ApplicantMeta::EXPERIENCE} as $experience ) {
			if ( $this->is_anonymized() ) {
				$experiences[] = sprintf(
					/* translators: %1$s: position. %2$s: industry. %3$s: number of years. */
					__( '%1$s in %2$s %3$s', 'level-playing-field' ),
					esc_html( $experience[ ApplicantMeta::POSITION ] ),
					esc_html( $experience[ ApplicantMeta::INDUSTRY ] ),
					! empty( $experience[ ApplicantMeta::YEAR_DURATION ] ) ? esc_html( 'for ' . $experience[ ApplicantMeta::YEAR_DURATION ] ) : ''
				);
			} else {
				$experiences[] = sprintf(
					/* translators: %1$s: position. %2$s: industry. %3$s: organization. %4$s: start date. %5$s: end date. */
					__( '%1$s in %2$s at %3$s from %4$s to %5$s', 'level-playing-field' ),
					esc_html( $experience[ ApplicantMeta::POSITION ] ),
					esc_html( $experience[ ApplicantMeta::INDUSTRY ] ),
					esc_html( $experience[ ApplicantMeta::ORGANIZATION ] ),
					esc_html( date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::START_DATE ] ) ) ),
					esc_html( ! empty( $experience[ ApplicantMeta::PRESENT_POSITION ] ) ? __( 'the present time.', 'level-playing-field' ) : date( 'm/d/Y', strtotime( $experience[ ApplicantMeta::END_DATE ] ) ) )
				);
			}
		}

		return $experiences;
	}

	/**
	 * Add a experience to the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param array $experience The experience data.
	 */
	public function add_experience( array $experience ) {
		$experience = $this->add_date_diff( $experience );
		$this->add_property( ApplicantMeta::EXPERIENCE, $experience );
	}

	/**
	 * Set the experiences for the applicant.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return array
	 */
	public function get_volunteer() {
		return $this->get_property( ApplicantMeta::VOLUNTEER );
	}

	/**
	 * Add volunteer work to the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param array $volunteer Array of volunteer work.
	 */
	public function add_volunteer( array $volunteer ) {
		$volunteer = $this->add_date_diff( $volunteer );
		$this->add_property( ApplicantMeta::VOLUNTEER, $volunteer );
	}

	/**
	 * Set the volunteer work for the applicant.
	 *
	 * @since 1.0.0
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
	 * Add date interval information to an array of data.
	 *
	 * This requires the array of data to have a start and end date.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The array of data with date information.
	 *
	 * @return array The array of data with date information added.
	 */
	private function add_date_diff( $data ) {
		// Make sure we have the correct array keys.
		if ( ! isset( $data[ ApplicantMeta::START_DATE ], $data[ ApplicantMeta::END_DATE ] ) ) {
			return $data;
		}

		// Make sure the keys we need aren't empty.
		if ( empty( $data[ ApplicantMeta::START_DATE ] ) || empty( $data[ ApplicantMeta::END_DATE ] ) ) {
			return $data;
		}

		// Calculate duration between start and end dates.
		$present = isset( $data[ ApplicantMeta::PRESENT_POSITION ] ) && $data[ ApplicantMeta::PRESENT_POSITION ];
		$start   = date_create( $data[ ApplicantMeta::START_DATE ] );
		$end     = date_create( $data[ ApplicantMeta::END_DATE ] );
		$diff    = date_diff( $start, $end );

		// Add calculated duration to data.
		$data[ ApplicantMeta::YEAR_DURATION ] = $diff instanceof DateInterval
			? $this->calculate_date_diff( $diff, $present )
			: '';

		return $data;
	}

	/**
	 * Calculate the date difference from a time interval and return a readable string.
	 *
	 * @since 1.0.0
	 *
	 * @param DateInterval $diff             A date interval object.
	 * @param bool         $present_position A flag indicating whether this is a present position.
	 *
	 * @return string A readable time difference, or an empty string if there is no time difference.
	 */
	private function calculate_date_diff( DateInterval $diff, $present_position ) {
		if ( 0 === $diff->y && 0 === $diff->m && 0 === $diff->d ) {
			return '';
		}

		$parts = [];
		if ( $diff->y > 0 ) {
			$parts[] = sprintf(
				/* translators: the placeholder is a number of years */
				_n( '%s Year', '%s Years', $diff->y, 'level-playing-field' ),
				number_format_i18n( (float) $diff->y )
			);
		}

		if ( $diff->m > 0 ) {
			$parts[] = sprintf(
				/* translators: the placeholder is a number of months */
				_n( '%s Month', '%s Months', $diff->m, 'level-playing-field' ),
				number_format_i18n( (float) $diff->m )
			);
		}

		if ( $diff->d > 0 ) {
			$parts[] = sprintf(
				/* translators: the placeholder is a number of days */
				_n( '%s Day', '%s Days', $diff->d, 'level-playing-field' ),
				number_format_i18n( (float) $diff->d )
			);
		}

		if ( $present_position ) {
			$parts[] = '. ' . __( 'Presently working here.', 'level-playing-field' );
		}

		return implode( ' ', $parts );
	}

	/**
	 * Get the nickname of the applicant.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_nickname() {
		return $this->get_property( ApplicantMeta::NICKNAME );
	}

	/**
	 * Set the nickname of the applicant.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return array The address data.
	 */
	public function get_address() {
		return $this->get_property( ApplicantMeta::ADDRESS );
	}

	/**
	 * Set the address of the Applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param array $address The array of address data.
	 */
	public function set_address( $address ) {
		$this->set_property( ApplicantMeta::ADDRESS, $address );
	}

	/**
	 * Get the user ID who viewed the applicant.
	 *
	 * @since 1.0.0
	 * @return int
	 */
	public function get_viewed_by() {
		return (int) $this->get_property( ApplicantMeta::VIEWED );
	}

	/**
	 * Set the user who viewed the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The user ID who viewed the applicant.
	 */
	public function set_viewed_by( $id ) {
		$this->set_property( ApplicantMeta::VIEWED, $id );
	}

	/**
	 * Get the languages and proficiency.
	 *
	 * When the Applicant is anonymized, an array of proficiencies and their counts and
	 * labels will be returned instead of the full language data.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_languages() {
		$languages = parent::get_property( ApplicantMeta::LANGUAGES );
		if ( ! $this->is_anonymized() ) {
			return $languages;
		}

		// Build up language proficiency data.
		$proficiency_labels = $this->get_language_options();
		$proficiency_counts = array_fill_keys( array_keys( $proficiency_labels ), 0 );

		foreach ( $languages as $language ) {
			$proficiency_counts[ $language[ ApplicantMeta::PROFICIENCY ] ]++;
		}

		// Clean up proficiencies with no count.
		$proficiency_counts = array_filter( $proficiency_counts );

		// Combine labels and counts for final return.
		$return = [];
		foreach ( $proficiency_counts as $proficiency => $count ) {
			$return[ $proficiency ] = [
				'count' => $count,
				'label' => $proficiency_labels[ $proficiency ],
			];
		}

		return $return;
	}

	/**
	 * Add a language to the applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param array $language Array of language data.
	 */
	public function add_language( array $language ) {
		$this->add_property( ApplicantMeta::LANGUAGES, $language );
	}

	/**
	 * Set the languages of the applicant.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 * @return array
	 */
	private function get_interview() {
		return $this->get_property( ApplicantMeta::INTERVIEW );
	}

	/**
	 * Set the interview details for the applicant.
	 *
	 * @since 1.0.0
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

		$this->set_property( ApplicantMeta::INTERVIEW, $interview );
	}

	/**
	 * Get an interview request status.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function get_interview_status() {
		return $this->get_property( ApplicantMeta::INTERVIEW_STATUS );
	}

	/**
	 * Get all interview details for interview widget
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_interview_details() {
		$status_check      = $this->get_interview_status();
		$interview_details = $this->get_interview();
		switch ( $status_check ) {
			case 'scheduled':
				return [
					'status' => __( 'Awaiting Applicant Confirmation.', 'level-playing-field' ),
					'date'   => [
						'label' => __( 'Date:', 'level-playing-field' ),
						'value' => $interview_details['date'],
					],
					'time'   => [
						'label' => __( 'Time:', 'level-playing-field' ),
						'value' => $interview_details['time'],
					],
				];

			case 'confirmed':
				return [
					'status'   => __( 'Interview Request Accepted', 'level-playing-field' ),
					'date'     => [
						'label' => __( 'Date:', 'level-playing-field' ),
						'value' => $interview_details['date'],
					],
					'time'     => [
						'label' => __( 'Time:', 'level-playing-field' ),
						'value' => $interview_details['time'],
					],
					'location' => [
						'label' => __( 'Location:', 'level-playing-field' ),
						'value' => $interview_details['location'],
					],
					'message'  => [
						'label' => __( 'Message:', 'level-playing-field' ),
						'value' => $interview_details['message'],
					],
				];

			case 'cancelled':
				return [
					'status' => __( 'Interview request cancelled by the applicant.', 'level-playing-field' ),
				];

			default:
				return [
					'status' => __( 'An interview has not been scheduled.', 'level-playing-field' ),
				];
		}
	}

	/**
	 * Set the status of an interview request.
	 *
	 * @since 1.0.0
	 *
	 * Possible values:
	 * - ''
	 * - 'scheduled'
	 * - 'confirmed'
	 * - 'cancelled'
	 *
	 * @param string $interview_status Whether an interview has been scheduled for this applicant.
	 */
	public function set_interview_status( $interview_status ) {
		$this->set_property( ApplicantMeta::INTERVIEW_STATUS, $interview_status );
	}

	/**
	 * Cancel an interview.
	 *
	 * @since 1.0.0
	 */
	public function cancel_interview() {
		// Don't allow cancellation of an interview that isn't scheduled, is already confirmed, or is already cancelled.
		if ( $this->get_interview_status() !== 'scheduled' ) {
			return;
		}

		$this->set_interview_status( 'cancelled' );
		$this->set_interview( [] );
		$this->persist_properties();

		$message_class = new ApplicantMessage();
		$message_class->create_comment(
			[
				'comment_author'   => ApplicantMessage::APPLICANT_AUTHOR,
				'comment_approved' => 1,
				'comment_post_ID'  => $this->get_id(),
				'comment_content'  => sprintf(
					'<div class="lpf-message-interview-declined">%s</div>',
					esc_html__( 'The applicant has declined the interview.', 'level-playing-field' )
				),
			]
		);

		// Send off canceled interview email to both the applicant and job managers.
		( new InterviewCancellationToApplicantEmail( $this ) )->send();
		( new InterviewCancellationFromApplicantEmail( $this ) )->send();
	}

	/**
	 * Confirm an interview.
	 *
	 * @since 1.0.0
	 *
	 * @throws InvalidClass When the anonymizer class saved to this applicant can't be found.
	 */
	public function confirm_interview() {
		if ( $this->get_interview_status() === 'confirmed' ) {
			return;
		}

		$this->set_interview_status( 'confirmed' );
		$this->load_lazy_property( ApplicantMeta::ANONYMIZER );
		$anonymizer = $this->get_anonymizer();

		// Ensure the anonymizer class exists.
		if ( ! class_exists( $anonymizer ) ) {
			throw InvalidClass::not_found( $anonymizer );
		}

		$this->unanonymize( new $anonymizer() );
		$this->persist_properties();

		$message = '<div class="lpf-message-interview-confirmed">' . __( 'The applicant has confirmed the interview.', 'level-playing-field' ) . '</div>';

		$message_class = new ApplicantMessage();
		$comment_data  = [
			'comment_author'   => ApplicantMessage::APPLICANT_AUTHOR,
			'comment_approved' => 1,
			'comment_post_ID'  => $this->get_id(),
			'comment_content'  => $message,
		];
		$message_class->create_comment( $comment_data );

		// Send off confirmed interview email to both the applicant and job managers.
		( new InterviewConfirmationToApplicantEmail( $this ) )->send();
		( new InterviewConfirmationFromApplicantEmail( $this ) )->send();
	}

	/**
	 * Get the URL to the messaging page with the applicant's secret keys appended.
	 *
	 * @since 1.0.0
	 *
	 * @return string $messaging_endpoint The URL to the messaging page with the applicant's secret keys appended.
	 */
	public function get_messaging_endpoint() {
		return add_query_arg(
			[
				'guid' => $this->get_guid(),
				'post' => $this->get_id(),
			],
			get_permalink( ( new ApplicantMessagingPage() )->get_page_id( ApplicantMessagingPage::PAGE_SLUG ) )
		);
	}

	/**
	 * Get the endpoint that cancels a scheduled interview.
	 *
	 * @since 1.0.0
	 *
	 * @return string $cancellation_endpoint The endpoint that cancels a scheduled interview.
	 */
	public function get_cancellation_endpoint() {
		return add_query_arg(
			[
				'cancel' => '1',
			],
			$this->get_messaging_endpoint()
		);
	}

	/**
	 * Get the endpoint that confirms a scheduled interview.
	 *
	 * @since 1.0.0
	 *
	 * @return string $confirmation_endpoint The endpoint that confirms a scheduled interview.
	 */
	public function get_confirmation_endpoint() {
		return add_query_arg(
			[
				'confirm' => '1',
			],
			$this->get_messaging_endpoint()
		);
	}

	/**
	 * Create a unique hash/guid.
	 *
	 * @since 1.0.0
	 */
	public function create_guid() {
		if ( ! empty( $this->{ApplicantMeta::GUID} ) ) {
			return;
		}

		$this->set_property( ApplicantMeta::GUID, wp_generate_uuid4() );
	}

	/**
	 * Get the applicant's guid.
	 *
	 * @since 1.0.0
	 *
	 * @return string $guid The applicant's guid.
	 */
	public function get_guid() {
		return $this->get_property( ApplicantMeta::GUID );
	}

	/**
	 * Set the anonymizer class used for this applicant's anonymization.
	 *
	 * Note: we need to use addslashes to escape namespace backslashes as WordPress will stripslashes()
	 * when updating the DB.
	 *
	 * This function should NOT use the set_property() method.
	 *
	 * @since 1.0.0
	 *
	 * @param string $anonymizer The class name used for anonymization.
	 */
	private function set_anonymizer( $anonymizer ) {
		$this->{ApplicantMeta::ANONYMIZER} = addslashes( $anonymizer );
		$this->changed_property( ApplicantMeta::ANONYMIZER );
	}

	/**
	 * Get the anonymizer class used for this applicant's anonymization.
	 *
	 * @since 1.0.0
	 *
	 * @return string $anonymizer The class name used for anonymization.
	 */
	public function get_anonymizer() {
		return $this->get_property( ApplicantMeta::ANONYMIZER );
	}

	/**
	 * Set the anonymized property.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $anonymized True to set this applicant as anonymized.
	 */
	private function set_anonymized( $anonymized ) {
		$this->{ApplicantMeta::ANONYMIZED} = (bool) $anonymized;
		$this->changed_property( ApplicantMeta::ANONYMIZED );
	}

	/**
	 * Whether this applicant's data is currently anonymized.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function is_anonymized() {
		return (bool) $this->{ApplicantMeta::ANONYMIZED};
	}

	/**
	 * Persist the additional properties of the entity.
	 *
	 * @since 1.0.0
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
				delete_post_meta( $this->post->ID, $this->get_meta_key( $key ) );
			} else {
				update_post_meta( $this->post->ID, $this->get_meta_key( $key ), $this->$key );
			}

			unset( $this->changes[ $key ] );
		}
	}

	/**
	 * Anonymize this applicant's data.
	 *
	 * @since 1.0.0
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

		// Set anonymizer properties.
		$this->set_anonymized( true );
		$this->set_anonymizer( get_class( $anonymizer ) );

		// Copy the changed properties back.
		$this->update_properties( $properties );
	}

	/**
	 * Get the callback for anonymizing.
	 *
	 * The Closure that is returned by this method is expected to be compatible with array_walk_recursive().
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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

		// Walk through the object properties, unanonymizing them.
		$properties = array_diff_key( get_object_vars( $this ), $this->get_excluded_properties() );
		array_walk_recursive( $properties, $this->get_anonymizer_callback( $anonymizer, 'reveal' ) );

		// Set the anonymizer properties.
		$this->set_anonymized( false );
		$this->set_anonymizer( '' );

		// Copy the changed properties back.
		$this->update_properties( $properties );
	}

	/**
	 * Copy changed properties back to the object.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return array
	 */
	protected function get_lazy_properties() {
		$defaults = [
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

		/**
		 * Filter additional applicant "lazy" properties.
		 *
		 * These are properties that are loaded only when needed.
		 *
		 * @param array $properties The array of applicant properties as keys, with their default
		 *                          setting as values.
		 */
		$additional = (array) apply_filters( 'lpf_applicant_lazy_properties', [] );

		return array_merge( $additional, $defaults );
	}

	/**
	 * Load a lazily-loaded property.
	 *
	 * After this process, the loaded property should be set within the
	 * object's state, otherwise the load procedure might be triggered multiple
	 * times.
	 *
	 * Due to the way WordPress handles post meta, loading a single property will load all of the post's meta properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property Name of the property to load. Default to an empty string because passing in a property name is not required.
	 */
	protected function load_lazy_property( $property = '' ) {
		if ( ApplicantMeta::STATUS === $property ) {
			$this->load_status();
			return;
		}

		// Load other properties from post meta.
		$meta = $this->new ? [] : get_post_meta( $this->get_id() );
		foreach ( $this->get_lazy_properties() as $key => $default ) {
			// If they key has been changed, don't overwrite the change.
			if ( array_key_exists( $key, $this->changes ) ) {
				continue;
			}

			$meta_key = $this->get_meta_key( $key );
			if ( array_key_exists( $meta_key, $meta ) ) {
				$this->$key = maybe_unserialize( $meta[ $meta_key ][0] );
			} else {
				$this->$key = $default;
				$this->changed_property( $key );
			}
		}
	}

	/**
	 * Load the status of the Applicant.
	 *
	 * @since 1.0.0
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
	 * Set a property for this Applicant.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property to set.
	 * @param mixed  $value    The value of that property.
	 *
	 * @throws InvalidApplicantValue When the value is not valid, or when there is no sanitization setting.
	 */
	public function set_property( $property, $value ) {
		// Overridden to note what exception could be thrown.
		return parent::set_property( $property, $value );
	}

	/**
	 * Add a value to a property.
	 *
	 * This is only to be used for properties that contain multiple values, such as experience. For properties
	 * with only one value (even if that value is an array, like address), use the set_property() method.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property name.
	 * @param mixed  $value    The property value.
	 * @param string $key      (Optional) The key to use for the property value.
	 */
	public function add_property( $property, $value, $key = null ) {
		$this->validate_can_modify_property( $property );
		$this->validate_property_multiple( $property );

		if ( ! isset( $this->{$property} ) ) {
			$this->{$property} = [];
		}

		$sanitized = $this->sanitize_property( $property, $value );
		if ( null === $key ) {
			$this->{$property}[] = $sanitized;
		} else {
			$this->{$property}[ $key ] = $sanitized;
		}

		$this->changed_property( $property );
	}

	/**
	 * Persist the status of the applicant.
	 *
	 * @since 1.0.0
	 */
	private function persist_status() {
		wp_set_object_terms( $this->post->ID, $this->{ApplicantMeta::STATUS}, ApplicantStatus::SLUG );
	}

	/**
	 * Get properties to exclude from anonymizer.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	private function get_excluded_properties() {
		static $properties = [];
		if ( empty( $properties ) ) {
			$properties = [
				ApplicantMeta::ANONYMIZER => 1,
				ApplicantMeta::ANONYMIZED => 1,
				'changes'                 => 1,
				'post'                    => 1,
				'new'                     => 1,
				'post_changed'            => 1,
			];
		}

		return $properties;
	}

	/**
	 * Get the sanitization value for use with filter_var().
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property being filtered.
	 *
	 * @return int The sanitization value.
	 */
	private function get_sanitize_filter( $property ) {
		switch ( $property ) {
			case ApplicantMeta::ANONYMIZER:
			case ApplicantMeta::CERT_TYPE:
			case ApplicantMeta::CITY:
			case ApplicantMeta::COUNTRY:
			case ApplicantMeta::DATE:
			case ApplicantMeta::DEGREE:
			case ApplicantMeta::END_DATE:
			case ApplicantMeta::GUID:
			case ApplicantMeta::INDUSTRY:
			case ApplicantMeta::INSTITUTION:
			case ApplicantMeta::INTERVIEW_STATUS:
			case ApplicantMeta::LANGUAGE:
			case ApplicantMeta::LINE_1:
			case ApplicantMeta::LINE_2:
			case ApplicantMeta::LOCATION:
			case ApplicantMeta::MAJOR:
			case ApplicantMeta::MESSAGE:
			case ApplicantMeta::NAME:
			case ApplicantMeta::NICKNAME:
			case ApplicantMeta::ORGANIZATION:
			case ApplicantMeta::POSITION:
			case ApplicantMeta::PROFICIENCY:
			case ApplicantMeta::SKILL:
			case ApplicantMeta::START_DATE:
			case ApplicantMeta::STATE:
			case ApplicantMeta::STATUS:
			case ApplicantMeta::TIME:
			case ApplicantMeta::TYPE:
			case ApplicantMeta::YEAR_DURATION:
				$sanitize = FILTER_SANITIZE_STRING;
				break;

			case ApplicantMeta::APPLICATION:
			case ApplicantMeta::JOB:
			case ApplicantMeta::PHONE:
			case ApplicantMeta::PRESENT_POSITION:
			case ApplicantMeta::VIEWED:
			case ApplicantMeta::YEAR:
			case ApplicantMeta::ZIP:
				$sanitize = FILTER_SANITIZE_NUMBER_INT;
				break;

			case ApplicantMeta::EMAIL:
				$sanitize = FILTER_SANITIZE_EMAIL;
				break;

			case ApplicantMeta::COVER_LETTER:
				$sanitize = FILTER_UNSAFE_RAW;
				break;

			case ApplicantMeta::ANONYMIZED:
				$sanitize = FILTER_VALIDATE_BOOLEAN;
				break;

			default:
				$sanitize = FILTER_FLAG_NONE;
		}

		/**
		 * Filter the sanitize setting for a given property.
		 *
		 * @param int    $sanitize The sanitization setting for use with filter_var().
		 * @param string $property The property being filtered.
		 */
		return (int) apply_filters( 'lpf_applicant_sanitize_property', $sanitize, $property );
	}

	/**
	 * Get the data structure of a complex property.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property name.
	 *
	 * @return array The structure of the data. Intended to be used with array_intersect_key().
	 */
	private function get_property_structure( $property ) {
		switch ( $property ) {
			case ApplicantMeta::SCHOOLING:
				$structure = [
					ApplicantMeta::INSTITUTION => 1,
					ApplicantMeta::TYPE        => 1,
					ApplicantMeta::YEAR        => 1,
					ApplicantMeta::MAJOR       => 1,
					ApplicantMeta::DEGREE      => 1,
				];
				break;

			case ApplicantMeta::CERTIFICATIONS:
				$structure = [
					ApplicantMeta::INSTITUTION => 1,
					ApplicantMeta::TYPE        => 1,
					ApplicantMeta::CERT_TYPE   => 1,
					ApplicantMeta::YEAR        => 1,
					ApplicantMeta::STATUS      => 1,
				];
				break;

			case ApplicantMeta::SKILLS:
				$structure = [
					ApplicantMeta::SKILL       => 1,
					ApplicantMeta::PROFICIENCY => 1,
				];
				break;

			case ApplicantMeta::EXPERIENCE:
			case ApplicantMeta::VOLUNTEER:
				$structure = [
					ApplicantMeta::ORGANIZATION     => 1,
					ApplicantMeta::INDUSTRY         => 1,
					ApplicantMeta::POSITION         => 1,
					ApplicantMeta::START_DATE       => 1,
					ApplicantMeta::END_DATE         => 1,
					ApplicantMeta::PRESENT_POSITION => 1,
					ApplicantMeta::YEAR_DURATION    => 1,
				];
				break;

			case ApplicantMeta::ADDRESS:
				$structure = [
					ApplicantMeta::LINE_1  => 1,
					ApplicantMeta::LINE_2  => 1,
					ApplicantMeta::CITY    => 1,
					ApplicantMeta::STATE   => 1,
					ApplicantMeta::COUNTRY => 1,
					ApplicantMeta::ZIP     => 1,
				];
				break;

			case ApplicantMeta::INTERVIEW:
				$structure = [
					ApplicantMeta::DATE     => 1,
					ApplicantMeta::TIME     => 1,
					ApplicantMeta::LOCATION => 1,
					ApplicantMeta::MESSAGE  => 1,
				];
				break;

			case ApplicantMeta::LANGUAGES:
				$structure = [
					ApplicantMeta::LANGUAGE    => 1,
					ApplicantMeta::PROFICIENCY => 1,
				];
				break;

			default:
				$structure = [];
		}

		/**
		 * Filter the structure of a complex property of applicant data.
		 *
		 * @param array  $structure The structure of the data. Intended to be used with array_intersect_key().
		 * @param string $property  The property name.
		 */
		return (array) apply_filters( 'lpf_applicant_property_structure', $structure, $property );
	}

	/**
	 * Determine if the property allows multiple values.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property name.
	 *
	 * @return bool
	 */
	private function property_is_multiple( $property ) {
		switch ( $property ) {
			case ApplicantMeta::SCHOOLING:
			case ApplicantMeta::CERTIFICATIONS:
			case ApplicantMeta::SKILLS:
			case ApplicantMeta::EXPERIENCE:
			case ApplicantMeta::VOLUNTEER:
			case ApplicantMeta::LANGUAGES:
				return true;

			default:
				/**
				 * Filter whether a given property contains multiple values.
				 *
				 * @param bool   $is_multiple Whether the property allows multiple values.
				 * @param string $property    The property name.
				 */
				return (bool) apply_filters( 'lpf_applicant_property_multiple', false, $property );
		}
	}

	/**
	 * Get the meta key for a given property.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The we want a meta key for.
	 *
	 * @return string The meta key.
	 */
	protected function get_meta_key( $property ) {
		$meta_key = array_key_exists( $property, ApplicantMeta::META_PREFIXES )
			? ApplicantMeta::META_PREFIXES[ $property ]
			: $property;

		/**
		 * Filter the meta key of a given property key.
		 *
		 * @param string $meta_key The version of the key used for the postmeta table.
		 * @param string $property The applicant property key.
		 */
		return apply_filters( 'lpf_applicant_meta_key', $meta_key, $property );
	}

	/**
	 * Sanitize a property for this object.
	 *
	 * For a structured property, each element will be sanitized individually. For
	 * a non-structured property, the value will be sanitized directly.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property name to sanitize.
	 * @param mixed  $data     The data for the property.
	 *
	 * @return mixed The sanitized data.
	 * @throws EmptyArray When a structured property has none of the correct keys.
	 */
	protected function sanitize_property( $property, $data ) {
		// Handle non-array properties.
		$structure = $this->get_property_structure( $property );
		if ( empty( $structure ) ) {
			return $this->sanitize_key( $property, $data );
		}

		// Remove any extraneous keys.
		$data = array_intersect_key( (array) $data, $structure );
		if ( empty( $data ) ) {
			throw EmptyArray::from_function( __METHOD__ );
		}

		// Sanitize each element of the array based on the sanitization rules.
		foreach ( $data as $key => &$value ) {
			$value = $this->sanitize_key( $key, $value );
		}

		return $data;
	}

	/**
	 * Sanitize a value for a given key.
	 *
	 * The key is either a top-level property of the Applicant, or an array key
	 * of a top-level property that has an array structure.
	 *
	 * @see Applicant::get_sanitize_filter()
	 *
	 * @since 1.0.0
	 *
	 * @param string $key   The key to sanitize against.
	 * @param mixed  $value The value to sanitize.
	 *
	 * @return mixed The sanitized value.
	 * @throws InvalidApplicantValue When the value has no sanitization filter, or when the
	 *                               sanitized value is invalide.
	 */
	private function sanitize_key( $key, $value ) {
		$original = $value;
		$sanitize = $this->get_sanitize_filter( $key );
		if ( FILTER_FLAG_NONE === $sanitize ) {
			throw InvalidApplicantValue::no_sanitization( $key );
		}

		$filtered = filter_var( $value, $sanitize );
		if ( false === $value && false !== $original ) {
			throw InvalidApplicantValue::property_value( $key, $value );
		}

		return $filtered;
	}

	/**
	 * Validate that a property can be modified.
	 *
	 * Certain properties are only able to be modified by internal methods.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property to validate.
	 *
	 * @throws InvalidProperty When the property cannot be modified.
	 */
	protected function validate_can_modify_property( $property ) {
		if ( array_key_exists( $property, $this->get_excluded_properties() ) ) {
			throw InvalidProperty::cannot_modify( $property );
		}
	}

	/**
	 * Validate that a property allows multiple values.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property name.
	 *
	 * @throws InvalidProperty When the property does not allow multiple values.
	 */
	private function validate_property_multiple( $property ) {
		if ( ! $this->property_is_multiple( $property ) ) {
			throw InvalidProperty::not_multiple( $property );
		}
	}

	/**
	 * Get a property from this object.
	 *
	 * @since %VERSION%
	 *
	 * @param string $property The property name.
	 *
	 * @return mixed The property value.
	 * @throws InvalidProperty When the property does not exist.
	 */
	public function get_property( $property ) {
		$value = parent::get_property( $property );
		if ( ! $this->is_anonymized() ) {
			return $value;
		}

		if ( is_array( $value ) ) {
			$value = array_diff_key( $value, ApplicantMeta::ANONYMOUS_FIELDS );
		} elseif ( is_string( $value ) && array_key_exists( $property, ApplicantMeta::ANONYMOUS_FIELDS ) ) {
			$value = __( '(anonymized)', 'level-playing-field' );
		}

		return $value;
	}
}
