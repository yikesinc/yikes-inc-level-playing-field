<?php
/**
 * Handle our job application form submissions
 */
class Yikes_Inc_Level_Playing_Field_Process_Submission extends Yikes_Inc_Level_Playing_Field_Public {

	// Private form data submitted via job application
	private $application_data;

	// Store our helper class
	private $helpers;

	public function __construct( $form_data, $helpers ) {
		// Store the form data
		$this->application_data = $form_data;
		$this->helpers = $helpers;
		// Submit the application
		$this->submit_job_application( $form_data );
	}

	/**
	 * Handle the submission of each job application
	 * @param  array $application_data Array of applicant data, submitted in the form
	 * @return bool                  	 True/False based on the creation of the applicatnt post type
	 */
	public function submit_job_application( $application_data ) {
		// Create post object
		$new_applicant = array(
			'post_title'    => wp_strip_all_tags( $application_data['name'] ),
			'post_name' => $this->helpers->obfuscate_string( 'user_name' ), // set the applicant slug to a random string/number
			'post_password' => $this->helpers->obfuscate_string( '123456789123456789' ), // Generate a random character password for this post (18 characters)
			'post_type'			=> 'applicants',
			'post_status'   => 'publish',
		);

		// Insert the post into the database
		$applicant = wp_insert_post( $new_applicant );

		// If the applicant was not inserted into the db, abort
		if ( ! $applicant ) {
			return false;
		}

		// Unset the 'name' field and the 'submit' button
		unset( $application_data['name'], $application_name['submit'] );

		// Loop over applicant data and store the meta
		foreach ( $application_data as $application_data_key => $application_data_value ) {
			update_post_meta( $applicant, $application_data_key, sanitize_text_field( $application_data_value ) );
		}

		// Set the applicant to 'New', so the numbers increase in the database menu item
		update_post_meta( $applicant, 'new_applicant', '1' );
		update_post_meta( $applicant, 'applicant_status', 'needs-review' );

		// Assign an avatar to this user
		update_post_meta( $applicant, 'applicant_avatar', $this->helpers->generate_user_avatar() );

		// clear our 'total_new_applicant_count' transient
		// so the count gets updated across the site
		delete_transient( 'total_new_applicant_count' );

		/**
		 * Action hook to allow for additional steps to be taken
		 * before the applicant is in the database
		 * @param integer   $applicant         The applicant ID as it was stored in the database.
		 * @param array     $application_data  The array of data submitted by the applicant via the form.
		 */
		do_action( 'yikes_inc_level_playing_field_process_application_submission', $applicant, $application_data );

		return true;
	}
}
