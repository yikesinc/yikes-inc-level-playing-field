<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Form;

use Yikes\LevelPlayingField\Field\Field;
use Yikes\LevelPlayingField\Field\Types;
use Yikes\LevelPlayingField\Model\ApplicantMeta as Meta;
use Yikes\LevelPlayingField\Model\ApplicationMeta;

/**
 * Class Factory
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Factory {

	/**
	 * Create an array of fields that can be rendered.
	 *
	 * @since %VERSION%
	 *
	 * @param array $fields        The active fields to render.
	 * @param array $field_classes The classes to apply to each field.
	 *
	 * @return Field[]
	 */
	public static function create( array $fields, array $field_classes ) {
		$return = [];
		foreach ( $fields as $field ) {
			$name        = str_replace( ApplicationMeta::META_PREFIX, '', $field );
			$field_name  = ApplicationMeta::FORM_FIELD_PREFIX . $name;
			$field_label = ucwords( str_replace( [ '-', '_' ], ' ', $name ) );
			$type        = isset( Meta::FIELD_MAP[ $name ] ) ? Meta::FIELD_MAP[ $name ] : Types::TEXT;
			$return[]    = new $type( $field_name, $field_label, $field_classes );
		}

		return $return;
	}
}
