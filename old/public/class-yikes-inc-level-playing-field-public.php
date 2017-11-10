<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/public
 * @author     YIKES, Inc. <plugins@yikesinc.com>
 */
class Yikes_Inc_Level_Playing_Field_Public {

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
	 * Helper functions, to make accessible to this class
	 */
	private $helpers;

	/**
	 * Global Error Check and response
	 */
	private $error;
	private $error_response;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $helpers ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->helpers = $helpers;

		/*
		 Register our Shortcodes */
		// Application Shortcode
		add_shortcode( 'lpf-application', array( $this, 'render_application_shortcode' ) );

		// Job Listing Table Shortcode
		add_shortcode( 'lpf-jobs', array( $this, 'render_job_listing_shortcode' ) );

		/* Template Loader */
		add_filter( 'template_include', array( $this, 'template_loader' ) );

		/* Process Form Submissions */
		add_action( 'init', array( $this, 'process_application_submission' ) );

		// Process Submissions
		add_action( 'init', array( $this, 'process_applicant_messenger_message_sending' ) );

		/* Render Submission Response */
		add_action( 'yikes_level_playing_field_before_single_job', array( $this, 'generate_application_submission_response' ), 10 );

		/* Render the message sent response */
		add_action( 'yikes_level_playing_field_before_applicant_messenger', array( $this, 'generate_application_submission_response' ), 15 );
		add_action( 'yikes_level_playing_field_before_applicant_messenger', array( $this, 'generate_message_submission_response' ), 15 );

		/* Fix the classes on the body */
		add_filter( 'body_class', array( $this, 'generate_proper_body_class' ), 999 );

		/* Fix the applicant messenger page title */
		add_filter( 'pre_get_document_title', array( $this, 'apply_level_playing_field_applicant_title' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/min/yikes-inc-level-playing-field-public.min.css', array(), YIKES_LEVEL_PLAYING_FIELD_VERSION, YIKES_LEVEL_PLAYING_FIELD_VERSION );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/min/yikes-inc-level-playing-field-public.min.js', array( 'jquery' ), YIKES_LEVEL_PLAYING_FIELD_VERSION, false );

	}

	/**
	 * Render the Job Application Form
	 *
	 * @param  array $atts  The shortcode attributes.
	 * @return string       HTML markup for th eform
	 */
	public function render_application_shortcode( $atts ) {

		// Parse the shortcode attributes
		$atts = shortcode_atts( array(
			'application' => false,
		), $atts, 'level-playing-field-application' );

		$application_id = (int) $atts['application'];
		$company_name = get_post_meta( $application_id, '_company_name', true );

		// If no application is specified, abort
		if ( ! $application_id ) {
			return __( 'It looks like you forgot to specify what application to retreive.', 'yikes-inc-level-playing-field' );
		}

		// Get the application fields
		$application_fields = $this->helpers->get_application_fields( (int) $application_id );

		?><div id="yikes-job-application-form" class="lity-hide"><?php

			printf( '<h3 class="application-title">' . esc_html( '%s Job Application' ) . '</h3>', get_the_title() );

if ( $company_name ) {
	printf( '<p class="company-name">' . esc_html( 'Company: %s' ) . '</p>', esc_html( get_post_meta( $application_id, '_company_name', true ) ) );
}

			// Render the application
if ( $application_fields ) {
	?>
	<form class="yikes-lpf-form yikes-lpf-section" action="" method="POST">
<?php
// Form Fields
foreach ( $application_fields as $app_field ) {
	// render the feild
	$this->helpers->render_field( $app_field );
}
?>
<input type="submit" name="submit" class="<?php echo esc_attr( apply_filters( 'yikes_level_playing_field_submit_application_button_class', 'yikes-lpf-submit' ) ); ?>" value="<?php esc_attr_e( 'Apply', 'yikes-inc-level-playing-field' ); ?>" />

		<!-- Application ID -->
		<input type="hidden" name="application_id" value="<?php echo esc_attr( $application_id ); ?>" />
		<!-- Security Nonce -->
		<?php wp_nonce_field( 'submit_job_application', 'submit_job_application' ); ?>
	</form>
	<?php
} else {
	?>
	<p><em><?php esc_html_e( 'It looks like you forgot to assign fields to this application!', 'yikes-inc-level-playing-field' ); ?></em></p>
	<?php
	if ( current_user_can( 'manage_options' ) ) {
		?><a href="<?php echo esc_url( admin_url( '/post.php?post=' . (int) $application_id . '&action=edit' ) ); ?>"><?php esc_html_e( 'Edit Job', 'yikes-inc-level-playing-field' ); ?></a><?php
	}
}
		?></div><?php

		// If the application is on a third party, setup that URL
		$third_party_site = ( get_post_meta( get_the_ID(), '_third_party_site', true ) ) ? true : false;
		$site_url = $third_party_site ? esc_url( get_post_meta( get_the_ID(), '_third_party_site_url', true ) ) : '#yikes-job-application-form';
		$data_lity = $third_party_site ? 'target="_blank"' : 'data-lity';
		$classes = $third_party_site ? 'yikes-btn yikes-btn-large yikes-btn-info' : 'apply-now-link yikes-btn yikes-btn-large yikes-btn-info';
		// return the 'Apply Now' link
		return '<a href="' . esc_attr( $site_url ) . '" class="' . apply_filters( 'yikes-level-playing-field-apply-now-link-classes', $classes ) . '" ' . esc_attr( $data_lity ) . '>Apply Now</a>';
	}

