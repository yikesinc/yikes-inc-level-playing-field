<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

/**
 * Class PostalCode
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class PostalCode extends BaseInput {

	const TYPE = 'text';

	/**
	 * Render any additional attributes.
	 *
	 * @since 1.0.0
	 */
	protected function render_extra_attributes() {
		parent::render_extra_attributes();
		echo 'autocomplete="postal-code" ';
	}
}
