<?php
/**
 * Register a custom menu page.
 */
function wpdocs_register_my_custom_menu_page() {
	add_submenu_page(
		'edit.php?post_type=jobs',
		__( 'Applicants', 'yikes-inc-level-playing-field' ),
		__( 'Applicants', 'yikes-inc-level-playing-field' ),
		'manage_options',
		'manage-applicants',
		'render_level_playing_field_dashboard'
	);
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/**
 * Render the Level Playing Field Dashboard Managemenet Page
 */
function render_level_playing_field_dashboard() {
	//Our class extends the WP_List_Table class, so we need to make sure that it's there
	if ( ! class_exists( 'WP_List_Table' ) ) {
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
	}

	// Include the appropriate table class (based on the view query arg)
	if ( ! isset( $_GET['view'] ) || 'all-applicants' === $_GET['view'] ) {
		// All applicants table class
		require_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/class-yikes-inc-level-playing-field-admin-applicants-table.php' );
	} else {
		// All jobs table class
		require_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/class-yikes-inc-level-playing-field-admin-jobs-table.php' );
	}

	//Prepare Table of elements
	$wp_list_table = new Link_List_Table();
	$wp_list_table->prepare_items();
	?><div class="wrap"><?php
		printf( '<h1>' . __( 'Job Applicants', 'yikes-inc-level-playing-field' ) . '</h1>' );
		printf( '<p class="description">' . __( 'Select a job to view the current job applicants.', 'yikes-inc-level-playing-field' ) . '</p>' );
		//Table of elements
		$wp_list_table->display();
	?></div><?php
}

/**
 * Get all of the current jobs from the database
 * @return array Data returned from the job query.
 * @since 1.0.0
 */
function get_level_playing_field_jobs() {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$applicant_args = array(
		'post_type' => 'jobs',
		'posts_per_page' => 20,
		'paged' => $paged,
	);
	$applicant_query = new WP_Query( $applicant_args );
	return $applicant_query;
}

/**
 * Get all of the current applicants from the database
 * @return array Data returned from the applicant query.
 * @since 1.0.0
 */
function get_level_playing_field_applicants() {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$applicant_args = array(
		'post_type' => 'applicants',
		'posts_per_page' => 20,
		'paged' => $paged,
	);
	$applicant_query = new WP_Query( $applicant_args );
	return $applicant_query;
}