	/**
	 * Render the Jobs Table
	 *
	 * @param  array $atts  The shortcode attributes.
	 * @return string       HTML markup for th eform
	 */
	public function render_job_listing_shortcode( $atts ) {
		// Parse the shortcode attributes
		$atts = shortcode_atts( array(
			'job-categories' => false,
			'job-tags' => false,
			'type' => 'table',
		), $atts, 'level-playing-field-job-table' );
		ob_start();
		$this->enqueue_job_table_scripts_and_styles();
		// Include our responsive table
		include_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/class-yikes-inc-level-playing-field-job-table.php' );
		// Initialize the job table class
		$jobs_table = new Yikes_Inc_Level_Playing_Field_Job_Table( $atts );
		$content = ob_get_contents();
		ob_get_clean();
		// return the shortcode contents
		return $content;
	}

	/**
	 * Enqueue the scripts and styles needed for the job listing table
	 *
	 * @since 1.0.0
	 */
	public function enqueue_job_table_scripts_and_styles() {
		wp_enqueue_style( 'fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' );
		wp_enqueue_style( 'footable-css', plugin_dir_url( __FILE__ ) . 'css/min/footable.standalone.min.css', array( 'fontawesome' ), '3.0.0', YIKES_LEVEL_PLAYING_FIELD_VERSION );
		wp_enqueue_script( 'footable-js', plugin_dir_url( __FILE__ ) . 'js/min/footable.min.js', array( 'jquery' ), '3.0.0', YIKES_LEVEL_PLAYING_FIELD_VERSION );
	}

	/**
	 * Load the job posting template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. Level Playing Field looks for theme.
	 * overrides in /theme/level-playing-field/ by default.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public function template_loader( $template ) {
		$find = array( 'single-job.php' );
		$file = '';
		// if embed, abort - return default template
		if ( is_embed() ) {
			return $template;
		}
		if ( is_single() && get_post_type() === 'jobs' ) {
			$file 	= 'single-job.php';
			$find[] = $file;
			$find[] = $this->helpers->template_path() . $file;
		} elseif ( $this->helpers->is_job_taxonomy() ) {
			$term   = get_queried_object();
			if ( is_tax( 'job_categories' ) || is_tax( 'job_tags' ) ) {
				$file = 'taxonomy-' . $term->taxonomy . '.php';
			} else {
				$file = 'archive-jobs.php';
			}
			$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = $this->helpers->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = $this->helpers->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = $file;
			$find[] = $this->helpers->template_path() . $file;
		} elseif ( is_single() && 'applicants' === get_post_type() ) { // SINGLE APPLICANT PAGES (EG: Applicant Messenger)
			// Include our messenger class
			include_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/class-yikes-inc-level-playing-field-applicant-messenger.php' );
			$applicant_messenger = new Yikes_Inc_Level_Playing_Field_Applicant_Messenger( $this->helpers, $_GET['job'], $_GET['applicant'] );
			$file 	= 'applicant-messenger.php';
			$find[] = $file;
			$find[] = $this->helpers->template_path() . $file;
		}
		if ( $file ) {
			$template       = locate_template( array_unique( $find ) );
			if ( ! $template || YIKES_LEVEL_PLAYING_FIELD_TEMPLATE_DEBUG_MODE ) {
				$template = YIKES_LEVEL_PLAYING_FIELD_PATH . '/templates/' . $file;
			}
		}
		return $template;
	}

	/**
	 * Process Application Submissions
	 *
	 * @return boolean true/false based on submission success
	 * @since 1.0.0
	 */
	public function process_application_submission() {
		// Job application was not submitted
		if ( ! isset( $_POST['submit_job_application'] ) ) {
			return;
		}
		// Nonce/Security check failed
		if ( ! wp_verify_nonce( $_POST['submit_job_application'], 'submit_job_application' ) ) {
			$this->error = true;
			$this->response = __( 'Security check failed. Please refresh this page and try again.', 'yikes-inc-level-playing-field' );
			return;
		}
		// Unset the nonce, since we no longer need it
		unset( $_POST['submit_job_application'], $_POST['_wp_http_referer'] );
		// Include our responsive table
		include_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/class-yikes-inc-level-playing-field-process-submission.php' );
		// Initialize the job table class
		$submit_job_application = new Yikes_Inc_Level_Playing_Field_Process_Submission( $_POST, $this->helpers );
		// if it was a success
		if ( $submit_job_application ) {
			$this->success = true;
			$this->response = __( 'Your job application has been successfully submitted.', 'yikes-inc-level-playing-field' );
		}
	}

