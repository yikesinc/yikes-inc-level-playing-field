<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\Components\Fields;

/**
 * Interface FormField
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface FormField extends Field {

	/**
	 * Render the field for a form.
	 *
	 * @since %VERSION%
	 * @return mixed
	 */
	public function render_form();
}
