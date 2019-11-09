<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\Exception\FailedToSavePost;
use Yikes\LevelPlayingField\Uninstallable;
use WP_Query;

/**
 * Abstract class CustomPostTypeRepository.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class CustomPostTypeRepository implements Uninstallable {

	/**
	 * Persist a modified entity to the storage.
	 *
	 * @since 1.0.0
	 *
	 * @param CustomPostTypeEntity $entity Entity instance to persist.
	 * @throws FailedToSavePost When there is a problem saving the post.
	 */
	public function persist( CustomPostTypeEntity $entity ) {
		$entity->persist();
	}

	/**
	 * Get the meta query for a particular Job ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $job_id The Job ID.
	 *
	 * @return array The meta query array.
	 */
	protected function get_job_meta_query( $job_id ) {
		return [
			'key'   => MetaLinks::JOB,
			'value' => $job_id,
		];
	}

	/**
	 * Get the meta query for a particular Application ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $application_id The Application ID.
	 *
	 * @return array The meta query array.
	 */
	protected function get_application_meta_query( $application_id ) {
		return [
			'key'   => MetaLinks::APPLICATION,
			'value' => $application_id,
		];
	}

	/**
	 * Get the meta query for viewed applicants.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_viewed_applicant_meta_query() {
		return [
			'key'     => ApplicantMeta::VIEWED,
			'value'   => 0,
			'compare' => '>=',
			'type'    => 'NUMERIC',
		];
	}

	/**
	 * Force delete all posts.
	 *
	 * @since 1.0.0
	 */
	public function uninstall() {
		$wp_query_args = [
			'post_type'      => $this->get_post_type(),
			'post_status'    => [ 'any' ],
			'posts_per_page' => -1,
			'fields'         => 'ids',
		];
		$posts         = new WP_Query( $wp_query_args );

		if ( ! empty( $posts->posts ) ) {
			foreach ( $posts->posts as $post ) {
				wp_delete_post( $post, true );
			}
		}
	}

	/**
	 * Get the post type slug to find.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract protected function get_post_type();
}
