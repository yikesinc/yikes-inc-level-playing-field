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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 *
 * @property string $post_type The post type.
 */
abstract class BasePostType implements Service {

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {

		// These actions will customize the columns in the list table.
		add_filter( "manage_{$this->post_type}_posts_columns", [ $this, 'columns' ] );
		add_action( "manage_{$this->post_type}_posts_custom_column", [ $this, 'column_content' ], 10, 2 );

		// This action will customize the available dropdowns for filtering.
		add_action( 'restrict_manage_posts', array( $this, 'custom_dropdowns' ), 10, 2 );
	}

	/**
	 * Allow getting class properties.
	 *
	 * @since %VERSION%
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
	 * Filter the available columns.
	 *
	 * @since %VERSION%
	 *
	 * @param array $original_columns The original array of columns.
	 *
	 * @return array The filtered array of columns.
	 */
	abstract public function columns( $original_columns );

	/**
	 * Output values for any custom columns.
	 *
	 * @since %VERSION%
	 *
	 * @param string $column_name The column slug.
	 * @param int    $post_id     The post ID.
	 */
	abstract public function column_content( $column_name, $post_id );

	/**
	 * Output custom dropdowns for filtering.
	 *
	 * @since %VERSION%
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
	 * Output custom dropdowns for filtering.
	 *
	 * @since %VERSION%
	 *
	 * @param string $which     The location of the extra table nav markup: 'top' or 'bottom' for WP_Posts_List_Table, 'bar' for WP_Media_List_Table.
	 */
	abstract protected function create_custom_dropdowns( $which );

	/**
	 * Get the post type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	abstract protected function get_post_type();
}
