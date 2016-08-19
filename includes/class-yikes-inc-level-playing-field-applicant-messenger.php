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
		// Add the appropriate body class
		add_filter( 'body_class', array( $this, 'generate_single_applicant_messenger_body_classes' ) );
		// Actions
		/* Generate the password form, to password protect the conversation */
		add_action( 'yikes_level_playing_field_before_applicant_messenger', array( $this, 'generate_the_application_messenger_password_form' ), 5 );
		add_action( 'yikes_level_playing_field_applicant_messenger', array( $this, 'render_applicant_messenger_header' ), 10 );
		add_action( 'yikes_level_playing_field_applicant_messenger', array( $this, 'render_applicant_messenger_messages' ), 15 );
		add_action( 'yikes_level_playing_field_applicant_messenger', array( $this, 'render_applicant_messenger_message_box' ), 20 );
	}

	/**
	 * Add additional classes to the body, so we can style things easier
	 * @param  array   $classes   Original array of classes to add to the body.
	 * @return array              New array with our additional classes added, to add to the body.
	 */
	public function generate_single_applicant_messenger_body_classes( $classes ) {
		$classes[] = 'yikes-level-playing-field applicant-messenger';
		return $classes;
	}

	/**
	 * Render the messenger application header
	 * @return mixed HTML content for the messenger header
	 */
	public function render_applicant_messenger_header() {
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
	public function render_applicant_messenger_message_box() {
		ob_start();
		?>
		<form name="yikes-level-playing-field-applicant-message" action="" method="POST">
			<?php wp_nonce_field( 'send_message', 'send_message_' . esc_attr( $this->job_id ) . '_' . esc_attr( $this->applicant_id ) ); ?>
			<!-- Job ID -->
			<input type="hidden" name="job" value="<?php echo esc_attr( $this->job_id ); ?>" />
			<!-- Applicant ID -->
			<input type="hidden" name="applicant" value="<?php echo esc_attr( $this->applicant_id ); ?>" />
			<!-- Comment/Message Box -->
			<textarea name="applicant_message" required class="applicant-message-box" placeholder="<?php esc_attr_e( 'Enter your message here.', 'yikes-inc-level-playing-field' ); ?>"></textarea>
			<!-- Submit Button -->
			<input type="submit" name="send_message" class="yikes-btn yikes-btn-success" value="<?php esc_attr_e( 'Send Message', 'yikes-inc-level-playing-field' ); ?>" />
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
	public function render_applicant_messenger_messages() {
		$conversation = get_post_meta( $this->applicant_id, 'applicant_conversation', true );
		// If no conversations have been created/found - abort and display a notice.
		if ( ! $conversation[ $this->job_id ][ $this->applicant_id ] || empty( $conversation[ $this->job_id ][ $this->applicant_id ] ) ) {
			?>
			<div class="yikes-lpf-no-messages-found">
				<p><?php esc_html_e( 'It looks like no messages have been sent.', 'yikes-inc-level-playing-field' ); ?></p>
				<p><?php esc_html_e( 'Get the conversation started below!', 'yikes-inc-level-playing-field' ); ?></p>
			</div>
			<?php
			return;
		}

		// $user_avatar = ( get_post_meta( $this->applicant_id, 'applicant_avatar', true ) ) ? get_post_meta( $this->applicant_id, 'applicant_avatar', true )

		foreach ( $conversation[ $this->job_id ][ $this->applicant_id ] as $message_position => $message_data ) {
			$is_admin = ( strpos( $message_data['user'], '-admin' ) > 0 ) ? true : false;
			// This is an admin user
			if ( $is_admin ) {
				$admin_user = get_userdata( str_replace( '-admin', '', $message_data['user'] ) );
				$user_name = ( isset( $admin_user->user_nicename ) ) ? $admin_user->user_nicename : __( 'Administrator', 'yikes-inc-level-playing-field' );
				$avatar = get_avatar( $admin_user->user_email, 96, false, __( 'Admin Avatar', 'yikes-inc-level-playing-field' ), array(
					'class' => 'avatar admin',
				) );
			} else {
				$user_object = get_post( (int) $message_data['user'] );
				$user_name = ( isset( $user_object->post_title ) ) ? $user_object->post_title : __( 'Applicant', 'yikes-inc-level-playing-field' );
				$avatar = '<img title="' . esc_attr_e( 'Applicant Avatar', 'yikes-inc-level-playing-field' ) . '" class="avatar applicant" src="' . esc_url( get_post_meta( $this->applicant_id, 'applicant_avatar', true ) ) . '">';
			}
			?>
			<ul>
				<!-- get_option( 'date_format') -->
				<li>Sent On: <?php echo date( 'm/d/Y', $message_data['timestamp'] ) . ' at ' . date( 'h:iA', $message_data['timestamp'] ); ?></li>
				<li>Sent By: <?php echo esc_html( ucfirst( $user_name ) ); ?></li>
				<li>Message: <?php echo $message_data['message']; ?></li>
				<li>Avatar: <?php echo $avatar; ?></li>
			</ul>
			<?php
		}
	}

	/**
	 * Generate the password protection form for applicant messenger
	 * @return mixed HTML content for the password protection form
	 */
	public function generate_the_application_messenger_password_form() {
		if ( current_user_can( 'manage_options' ) ) :
			return;
		endif;
		// if the password is required, display it
		if ( post_password_required() ) :
			echo get_the_password_form();
			get_footer();
			exit;
		endif;
	}
}
