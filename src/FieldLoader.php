<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Carbon_Fields\Carbon_Fields;

/**
 * Class FieldLoader
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class FieldLoader implements Service {

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'plugins_loaded', function () {
			Carbon_Fields::boot();
		} );
	}
}
