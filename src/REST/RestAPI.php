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
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Roles\Capabilities;
use Yikes\LevelPlayingField\Model\ApplicantRepository;

/**
 * Class RestAPI
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Freddie Mixell
 */
final class RestAPI implements Service, AssetsAware {

	use AssetsAwareness;

	// API SETTINGS.
	const API_VERSION   = 1;
	const LPF_NAMESPACE = 'yikes-level-playing-field/v' . self::API_VERSION;

	// API ROUTES.
	const INTERVIEW_STATUS_ROUTE = '/interview-status/';

	/**
	 * Register the REST Registerables.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();

		add_action( 'rest_api_init', [ $this, 'register_routes' ] );
	}

	/**
	 * Define LPF rest api routes.
	 *
	 * @since %VERSION%
	 */
	public function register_routes() {
		// Interview Status Route Registration.
		register_rest_route(
			self::LPF_NAMESPACE,
			self::INTERVIEW_STATUS_ROUTE,
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
		// Register the rest of the routes here.
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
		// Initialize our response to modify for different responses.
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

		$response->set_data( $applicant->get_interview_object() );

		return $response;
	}

	/**
	 * Check if user can view routes.
	 *
	 * @since %VERSION%
	 */
	public function check_api_permissions() {
		return current_user_can( Capabilities::EDIT_APPLICANTS );
	}

}