	/**
	 * Process the data submitted when a user submits a message via the messenger
	 *
	 * @return true/false
	 */
	public function process_applicant_messenger_message_sending() {
		// If a message wasn't sent, abort
		if ( ! isset( $_POST['send_message'] ) ) {
			return;
		}

		// Store the job and applicant IDs
		$job_id = absint( $_POST['job'] );
		$applicant_id = absint( $_POST['applicant'] );

		// Nonce/Security check failed
		if ( ! wp_verify_nonce( $_POST[ 'send_message_' . $job_id . '_' . $applicant_id ], 'send_message' ) ) {
			$this->error = true;
			$this->response = __( 'Security check failed. Please refresh this page and try again.', 'yikes-inc-level-playing-field' );
			return;
		}

		if ( '' === trim( $_POST['applicant_message'] ) ) {
			$this->error = true;
			$this->response = __( 'Please enter a message in the textbox below.', 'yikes-inc-level-playing-field' );
			return;
		}

		// Unset the nonce, since we no longer need it
		unset( $_POST[ 'send_message_' . $job_id . '_' . $applicant_id ], $_POST['_wp_http_referer'] );

		// Setup the new message data, and the responses
		$new_data = array(
			'timestamp' => current_time( 'timestamp' ),
			'user' => ( is_user_logged_in() ) ? get_current_user_ID() . '-admin' : $applicant_id,
			'message' => sanitize_text_field( $_POST['applicant_message'] ),
		);
		// If old data was previously stored, we need to append it
		if ( get_post_meta( $applicant_id, 'applicant_conversation', true ) ) {
			$new_message_data = get_post_meta( $applicant_id, 'applicant_conversation', true );
		}
		// Setup the final array to sore
		$new_message_data[ $job_id ][ $applicant_id ][] = $new_data;

		// The messages are stored in the applicants post type meta data (nested in a multi-dimensional array under the JOB ID)
		if ( ! update_post_meta( $applicant_id, 'applicant_conversation', $new_message_data ) ) {
			wp_redirect( add_query_arg( array( 'page' => 'applicant-messenger', 'job' => $job_id, 'applicant' => $applicant_id, 'message-sent' => 'false' ), get_the_permalink( $applicant_id ) ) );
			exit;
		}

		// Redirect the user, display the message (This prevents page refreshes from re-sending messages)
		wp_redirect( add_query_arg( array( 'page' => 'applicant-messenger', 'job' => $job_id, 'applicant' => $applicant_id, 'message-sent' => 'true' ), get_the_permalink( $applicant_id ) ) );
		exit;
	}

	/**
	 * Fix the body class on our applicant messenger (.no-sidebar is being added)
	 */
	public function generate_proper_body_class( $classes ) {
		$page = ( isset( $_GET['page'] ) ) ? esc_textarea( $_GET['page'] ) : false;
		if ( ! $page || 'applicant-messenger' !== $page ) {
			return $classes;
		}
		if ( is_active_sidebar( 'applicant-messenger' ) ) :
			$no_sidebar_key = array_search( 'no-sidebar', $classes );
			if ( $no_sidebar_key ) {
				unset( $classes[ $no_sidebar_key ] );
			}
		endif;
		return $classes;
	}

	/**
	 * Generate the success/error responses
	 *
	 * @return [type] [description]
	 */
	function generate_application_submission_response() {
		// Error
		if ( $this->error ) {
			if ( ! empty( $this->response ) ) {
				echo '<p class="submission-response error">' . esc_html( $this->response ) . '</p>';
				return;
			}
		}
		// Success
		if ( $this->success ) {
			if ( ! empty( $this->response ) ) {
				echo '<p class="submission-response success">' . esc_html( $this->response ) . '</p>';
				return;
			}
		}
	}

	/**
	 * Generate a response after the user attempts to submit the form
	 *
	 * @return mixed HTML content displayed back tot he user 'success/error'
	 * @since 1.0.0
	 */
	function generate_message_submission_response() {
		// If the message was not ever sent, abort.
		if ( ! isset( $_GET['message-sent'] ) ) {
			return;
		}
		$success = ( isset( $_GET['message-sent'] ) && 'true' === $_GET['message-sent'] ) ? true : false;
		if ( ! $success ) {
			echo '<p class="submission-response error">' . esc_html( $this->response ) . '</p>';
			return;
		}
		echo '<p class="submission-response success">' . esc_html__( 'Your message has been successfully sent.', 'yikes-inc-level-playing-field' ) . '</p>';
	}

	/**
	 * Filter the title on the applicant messenger
	 * This prevents the page title from being random numbers
	 */
	public function apply_level_playing_field_applicant_title() {
		global $post;
		// if not on the correct post type, abort
		if ( ! isset( $post->post_type ) || 'applicants' !== $post->post_type ) {
			return;
		}
		// if not on the correct page, abort
		if ( ! isset( $_GET['page'] ) || 'applicant-messenger' !== esc_textarea( $_GET['page'] ) ) {
			return;
		}
		return __( 'Applicant Messenger', 'yikes-inc-level-playing-field' );
	}
}
