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

/**
 * Abstract class CustomPostTypeRepository.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class CustomPostTypeRepository {

	/**
	 * Persist a modified entity to the storage.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
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
	 * @since %VERSION%
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
	 * @since %VERSION%
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
}
