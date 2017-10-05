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
		add_filter( 'disable_months_dropdown', array( $this, 'months_dropdown' ), 10, 2 );
		add_action( 'restrict_manage_posts', array( $this, 'dropdown_filters' ), 10, 2 );
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
		$columns      = array(
			'cb'                             => $original_columns['cb'],
			'title'                          => _x( 'Job Title', 'column heading' ),
			"taxonomy-{$category_tax->name}" => $category_tax->label,
			"taxonomy-{$status_tax->name}"   => $status_tax->label,
			'applications'                   => _x( 'Applications', 'column heading', 'yikes-level-playing-field' ),
			'date'                           => $original_columns['date'],
		);

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

		// Do application stuff.
	}

	/**
	 * Disable the months drop-down on this post type.
	 *
	 * @since %VERSION%
	 *
	 * @param bool   $disable   Whether to disable the dropdown.
	 * @param string $post_type The post type.
	 *
	 * @return bool
	 */
	public function months_dropdown( $disable, $post_type ) {
		if ( $this->post_type !== $post_type ) {
			return $disable;
		}

		return true;
	}

	/**
	 * Display a dropdown filter for this category.
	 *
	 * @since %VERSION%
	 *
	 * @param string $post_type The post type being displayed.
	 * @param string $which     Where the action is firing. Will be 'top' or 'bottom'.
	 */
	public function dropdown_filters( $post_type, $which ) {
		if ( $this->post_type !== $post_type || 'top' !== $which ) {
			return;
		}

		if ( ! is_object_in_taxonomy( $post_type, JobCategory::SLUG ) ) {
			return;
		}

		$taxonomy         = get_taxonomy( JobCategory::SLUG );
		$dropdown_options = array(
			'show_option_all' => $taxonomy->labels->all_items,
			'hide_empty'      => false,
			'hierarchical'    => $taxonomy->hierarchical,
			'show_count'      => false,
			'orderby'         => 'name',
			'selected'        => get_query_var( JobCategory::SLUG ),
			'name'            => JobCategory::SLUG,
			'taxonomy'        => JobCategory::SLUG,
			'value_field'     => 'slug',
		);

		printf(
			'<label class="screen-reader-text" for="%1$s">%2$s</label>',
			esc_attr( JobCategory::SLUG ),
			esc_html( $taxonomy->labels->filter_items_list )
		);

		wp_dropdown_categories( $dropdown_options );
	}
}
