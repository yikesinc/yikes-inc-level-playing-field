<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    JP, KU, EB, TL
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\TemplateController;

use Yikes\LevelPlayingField\Messaging\MessagingAssets;
use Yikes\LevelPlayingField\RequiredPages\ApplicantMessagingPage;
use Yikes\LevelPlayingField\Messaging\ApplicantMessaging;
use Yikes\LevelPlayingField\Model\Applicant;

/**
 * Class ApplicantMessagingTemplateController.
 *
 * A class to control which template file is used to display the applicant messaging page.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  JP, KU, EB, TL
 */
class ApplicantMessagingTemplateController extends TemplateController {

	use MessagingAssets;

	const PRIORITY = 10;
	const VIEW_URI = ApplicantMessaging::VIEW;

	/**
	 * Register the current Registerable.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		parent::register();
		$this->register_assets();
	}

	/**
	 * Check if the current request is for this class' object and supply the current post w/ content.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $content The default template file WordPress is handing us.
	 *
	 * @return string The text to be used for the menu.
	 */
	public function set_content( $content ) {
		if ( $this->is_template_request() ) {
			$content = $this->render( $this->get_context( $this->get_context_data() ) );
		}

		return $content;
	}

	/**
	 * Custom logic to determine if the current request should be displayed with your template.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if the current request should use your template.
	 */
	protected function is_template_request() {
		$applicant_id        = $this->get_applicant_post_id();
		$applicant_id_exists = $applicant_id > 0;
		$is_messaging_page   = ( new ApplicantMessagingPage() )->get_page_id( ApplicantMessagingPage::PAGE_SLUG ) === get_queried_object_id();
		$is_allowed_to_view  = $this->verify_url_hash( $applicant_id ) || is_user_logged_in() && current_user_can( 'lpf_message_applicants' );
		return in_the_loop() && $applicant_id_exists && $is_messaging_page && $is_allowed_to_view;
	}

	/**
	 * Retrieve the applicant ID based on parameters in the URL.
	 *
	 * @since 1.0.0
	 *
	 * @return int $post_id ID of the applicant object.
	 */
	protected function get_applicant_post_id() {
		return isset( $_GET['post'] ) ? filter_var( wp_unslash( $_GET['post'] ), FILTER_SANITIZE_NUMBER_INT ) : 0;
	}

	/**
	 * Verify that the value in the URL is valid.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $applicant_id The applicant's post ID.
	 *
	 * @return bool True if the URL has a valid value.
	 */
	protected function verify_url_hash( $applicant_id ) {
		return isset( $_GET['guid'] ) && ( new Applicant( get_post( $applicant_id ) ) )->get_guid() === filter_var( wp_unslash( $_GET['guid'] ), FILTER_SANITIZE_STRING );
	}

	/**
	 * Check if this is an interview cancellation request.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if this is a cancellation request.
	 */
	protected function is_interview_cancellation() {
		return isset( $_GET['cancel'] ) && filter_var( wp_unslash( $_GET['cancel'] ), FILTER_SANITIZE_NUMBER_INT ) === '1';
	}

	/**
	 * Check if this is an interview confirmation request.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True if this is a confirmation request.
	 */
	protected function is_interview_confirmation() {
		return isset( $_GET['confirm'] ) && filter_var( wp_unslash( $_GET['confirm'] ), FILTER_SANITIZE_NUMBER_INT ) === '1';
	}

	/**
	 * Get the data needed for this context. In this case, the page ID.
	 *
	 * @since 1.0.0
	 *
	 * @return int The page ID.
	 */
	protected function get_context_data() {
		return get_queried_object_id();
	}

	/**
	 * Get the data to pass onto the view.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $id The Page ID.
	 *
	 * @return array Context to pass onto view.
	 */
	protected function get_context( $id ) {
		$context               = ( new ApplicantMessaging() )->get_context_data( $this->get_applicant_post_id(), false );
		$context['is_cancel']  = $this->is_interview_cancellation();
		$context['is_confirm'] = $this->is_interview_confirmation();
		return $context;
	}
}
