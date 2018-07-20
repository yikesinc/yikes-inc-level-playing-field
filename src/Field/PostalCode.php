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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class PostalCode extends BaseInput {

	const TYPE = 'text';

	/**
	 * Render any additional attributes.
	 *
	 * @since %VERSION%
	 */
	protected function render_extra_attributes() {
		parent::render_extra_attributes();
		echo 'autocomplete="postal-code" ';
	}
}
