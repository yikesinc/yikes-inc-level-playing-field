<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\Service;

/**
 * Abstract class AwesomeBaseMetabox.
 *
 * Base class for building metaboxes with the Awesome framework.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField\Metabox
 */
abstract class AwesomeBaseMetabox implements Service {

	/**
	 * Register hooks.
	 *
	 * @since  1.0.0
	 * @author Jeremy Pry
	 */
	public function register() {
		add_filter( 'yks_mboxes', [ $this, 'register_boxes' ] );
	}

	/**
	 * Register meta boxes.
	 *
	 * @since  1.0.0
	 * @author Jeremy Pry
	 *
	 * @param array $meta_boxes Array of existing meta boxes.
	 *
	 * @return array The filtered meta boxes.
	 */
	abstract public function register_boxes( $meta_boxes );
}
