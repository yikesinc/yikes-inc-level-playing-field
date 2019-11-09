<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Freddie Mixell
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\REST;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use WP_Error;
use Yikes\LevelPlayingField\Model\ApplicantRepository;

/**
 *  Class InterviewAPI
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Freddie Mixell
 */
final class InterviewAPI extends RestAPI {

	use RestRestrict;

	/**
	 * Registering Interview API Routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		register_rest_route(
			APISettings::LPF_NAMESPACE,
			APISettings::INTERVIEW_STATUS_ROUTE . '/',
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_interview_status' ],
				'permission_callback' => [ $this, 'can_edit_applications' ],
				'args'                => [
					'id' => [
						'required'          => true,
						'validate_callback' => function ( $param ) {
							return is_numeric( $param );
						},
					],
				],
			]
		);
	}

	/**
	 * Get interview status object by Applicant ID.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request WordPress REST API Request Object.
	 *
	 * @return WP_REST_Response|WP_Error $response WordPress REST API Response Object.
	 */
	public function get_interview_status( WP_REST_Request $request ) {
		$response = new WP_REST_Response();

		$id = isset( $request['id'] ) ? absint( $request['id'] ) : 0;

		try {
			$applicant = ( new ApplicantRepository() )->find( $id );
		} catch ( \Exception $e ) {
			return new WP_Error( get_class( $e ), $e->getMessage() );
		}

		// Return Interview Status Object.
		$response->set_data( $applicant->get_interview_details() );

		return $response;
	}
}
