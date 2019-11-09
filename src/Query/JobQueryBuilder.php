<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Query;

use Yikes\LevelPlayingField\Model\PostTypeJob;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Class JobQuery
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class JobQueryBuilder extends BaseQueryBuilder {

	use PostTypeJob;

	/**
	 * BaseQuery constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->post_status( [ 'published' ] );
	}

	/**
	 * Filter by Jobs with active status.
	 *
	 * @since 1.0.0
	 * @return $this
	 */
	public function where_job_active() {
		return $this->tax_query( JobStatus::SLUG, 'active', 'slug' );
	}

	/**
	 * Exclude Jobs with certain categories from results.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $ids Category IDs to exclude. A string should be comma-separated.
	 *
	 * @return $this
	 */
	public function exclude_category_ids( $ids ) {
		$ids = array_filter( is_array( $ids ) ? $ids : array_map( 'trim', explode( ',', $ids ) ) );
		if ( empty( $ids ) ) {
			return $this;
		}

		return $this->tax_query( JobCategory::SLUG, $ids, 'term_id', 'NOT IN' );
	}
}
