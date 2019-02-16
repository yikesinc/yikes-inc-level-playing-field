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
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\Comment\ApplicantMessageRepository;
use Yikes\LevelPlayingField\Comment\ApplicantMessage;
use Yikes\LevelPlayingField\Email\ApplicantMessageToApplicantEmail;
use Yikes\LevelPlayingField\Email\ApplicantMessageFromApplicantEmail;
use Yikes\LevelPlayingField\Email\InterviewRequestToApplicantEmail;
use Yikes\LevelPlayingField\Metabox;
use Yikes\LevelPlayingField\RequiredPages\ApplicantMessagingPage;
use Yikes\LevelPlayingField\RequiredPages\BaseRequiredPage;
use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\ApplicantRepository;

/**
 * Class ApplicantMessaging.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
class ApplicantMessaging extends Metabox\BaseMetabox {

	const CONTEXT   = 'normal';
	const POST_TYPE = ApplicantManager::SLUG;
	const BOX_ID    = 'applicant-messaging';
	const BOX_TITLE = 'Messaging';
	const VIEW      = 'views/applicant-messaging';

	// Partials.
	const INTERVIEW_CONFIRMATION_PARTIAL = 'views/interview-confirmation';
	const INTERVIEW_SCHEDULER_PARTIAL    = 'views/interview-scheduler';

	// Define the JavaScript & CSS files.
	const JS_HANDLE                  = 'lpf-messaging-admin-script';
	const JS_URI                     = 'assets/js/messaging';
	const JS_DEPENDENCIES            = [ 'jquery', 'jquery-ui-datepicker' ];
	const JS_VERSION                 = false;
	const TIMEPICKER_JS_HANDLE       = 'jquery-timepicker-script';
	const TIMEPICKER_JS_URI          = '/assets/vendor/timepicker/jquery.timepicker';
	const TIMEPICKER_JS_DEPENDENCIES = [ self::JS_HANDLE ];
	const CSS_HANDLE                 = 'lpf-messaging-admin-styles';
	const CSS_URI                    = '/assets/css/messaging';
	const DATEPICKER_CSS_HANDLE      = 'jquery-ui-datepicker-styles';
	const DATEPICKER_CSS_URI         = '/assets/vendor/datepicker/jquery-ui';
	const TIMEPICKER_CSS_HANDLE      = 'jquery-timepicker-styles';
	const TIMEPICKER_CSS_URI         = '/assets/vendor/timepicker/jquery.timepicker';

	/**
	 * Register our hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();

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
		add_filter( 'comments_clauses', [ $this, 'exclude_applicant_messages' ], 10, 1 );
		add_filter( 'comment_feed_where', [ $this, 'exclude_applicant_messages_from_feed_where' ], 10 );
		add_action( 'admin_menu', [ $this, 'remove_default_comments_meta_boxes' ], 1 );
		add_action( 'wp_ajax_send_interview_request', [ $this, 'send_interview_request' ] );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		$post_id = isset( $_GET['post'] ) ? filter_var( $_GET['post'], FILTER_SANITIZE_NUMBER_INT ) : 0;
		$script  = new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER );
		$script->add_localization(
			'messaging_data',
			[
				'post'       => [
					'ID' => $post_id,
				],
				'ajax'       => [
					'url'             => admin_url( 'admin-ajax.php' ),
					'send_nonce'      => wp_create_nonce( 'send_message' ),
					'refresh_nonce'   => wp_create_nonce( 'refresh_conversation' ),
					'interview_nonce' => wp_create_nonce( 'send_interview_request' ),
				],
				'strings'    => [
					'show_additional_messages' => __( 'Show All Messages', 'yikes-level-playing-field' ),
					'hide_additional_messages' => __( 'Hide Additional Messages', 'yikes-level-playing-field' ),
				],
				'is_metabox' => is_admin(),
			]
		);

		return [
			$script,
			new ScriptAsset( self::TIMEPICKER_JS_HANDLE, self::TIMEPICKER_JS_URI, self::TIMEPICKER_JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER ),
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
			new StyleAsset( self::DATEPICKER_CSS_HANDLE, self::DATEPICKER_CSS_URI ),
			new StyleAsset( self::TIMEPICKER_CSS_HANDLE, self::TIMEPICKER_CSS_URI ),
		];
	}

	/**
	 * Get the ID to use for the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string ID to use for the metabox.
	 */
	protected function get_id() {
		return static::BOX_ID;
	}

	/**
	 * Get the title to use for the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string Title to use for the metabox.
	 */
	protected function get_title() {
		return static::BOX_TITLE;
	}

	/**
	 * Get the screen on which to show the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string|array|\WP_Screen Screen on which to show the metabox.
	 */
	protected function get_screen() {
		return static::POST_TYPE;
	}

	/**
	 * Get the context in which to show the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string Context to use.
	 */
	protected function get_context() {
		return static::CONTEXT_NORMAL;
	}

	/**
	 * Get the array of arguments to pass to the render callback.
	 *
	 * @since %VERSION%
	 *
	 * @return array Array of arguments.
	 */
	protected function get_callback_args() {
		return [
			'is_metabox' => true,
		];
	}

	/**
	 * Do the actual persistence of the changed data.
	 *
	 * @since %VERSION%
	 *
	 * @param int $post_id ID of the post to persist.
	 */
	protected function persist( $post_id ) {}

	/**
	 * Get the View URI to use for rendering the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string View URI.
	 */
	protected function get_view_uri() {
		return static::VIEW;
	}

	/**
	 * Process the metabox attributes.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
	 *
	 * @param int  $applicant_id The ID of an applicant.
	 * @param bool $is_metabox   Whether the current context is in an admin metabox.
	 *
	 * @return array $context   The data needed for this metabox or template.
	 */
	public static function get_context_data( $applicant_id, $is_metabox = false ) {

		$repository = new ApplicantMessageRepository();
		$comments   = $repository->find_all( $applicant_id );
		$applicant  = ( new ApplicantRepository() )->find( $applicant_id );

		return [
			'applicant'  => $applicant,
			'comments'   => $comments,
			'is_metabox' => $is_metabox,
			'partials'   => [
				'interview_scheduler'    => static::INTERVIEW_SCHEDULER_PARTIAL,
				'interview_confirmation' => static::INTERVIEW_CONFIRMATION_PARTIAL,
			],
		];
	}
	/**
	 * Create a new comment.
	 *
	 * @since %VERSION%
	 */
	public function send_message() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'send_message', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'yikes-level-playing-field' ),
			], 403 );
		}

		// Sanitize our variables.
		$comment = filter_var( $_POST['message'], FILTER_SANITIZE_STRING );
		$post_id = filter_var( $_POST['post_id'], FILTER_SANITIZE_NUMBER_INT );

		// Confirm we have our variables.
		if ( empty( $comment ) || empty( $post_id ) ) {
			wp_send_json_error( [
				'reason'  => __( 'An error occurred: a required field is missing.', 'yikes-level-playing-field' ),
				'comment' => $comment,
				'post_id' => $post_id,
			], 400 );
		}

		$message_class = new ApplicantMessage();
		$author        = is_user_logged_in() ? ApplicantMessage::ADMIN_AUTHOR : ApplicantMessage::APPLICANT_AUTHOR;
		$new_message   = $message_class->create_comment( $post_id, $comment, $author );

		if ( $new_message ) {

			if ( ApplicantMessage::APPLICANT_AUTHOR === $author ) {

				// Send the message as an email to the admin/job manager.
				$email = ( new ApplicantMessageFromApplicantEmail( $post_id, $comment ) )->send();
			} else {

				// Send the message as an email to the applicant.
				$email = ( new ApplicantMessageToApplicantEmail( $post_id, $comment ) )->send();
			}

			wp_send_json_success( [
				'reason'  => __( 'The message was successfully sent.', 'yikes-level-playing-field' ),
				'post_id' => $post_id,
				'email'   => $email,
			], 200 );
		}

		wp_send_json_error( [
			'reason' => __( 'The comment could not be inserted.', 'yikes-level-playing-field' ),
		], 400 );
	}

	/**
	 * Refresh the conversation and send the HTML back to the browser.
	 *
	 * @since %VERSION%
	 */
	public function refresh_conversation() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'refresh_conversation', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'yikes-level-playing-field' ),
			], 403 );
		}

		$post_id    = filter_var( wp_unslash( $_POST['post_id'] ), FILTER_SANITIZE_NUMBER_INT );
		$is_metabox = filter_var( filter_var( wp_unslash( $_POST['is_metabox'] ), FILTER_SANITIZE_NUMBER_INT ), FILTER_VALIDATE_INT ) === 1;

		// We need to format the args array to match the way `get_callback_args()` formats the callback data.
		$args         = [];
		$args['args'] = [
			'is_metabox' => $is_metabox,
		];

		ob_start();
		$this->process_metabox( get_post( $post_id ), $args );
		$html = ob_get_clean();

		wp_send_json_success( $html, 200 );
	}

	/**
	 * Exclude Applicant Messages from queries and RSS.
	 *
	 * @param  string $where The WHERE clause of the query.
	 * @return string
	 */
	public static function exclude_applicant_messages_from_feed_where( $where ) {
		$type = ApplicantMessage::TYPE;
		return $where . ( $where ? ' AND ' : '' ) . " comment_type != '{$type}' ";
	}

	/**
	 * Exclude Applicant Messages from queries and RSS.
	 *
	 * @param  array $clauses A compacted array of comment query clauses.
	 *
	 * @return array
	 */
	public static function exclude_applicant_messages( $clauses ) {

		// Check if we're on the admin.
		if ( is_admin() ) {

			// Ensure this is a real screen object.
			$screen = get_current_screen();
			if ( ! ( $screen instanceof \WP_Screen ) ) {
				return $clauses;
			}

			// If we're looking at our the post type, do not hide the comments.
			if ( static::POST_TYPE === $screen->post_type ) {
				return $clauses;
			}
		} elseif ( BaseRequiredPage::get_required_page_id( ApplicantMessagingPage::PAGE_SLUG ) === get_queried_object_id() ) {
			return $clauses;
		}

		$type              = ApplicantMessage::TYPE;
		$clauses['where'] .= ( $clauses['where'] ? ' AND ' : '' ) . " comment_type != '{$type}' ";
		return $clauses;
	}

	/**
	 * Remove the default comments' meta boxes.
	 *
	 * @since %VERSION%
	 */
	public function remove_default_comments_meta_boxes() {

		// Removes comments' status & comments' meta boxes.
		remove_meta_box( 'commentstatusdiv', static::POST_TYPE, 'normal' );
		remove_meta_box( 'commentsdiv', static::POST_TYPE, 'normal' );
	}

	/**
	 * AJAX handler for sending an interview request.
	 *
	 * @since %VERSION%
	 */
	public function send_interview_request() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'send_interview_request', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'yikes-level-playing-field' ),
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
				'reason'   => __( 'An error occurred: a required field is missing.', 'yikes-level-playing-field' ),
				'comment'  => $comment,
				'date'     => $date,
				'time'     => $time,
				'location' => $location,
				'post_id'  => $post_id,
			], 400 );
		}

		$applicant = new Applicant( get_post( $post_id ) );

		/* translators: %1$s is the date and %2$s is the time. */
		$message  = sprintf( __( 'You have been requested for an interview on %1$s at %2$s.', 'yikes-level-playing-field' ), $date, $time );
		$message .= '<br>';
		/* translators: %1$s is the location. */
		$message .= sprintf( __( 'The interview location is: %1$s.', 'yikes-level-playing-field' ), '<br>' . $location );
		$message .= '<br>';
		$message .= $comment;
		$message .= '<br>';
		$message .= __( 'A few statements on un-anonymization.', 'yikes-level-playing-field' );
		$message .= '<br>';

		$message_class = new ApplicantMessage();
		$new_message   = $message_class->create_comment( $post_id, $message, ApplicantMessage::ADMIN_AUTHOR );

		if ( $new_message ) {

			// Send the message as an email to the applicant.
			$email = ( new InterviewRequestToApplicantEmail( $applicant, $message ) )->send();

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
				'reason'  => __( 'The interview request was successfully sent.', 'yikes-level-playing-field' ),
				'email'   => $email,
				'post_id' => $post_id,
			], 200 );
		}

		wp_send_json_error( [
			'reason' => __( 'The comment could not be inserted.', 'yikes-level-playing-field' ),
		], 400 );
	}
}
