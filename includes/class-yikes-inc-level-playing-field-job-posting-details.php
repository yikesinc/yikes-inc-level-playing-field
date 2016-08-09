<?php

/**
 * Handles the display of our job posting details metabox
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 */

/**
 * Render the content inside of our job posting details array
 * *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 * @author     YIKES, Inc. <plugins@yikesinc.com>
 */
class Yikes_Inc_Level_Playing_Field_Job_Posting_Details {

	// Sidebar menu var
	public $sidebar_menu;

	// Sidebar menu var
	public $fields;

	// Allowed tags in our containers
	public $allowed_tags;

	public function __construct() {
		// sidebar items
		$this->sidebar_menu = $this->get_job_posting_details_menu();
		// sections
		$this->fields = $this->get_job_posting_details_fields();
		// allowed tags
		$this->allowed_tags = $this->get_job_posting_details_allowed_tags();
	}

	/**
	 * Array of job posting details sidebar menu items
	 *
	 * @return array Full array of items used to build our sidebar menu
	 * @since 1.0.0
	 */
	public function get_job_posting_details_menu() {
		return apply_filters( 'yikes_level_playing_field_job_posting_details_menu_items', array(
			array(
				'id' => 'company_details',
				'text' => __( 'Company Details', 'yikes-inc-level-playing-field' ),
			),
			array(
				'id' => 'job_details',
				'text' => __( 'Job Details', 'yikes-inc-level-playing-field' ),
			),
			array(
				'id' => 'compensation',
				'text' => __( 'Compensation (optional)', 'yikes-inc-level-playing-field' ),
			),
			array(
				'id' => 'schedule',
				'text' => __( 'Schedule (optional)', 'yikes-inc-level-playing-field' ),
			),
			array(
				'id' => 'notifications',
				'text' => __( 'Notifications', 'yikes-inc-level-playing-field' ),
			),
		) );
	}

	/**
	 * Array of job posting details sections
	 *
	 * @return array Full array of items used to build our main section controlled by the sidebar menu
	 * @since 1.0.0
	 */
	public function get_job_posting_details_fields() {
		$default_fields = include plugin_dir_path( dirname( __FILE__ ) ) . 'includes/templates/default-job-posting-details-fields.php';
		return apply_filters( 'yikes_level_playing_field_job_posting_details_fields', $default_fields );
	}

	/**
	 * Array of allowed tags for our containers (security purposes)
	 * @return array Array of allowed HTML tags in our containers.
	 * @since 1.0.0
	 */
	public function get_job_posting_details_allowed_tags() {
		return array(
			'div' => array(
				'id' => array(),
				'class' => array(),
			),
			'a' => array(
				'href' => array(),
				'title' => array(),
			),
			'p' => array(
				'class' => array(),
			),
			'abbr' => array(
				'title' => array(),
			),
			'br' => array(),
			'em' => array(
				'class' => array(),
			),
			'span' => array(
				'class' => array(),
			),
			'strong' => array(),
			'input' => array(
				'type' => array(),
				'class' => array(),
				'name' => array(),
				'id' => array(),
				'value' => array(),
				'placeholder' => array(),
				'min' => array(),
				'step' => array(),
			),
			'label' => array(
				'for' => array(),
			),
		);
	}
}
