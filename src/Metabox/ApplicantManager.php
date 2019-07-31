<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use WP_Post;
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantCPT;
use Yikes\LevelPlayingField\Comment\ApplicantMessageRepository;
use Yikes\LevelPlayingField\Exception\Exception;
use Yikes\LevelPlayingField\Messaging\ApplicantMessaging;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\View\View;
use Yikes\LevelPlayingField\Roles\Capabilities;

/**
 * Class ApplicantManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class ApplicantManager extends BaseMetabox implements AssetsAware, Service {

	use AssetsAwareness;

	// Base Metabox.
	const BOX_ID   = 'view-applicant';
	const VIEW     = 'views/applicant';
	const PRIORITY = 1;

	// CSS and Javascript.
	const CSS_HANDLE       = 'lpf-admin-applicant-css';
	const CSS_URI          = 'assets/css/lpf-applicant-admin';
	const CSS_DEPENDENCIES = [ 'wp-components' ];
	const JS_HANDLE        = 'lpf-interview-details-admin-script';
	const JS_URI           = 'assets/js/interview-details';
	const JS_DEPENDENCIES  = [ 'wp-element', 'wp-i18n', 'wp-components' ];
	const JS_VERSION       = false;

	// Applicant Partials.
	const APPLICANT_BASIC_INFO            = 'views/applicant-basic-info';
	const APPLICANT_INTERVIEW_DETAILS     = 'views/applicant-interview-details';
	const APPLICANT_DETAILS               = 'views/applicant-details.php';
	const APPLICANT_SKILLS_QUALIFICATIONS = 'views/applicant-skills-qualifications.php';

	/**
	 * Whether to remove 3rd party metaboxes.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $remove_3rd_party_boxes = true;

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();
		$this->register_assets();

		add_action( 'admin_enqueue_scripts', function() {
			if ( ! $this->is_applicant_screen() ) {
				return;
			}

			$this->enqueue_assets();
		} );

		add_action( 'rest_api_init', function() {
			register_rest_route( 'yikes-level-playing-field/v1', '/interview-status', [
				'methods'             => \WP_REST_Server::READABLE,
				'permission_callback' => function () {
					return current_user_can( Capabilities::EDIT_APPLICANTS );
				},
				'callback'            => function( \WP_REST_Request $request ) {
					// Initialize our response to modify for different responses.
					$response = new \WP_REST_Response();

					$id = isset( $request['id'] ) ? absint( wp_unslash( $request['id'] ) ) : 0;

					if ( 0 === $id ) {
						$response->set_data( [
							'message' => __( 'User Not Found.', 'yikes-level-playing-field' ),
						] );

						// Set 400 status code
						$response->set_status( 400 );

						return $response;
					}

					try {
						$applicant = ( new ApplicantRepository() )->find( $id );
					} catch ( \Exception $e ) {
						$response->set_data([
							'code'    => get_class( $e ),
							'message' => esc_js( $e->getMessage() ),
						]);
						return $response;
					}

					$status    = $applicant->get_interview_status();
					$interview = $applicant->get_interview();

					$response->set_data( [
						'id'       => $id,
						'status'   => $status,
						'date'     => $interview['date'] ? $interview['date'] : '',
						'time'     => $interview['time'] ? $interview['time'] : '',
						'location' => $interview['location'] ? $interview['location'] : '',
						'message'  => $interview['message'] ? $interview['message'] : '',
					] );

					return $response;
				},
			]);
		});

		add_action( 'wp_ajax_save_nickname', function() {
			$this->save_nickname();
		} );

		add_action( 'lpf_applicant_screen_metabox', function( View $view ) {
			echo $view->render_partial( static::APPLICANT_DETAILS ); // phpcs:ignore WordPress.Security.EscapeOutput
		}, 10 );

		add_action( 'lpf_applicant_screen_metabox', function( View $view ) {
			echo $view->render_partial( static::APPLICANT_SKILLS_QUALIFICATIONS ); // phpcs:ignore WordPress.Security.EscapeOutput
		}, 20 );

		add_action( 'lpf_applicant_screen_metabox', function( View $view ) {
			$context = ( new ApplicantMessaging() )->get_context_data( $view->applicant->get_id(), true );
			echo $view->render_partial( ApplicantMessaging::VIEW, $context );  // phpcs:ignore WordPress.Security.EscapeOutput
		}, 30 );

		add_action( 'lpf_applicant_screen_rendered', function( View $view ) {
			$this->update_viewed_by( $view->applicant );
		}, 10 );

		add_action( 'lpf_applicant_screen_rendered', function( View $view ) {
			$this->mark_messages_read( $view->applicant );
		}, 10 );
	}

	/**
	 * Wrapper for adding/removing metaboxes for a given post type.
	 *
	 * @since %VERSION%
	 *
	 * @param string $post_type The post type.
	 */
	protected function meta_boxes( $post_type ) {
		parent::meta_boxes( $post_type );
		remove_meta_box( 'submitdiv', $post_type, 'side' );
		remove_meta_box( 'slugdiv', $post_type, 'normal' );
		remove_meta_box( 'authordiv', $post_type, 'normal' );
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
	 * Do the actual persistence of the changed data.
	 *
	 * @since %VERSION%
	 *
	 * @param int $post_id ID of the post to persist.
	 */
	protected function persist( $post_id ) {
		// There's no data to save for Applicants, so intentionally do nothing.
	}

	/**
	 * Get the title to use for the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string Title to use for the metabox.
	 */
	protected function get_title() {
		return __( 'Applicant', 'yikes-level-playing-field' );
	}

	/**
	 * Get the priority within the context where the boxes should show.
	 *
	 * @since %VERSION%
	 *
	 * @return string Priority within context.
	 */
	protected function get_priority() {
		return static::PRIORITY_HIGH;
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
	 * Set the "Viewed_By" property of applicant objeect.
	 *
	 * @since %VERSION%
	 *
	 * @param Applicant $applicant The applicant object.
	 */
	protected function update_viewed_by( $applicant ) {
		if ( $applicant->get_viewed_by() === 0 ) {
			$applicant->set_viewed_by( get_current_user_id() );
			$applicant->persist();
		}
	}

	/**
	 * Set messages sent by applicant to read/approved.
	 *
	 * @since %VERSION%
	 *
	 * @param Applicant $applicant The applicant object.
	 */
	protected function mark_messages_read( $applicant ) {
		$msgs = ( new ApplicantMessageRepository() )->find_all( $applicant->get_id() );

		if ( ! empty( $msgs ) ) {
			foreach ( $msgs as $key => $msg ) {
				wp_set_comment_status( $key, 1 );
			}
		}
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
		$applicant = ( new ApplicantRepository() )->find( $post->ID );
		$job       = ( new JobRepository() )->find( $applicant->get_job_id() );

		return [
			'applicant' => $applicant,
			'job'       => $job,
		];
	}

	/**
	 * Get the screen on which to show the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string|array|\WP_Screen Screen on which to show the metabox.
	 */
	protected function get_screen() {
		return $this->get_post_types();
	}

	/**
	 * Save new nickname upon edit.
	 *
	 * @since %VERSION%
	 */
	private function save_nickname() {
		// Handle nonce.
		if ( ! check_ajax_referer( 'lpf_applicant_nonce', 'nonce', false ) ) {
			wp_send_json_error();
		}

		$id       = isset( $_POST['id'] ) ? absint( wp_unslash( $_POST['id'] ) ) : 0;
		$nickname = isset( $_POST['nickname'] ) ? sanitize_text_field( wp_unslash( $_POST['nickname'] ) ) : '';

		try {
			$applicant = ( new ApplicantRepository() )->find( $id );
			$applicant->set_nickname( $nickname );
			$applicant->persist();
		} catch ( Exception $e ) {
			wp_send_json_error( [
				'code'    => get_class( $e ),
				'message' => esc_js( $e->getMessage() ),
			], 400 );
		}

		wp_send_json_success( [
			'id'       => $id,
			'nickname' => $applicant->get_nickname(),
		] );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		$post_id          = isset( $_GET['post'] ) ? filter_var( $_GET['post'], FILTER_SANITIZE_NUMBER_INT ) : 0;
		$interview_status = new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER );
		$interview_status->add_localization( 'wpApiSettings', [
			'nonce' => wp_create_nonce( 'wp_rest' ),
		] );
		$applicant = new ScriptAsset( 'lpf-applicant-manager-js', 'assets/js/applicant-manager', [ 'jquery' ] );
		$applicant->add_localization( 'applicantManager', [
			'cancel' => _x( 'Cancel', 'undo action to edit nickname when viewing an applicant', 'yikes-level-playing-field' ),
			'hide'   => _x( 'Hide Cover Letter', 'hide cover letter when viewing an applicant', 'yikes-level-playing-field' ),
			'ok'     => _x( 'OK', 'confirm action to edit nickname when viewing an applicant', 'yikes-level-playing-field' ),
			'nonce'  => wp_create_nonce( 'lpf_applicant_nonce' ),
			'title'  => _x( 'Applicants | Applicant ID', 'heading when viewing an applicant', 'yikes-level-playing-field' ),
			'view'   => _x( 'View Cover Letter', 'view cover letter when viewing an applicant', 'yikes-level-playing-field' ),
		] );

		return [
			$interview_status,
			$applicant,
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI, self::CSS_DEPENDENCIES ),
		];
	}

	/**
	 * Get the post types for this metabox..
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_post_types() {
		return [ ApplicantCPT::SLUG ];
	}

	/**
	 * Determine we're on the applicant screen.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	private function is_applicant_screen() {
		if ( ! is_admin() || ! function_exists( 'get_current_screen' || get_current_screen()->base !== 'post' ) ) {
			return false;
		}

		return ApplicantCPT::SLUG === get_current_screen()->post_type;
	}
}
