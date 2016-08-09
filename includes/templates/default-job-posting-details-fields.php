<?php
/**
 * File Containing all of the default fields for each section of the 'Job Posting Details' metabox
 *
 * @since 1.0.0
 */
return array(
	// Company Details
	'company_details' => array(
		// Company Name
		array(
			'type' => 'text',
			'label' => __( 'Company Name', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( '', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Company Tagline
		array(
			'type' => 'text',
			'label' => __( 'Company Tagline', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( '', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Company Logo
		array(
			'type' => 'text',
			'label' => __( 'Company Logo (optional)', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Add the associated company logo to this job posting.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Company Website
		array(
			'type' => 'text',
			'label' => __( 'Company Website (optional)', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Enter the company website in the field above.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( 'http://www.example.com', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Company Twitter
		array(
			'type' => 'text',
			'label' => __( 'Company Twitter Account (optional)', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Enter your companys twitter username in the field above.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '@Example', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
	// Job Details
	'job_details' => array(
		// Company Name
		array(
			'type' => 'text',
			'label' => __( 'Job Title', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'The title of this job position.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( 'Senior Engineer', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
		// Company Name
		array(
			'type' => 'text',
			'label' => __( 'Job Location', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Where is this job located (address).', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
	// Compensation Details
	'compensation' => array(
		// Company Name
		array(
			'type' => 'number',
			'label' => __( 'Compensation Details', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'How much money will the employee be offered?', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '50,000.00', 'yikes-inc-level-playing-field' ),
			'class' => 'short currency',
		),
	),
	// Schedule Details
	'schedule' => array(
		// Company Name
		array(
			'type' => 'text',
			'label' => __( 'Schedule Details', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Placeholder description.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '---temp---', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
	// Schedule Details
	'notifications' => array(
		// Company Name
		array(
			'type' => 'text',
			'label' => __( 'Notifications Details', 'yikes-inc-level-playing-field' ),
			'default' => '',
			'description' => __( 'Placeholder description.', 'yikes-inc-level-playing-field' ),
			'placeholder' => __( '---temp---', 'yikes-inc-level-playing-field' ),
			'class' => 'short',
		),
	),
);
