<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Messaging;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\Comment\ApplicantMessageRepository;
use Yikes\LevelPlayingField\Comment\ApplicantMessage;

/**
 * Class ApplicantMessaging.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
class ApplicantMessaging extends BaseMessaging implements AssetsAware {

	use AssetsAwareness;

	const HOOK_PRIORITY = 10;
	const CONTEXT       = 'normal';
	const POST_TYPE     = ApplicantManager::SLUG;
	const BOX_ID        = 'applicant-messaging';
	const BOX_TITLE     = 'Messaging';

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
		add_action( 'wp_ajax_refresh_conversation', [ $this, 'refresh_conversation' ] );
		add_filter( 'comments_clauses', [ $this, 'exclude_applicant_messages' ], 10, 1 );
		add_filter( 'comment_feed_where', [ $this, 'exclude_applicant_messages_from_feed_where' ], 10 );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {

		$script = new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER );
		$script->add_localization(
			'messaging_data',
			[
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
	 * Return the screen that this metabox should appear on.
	 *
	 * @since %VERSION%
	 *
	 * @return string | array | WP_Screen.
	 */
	protected function screen() {
		return static::POST_TYPE;
	}

	/**
	 * Create the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @param WP_Post $post The $post object.
	 */
	public function create_meta_box( $post ) {

		$this->conversation( $post->ID );
		$this->messaging_input();
	}

	/**
	 * Show the conversation.
	 *
	 * @since %VERSION%
	 *
	 * @param int $post_id The post ID.
	 */
	private function conversation( $post_id ) {

		// Fetch all comments.
		$repository = new ApplicantMessageRepository();
		$comments   = $repository->find_all( $post_id );

		?>
		<div class="conversation-container">
		<?php

		if ( empty( $comments ) ) {
			?>
			<strong>Start the conversation.</strong>
			<?php
		} else {

			$count    = count( $comments );
			$suppress = $count > 10;
			$counter  = 1;

			foreach ( $comments as $comment ) {

				if ( 1 === $counter && $suppress ) {
					?>
					<h3 id="conversation-show-all"><?php esc_html_e( 'Show All Messages', 'yikes-level-playing-field' ); ?></h3>
					<?php
				}

				$classes = [];

				$classes[] = $suppress && ( $count - $counter >= 10 ) ? 'hidden' : '';

				$classes[] = $comment->get_author() === ApplicantMessage::DEFAULT_AUTHOR ? 'message-to-applicant' : 'message-from-applicant';

				$classes = array_map( 'sanitize_html_class', $classes );
				?>
				<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
					<span class="message"><?php echo esc_html( $comment->get_content() ); ?></span>
					<small class="message-timestamp"><?php echo esc_html( $comment->get_formatted_date() ); ?></small>
				</div>
				<?php
				$counter++;
			}
		}
		?>
		</div>
		<?php
	}

	/**
	 * Show the textarea & send button.
	 *
	 * @since %VERSION%
	 */
	private function messaging_input() {
		// This is where the user can enter their message to send to the Applicant.
		?>
			<div class="new-applicant-message-container">
				<textarea id="new-applicant-message" name="new-applicant-message"></textarea>
			</div>

			<div class="send-new-applicant-message-container">
				<button type="button" id="send-new-applicant-message" class="button button-primary">Send</button>
			</div>
		<?php
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
		$new_message   = $message_class->create_comment( $post_id, $message );

		if ( $new_message ) {
			wp_send_json_success();
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
		$this->conversation( $post_id );
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

		// Ensure this is a real screen object.
		$screen = get_current_screen();
		if ( ! ( $screen instanceof \WP_Screen ) ) {
			return $comments_query;
		}

		// If we're looking at our the post type, do not hide the comments.
		if ( static::POST_TYPE === $screen->post_type ) {
			return $clauses;
		}

		$type              = ApplicantMessage::TYPE;
		$clauses['where'] .= ( $clauses['where'] ? ' AND ' : '' ) . " comment_type != '{$type}' ";
		return $clauses;
	}
}
