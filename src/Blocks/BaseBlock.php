<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Blocks;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Service;

/**
 * Class BaseBlock
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseBlock implements Service, AssetsAware {

	use AssetsAwareness;

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		add_action( 'init', function() {
			$this->register_assets();
			register_block_type( $this->get_block_slug(), $this->get_block_args() );
		} );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	abstract protected function get_assets();

	/**
	 * Get the slug for use with registering the block.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	abstract protected function get_block_slug();

	/**
	 * Get the arguments used when registering a block.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	abstract protected function get_block_args();
}
