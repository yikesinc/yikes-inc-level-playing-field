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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface Types {

	const TEXT           = Text::class;
	const TEXTAREA       = Textarea::class;
	const EMAIL          = Email::class;
	const HIDDEN         = Hidden::class;
	const NUMBER         = Number::class;
	const PHONE          = Phone::class;
	const DATE           = Date::class;
	const CHECKBOX       = Checkbox::class;
	const SELECT         = Select::class;
	const ADDRESS        = Address::class;
	const POSTAL_CODE    = PostalCode::class;
	const YEAR           = Year::class;
	const SCHOOLING      = Schooling::class;
	const CERTIFICATIONS = Certifications::class;
	const EXPERIENCE     = Experience::class;
	const VOLUNTEER      = Volunteer::class;
	const SKILLS         = Skills::class;
	const LANGUAGES      = Languages::class;
	const WYSIWYG        = WYSIWYG::class;
}
