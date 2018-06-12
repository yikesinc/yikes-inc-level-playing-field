<?php
/**
 * File Containing all of the default fields for each section of the 'Job Posting Details' metabox
 *
 * @since 1.0.0
 */
return array(
	// Company / Organization Details
	'company_details' => array(		
		// Name
		array(
			'id' => '_company_name',
			'type' => 'text',
			'label' => __( 'Name', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( '', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Description
		array(
			'id' => '_company_tagline',
			'type' => 'text',
			'label' => __( 'Company / Organization Description', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( '', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Logo
		array(
			'id' => '_company_logo',
			'type' => 'text',
			'label' => __( 'Company / Organization Logo', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Add the associated company logo to this job posting.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Website
		array(
			'id' => '_company_website',
			'type' => 'text',
			'label' => __( 'Company / Organization Website', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'A URL in the field above.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( 'http://www.example.com', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Twitter
		array(
			'id' => '_company_twitter',
			'type' => 'text',
			'label' => __( 'Company Twitter Account (optional)', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Enter a twitter username in the field above.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '@Example', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
	// General Job Information
	'job_details' => array(
		// Job Title
		array(
			'id' => '_job_title',
			'type' => 'text',
			'label' => __( 'Job Title', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'The title of this job position.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( 'Senior Engineer', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Job Location
		array(
			'id' => '_job_location',
			'type' => 'text',
			'label' => __( 'Job Location', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Where is this job located (address).', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
	// Responsibilities
	'responsibilities' => array(
		// Company Name
		array(
			'id' => '_responsibility_details',
			'type' => 'text',
			'label' => __( 'Responsibilities', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Placeholder description.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '---temp---', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
	// Qualifications
	'qualifications' => array(
		// Notifications Details
		array(
			'id' => '_qualifications_details',
			'type' => 'text',
			'label' => __( 'Qualifications Details', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Placeholder description.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '---temp---', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
	// Compensation Details
	'compensation' => array(
		// Compensation Details
		array(
			'id' => '_compensation_details',
			'type' => 'number',
			'label' => __( 'Compensation Details', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'How much money will the employee be offered?', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '50,000.00', 'yikes-inc-level-playing-field' ),
			'class' => 'short currency',
		),
	),
	// Applications
	'application_details' => array(
		// Third Party Site Checkbox
		array(
			'id' => '_applicants_details',
			'label' => __( 'Applicants', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Placeholder description.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '---temp---', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
);
