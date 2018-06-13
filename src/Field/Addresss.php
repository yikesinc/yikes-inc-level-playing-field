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
 * Class Addresss
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Addresss extends BaseField {

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		$classes    = array_merge( $this->classes, [ 'lpf-field-address' ] );
		$sub_fields = [
			new Text( "{$this->id}[address-1]", '', $classes ),
			new Text( "{$this->id}[address-2]", '', $classes, false ),
			new Text( "{$this->id}[city]", '', $classes ),
			new Text( "{$this->id}[state]", '', $classes ),
			new Text( "{$this->id}[province]", '', $classes ),
			new Text( "{$this->id}[country]", '', $classes ),
			new Number( "{$this->id}[zip]", '', $classes ),
		];

		/** @var Field $sub_field */
		foreach ( $sub_fields as $sub_field ) {
			$sub_field->render();
		}
	}
}
