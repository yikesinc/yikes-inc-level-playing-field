<?php
/**
 * Default Fields for new job applications
 * @since 1.0.0
 */
return array(
	// text field
	array(
		'label' => 'Test',
		'name' => 'testing',
		'type' => 'text',
		'class' => 'testing-text-field',
		'custom_atts' => array(
			'placeholder' => 'Evan Is So Friggen Cool',
		),
	),
	// number field
	array(
		'label' => 'Test 2',
		'name' => 'Testing Field 2',
		'type' => 'number',
		'class' => 'testing-number-field',
		'custom_atts' => array(
			'value' => '8',
			'placeholder' => '10',
			'min' => '1',
			'max' => '100',
			'step' => '10',
		),
	),
);
