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
		),
		__( 'Advanced Fields', 'yikes-inc-level-playing-field' ) => array(
			// Checkbox Field
			array(
				'label' => __( 'Multi-Checkbox', 'yikes-inc-level-playing-field' ),
				'type' => 'multi-check',
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
		),
);
