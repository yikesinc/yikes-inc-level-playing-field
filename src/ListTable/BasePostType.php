<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\ListTable;

use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;

/**
 * Abstract class BasePostType
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 *
 * @property string $post_type The post type.
 */
abstract class BasePostType implements Service {

	/**
	 * Register the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {

		// These actions will customize the columns in the list table.
		add_filter( "manage_{$this->post_type}_posts_columns", [ $this, 'columns' ] );
		add_action( "manage_{$this->post_type}_posts_custom_column", [ $this, 'column_content' ], 10, 2 );

		// This action will customize the available dropdowns for filtering.
		add_action( 'restrict_manage_posts', [ $this, 'custom_dropdowns' ], 10, 2 );

		// This action will customize the main query vars.
		add_action( 'parse_query', [ $this, 'custom_query_vars' ] );

	}

	/**
	 * Allow getting class properties.
	 *
	 * @since 1.0.0
	 *
	 * @param string $name The property name to get.
	 *
	 * @return mixed The property value if available, or null.
	 */
	public function __get( $name ) {
		switch ( $name ) {
			case 'post_type':
				return $this->get_post_type();

			default:
				return null;
		}
	}

	/**
	 * Disable the months drop-down on this post type.
	 *
	 * @since 1.0.0
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
	 * Filter the available columns.
	 *
	 * @since 1.0.0
	 *
	 * @param array $original_columns The original array of columns.
	 *
	 * @return array The filtered array of columns.
	 */
	abstract public function columns( $original_columns );

	/**
	 * Output values for any custom columns.
	 *
	 * @since 1.0.0
	 *
	 * @param string $column_name The column slug.
	 * @param int    $post_id     The post ID.
	 */
	abstract public function column_content( $column_name, $post_id );

	/**
	 * Output custom dropdowns for filtering.
	 *
	 * @since 1.0.0
	 *
	 * @param string $post_type The post type.
	 * @param string $which     The location of the extra table nav markup: 'top' or 'bottom' for WP_Posts_List_Table, 'bar' for WP_Media_List_Table.
	 */
	public function custom_dropdowns( $post_type, $which ) {
		if ( $this->post_type !== $post_type ) {
			return;
		}

		$this->create_custom_dropdowns( $which );
	}

	/**
	 * Modifies current query variables.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Query $query Query object.
	 */
	public function custom_query_vars( $query ) {}

	/**
	 * Output custom dropdowns for filtering.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which     The location of the extra table nav markup: 'top' or 'bottom' for WP_Posts_List_Table, 'bar' for WP_Media_List_Table.
	 */
	abstract protected function create_custom_dropdowns( $which );

	/**
	 * Get the post type.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract protected function get_post_type();
}
