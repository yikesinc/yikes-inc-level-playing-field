<?php
/**
 * Applicant Messenger Class & Methods
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 */
class Yikes_Inc_Level_Playing_Field_Applicant_Messenger {

	private $helpers;

	private $job_id;

	private $applicant_id;

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $helpers, $job_id, $applicant_id ) {
		$this->helpers = $helpers;
		$this->job_id = $job_id;
		$this->applicant_id = $applicant_id;
		// Actions
		add_action( 'yikes_level_playing_field_application_messenger', array( $this, 'render_messenger_application_header' ), 10 );
		add_action( 'yikes_level_playing_field_application_messenger', array( $this, 'render_messenger_application_message_box' ), 20 );
	}

	/**
	 * Render the messenger application header
	 * @return mixed HTML content for the messenger header
	 */
	public function render_messenger_application_header() {
		$applicant_conversation = $this->get_applicant_conversation();
		// Render the page
		?>
		<h2 class="yikes-applicant-messenger-title">
			<?php
				$job_obj = get_post( $this->job_id );
				if ( current_user_can( 'manage_options' ) ) {
					// Admin Page Title
					echo esc_html( sprintf( '%s | Applicant Conversation', $job_obj->post_title ) );
				} else {
					// User Visible Page title
					echo esc_html( sprintf( 'Conversation About %s', $job_obj->post_title ) );
			} ?>
		</h2>
		<?php
	}

	/**
	 * Render the container that users/admins use to send messages
	 * @return mixed HTML Content for the message container.
	 */
	public function render_messenger_application_message_box() {
		ob_start();
		?>
		<form name="yikes-level-playing-field-applicant-message" action="" method="POST">
			<textarea name="applicant-message" class="applicant-message-box" placeholder="<?php esc_attr_e( 'Enter your message here.', 'yikes-inc-level-playing-field' ); ?>"></textarea>
			<input type="submit" class="yikes-btn yikes-btn-success" value="<?php esc_attr_e( 'Send Message', 'yikes-inc-level-playing-field' ); ?>" />
		</form>
		<?php
		$content = ob_get_contents();
		ob_get_clean();
		echo $content;
	}

	/**
	 * Get the applicant conversation from the databse
	 * @return mixed HTML Content of the conversation, extracted from the db
	 */
	public function get_applicant_conversation() {
		return get_post_meta( $this->applicant_id, 'applicant_conversation', true );
	}

	/**
	 * Function to send messages back and forth
	 * @since 1.0.0
	 */
	public function send_applicant_message() {

	}

}
