<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\Components\Fields;

use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Model\Components\Disabled;
use Yikes\LevelPlayingField\Model\Components\MaybeRepeatable;
use Yikes\LevelPlayingField\Model\Components\Repeatable;

/**
 * Abstract class BaseField.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseField implements Field, MaybeRepeatable {

	use Disabled;
	use Repeatable;

	const TYPE = '_basefield_';

	/**
	 * Whether the field is anonymous.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $anonymous;

	/**
	 * The key for saving the field.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $key;

	/**
	 * Whether the field is a required field.
	 *
	 * @since %VERSION%
	 * @var bool
	 */
	protected $required;

	/**
	 * Get the field type.
	 *
	 * @since %VERSION%
	 * @return string The field type.
	 * @throws MustExtend When the default type has not been extended.
	 */
	public function get_type() {
		if ( self::TYPE === static::TYPE ) {
			throw MustExtend::default_type( self::TYPE );
		}

		return static::TYPE;
	}

	/**
	 * Get the key for the field.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_key() {
		return $this->key;
	}

	/**
	 * Determine if the field is anonymous.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_anonymous() {
		return $this->anonymous;
	}

	/**
	 * Determine if the field is required.
	 *
	 * @since %VERSION%
	 * @return bool
	 */
	public function is_required() {
		return $this->required;
	}
}
