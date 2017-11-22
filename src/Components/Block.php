<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\Components;

use Yikes\LevelPlayingField\Model\Components\Fields\Field;

/**
 * Class Block
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Block {

	use Disabled;
	use Repeatable;

	/**
	 * Fields that are part of this block.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $fields = array();

	/**
	 * Add a field to the block.
	 *
	 * @since %VERSION%
	 *
	 * @param Field $field The field object to add.
	 */
	public function add_field( Field $field ) {
		$this->fields[ $field->get_key() ] = $field;
	}

	/**
	 * Get the fields for this block.
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}
}
