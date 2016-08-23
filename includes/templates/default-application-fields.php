<?php
/**
 * Default Fields for new job applications
 * @since 1.0.0
 */
return array(
	// text field
	array(
		'label' => __( 'Name', 'yikes-inc-level-playing-field' ),
		'name' => 'name',
		'type' => 'text',
		'custom_atts' => array(
			'placeholder' => __( 'Your Name', 'yikes-inc-level-playing-field' ),
		),
	),
	// email field
	array(
		'label' => __( 'Email', 'yikes-inc-level-playing-field' ),
		'name' => 'email',
		'type' => 'email',
		'custom_atts' => array(
			'placeholder' => __( 'email@example.com', 'yikes-inc-level-playing-field' ),
			'pattern' => '[^@]+@[^@]+\.[a-zA-Z]{2,6}',
		),
	),
);
