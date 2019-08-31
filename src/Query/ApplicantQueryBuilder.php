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

/**
 * Class ApplicantQuery
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class ApplicantQueryBuilder extends BaseQueryBuilder {

	use PostTypeApplicant;

	/**
	 * Filter the query by Job ID.
	 *
	 * @since %VERSION%
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
	 * @since %VERSION%
	 * @return $this
	 */
	public function where_applicant_viewed() {
		return $this->meta_query( AM::META_PREFIXES[ AM::VIEWED ], 0, '>=', 'NUMERIC' );
	}

	/**
	 * Filter the query by applicants who have not been viewed.
	 *
	 * @since %VERSION%
	 * @return $this
	 */
	public function where_applicant_not_viewed() {
		return $this->meta_not_exists( AM::META_PREFIXES[ AM::VIEWED ] );
	}
}
