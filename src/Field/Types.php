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
 * Interface Types
 *
 * These are the available field types and their class mappings.
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Types {

	const TEXT        = Text::class;
	const TEXTAREA    = Textarea::class;
	const EMAIL       = Email::class;
	const HIDDEN      = Hidden::class;
	const NUMBER      = Number::class;
	const PHONE       = Phone::class;
	const ADDRESS     = Address::class;
	const POSTAL_CODE = PostalCode::class;
	const YEAR        = Year::class;
	const EDUCATION   = Education::class;
}
