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
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Jeremy Pry
 */
final class BlockAsset extends ScriptAsset {

	const ENQUEUE_PRIORITY = 5;

	/**
	 * Get the enqueue action to use.
	 *
	 * @since 1.0.0
	 *
	 * @return string Enqueue action name.
	 */
	protected function get_enqueue_action() {
		return 'enqueue_block_editor_assets';
	}

	/**
	 * Get the enqueue closure to use.
	 *
	 * @since 1.0.0
	 *
	 * @return Closure
	 */
	protected function get_enqueue_closure() {
		return function () {
			call_user_func( parent::get_enqueue_closure() );
			wp_set_script_translations( $this->handle, 'yikes-level-playing-field' );
		};
	}
}
