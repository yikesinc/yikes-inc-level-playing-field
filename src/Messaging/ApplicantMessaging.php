<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Messaging;

use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\Comment\ApplicantMessageRepository;
use Yikes\LevelPlayingField\Comment\ApplicantMessage;
use Yikes\LevelPlayingField\Email\ApplicantMessageEmail;
use Yikes\LevelPlayingField\Metabox;
use Yikes\LevelPlayingField\RequiredPages\ApplicantMessagingPage;
use Yikes\LevelPlayingField\RequiredPages\BaseRequiredPage;

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

	// Define the JavaScript & CSS files.
	const JS_HANDLE       = 'lpf-messaging-admin-script';
	const JS_URI          = 'assets/js/messaging';
	const JS_DEPENDENCIES = [ 'jquery' ];
	const JS_VERSION      = false;
	const CSS_HANDLE      = 'lpf-messaging-admin-styles';
	const CSS_URI         = '/assets/css/messaging';

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
		add_action( 'wp_ajax_refresh_conversation', [ $this, 'refresh_conversation' ] );
		add_filter( 'comments_clauses', [ $this, 'exclude_applicant_messages' ], 10, 1 );
		add_filter( 'comment_feed_where', [ $this, 'exclude_applicant_messages_from_feed_where' ], 10 );
		add_action( 'admin_menu', [ $this, 'remove_default_comments_meta_boxes' ], 1 );
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
				'post'    => [
					'ID' => $post_id,
				],
				'ajax'    => [
					'url'           => admin_url( 'admin-ajax.php' ),
					'send_nonce'    => wp_create_nonce( 'send_message' ),
					'refresh_nonce' => wp_create_nonce( 'refresh_conversation' ),
				],
				'strings' => [
					'show_additional_messages' => __( 'Show All Messages', 'yikes-level-playing-field' ),
					'hide_additional_messages' => __( 'Hide Additional Messages', 'yikes-level-playing-field' ),
				],
			]
		);

		return [
			$script,
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
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
		global $post;
		return [ $post ];
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
	 * @param array|string $atts Raw metabox attributes passed into the
	 *                           metabox function.
	 *
	 * @return array Processed metabox attributes.
	 */
	protected function process_attributes( $atts ) {

		// Fetch all comments.
		$repository = new ApplicantMessageRepository();
		$comments   = $repository->find_all( $atts->ID );

		return [
			'post'     => $atts,
			'comments' => $comments,
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
			wp_send_json_error();
		}

		// Sanitize our variables.
		$message = filter_var( $_POST['message'], FILTER_SANITIZE_STRING );
		$post_id = filter_var( $_POST['post_id'], FILTER_SANITIZE_NUMBER_INT );

		// Confirm we have our variables.
		if ( empty( $message ) || empty( $post_id ) ) {
			wp_send_json_error();
		}

		$message_class = new ApplicantMessage();
		$author        = is_user_logged_in() ? ApplicantMessage::ADMIN_AUTHOR : ApplicantMessage::APPLICANT_AUTHOR;
		$new_message   = $message_class->create_comment( $post_id, $message, $author );

		if ( $new_message ) {

			// Send the message as an email to the applicant.
			// $email = new ApplicantMessageEmail( $post_id, $message );
			// $email->send();

			wp_send_json_success( [ 'post_id' => $post_id ] );
		}

		wp_send_json_error();
	}

	/**
	 * Refresh the conversation and send the HTML back to the browser.
	 *
	 * @since %VERSION%
	 */
	public function refresh_conversation() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'refresh_conversation', 'nonce', false ) ) {
			wp_send_json_error();
		}

		$post_id = filter_var( $_POST['post_id'], FILTER_SANITIZE_NUMBER_INT );

		ob_start();
		$this->process_metabox( get_post( $post_id ) );
		$html = ob_get_clean();

		wp_send_json_success( $html );
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

}
