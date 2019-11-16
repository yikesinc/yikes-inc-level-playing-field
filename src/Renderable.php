<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField;

/**
 * Interface Renderable.
 *
 * An object that can be `render()`ed.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
interface Renderable {

	/**
	 * Render the current Renderable.
	 *
	 * @since 1.0.0
	 *
	 * @param array $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 */
	public function render( array $context = [] );
}
