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
		add_action( 'yikes_level_playing_field_before_applicant_messenger', array( $this, 'generate_applicant_messenger_password_form' ), 10 );
		add_action( 'yikes_level_playing_field_applicant_messenger', array( $this, 'render_applicant_messenger_header' ), 10 );
		add_action( 'yikes_level_playing_field_applicant_messenger', array( $this, 'render_applicant_messenger_messages' ), 15 );
		add_action( 'yikes_level_playing_field_applicant_messenger', array( $this, 'render_applicant_messenger_message_box' ), 20 );
		/** Password Protected Form **/
		add_filter( 'the_password_form', array( $this, 'level_playing_field_custom_password_protection_form' ) );
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
		// If password protected, abort
		if ( $this->helpers->is_password_protected() ) {
			return;
		}
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
		// If password protected, abort
		if ( $this->helpers->is_password_protected() ) {
			return;
		}
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
		// If password protected, abort
		if ( $this->helpers->is_password_protected() ) {
			return;
		}
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

		$count = 1;
		?>
		<ul class="applicant-messenger-chat">
		<?php
			foreach ( $conversation[ $this->job_id ][ $this->applicant_id ] as $message_position => $message_data ) {
				$is_admin = ( strpos( $message_data['user'], '-admin' ) > 0 ) ? true : false;
				$container_class = ( $is_admin ) ? 'left' : 'right';
				$message_class = ( ! $is_admin ) ? 'other-message' : 'my-message';
				$user_name = $this->lpf_get_user_data( 'username', $is_admin, $message_data['user'] );
				$avatar = $this->lpf_get_user_data( 'avatar', $is_admin, $message_data['user'] );
				?>
				<!-- Display The Message -->
				<li class="clearfix yikes-animated yikes-fadeInUp">
					<div class="message-data align-<?php echo esc_attr( $container_class ); ?>">
						<?php echo wp_kses_post( $avatar . ' ' . $user_name ); ?>
						<span class="message-data-time">
							<?php echo wp_kses_post( $this->generate_message_details( $message_data['timestamp'] ) ); ?>
						</span>
					</div>
					<div class="message <?php echo esc_attr( $message_class ); ?> float-<?php echo esc_attr( $container_class ); ?>">
						<?php echo apply_filters( 'the_content', esc_textarea( $message_data['message'] ) ); ?>
					</div>
				</li>
				<?php
				$count++;
			}
		?>
		</ul>
		<?php
	}

	/**
	 * Get the current users username
	 * @param  boolean $is_admin True/False if this is the admin user
	 * @return string The username to use in the messenger application.
	 */
	public function lpf_get_user_data( $data, $is_admin, $user ) {
		$user_data = ( $is_admin ) ? get_userdata( str_replace( '-admin', '', $user ) ) : get_post( (int) $message_data['user'] );
		$username = ( $is_admin ) ? ( ( $user_data->user_nicename ) ? $user_data->user_nicename : __( 'Administrator', 'yikes-inc-level-playing-field' ) ) : $this->helpers->blur_string( $user_data->post_title );
		$avatar = ( $is_admin ) ? get_avatar( $admin_user->user_email, 96, false, __( 'Admin Avatar', 'yikes-inc-level-playing-field' ), array(
			'class' => 'avatar admin',
			'alt' => esc_attr__( 'Admin Avatar', 'yikes-inc-leve' ),
		) ) : '<img alt="' . esc_attr__( 'Applicant Avatar', 'yikes-inc-level-playing-field' ) . '" class="avatar applicant" src="' . esc_url( get_post_meta( $this->applicant_id, 'applicant_avatar', true ) ) . '">';

		// Switch over and return the data type
		switch ( $data ) {
			default:
			case 'username':
				return ucfirst( $username );
			case 'avatar':
				return $avatar;
		}
	}

	/**
	 * Generate the message details
	 * @param  integer $timestamp UNIX timestamp, when the message was sent
	 * @return mixed 						  HTML content to display.
	 */
	public function generate_message_details( $timestamp ) {
		$today = ( date( 'Ymd' ) == date( 'Ymd', $timestamp ) ) ? true : false;
		$formatted_time = date( get_option( 'time_format' ), $timestamp );
		$formatted_date = date( get_option( 'date_format' ), $timestamp );
		if ( $today ) {
			$formatted_data = __( 'Today', 'yikes-inc-level-playing-field' );
		}
		return sprintf( __( 'Sent at %s, %s', 'yikes-inc-level-playing-field' ), $formatted_time, $formatted_data );
	}

	/**
	 * Generate the password protection form for applicant messenger
	 * @return mixed HTML content for the password protection form
	 */
	public function generate_applicant_messenger_password_form() {
		if ( current_user_can( 'manage_options' ) ) :
			return;
		endif;
		// if the password is required, display it
		if ( $this->helpers->is_password_protected() ) :
			echo get_the_password_form();
		endif;
	}

	/**
	 * Render a new password protecton form, with a custom message
	 * @param  mixed $form HTML mixed content, password protection form.
	 * @return mixed       New HTML password protection form.
	 */
	function level_playing_field_custom_password_protection_form( $form ) {
		global $post;
		$page = ( isset( $_GET['page'] ) ) ? esc_textarea( $_GET['page'] ) : false;
		if ( ! $page || 'applicant-messenger' !== $page || ! isset( $post->post_type ) || 'applicants' !== $post->post_type ) {
			return $form;
		}
		$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
		$form = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
		<p>' . __( 'This messege is currently password protected. Please enter the password you received via email.', 'yikes-inc-level-playing-field' ) . '</p>
		<p><label for="' . esc_attr( $label ) . '">' . __( 'Password:', 'yikes-inc-level-playing-field' ) . ' </label>' .
		'<input name="post_password" id="' . esc_attr( $label ) . '" type="password" size="20" maxlength="20" /></p>' .
		'<input type="submit" name="Submit" value="' . esc_attr__( 'Submit', 'yikes-inc-level-playing-field' ) . '" />
		</form>';
		return $form;
	}
}
