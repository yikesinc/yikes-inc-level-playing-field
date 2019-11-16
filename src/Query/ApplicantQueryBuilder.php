<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Query;

use Yikes\LevelPlayingField\Model\ApplicantMeta as AM;
use Yikes\LevelPlayingField\Model\MetaLinks;
use Yikes\LevelPlayingField\Model\PostTypeApplicant;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

/**
 * Class ApplicantQuery
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class ApplicantQueryBuilder extends BaseQueryBuilder {

	use PostTypeApplicant;

	/**
	 * Filter the query by Job ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The Job ID.
	 *
	 * @return $this
	 */
	public function where_job_id( $id ) {
		return $this->meta_query( MetaLinks::JOB, $id );
	}

	/**
	 * Filter the query by applicants who have been viewed.
	 *
	 * @since 1.0.0
	 * @return $this
	 */
	public function where_applicant_viewed() {
		return $this->meta_query( AM::META_PREFIXES[ AM::VIEWED ], 0, '>=', 'NUMERIC' );
	}

	/**
	 * Filter the query by applicants who have been viewed by a particular user.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The user ID who viewed the applicant.
	 *
	 * @return $this
	 */
	public function where_applicant_viewed_by( $id ) {
		return $this->meta_query( AM::META_PREFIXES[ AM::VIEWED ], $id );
	}

	/**
	 * Filter the query by applicants who have not been viewed.
	 *
	 * @since 1.0.0
	 * @return $this
	 */
	public function where_applicant_not_viewed() {
		return $this->meta_not_exists( AM::META_PREFIXES[ AM::VIEWED ] );
	}

	/**
	 * Filter the query by applicants with a particular status.
	 *
	 * @since 1.0.0
	 *
	 * @param string $status The status to filter by.
	 *
	 * @return $this
	 */
	public function where_applicant_status( $status ) {
		return $this->tax_query( ApplicantStatus::SLUG, $status, 'slug' );
	}
}
