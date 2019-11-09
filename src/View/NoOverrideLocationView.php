<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\View;

use Yikes\LevelPlayingField\PluginHelper;

/**
 * Class NoOverrideLocationView
 *
 * This class works like TemplatedView, but does not allow overriding the
 * template file in a theme.
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class NoOverrideLocationView extends TemplatedView {

	use PluginHelper;

	/**
	 * Get the possible locations for the view.
	 *
	 * @since 1.0.0
	 *
	 * @param string $uri URI of the view to get the locations for.
	 *
	 * @return array Array of possible locations.
	 */
	protected function get_locations( $uri ) {
		return [
			trailingslashit( $this->get_root_dir() ) . $uri,
		];
	}
}
