<?php
/**
 * AlainSchlesser.com Speaking Page Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      https://www.alainschlesser.com/
 * @copyright 2017 Alain Schlesser
 */

namespace Yikes\LevelPlayingField;

/**
 * Interface Renderable.
 *
 * An object that can be `render()`ed.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface Renderable {

	/**
	 * Render the current Renderable.
	 *
	 * @since 0.1.0
	 *
	 * @param array $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 */
	public function render( array $context = array() );
}
