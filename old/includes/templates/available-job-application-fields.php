<?php
/**
 * Helper file that lists out all of the possible fields that can be
 * assigned to the application builder
 */
return array(
		__( 'Standard Fields', 'yikes-inc-level-playing-field' ) => array(
			// Text Field
			array(
				'label' => __( 'Text', 'yikes-inc-level-playing-field' ),
				'type' => 'text',
				'class' => 'widefat',
			),
			// Paragraph Text Field
			array(
				'label' => __( 'Paragraph Text', 'yikes-inc-level-playing-field' ),
				'type' => 'textarea',
				'class' => 'widefat',
			),
			// Paragraph Text Field
			array(
				'label' => __( 'Number', 'yikes-inc-level-playing-field' ),
				'type' => 'number',
				'class' => 'widefat',
			),
			// Email Field
			array(
				'label' => __( 'Email', 'yikes-inc-level-playing-field' ),
				'type' => 'email',
				'class' => 'widefat',
			),
			// Select Field
			array(
				'label' => __( 'Dropdown', 'yikes-inc-level-playing-field' ),
				'type' => 'select',
				'class' => 'widefat',
			),
			// Radio Field
			array(
				'label' => __( 'Radio Buttons', 'yikes-inc-level-playing-field' ),
				'type' => 'radio',
				'class' => '',
			),
			// Checkbox Field
			array(
				'label' => __( 'Checkbox', 'yikes-inc-level-playing-field' ),
				'type' => 'checkbox',
				'class' => '',
			),
			// Paragraph Text Field
			array(
				'label' => __( 'Hidden', 'yikes-inc-level-playing-field' ),
				'type' => 'hidden',
				'class' => 'widefat',
			),
			// Section Break/Line Break
			array(
				'label' => __( 'Section', 'yikes-inc-level-playing-field' ),
				'type' => 'section-break',
				'class' => 'widefat',
			),
		),
		__( 'Advanced Fields', 'yikes-inc-level-playing-field' ) => array(
			// Name Field
			array(
				'label' => __( 'Name', 'yikes-inc-level-playing-field' ),
				'type' => 'name',
				'class' => '',
			),
			// Date Field
			array(
				'label' => __( 'Date', 'yikes-inc-level-playing-field' ),
				'type' => 'date',
				'class' => '',
			),
			array(
				'label' => __( 'Education', 'yikes-inc-level-playing-field' ),
				'type' => 'education',
				'class' => '',
			),
			array(
				'label' => __( 'HTML', 'yikes-inc-level-playing-field' ),
				'type' => 'html',
				'class' => '',
			),
			array(
				'label' => __( 'File Upload', 'yikes-inc-level-playing-field' ),
				'type' => 'file',
				'class' => '',
			),
		),
);
