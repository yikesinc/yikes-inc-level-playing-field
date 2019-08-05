<?php

namespace Yikes\LevelPlayingField\REST;

use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\REST\BaseRestAPI;
use Yikes\LevelPlayingField\REST\APISettings;

final class InterviewAPI extends BaseRestAPI {

    function register_routes() {
        register_rest_route(
			APISettings::LPF_NAMESPACE,
			APISettings::INTERVIEW_STATUS_ROUTE,
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_interview_status' ],
				'permission_callback' => [ $this, 'check_api_permissions' ],
				'args'                => [
					'id' => [
						'required'          => true,
						'validate_callback' => function ( $param, $request, $key ) {
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
	 * @since %VERSION%
	 *
	 * @param WP_REST_Request $request WordPress REST API Request Object.
	 *
	 * @return WP_REST_Response $response WordPress REST API Response Object.
	 */
	public function get_interview_status( WP_REST_Request $request ) {
		$response = new WP_REST_Response();

		$id = isset( $request['id'] ) ? absint( wp_unslash( $request['id'] ) ) : 0;

		// If the users not found return an error.
		if ( 0 === $id ) {
			$response->set_data( [
				'message' => __( 'User Not Found.', 'yikes-level-playing-field' ),
			] );

			// Set 400 status code.
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

        // Return Interview Status Object.
		$response->set_data( $applicant->get_interview_object() );

		return $response;
	}

}