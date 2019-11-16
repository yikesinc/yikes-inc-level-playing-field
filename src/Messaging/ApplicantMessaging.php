<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Messaging;

use WP_Post;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Activateable;
use Yikes\LevelPlayingField\Deactivateable;
use Yikes\LevelPlayingField\Renderable;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\Comment\ApplicantMessageRepository;
use Yikes\LevelPlayingField\Comment\ApplicantMessage;
use Yikes\LevelPlayingField\Email\ApplicantMessageToApplicantEmail;
use Yikes\LevelPlayingField\Email\ApplicantMessageFromApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewRequestToApplicantEmail;
use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\View\FormEscapedView;
use Yikes\LevelPlayingField\View\TemplatedView;

/**
 * Class ApplicantMessaging.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
class ApplicantMessaging implements Activateable, Deactivateable, Renderable, AssetsAware, Service {

	use MessagingAssets {
		load_assets as trait_load_assets;
	}

	const POST_TYPE = ApplicantManager::SLUG;
	const VIEW      = 'views/applicant-messaging';

	// Partials.
	const INTERVIEW_CONFIRMATION_PARTIAL = 'views/interview-confirmation';
	const INTERVIEW_SCHEDULER_PARTIAL    = 'views/interview-scheduler';

	// Define the JavaScript & CSS files.
	const CSS_HANDLE            = 'lpf-messaging-admin-styles';
	const CSS_URI               = '/assets/css/messaging';
	const DATEPICKER_CSS_HANDLE = 'jquery-ui-datepicker-styles';
	const DATEPICKER_CSS_URI    = '/assets/vendor/datepicker/jquery-ui';
	const TIMEPICKER_CSS_HANDLE = 'jquery-timepicker-styles';
	const TIMEPICKER_CSS_URI    = '/assets/vendor/timepicker/jquery.timepicker';

	/**
	 * Register our hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_assets();
		add_filter( 'admin_enqueue_scripts', function( $hook ) {

			// Ensure this is the edit page.
			if ( 'post.php' !== $hook ) {
				return;
			}

			// Ensure this is a real screen object.
			$screen = get_current_screen();
			if ( ! ( $screen instanceof \WP_Screen ) ) {
				return;
			}

			// Ensure this is the edit screen for the correct post type.
			if ( static::POST_TYPE !== $screen->post_type ) {
				return;
			}

			$this->enqueue_assets();
		} );

		add_action( 'wp_ajax_send_message', [ $this, 'send_message' ] );
		add_action( 'wp_ajax_nopriv_send_message', [ $this, 'send_message' ] );
		add_action( 'wp_ajax_refresh_conversation', [ $this, 'refresh_conversation' ] );
		add_action( 'wp_ajax_nopriv_refresh_conversation', [ $this, 'refresh_conversation' ] );
		add_action( 'admin_menu', [ $this, 'remove_default_comments_meta_boxes' ], 1 );
		add_action( 'wp_ajax_send_interview_request', [ $this, 'send_interview_request' ] );
		add_action( 'pre_get_comments', [ $this, 'exclude_applicant_messages' ] );
		add_filter( 'wp_count_comments', [ $this, 'exclude_applicant_messages_from_counts' ], 99, 2 );
		add_option( 'lpf_interview_request_failed', false, '', 'yes' );
		add_action( 'admin_notices', [ $this, 'maybe_display_email_error_notice' ] );
	}

	/**
	 * Activate the service: show any Applicant Messages that we previously hid.
	 *
	 * We're running a direct DB query here and in the deactivate function in order to prevent this operation from being filtered or from timing out.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare( "UPDATE {$wpdb->comments} SET comment_approved = '1' WHERE comment_agent = %s", ApplicantMessage::AGENT )
		);
	}

	/**
	 * Deactivate the service: hide our applicant messages from the main comments dashboard list table.
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {
		global $wpdb;

		$wpdb->query(
			$wpdb->prepare( "UPDATE {$wpdb->comments} SET comment_approved = 'post-trashed' WHERE comment_agent = %s", ApplicantMessage::AGENT )
		);
	}

	/**
	 * Load asset objects for use.
	 *
	 * @since 1.0.0
	 */
	protected function load_assets() {
		$this->trait_load_assets();
		$this->assets = array_merge(
			$this->assets,
			[
				self::DATEPICKER_CSS_HANDLE => new StyleAsset( self::DATEPICKER_CSS_HANDLE, self::DATEPICKER_CSS_URI ),
				self::TIMEPICKER_CSS_HANDLE => new StyleAsset( self::TIMEPICKER_CSS_HANDLE, self::TIMEPICKER_CSS_URI ),
			]
		);
	}

	/**
	 * Render the current Renderable.
	 *
	 * @since 1.0.0
	 *
	 * @param array $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 */
	public function render( array $context = [] ) {
		try {
			$this->enqueue_assets();

			$view = new FormEscapedView(
				new TemplatedView( $this->get_view_uri() )
			);

			return $view->render( $context );
		} catch ( \Exception $exception ) {
			// Don't let exceptions bubble up. Just render the exception message
			// into the metabox.
			return sprintf(
				'<pre>%s</pre>',
				$exception->getMessage()
			);
		}
	}

	/**
	 * Do the actual persistence of the changed data.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id ID of the post to persist.
	 */
	protected function persist( $post_id ) {}

	/**
	 * Get the View URI to use for rendering the metabox.
	 *
	 * @since 1.0.0
	 *
	 * @return string View URI.
	 */
	protected function get_view_uri() {
		return static::VIEW;
	}

	/**
	 * Process the metabox attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post      $post The post object.
	 * @param array|string $atts Raw metabox attributes passed into the
	 *                           metabox function.
	 *
	 * @return array Processed metabox attributes.
	 */
	protected function process_attributes( $post, $atts ) {
		return self::get_context_data( $post->ID, $atts['args']['is_metabox'] );
	}

	/**
	 * Get the data needed for this metabox or template.
	 *
	 * @since 1.0.0
	 *
	 * @param int  $applicant_id The ID of an applicant.
	 * @param bool $is_metabox   Whether the current context is in an admin metabox.
	 *
	 * @return array $context   The data needed for this metabox or template.
	 */
	public function get_context_data( $applicant_id, $is_metabox = false ) {

		$repository = new ApplicantMessageRepository();
		$comments   = $repository->find_all( $applicant_id );
		$applicant  = ( new ApplicantRepository() )->find( $applicant_id );

		return [
			'applicant'  => $applicant,
			'comments'   => $comments,
			'is_metabox' => $is_metabox,
			'is_cancel'  => false,
			'is_confirm' => false,
			'partials'   => [
				'interview_scheduler'    => static::INTERVIEW_SCHEDULER_PARTIAL,
				'interview_confirmation' => static::INTERVIEW_CONFIRMATION_PARTIAL,
			],
		];
	}

	/**
	 * Create a new comment.
	 *
	 * @since 1.0.0
	 */
	public function send_message() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'send_message', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'level-playing-field' ),
			], 403 );
		}

		// Sanitize our variables.
		$comment = filter_var( $_POST['message'], FILTER_SANITIZE_STRING );
		$post_id = filter_var( $_POST['post_id'], FILTER_SANITIZE_NUMBER_INT );

		// Confirm we have our variables.
		if ( empty( $comment ) || empty( $post_id ) ) {
			wp_send_json_error( [
				'reason'  => __( 'Please review: a required field is missing.', 'level-playing-field' ),
				'comment' => $comment,
				'post_id' => $post_id,
			], 400 );
		}

		$message_class = new ApplicantMessage();

		$comment_data = [
			'comment_author'   => is_user_logged_in() ? ApplicantMessage::ADMIN_AUTHOR : ApplicantMessage::APPLICANT_AUTHOR,
			'comment_approved' => is_user_logged_in() ? 1 : 0,
			'comment_post_ID'  => $post_id,
			'comment_content'  => $comment,
		];

		$new_message = $message_class->create_comment( $comment_data );

		if ( $new_message ) {

			if ( ApplicantMessage::APPLICANT_AUTHOR === $comment_data['comment_author'] ) {

				// Send the message as an email to the admin/job manager.
				$email = ( new ApplicantMessageFromApplicantEmail( $post_id, $comment ) )->send();
			} else {

				// Send the message as an email to the applicant.
				$email = ( new ApplicantMessageToApplicantEmail( $post_id, $comment ) )->send();
			}

			wp_send_json_success( [
				'reason'  => __( 'The message was sent successfully.', 'level-playing-field' ),
				'post_id' => $post_id,
				'email'   => $email,
			], 200 );
		}

		wp_send_json_error( [
			'reason' => __( 'The comment could not be inserted.', 'level-playing-field' ),
		], 400 );
	}

	/**
	 * Refresh the conversation and send the HTML back to the browser.
	 *
	 * @since 1.0.0
	 */
	public function refresh_conversation() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'refresh_conversation', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'level-playing-field' ),
			], 403 );
		}

		$post_id    = filter_var( wp_unslash( $_POST['post_id'] ), FILTER_SANITIZE_NUMBER_INT );
		$is_metabox = filter_var( filter_var( wp_unslash( $_POST['is_metabox'] ), FILTER_SANITIZE_NUMBER_INT ), FILTER_VALIDATE_INT ) === 1;

		// We need to format the args array to match the way `get_callback_args()` formats the callback data.
		$args = [
			'args' => [
				'is_metabox' => $is_metabox,
			],
		];

		ob_start();
		echo $this->render( $this->process_attributes( get_post( $post_id ), $args ) ); // phpcs:ignore WordPress.Security.EscapeOutput
		$html = ob_get_clean();

		wp_send_json_success( $html, 200 );
	}

	/**
	 * Exclude comments of type 'applicant_message' from comment queries.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Comment_Query $query Current instance of WP_Comment_Query (passed by reference).
	 */
	public function exclude_applicant_messages( \WP_Comment_Query $query ) {
		$vars = &$query->query_vars;
		if (
			empty( $vars['type'] ) ||
			( ! empty( $vars['type__in'] ) && ! in_array( ApplicantMessage::TYPE, (array) $vars['type__in'], true ) )
		) {
			$vars['type__not_in']   = (array) $vars['type__not_in'];
			$vars['type__not_in'][] = ApplicantMessage::TYPE;
		}
	}

	/**
	 * Exclude applicant messages from comments list table counts.
	 *
	 * @see get_comment_count()
	 * @see wp_count_comments
	 *
	 * @param mixed $comment_counts An array or object of comment counts by comment status.
	 * @param int   $post_id        A post ID if we're calculating counts for an individual post.
	 *
	 * @return object $comment_counts An object of comment counts by comment status in the following format.
	 *  array(
	 *   ["moderated"]      => 0,
	 *   ["approved"]       => 1,
	 *   ["total_comments"] => 1,
	 *   ["all"]            => 1,
	 *   ["spam"]           => 0,
	 *   ["trash"]          => 0,
	 *   ["post-trashed"]   => 0,
	 * )
	 */
	public function exclude_applicant_messages_from_counts( $comment_counts, $post_id ) {
		global $wpdb;

		// Don't change counts for single posts.
		if ( ! empty( $post_id ) ) {
			return (object) $comment_counts;
		}

		// WordPress expects the comments returned as an object but passes them in as an array.
		// WooCommerce, for example, turns the comment counts into an object. We should work with an array and convert it back to an object.
		$comment_counts = (array) $comment_counts;

		// These are the keys WordPress expects for the comment statuses.
		$comment_stati = [
			'0'              => 'moderated',
			'1'              => 'approved',
			'spam'           => 'spam',
			'trash'          => 'trash',
			'post-trashed'   => 'post-trashed',
			'all'            => 'all',
			'total_comments' => 'total_comments',
		];

		// If nothing else has filtered the comment counts, use WordPress' function to get the default counts.
		if ( empty( $comment_counts ) ) {
			$comment_counts = get_comment_count();

			// WP backwards compatibility.
			$comment_counts['moderated'] = $comment_counts['awaiting_moderation'];
			unset( $comment_counts['awaiting_moderation'] );
		}

		// Get the count of applicant message comments per comment status.
		$count = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT comment_approved, COUNT(*) AS num_comments
				FROM {$wpdb->comments}
				WHERE comment_type = %s
				GROUP BY comment_approved",
				ApplicantMessage::TYPE
			),
			ARRAY_A
		);

		if ( empty( $count ) ) {
			return (object) $comment_counts;
		}

		// WordPress also stores a total count as "all" and "total_comments" so default a total # of applicant comments here.
		$total = 0;

		// Go through each comment status and subtract our comment numbers from the total.
		foreach ( $count as $row ) {
			$comment_approved = $comment_stati[ $row['comment_approved'] ];
			$comment_count    = $row['num_comments'];
			$total           += $row['num_comments'];

			$comment_counts[ $comment_approved ] -= (int) $comment_count;
		}

		// Subtract our total number of comments from the total.
		$comment_counts['all']            -= $total;
		$comment_counts['total_comments'] -= $total;

		/**
		 * Allow other plugins to filter/exclude our logic completely.
		 *
		 * @param array $comment_counts The array of comment counts by comment status.
		 *
		 * @return array $comment_counts The array of comment counts by comment status.
		 */
		$comment_counts = apply_filters( 'lpf_count_comments', $comment_counts );

		return (object) $comment_counts;
	}

	/**
	 * Remove the default comments' meta boxes.
	 *
	 * @since 1.0.0
	 */
	public function remove_default_comments_meta_boxes() {

		// Removes comments' status & comments' meta boxes.
		remove_meta_box( 'commentstatusdiv', static::POST_TYPE, 'normal' );
		remove_meta_box( 'commentsdiv', static::POST_TYPE, 'normal' );
	}

	/**
	 * AJAX handler for sending an interview request.
	 *
	 * @since 1.0.0
	 */
	public function send_interview_request() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'send_interview_request', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'level-playing-field' ),
			], 403 );
		}

		// Sanitize our variables.
		$comment  = filter_var( $_POST['message'], FILTER_SANITIZE_STRING );
		$date     = filter_var( $_POST['date'], FILTER_SANITIZE_STRING );
		$time     = filter_var( $_POST['time'], FILTER_SANITIZE_STRING );
		$location = filter_var( $_POST['location'], FILTER_SANITIZE_STRING );
		$post_id  = filter_var( $_POST['post_id'], FILTER_SANITIZE_NUMBER_INT );

		// Confirm we have our variables.
		if ( empty( $comment ) || empty( $date ) || empty( $time ) || empty( $location ) || empty( $post_id ) ) {
			wp_send_json_error( [
				'reason'   => __( 'Please review: a required field is missing.', 'level-playing-field' ),
				'comment'  => $comment,
				'date'     => $date,
				'time'     => $time,
				'location' => $location,
				'post_id'  => $post_id,
			], 400 );
		}

		$applicant = new Applicant( get_post( $post_id ) );

		/* translators: %1$s is the date and %2$s is the time. */
		$message = '<div class="lpf-message-interview-date">' . sprintf( __( 'You have received a request for an interview on %1$s at %2$s.', 'level-playing-field' ), $date, $time ) . '</div>';
		/* translators: %1$s is the location. */
		$message .= '<div class="lpf-message-interview-location">' . sprintf( __( 'Interview location: %1$s.', 'level-playing-field' ), '</div>' . $location );
		$message .= '<div class="lpf-message-interview-message">' . sprintf( __( 'Message from employer', 'level-playing-field' ) ) . '</div>';
		$message .= $comment;
		$message .= '<div class="lpf-message-interview-instructions">';
		$message .= __( 'Please click Confirm Interview to accept this date and time or Decline Interview to reschedule.', 'level-playing-field' );
		$message .= '</div>';

		$message_class = new ApplicantMessage();
		$comment_data  = [
			'comment_author'   => ApplicantMessage::ADMIN_AUTHOR,
			'comment_approved' => 1,
			'comment_post_ID'  => $post_id,
			'comment_content'  => $message,
		];
		$new_message   = $message_class->create_comment( $comment_data );

		if ( $new_message ) {

			// Send the message as an email to the applicant.
			$email = ( new InterviewRequestToApplicantEmail( $applicant, $message ) )->send();

			// Check if error occured and handle it.
			if ( ! $email ) {

				// Delete interview request comment because it wasn't sent.
				$message_class->delete_comment( $new_message );

				// Update option to set off admin notice for email error.
				if ( ! get_option( 'lpf_interview_request_failed' ) ) {

					update_option( 'lpf_interview_request_failed', true );

				}

				wp_send_json_error( [
					'reason'  => __( 'Email failed to send.', 'level-playing-field' ),
					'post_id' => $post_id,
				] );
			}

			// Email was successful reset our error option if set.
			if ( get_option( 'lpf_interview_request_failed' ) ) {

				update_option( 'lpf_interview_request_failed', false );

			}

			// Save the interview variables to the applicant model.
			$applicant->set_interview_status( 'scheduled' );
			$applicant->set_interview([
				ApplicantMeta::DATE     => $date,
				ApplicantMeta::TIME     => $time,
				ApplicantMeta::LOCATION => $location,
				ApplicantMeta::MESSAGE  => $comment,
			]);
			$applicant->persist_properties();

			wp_send_json_success( [
				'reason'  => __( 'The interview request was sent successfully.', 'level-playing-field' ),
				'email'   => $email,
				'post_id' => $post_id,
			], 200 );
		}

		wp_send_json_error( [
			'reason' => __( 'The comment could not be inserted.', 'level-playing-field' ),
		], 400 );
	}

	/**
	 * Display email error notice if error occurs during interview request.
	 *
	 * @since 1.0.0
	 */
	public function maybe_display_email_error_notice() {
		$error_id = 'lpf-email-error-message';
		$class    = 'notice notice-error';
		$link     = 'https://wordpress.org/plugins/wp-mail-smtp/';

		$check_for_errors = get_option( 'lpf_interview_request_failed' );

		if ( ! $check_for_errors ) {
			return;
		}

		printf(
			'<div id="%1$s" class="%2$s"><p>%3$s<a href="%4$s" rel=”noopener noreferrer” target="_blank">%5$s</a></p></div>',
			esc_attr( $error_id ),
			esc_attr( $class ),
			esc_html__( 'Irks! Your website is having trouble sending email. ', 'level-playing-field' ),
			esc_attr( $link ),
			esc_html__( 'Try using WP Mail SMTP To Send Emails.', 'level-playing-field' )
		);
	}

}
