<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/admin
 * @author     YIKES, Inc. <plugins@yikesinc.com>
 */
class Yikes_Inc_Level_Playing_Field_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The helper functions
	 * @var class
	 */
	private $helpers;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $helpers ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->helpers = $helpers;

		// include our custom menu(s)
		include_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'admin/partials/menu/class-yikes-inc-level-playing-field-application-management.php' );
		$application_management = new Yikes_Inc_Level_Playing_Field_Application_Management( $this->helpers );

		add_action( 'admin_print_scripts-post-new.php', array( $this, 'job_admin_scripts_and_styles' ), 11 );
		add_action( 'admin_print_scripts-post.php', array( $this, 'job_admin_scripts_and_styles' ), 11 );

		/* Alter the applicant status when the button is clicked */
		add_action( 'wp_ajax_update_applicant_status', array( $this, 'update_applicant_status' ) );

		include_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'admin/partials/yikes-inc-level-playing-field-ajax-functions.php' );

		/* Handle deleting an applicant from the database */
		add_action( 'admin_init', array( $this, 'delete_applicant_from_db' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		$enqueue_styles = $this->should_scripts_enqueue();
		if ( ! $enqueue_styles ) {
			return;
		}
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yikes_Inc_Level_Playing_Field_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yikes_Inc_Level_Playing_Field_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/min/yikes-inc-level-playing-field-admin.min.css', array(), YIKES_LEVEL_PLAYING_FIELD_VERSION, false );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		$enqueue_styles = $this->should_scripts_enqueue();
		if ( ! $enqueue_styles ) {
			return;
		}
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yikes_Inc_Level_Playing_Field_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yikes_Inc_Level_Playing_Field_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/min/yikes-inc-level-playing-field-admin.min.js', array( 'jquery' ), YIKES_LEVEL_PLAYING_FIELD_VERSION, false );
	}

	/**
	 * Enqueue scripts and styles to our 'job' custom post type explicilty
	 * @since 1.0.0
	 */
	public function job_admin_scripts_and_styles() {
		global $post_type;
		if ( 'jobs' === $post_type ) {
			wp_enqueue_style( 'jobs-metabox-styles', plugin_dir_url( __FILE__ ) . 'css/min/yikes-inc-level-playing-field-metabox-styles.min.css', array(), YIKES_LEVEL_PLAYING_FIELD_VERSION, false );
			wp_enqueue_script( 'jobs-metabox-scripts', plugin_dir_url( __FILE__ ) . 'js/min/yikes-inc-level-playing-field-metabox-scripts.min.js', array( 'jquery' ), YIKES_LEVEL_PLAYING_FIELD_VERSION, true );
		}
	}

	/**
	 * Update a given applicants status
	 * This is used on the applicant listing table, admin side
	 * @return boolean true/false based on update meta response.
	 * @since 1.0.0
	 */
	public function update_applicant_status() {
		// catch & store our variables passed in
		$applicant_id = ( isset( $_POST['applicant_id'] ) ) ? $_POST['applicant_id'] : false;
		$applicant_status = ( isset( $_POST['applicant_status'] ) ) ? $_POST['applicant_status'] : false;
		// If no applicant ID is passed in, return an error
		if ( ! $applicant_id || ! $applicant_status ) {
			return wp_send_json_error();
		}
		// update the status, and return a success response
		update_post_meta( $applicant_id, 'applicant_status', $applicant_status );
		// Ensure the applicant is no longer marked as 'new'
		delete_post_meta( $applicant_id, 'new_applicant' );

		return wp_send_json_success();
	}

	/**
	 * Delete an applicant from the database
	 * @return redirect the user after action is taken
	 */
	public function delete_applicant_from_db() {
		if ( ! isset( $_GET['action'] ) || 'delete-applicant' !== esc_attr( $_GET['action'] ) ) {
			return;
		}
		// Detect when our delete action is triggered
		if ( 'delete-applicant' === $_GET['action'] ) {
			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );
			// verify the nonce
			if ( ! wp_verify_nonce( $nonce, 'yikes_delete_applicant' ) ) {
				wp_die( 'Get a life script kiddie :)' );
				exit;
			} else {
				// delete an applicant
				$deleted_post = ( ! $this->helpers->delete_applicant( absint( $_GET['applicant'] ) ) ) ? false : true;
				// redirect back to the list table
				wp_redirect( esc_url_raw( add_query_arg( array(
					'post_type' => 'jobs',
					'page' => 'manage-applicants',
					'view' => 'all-applicants',
					'job' => absint( $_GET['job' ] ),
					'applicant-deleted' => $deleted_post,
				), admin_url( 'edit.php' ) ) ) );
				exit;
			}
		}
	}

	/**
	 * Determine if our scripts and styles should enqueue
	 * @return boolean true/false based on current admin page
	 */
	public function should_scripts_enqueue() {
		$screen = get_current_screen();
		$allowed_pages = array(
			'edit',
			'post',
			'add',
			'edit-tags',
			'jobs',
		);
		if ( ! isset( $screen ) || ! isset( $screen->base ) ) {
			return false;
		}
		// if we are on edit or add, and it's not a jobs post type, abort
		if ( in_array( $screen->base, $allowed_pages ) ) {
			if ( 'edit' === $screen->base || 'add' === $screen->base || 'post' === $screen->base ) {
				if ( ! isset( $screen->post_type ) || 'jobs' !== $screen->post_type ) {
					return false;
				}
			}
		}
		return true;
	}
}
