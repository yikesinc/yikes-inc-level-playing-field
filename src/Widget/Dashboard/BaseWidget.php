<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Service;

/**
 * Abstract class BaseCustomPostType.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 *
 * @property string $slug The CPT slug.
 */
abstract class BaseWidget implements Service {

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'wp_dashboard_setup', array( $this, 'add_widget' ) );
	}
	/**
	 * Get the arguments that configure the custom post type.
	 *
	 * @since %VERSION%
	 *
	 * @return array Array of arguments.
	 */
	abstract public function add_widget();
}
