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
 * Class Phone
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Phone extends BaseInput {
	const TYPE         = 'tel';
	const SANITIZATION = FILTER_SANITIZE_NUMBER_INT;

	/**
	 * Render any additional attributes.
	 *
	 * @since %VERSION%
	 */
	protected function render_extra_attributes() {
		parent::render_extra_attributes();
		echo 'autocomplete="tel" ';
	}
}
