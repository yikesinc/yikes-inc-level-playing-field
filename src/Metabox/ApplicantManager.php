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
use Yikes\LevelPlayingField\Exception\Exception;
use Yikes\LevelPlayingField\Messaging\ApplicantMessaging;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\View\View;

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

	// CSS.
	const CSS_HANDLE = 'lpf-admin-applicant-css';
	const CSS_URI    = 'assets/css/lpf-applicant-admin';

	// Applicant Partials.
	const APPLICANT_BASIC_INFO            = 'views/applicant-basic-info';
	const APPLICANT_INTERVIEW_DETAILS     = 'views/applicant-interview-details';
	const APPLICANT_DETAILS               = 'views/applicant-details.php';
	const APPLICANT_SKILLS_QUALIFICATIONS = 'views/applicant-skills-qualifications.php';

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();
		$this->register_assets();

		add_action( 'in_admin_header', function() {
			$this->set_screen_columns();
		} );

		add_action( 'admin_enqueue_scripts', function() {
			if ( ! $this->is_applicant_screen() ) {
				return;
			}

			$this->enqueue_assets();
		} );

		add_action( "add_meta_boxes_{$this->get_post_type()}", function() {
			$this->meta_boxes();
		} );

		add_action( 'wp_ajax_save_nickname', function() {
			$this->save_nickname();
		} );

		add_action( 'lpf_applicant_screen_sidebar', function( View $view ) {
			echo $view->render_partial( static::APPLICANT_BASIC_INFO ); // phpcs:ignore WordPress.Security.EscapeOutput
		}, 20 );

		add_action( 'lpf_applicant_screen_sidebar', function( View $view ) {
			echo $view->render_partial( static::APPLICANT_INTERVIEW_DETAILS ); // phpcs:ignore WordPress.Security.EscapeOutput
		}, 30 );

		add_action( 'lpf_applicant_screen_section_one', function( View $view ) {
			echo $view->render_partial( static::APPLICANT_DETAILS ); // phpcs:ignore WordPress.Security.EscapeOutput
		}, 10 );

		add_action( 'lpf_applicant_screen_section_one', function( View $view ) {
			echo $view->render_partial( static::APPLICANT_SKILLS_QUALIFICATIONS ); // phpcs:ignore WordPress.Security.EscapeOutput
		}, 20 );

		add_action( 'lpf_applicant_screen_section_two', function( View $view ) {
			$context = ( new ApplicantMessaging() )->get_context_data( $view->applicant->get_id(), true );
			echo $view->render_partial( ApplicantMessaging::VIEW, $context );  // phpcs:ignore WordPress.Security.EscapeOutput
		}, 10 );
	}

	/**
	 * Register our meta boxes, and remove some default boxes.
	 *
	 * @since %VERSION%
	 */
	private function meta_boxes() {
		// Remove some of the core boxes.
		remove_meta_box( 'submitdiv', $this->get_post_type(), 'side' );
		remove_meta_box( 'slugdiv', $this->get_post_type(), 'normal' );
		remove_meta_box( 'authordiv', $this->get_post_type(), 'normal' );
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
	 * Get the priority within the context where the boxes should show.
	 *
	 * @since %VERSION%
	 *
	 * @param Applicant $applicant The applicant object.
	 */
	protected function update_viewed_by( $applicant ) {
		$applicant->set_viewed_by( get_current_user_id() );
		$applicant->persist();
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
		$this->update_viewed_by( $applicant );
		return [
			'applicant' => $applicant,
			'job'       => $job,
		];
	}

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
	 * Get the screen on which to show the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string|array|\WP_Screen Screen on which to show the metabox.
	 */
	protected function get_screen() {
		return $this->get_post_type();
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
	 * Set the number of screen columns to 1.
	 *
	 * @since %VERSION%
	 */
	private function set_screen_columns() {
		if ( ! $this->is_applicant_screen() ) {
			return;
		}

		add_screen_option( 'layout_columns', [
			'default' => 1,
			'max'     => 1,
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
			$applicant,
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
		];
	}

	/**
	 * Get the post type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	private function get_post_type() {
		return ApplicantCPT::SLUG;
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

		return $this->get_post_type() === get_current_screen()->post_type;
	}

	/**
	 * Run content through the functions that WordPress' uses in `the_content` filter.
	 *
	 * `the_content` filter is needed to format WYSIWYG content. However, a lot of themes hijack this filter.
	 * This function provides the same basic formatting functionality while avoiding the pitfalls of the filter.
	 *
	 * @param string $content Content.
	 *
	 * @return string $content Content.
	 */
	private function the_content_filter( $content ) {
		$content = function_exists( 'capital_P_dangit' ) ? capital_P_dangit( $content ) : $content;
		$content = function_exists( 'wptexturize' ) ? wptexturize( $content ) : $content;
		$content = function_exists( 'convert_smilies' ) ? convert_smilies( $content ) : $content;
		$content = function_exists( 'wpautop' ) ? wpautop( $content ) : $content;
		$content = function_exists( 'shortcode_unautop' ) ? shortcode_unautop( $content ) : $content;
		$content = function_exists( 'prepend_attachment' ) ? prepend_attachment( $content ) : $content;
		$content = function_exists( 'wp_make_content_images_responsive' ) ? wp_make_content_images_responsive( $content ) : $content;
		$content = function_exists( 'do_shortcode' ) ? do_shortcode( $content ) : $content;

		if ( class_exists( 'WP_Embed' ) ) {

			// Deal with URLs.
			$embed   = new \WP_Embed();
			$content = method_exists( $embed, 'autoembed' ) ? $embed->autoembed( $content ) : $content;
		}
		return $content;
	}
}
