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
 * Class Field
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Field {

	/**
	 * Get the key for the field.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_key();

	/**
	 * Get the field type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_type();

	/**
	 * Determine if the field is anonymous.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_anonymous();

	/**
	 * Determine if the field is required.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_required();
}
