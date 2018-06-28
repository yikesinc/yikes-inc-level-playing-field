<?php

return [
	[
		'name'   => __( 'Basic Info', 'yikes-level-playing-field' ),
		'id'     => 'basic',
		'type'   => 'group',
		'fields' => [
			[
				'desc' => __( 'Check the fields you want included on this Application Form', 'yikes-level-playing-field' ),
				'type' => 'message',
				'id'   => $this->prefix_field( 'intro_message' ),
			],
			[
				'name' => __( 'Basic Info', 'yikes-level-playing-field' ),
				'type' => 'title',
				'id'   => $this->prefix_field( 'intro_title' ),
			],
			[
				'name'      => __( 'Name (required)', 'yikes-level-playing-field' ),
				'desc'      => __( 'Name (required)', 'yikes-level-playing-field' ),
				'id'        => $this->prefix_field( 'name' ),
				'type'      => 'checkbox',
				'value'     => 1,
				'desc_type' => 'inline',
			],
			[
				'name'      => __( 'Email Address (required)', 'yikes-level-playing-field' ),
				'desc'      => __( 'Email Address (required)', 'yikes-level-playing-field' ),
				'id'        => $this->prefix_field( 'email' ),
				'type'      => 'checkbox',
				'value'     => 1,
				'desc_type' => 'inline',
			],
			[
				'name'      => __( 'Phone Number', 'yikes-level-playing-field' ),
				'desc'      => __( 'Phone Number', 'yikes-level-playing-field' ),
				'id'        => $this->prefix_field( 'phone' ),
				'type'      => 'checkbox',
				'value'     => 1,
				'desc_type' => 'inline',
			],
			[
				'name'      => __( 'Address', 'yikes-level-playing-field' ),
				'desc'      => __( 'Address', 'yikes-level-playing-field' ),
				'id'        => $this->prefix_field( 'address' ),
				'type'      => 'checkbox',
				'value'     => 1,
				'desc_type' => 'inline',
			],
			[
				'name'      => __( 'Cover Letter', 'yikes-level-playing-field' ),
				'desc'      => __( 'Cover Letter', 'yikes-level-playing-field' ),
				'id'        => $this->prefix_field( 'cover_letter' ),
				'type'      => 'checkbox',
				'value'     => 1,
				'desc_type' => 'inline',
			],
		],
	],
	[
		'name'   => __( 'Education', 'yikes-level-playing-field' ),
		'id'     => 'education',
		'type'   => 'group',
		'fields' => [
			[
				'name'       => __( 'Schooling', 'yikes-level-playing-field' ),
				'desc'       => __( 'Schooling', 'yikes-level-playing-field' ),
				'id'         => $this->prefix_field( 'schooling' ),
				'type'       => 'checkbox',
				'value'      => 1,
				'desc_type'  => 'inline',
				'attributes' => [
					'class'        => [ 'yks_extra_info' ],
					'data-section' => 'lpf_schooling',
				],
			],
			[
				'desc' => $this->get_schooling_html(),
				'type' => 'message',
				'id'   => $this->prefix_field( 'schooling' ),
			],
			[
				'name'       => __( 'Certifications', 'yikes-level-playing-field' ),
				'desc'       => __( 'Certifications', 'yikes-level-playing-field' ),
				'id'         => $this->prefix_field( 'certifications' ),
				'type'       => 'checkbox',
				'value'      => 1,
				'desc_type'  => 'inline',
				'attributes' => [
					'class'        => [ 'yks_extra_info' ],
					'data-section' => 'lpf_certification',
				],
			],
			[
				'desc' => $this->get_certification_html(),
				'type' => 'message',
				'id'   => $this->prefix_field( 'certification' ),
			],
		],
	],
	[
		'name'   => __( 'Skills', 'yikes-level-playing-field' ),
		'id'     => 'skills',
		'type'   => 'group',
		'fields' => [
			[
				'name'      => __( 'Skill and Proficiency', 'yikes-level-playing-field' ),
				'desc'      => __( 'Skill and Proficiency', 'yikes-level-playing-field' ),
				'id'        => $this->prefix_field( 'skills' ),
				'type'      => 'checkbox',
				'value'     => 1,
				'desc_type' => 'inline',
			],
		],
	],
	[
		'name'   => __( 'Languages', 'yikes-level-playing-field' ),
		'id'     => 'languages',
		'type'   => 'group',
		'fields' => [
			[
				'name'      => __( 'Language and Proficiency', 'yikes-level-playing-field' ),
				'desc'      => __( 'Language and Proficiency', 'yikes-level-playing-field' ),
				'id'        => $this->prefix_field( 'languages' ),
				'type'      => 'checkbox',
				'value'     => 1,
				'desc_type' => 'inline',
			],
		],
	],
	[
		'name'   => __( 'Experience', 'yikes-level-playing-field' ),
		'id'     => 'experience',
		'type'   => 'group',
		'fields' => [
			[
				'name'       => __( 'Experience', 'yikes-level-playing-field' ),
				'desc'       => __( 'Experience', 'yikes-level-playing-field' ),
				'id'         => $this->prefix_field( 'experience' ),
				'type'       => 'checkbox',
				'value'      => 1,
				'desc_type'  => 'inline',
				'attributes' => [
					'class'        => [ 'yks_extra_info' ],
					'data-section' => 'lpf_experience',
				],
			],
			[
				'desc' => $this->get_experience_html(),
				'type' => 'message',
				'id'   => $this->prefix_field( 'experience' ),
			],
		],
	],
	[
		'name'   => __( 'Volunteer Work', 'yikes-level-playing-field' ),
		'id'     => 'volunteer',
		'type'   => 'group',
		'fields' => [
			[
				'name'       => __( 'Volunteer Work', 'yikes-level-playing-field' ),
				'desc'       => __( 'Volunteer Work', 'yikes-level-playing-field' ),
				'id'         => $this->prefix_field( 'volunteer' ),
				'type'       => 'checkbox',
				'value'      => 1,
				'desc_type'  => 'inline',
				'attributes' => [
					'class'        => [ 'yks_extra_info' ],
					'data-section' => 'lpf_volunteer',
				],
			],
			[
				'desc' => $this->get_volunteer_html(),
				'type' => 'message',
				'id'   => $this->prefix_field( 'volunteer' ),
			],
		],
	],
];