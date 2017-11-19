<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\ListTable;

use Yikes\LevelPlayingField\CustomPostType\ApplicationManager as ApplicationManagerCPT;
use Yikes\LevelPlayingField\Model\ApplicantRepository;

/**
 * Class ApplicationManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicationManager extends BasePostType {

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();

		add_filter( 'disable_months_dropdown', [ $this, 'months_dropdown' ], 10, 2 );
		add_action( 'restrict_manage_posts', [ $this, 'job_category_dropdown_filter' ], 10, 2 );
		add_filter( 'request', [ $this, 'filter_request' ] );
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
		$columns = [
			'cb'         => $original_columns['cb'],
			'title'      => _x( 'Application Name', 'column heading' ),
			'jobs'       => _x( 'Jobs', 'column heading' ),
			'applicants' => _x( 'Applicants', 'column heading' ),
			'date'       => $original_columns['date'],
		];

		return $columns;
	}

	/**
	 * Output values for any custom columns.
	 *
	 * @since %VERSION%
	 *
	 * @param string $column_name The column slug.
	 * @param int    $post_id     The post ID.
	 */
	public function column_content( $column_name, $post_id ) {
		static $columns = [
			'jobs'       => 1,
			'applicants' => 1,
		];

		if ( ! isset( $columns[ $column_name ] ) ) {
			return;
		}

		static $applicant_repo = null;
		if ( null === $applicant_repo ) {
			$applicant_repo = new ApplicantRepository();
		}

		// TODO: Implement column_content() method.
		echo 'placeholder';
	}

	/**
	 * Filter the query vars for the request.
	 *
	 * @since %VERSION%
	 *
	 * @param array $query_vars The query vars for the request.
	 *
	 * @return array
	 */
	public function filter_request( $query_vars ) {
		// Ensure this is the screen we want.
		$screen = get_current_screen();
		if ( ! ( $screen instanceof \WP_Screen ) ) {
			return $query_vars;
		}

		if ( 'edit' !== $screen->base || $this->post_type !== $screen->post_type ) {
			return $query_vars;
		}

		// Set query vars only if they're not already set.
		$query_vars['orderby'] = isset( $query_vars['orderby'] ) ? $query_vars['orderby'] : 'title';
		$query_vars['order']   = isset( $query_vars['order'] ) ? $query_vars['order'] : 'ASC';

		return $query_vars;
	}

	/**
	 * Get the post type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_post_type() {
		return ApplicationManagerCPT::SLUG;
	}
}
