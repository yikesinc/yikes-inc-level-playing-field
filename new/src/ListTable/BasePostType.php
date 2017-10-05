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
		add_filter( "manage_{$this->post_type}_posts_columns", array( $this, 'columns' ) );
		add_action( "manage_{$this->post_type}_posts_custom_column", array( $this, 'column_content' ), 10, 2 );
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
	 * Get the post type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	abstract protected function get_post_type();
}
