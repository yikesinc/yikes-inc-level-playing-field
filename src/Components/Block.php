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
class Block implements Component {

	use Disabled;
	use Repeatable;

	/**
	 * The key
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $key;

	/**
	 * Fields that are part of this block.
	 *
	 * @since %VERSION%
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Block constructor.
	 *
	 * @param string $key    The key to use for saving this block.
	 * @param array  $fields Array of fields to register with the block.
	 */
	public function __construct( $key, array $fields = [] ) {
		$this->key = $key;

		/** @var Field $field */
		foreach ( $fields as $field ) {
			$this->add_field( $field );
		}
	}

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

	/**
	 * Get the key for this component.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}
}
