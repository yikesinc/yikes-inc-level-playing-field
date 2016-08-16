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
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/min/yikes-inc-level-playing-field-admin.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/min/yikes-inc-level-playing-field-admin.min.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Enqueue scripts and styles to our 'job' custom post type explicilty
	 * @since 1.0.0
	 */
	public function job_admin_scripts_and_styles() {
		global $post_type;
		if ( 'jobs' === $post_type ) {
			wp_enqueue_style( 'jobs-metabox-styles', plugin_dir_url( __FILE__ ) . 'css/min/yikes-inc-level-playing-field-metabox-styles.min.css', array(), $this->version, 'all' );
			wp_enqueue_script( 'jobs-metabox-scripts', plugin_dir_url( __FILE__ ) . 'js/min/yikes-inc-level-playing-field-metabox-scripts.min.js', array( 'jquery' ), $this->version, 'all' );
		}
	}
}
