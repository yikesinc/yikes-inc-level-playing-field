<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\View;

/**
 * Class NoOverrideLocationView
 *
 * This class works like TemplatedView, but does not allow overriding the
 * template file in a theme.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class NoOverrideLocationView extends TemplatedView {

	/**
	 * Get the possible locations for the view.
	 *
	 * @since %VERSION%
	 *
	 * @param string $uri URI of the view to get the locations for.
	 *
	 * @return array Array of possible locations.
	 */
	protected function get_locations( $uri ) {
		return [
			trailingslashit( dirname( __DIR__, 2 ) ) . $uri,
		];
	}
}
