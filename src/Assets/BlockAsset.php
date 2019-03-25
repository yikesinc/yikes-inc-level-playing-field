<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Assets;

use Yikes\LevelPlayingField\Plugin;
use Closure;

/**
 * Class BlockAsset.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
final class BlockAsset extends ScriptAsset {

	/**
	 * Get the enqueue closure to use.
	 *
	 * @since %VERSION%
	 *
	 * @return Closure
	 */
	protected function get_enqueue_closure() {
		return function () {
			wp_enqueue_script( $this->handle );
			wp_set_script_translations( $this->handle, 'yikes-level-playing-field' );
		};
	}
}
