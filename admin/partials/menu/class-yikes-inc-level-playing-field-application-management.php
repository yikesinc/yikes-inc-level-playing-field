<?php
/**
 * Register a custom menu page.
 */
class Yikes_Inc_Level_Playing_Field_Application_Management {

	// Helpers class
	protected $helpers;

	public function __construct( $helpers ) {
		// Store the helpers class for later use
		$this->helpers = $helpers;
		add_action( 'admin_menu', array( $this, 'register_applicant_management_menu_page' ) );
	}

	/**
	 * Render a menu item to manage applicants
	 * @return null
	 * @since 1.0.0
	 */
	public function register_applicant_management_menu_page() {
		if ( false === ( $applicant_count = get_transient( 'total_new_applicant_count' ) ) ) {
			$applicant_count = $this->helpers->get_new_applicant_count();
		}
		$applicant_count_notice = sprintf(
			'<span class="update-plugins count-%s" title="%s"><span class="update-count">%s</span></span>',
			$applicant_count,
			sprintf( _n( '%s New Applicant', '%s New Applicants', $applicant_count, 'yikes-inc-level-playing-field' ), $applicant_count ),
			$applicant_count
		);
		add_submenu_page(
			'edit.php?post_type=jobs',
			__( 'Applicants', 'yikes-inc-level-playing-field' ),
			__( 'Applicants', 'yikes-inc-level-playing-field' ) . '&nbsp;' . $applicant_count_notice,
			'manage_options',
			'manage-applicants',
			array( $this, 'render_level_playing_field_dashboard' )
		);
	}

	/**
	 * Render the Level Playing Field Dashboard Managemenet Page
	 */
	public function render_level_playing_field_dashboard() {
		//Our class extends the WP_List_Table class, so we need to make sure that it's there
		if ( ! class_exists( 'WP_List_Table' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		}

		// Include the appropriate table class (based on the view query arg)
		if ( ! isset( $_GET['view'] ) || 'sort-by-jobs' === $_GET['view'] ) {
			// All jobs table class
			require_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/class-yikes-inc-level-playing-field-admin-jobs-table.php' );
		} else {
			// All applicants table class
			require_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/class-yikes-inc-level-playing-field-admin-applicants-table.php' );
		}

		// Prepare Table of elements
		$wp_list_table = new Link_List_Table( $this->helpers );
		$wp_list_table->prepare_items();
		?><div class="wrap"><?php
			printf( '<h1>' . esc_html__( 'Job Applicants', 'yikes-inc-level-playing-field' ) . '</h1>' );
			printf( '<p class="description">' . esc_html__( 'Select a job to view the current job applicants.', 'yikes-inc-level-playing-field' ) . '</p>' );
			//Table of elements
			$wp_list_table->display();
		?></div><?php
	}
}
