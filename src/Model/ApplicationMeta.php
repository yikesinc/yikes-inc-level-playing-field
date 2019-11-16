<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
 * Interface ApplicationMeta
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface ApplicationMeta {

	const META_PREFIX       = 'application_cpt_meta_';
	const FORM_FIELD_PREFIX = 'application_cpt_';
	const REQUIRED_SUFFIX   = '_required';

	// Basic Info fields.
	const NAME         = 'name';
	const EMAIL        = 'email';
	const PHONE        = 'phone';
	const ADDRESS      = 'address';
	const COVER_LETTER = 'cover_letter';

	// Extended fields.
	const SCHOOLING      = 'schooling';
	const EDUCATION      = 'education';
	const CERTIFICATIONS = 'certifications';
	const SKILLS         = 'skills';
	const LANGUAGES      = 'languages';
	const EXPERIENCE     = 'experience';
	const VOLUNTEER      = 'volunteer';

	// Required field.
	const REQUIRED = 'required';
}
