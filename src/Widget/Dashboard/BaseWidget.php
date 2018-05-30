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
 * Abstract class BaseWidget.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Ebonie Butler
 *
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
	 * Add widget to dashboard.
	 *
	 * @since %VERSION%
	 */
	abstract public function add_widget();
}
