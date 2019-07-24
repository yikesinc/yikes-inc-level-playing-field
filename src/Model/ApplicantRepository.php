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
use WP_Query;
use Yikes\LevelPlayingField\Anonymizer\AnonymizerFactory;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantCPT;
use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Field\Field;
use Yikes\LevelPlayingField\Form\Application;

/**
 * Class ApplicantRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicantRepository extends CustomPostTypeRepository {

	use PostFinder;

	/**
	 * Find the Applicant with a given post ID.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
	 *
	 * @return Applicant[]
	 */
	public function find_all() {
		return $this->find_all_items();
	}

	/**
	 * Get the post type slug to find.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_post_type() {
		return ApplicantCPT::SLUG;
	}

	/**
	 * Get the name of the class to use when instantiating a model object.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return int The count of applicants for the Job.
	 */
	public function get_applicant_count_for_job( $job_id ) {
		$args = $this->get_default_query_vars();

		// Specifics for this query.
		$args['posts_per_page'] = 1;
		$args['fields']         = 'ids';
		$args['meta_query'][]   = $this->get_job_meta_query( $job_id );

		$query = new WP_Query( $args );

		return absint( $query->found_posts );
	}

	/**
	 * Get the count of Applicants who have been viewed for a Job.
	 *
	 * @since %VERSION%
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return int The count of applicants for the Job who have been viewed.
	 */
	public function get_viewed_applicant_count_for_job( $job_id ) {
		$args = $this->get_default_query_vars();

		// Specifics for this query.
		$args['posts_per_page'] = 1;
		$args['fields']         = 'ids';
		$args['meta_query'][]   = $this->get_job_meta_query( $job_id );
		$args['meta_query'][]   = $this->get_viewed_applicant_meta_query();

		$query = new WP_Query( $args );

		return absint( $query->found_posts );
	}

	/**
	 * Get the count of new (unviewed) applicants for a given Job ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return int The count of new applicants for the Job.
	 */
	public function get_new_applicant_count_for_job( $job_id ) {
		$args = $this->get_default_query_vars();

		// Specifics for this query.
		$args['posts_per_page'] = 1;
		$args['fields']         = 'ids';
		$args['meta_query'][]   = $this->get_job_meta_query( $job_id );
		$args['meta_query'][]   = [
			'key'     => ApplicantMeta::META_PREFIXES['viewed'],
			'compare' => 'NOT EXISTS',
		];

		$query = new WP_Query( $args );

		return absint( $query->found_posts );
	}

	/**
	 * Get Applicants that have applied for a particular job.
	 *
	 * @since %VERSION%
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return Applicant[]
	 */
	public function get_applicants_for_job( $job_id ) {
		$args                 = $this->get_default_query_vars();
		$args['meta_query'][] = $this->get_job_meta_query( $job_id );
		$query                = new WP_Query( $args );

		$applicants = [];
		foreach ( $query->posts as $post ) {
			$applicants[ $post->ID ] = $this->get_model_object( $post );
		}

		return $applicants;
	}

	/**
	 * Get the count of applicants for a given Application ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $application_id The Application ID.
	 *
	 * @return int The count of applicants for the Application.
	 */
	public function get_count_for_application( $application_id ) {
		$args = $this->get_default_query_vars();

		// Specifics for this query.
		$args['posts_per_page'] = 1;
		$args['fields']         = 'ids';
		$args['meta_query'][]   = $this->get_application_meta_query( $application_id );

		$query = new WP_Query( $args );

		return absint( $query->found_posts );
	}

	/**
	 * Create a new Applicant.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
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

		// Anonymize!
		$applicant->anonymize( AnonymizerFactory::get_anonymizer() );

		// Set the nickname after the post object has been saved.
		$applicant->set_nickname( sprintf(
			'%s%d',
			_x( 'Applicant #', 'Default applicant nickname. Followed by a number', 'yikes-level-playing-field' ),
			$applicant->get_id()
		) );

		// Add a GUID to the applicant. This helps keep our front end pages (e.g. messaging) covert.
		$applicant->set_guid( $applicant->create_guid() );

		// Save the changes.
		$applicant->persist();

		return $applicant;
	}

	/**
	 * Get the default arguments to use when querying for this repository.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_default_query_vars() {
		return [
			'post_type'              => $this->get_post_type(),
			'post_status'            => [ 'any' ],
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'meta_query'             => [],
		];
	}
}
