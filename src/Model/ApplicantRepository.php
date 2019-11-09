<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use InvalidArgumentException;
use stdClass;
use WP_Post;
use Yikes\LevelPlayingField\Anonymizer\AnonymizerFactory;
use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Field\Field;
use Yikes\LevelPlayingField\Form\Application;
use Yikes\LevelPlayingField\Query\ApplicantQueryBuilder;

/**
 * Class ApplicantRepository
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class ApplicantRepository extends CustomPostTypeRepository {

	use PostFinder;
	use PostTypeApplicant;

	/**
	 * Find the Applicant with a given post ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return Applicant
	 * @throws InvalidPostID If the post for the requested ID was not found or is not the correct type.
	 */
	public function find( $id ) {
		return $this->find_item( $id );
	}

	/**
	 * Find all the published Applicants.
	 *
	 * @since 1.0.0
	 *
	 * @return Applicant[]
	 */
	public function find_all() {
		return $this->find_all_items();
	}

	/**
	 * Get the name of the class to use when instantiating a model object.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post $post The post object to use when instantiating the model.
	 *
	 * @return Applicant
	 */
	protected function get_model_object( WP_Post $post ) {
		return new Applicant( $post );
	}

	/**
	 * Get the count of applicants for a given Job ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return int The count of applicants for the Job.
	 */
	public function get_applicant_count_for_job( $job_id ) {
		$query = ( new ApplicantQueryBuilder() )
			->for_count()
			->where_job_id( $job_id )
			->get_query();

		return absint( $query->found_posts );
	}

	/**
	 * Get the count of Applicants who have been viewed for a Job.
	 *
	 * @since 1.0.0
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return int The count of applicants for the Job who have been viewed.
	 */
	public function get_viewed_applicant_count_for_job( $job_id ) {
		$query = ( new ApplicantQueryBuilder() )
			->for_count()
			->where_job_id( $job_id )
			->where_applicant_viewed()
			->get_query();

		return absint( $query->found_posts );
	}

	/**
	 * Get the count of new (unviewed) applicants for a given Job ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return int The count of new applicants for the Job.
	 */
	public function get_new_applicant_count_for_job( $job_id ) {
		$query = ( new ApplicantQueryBuilder() )
			->for_count()
			->where_job_id( $job_id )
			->where_applicant_not_viewed()
			->get_query();

		return absint( $query->found_posts );
	}

	/**
	 * Get Applicants that have applied for a particular job.
	 *
	 * @since 1.0.0
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return Applicant[]
	 */
	public function get_applicants_for_job( $job_id ) {
		$query = ( new ApplicantQueryBuilder() )->where_job_id( $job_id );

		return $this->get_applicants( $query );
	}

	/**
	 * Get the count of applicants for a given Application ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $application_id The Application ID.
	 *
	 * @return int The count of applicants for the Application.
	 */
	public function get_count_for_application( $application_id ) {
		$query = ( new ApplicantQueryBuilder() )
			->for_count()
			->where_application_id( $application_id )
			->get_query();

		return absint( $query->found_posts );
	}

	/**
	 * Get Applicants using a query builder object.
	 *
	 * @since 1.0.0
	 *
	 * @param ApplicantQueryBuilder $query_builder The query builder object.
	 *
	 * @return Applicant[] Array of applicant objects.
	 */
	public function get_applicants( ApplicantQueryBuilder $query_builder ) {
		$query      = $query_builder->get_query();
		$applicants = [];
		foreach ( $query->posts as $post ) {
			$applicants[ $post->ID ] = $this->get_model_object( $post );
		}

		return $applicants;
	}

	/**
	 * Create a new Applicant.
	 *
	 * @since 1.0.0
	 *
	 * @return Applicant
	 */
	public function create() {
		$post                 = new stdClass();
		$post->ID             = 0;
		$post->post_author    = '';
		$post->post_date      = '';
		$post->post_date_gmt  = '';
		$post->post_type      = $this->get_post_type();
		$post->comment_status = 'open';
		$post->ping_status    = 'closed';
		$post->page_template  = 'default';

		return $this->get_model_object( new WP_Post( $post ) );
	}

	/**
	 * Create a new Applicant from a submitted form.
	 *
	 * @since 1.0.0
	 *
	 * @param Application $form The form object.
	 *
	 * @return Applicant The new applicant object.
	 *
	 * @throws InvalidArgumentException When the provided form has errors.
	 */
	public function create_from_form( Application $form ) {
		// Don't create an object if there are errors in the form.
		if ( $form->has_errors() ) {
			throw new InvalidArgumentException( 'An applicant object cannot be created because the form has errors.' );
		}

		$applicant = $this->create();
		$applicant->persist();
		foreach ( $form->fields as $field ) {
			$name   = str_replace( ApplicationMeta::FORM_FIELD_PREFIX, '', $field->get_id() );
			$method = "set_{$name}";
			if ( method_exists( $applicant, $method ) ) {
				$applicant->{$method}( $field->get_sanitized_value() );
			}

			/**
			 * Fires when an applicant field is saved.
			 *
			 * @param Field       $field     The current field being saved.
			 * @param Applicant   $applicant The applicant object.
			 * @param Application $form      The application form object.
			 */
			do_action( 'lpf_applicant_save_field', $field, $applicant, $form );
		}

		/**
		 * Fires before the applicant is anonymized.
		 *
		 * @param Applicant   $applicant The applicant object.
		 * @param Application $form      The application form object.
		 */
		do_action( 'lpf_applicant_pre_anonymize', $applicant, $form );

		// Anonymize!
		$applicant->anonymize( AnonymizerFactory::get_anonymizer() );

		// Set the nickname after the post object has been saved.
		$applicant->set_nickname( sprintf(
			'%s%d',
			_x( 'Applicant #', 'Default applicant nickname. Followed by a number', 'level-playing-field' ),
			$applicant->get_id()
		) );

		// Add a GUID to the applicant. This helps keep our front end pages (e.g. messaging) covert.
		$applicant->create_guid();

		// Save the changes.
		$applicant->persist();

		return $applicant;
	}
}
