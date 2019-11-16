<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Taxonomy;

use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Roles\Capabilities;

/**
 * Class JobCategory
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class JobCategory extends BaseTaxonomy {

	const SLUG = 'job_category';

	/**
	 * Get the arguments that configure the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_arguments() {
		return [
			'hierarchical'       => true,
			'public'             => true,
			'show_in_nav_menus'  => true,
			'show_ui'            => true,
			'show_admin_column'  => true,
			'show_in_quick_edit' => true,
			'query_var'          => true,
			'show_in_rest'       => true,
			'capabilities'       => [
				'manage_terms' => Capabilities::EDIT_JOBS,
				'edit_terms'   => Capabilities::EDIT_JOBS,
				'delete_terms' => Capabilities::EDIT_JOBS,
				'assign_terms' => Capabilities::EDIT_JOBS,
			],
			'labels'             => [
				'name'                       => __( 'Job Categories', 'level-playing-field' ),
				'singular_name'              => _x( 'Job Category', 'taxonomy general name', 'level-playing-field' ),
				'search_items'               => __( 'Search Job Categories', 'level-playing-field' ),
				'popular_items'              => __( 'Popular Job Categories', 'level-playing-field' ),
				'all_items'                  => __( 'All Job Categories', 'level-playing-field' ),
				'parent_item'                => __( 'Parent Job Category', 'level-playing-field' ),
				'parent_item_colon'          => __( 'Parent Job Category:', 'level-playing-field' ),
				'edit_item'                  => __( 'Edit Job Category', 'level-playing-field' ),
				'update_item'                => __( 'Update Job Category', 'level-playing-field' ),
				'add_new_item'               => __( 'New Job Category', 'level-playing-field' ),
				'new_item_name'              => __( 'New Job Category', 'level-playing-field' ),
				'separate_items_with_commas' => __( 'Separate Job Categories with commas', 'level-playing-field' ),
				'add_or_remove_items'        => __( 'Add or remove Job Categories', 'level-playing-field' ),
				'choose_from_most_used'      => __( 'Choose from the most used Job Categories', 'level-playing-field' ),
				'not_found'                  => __( 'No Job Categories found.', 'level-playing-field' ),
				'menu_name'                  => __( 'Job Categories', 'level-playing-field' ),
			],
		];
	}

	/**
	 * Get the object type(s) to use when registering the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_object_types() {
		return [
			JobManager::SLUG,
		];
	}
}
