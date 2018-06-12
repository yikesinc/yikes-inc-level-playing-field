<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\ListTable;

use Yikes\LevelPlayingField\CustomPostType\JobManager as JobManagerCPT;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;

/**
 * Class JobManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class JobManager extends BasePostType {

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();

		// Additional functionality for this object.
		add_filter( 'disable_months_dropdown', [ $this, 'months_dropdown' ], 10, 2 );
		add_action( 'restrict_manage_posts', [ $this, 'job_category_dropdown_filter' ], 10, 2 );
	}

	/**
	 * Get the post type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_post_type() {
		return JobManagerCPT::SLUG;
	}

	/**
	 * Adjust the columns to display for the list table.
	 *
	 * @since %VERSION%
	 *
	 * @param array $original_columns The original columns.
	 *
	 * @return array
	 */
	public function columns( $original_columns ) {
		$category_tax = get_taxonomy( JobCategory::SLUG );
		$status_tax   = get_taxonomy( JobStatus::SLUG );
		$columns      = [
			'cb'                             => $original_columns['cb'],
			'title'                          => _x( 'Job Title', 'column heading', 'yikes-level-playing-field' ),
			"taxonomy-{$category_tax->name}" => $category_tax->label,
			"taxonomy-{$status_tax->name}"   => $status_tax->label,
			'applications'                   => _x( 'Applications', 'column heading', 'yikes-level-playing-field' ),
			'date'                           => $original_columns['date'],
		];

		return $columns;
	}

	/**
	 * Output the values for our custom columns.
	 *
	 * @since %VERSION%
	 *
	 * @param string $column_name The column slug.
	 * @param int    $post_id     The post ID.
	 */
	public function column_content( $column_name, $post_id ) {
		// Only need to handle the Applications column.
		if ( 'applications' !== $column_name ) {
			return;
		}

		static $applicant_repo = null;
		if ( null === $applicant_repo ) {
			$applicant_repo = new ApplicantRepository();
		}

		echo esc_html( $applicant_repo->get_applicant_count_for_job( $post_id ) );
	}
}
