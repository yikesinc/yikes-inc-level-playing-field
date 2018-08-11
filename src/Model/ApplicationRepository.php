<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\ApplicationManager as ApplicationManagerCPT;
use Yikes\LevelPlayingField\Exception\InvalidPostID;

/**
 * Class ApplicationRepository
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicationRepository extends CustomPostTypeRepository {

	/**
	 * Find the Application with a given post ID.
	 *
	 * @since %VERSION%
	 *
	 * @param int $id Post ID to retrieve.
	 *
	 * @return Application
	 * @throws InvalidPostID If the post for the requested ID was not found.
	 */
	public function find( $id ) {
		$post = get_post( $id );
		if ( null === $post ) {
			throw InvalidPostID::from_id( $id );
		}

		return new Application( $post );
	}

	/**
	 * Find all the published Applications.
	 *
	 * @since %VERSION%
	 *
	 * @return Application[]
	 */
	public function find_all() {
		$args  = [
			'post_type'   => ApplicationManagerCPT::SLUG,
			'post_status' => [ 'publish' ],
		];
		$query = new \WP_Query( $args );

		$applications = [];
		foreach ( $query->posts as $post ) {
			$applications[ $post->ID ] = new Application( $post );
		}

		return $applications;
	}
}
