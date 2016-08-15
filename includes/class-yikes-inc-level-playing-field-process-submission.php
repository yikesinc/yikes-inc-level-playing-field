<?php
/**
 * Handle our job application form submissions
 */
class Yikes_Inc_Level_Playing_Field_Process_Submission extends Yikes_Inc_Level_Playing_Field_Public {

	// Private form data submitted via job application
	private $application_data;

	public function __construct( $form_data ) {
		// Store the form data
		$this->application_data = $form_data;
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
			'post_type'			=> 'applicants',
			'post_status'   => 'publish',
		);

		// Insert the post into the database
		$applicant = wp_insert_post( $new_applicant );
		return ( $applicant ) ? true : false;
	}
}
