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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class JobCategory extends BaseTaxonomy {

	const SLUG               = 'job_category';
	const SHOW_IN_QUICK_EDIT = true;

	/**
	 * Get the arguments that configure the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_arguments() {
		return array(
			'hierarchical'          => true,
			'public'                => true,
			'show_in_nav_menus'     => true,
			'show_ui'               => true,
			'show_admin_column'     => true,
			'query_var'             => true,
			'rewrite'               => true,
			'capabilities'          => array(
				'manage_terms' => Capabilities::EDIT_JOBS,
				'edit_terms'   => Capabilities::EDIT_JOBS,
				'delete_terms' => Capabilities::EDIT_JOBS,
				'assign_terms' => Capabilities::EDIT_JOBS,
			),
			'labels'                => array(
				'name'                       => __( 'Job Categories', 'yikes-level-playing-field' ),
				'singular_name'              => _x( 'Job Category', 'taxonomy general name', 'yikes-level-playing-field' ),
				'search_items'               => __( 'Search Job Categories', 'yikes-level-playing-field' ),
				'popular_items'              => __( 'Popular Job Categories', 'yikes-level-playing-field' ),
				'all_items'                  => __( 'All Job Categories', 'yikes-level-playing-field' ),
				'parent_item'                => __( 'Parent Job Category', 'yikes-level-playing-field' ),
				'parent_item_colon'          => __( 'Parent Job Category:', 'yikes-level-playing-field' ),
				'edit_item'                  => __( 'Edit Job Category', 'yikes-level-playing-field' ),
				'update_item'                => __( 'Update Job Category', 'yikes-level-playing-field' ),
				'add_new_item'               => __( 'New Job Category', 'yikes-level-playing-field' ),
				'new_item_name'              => __( 'New Job Category', 'yikes-level-playing-field' ),
				'separate_items_with_commas' => __( 'Separate Job Categories with commas', 'yikes-level-playing-field' ),
				'add_or_remove_items'        => __( 'Add or remove Job Categories', 'yikes-level-playing-field' ),
				'choose_from_most_used'      => __( 'Choose from the most used Job Categories', 'yikes-level-playing-field' ),
				'not_found'                  => __( 'No Job Categories found.', 'yikes-level-playing-field' ),
				'menu_name'                  => __( 'Job Categories', 'yikes-level-playing-field' ),
				'filter_items_list'          => __( 'Filter Job Categories', 'yikes-level-playing-field' ),
			),
			'show_in_rest'          => true,
			'rest_base'             => self::SLUG,
			'rest_controller_class' => 'WP_REST_Terms_Controller',
		);
	}

	/**
	 * Get the object type(s) to use when registering the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_object_types() {
		return array(
			JobManager::SLUG,
		);
	}
}
