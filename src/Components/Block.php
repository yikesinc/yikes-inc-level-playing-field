<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\Components;

class Block {

	use Repeatable;

	protected $fields = array();

	public function add_field( Field $field ) {
		$this->fields[ $field->get_key() ] = $field;
	}

	/**
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}
}
