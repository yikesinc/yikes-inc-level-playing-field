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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface ApplicationMeta {

	const META_PREFIX       = 'application_cpt_meta_';
	const FORM_FIELD_PREFIX = 'application_cpt_';

	// Basic Info fields.
	const NAME         = 'name';
	const EMAIL        = 'email';
	const PHONE        = 'phone';
	const ADDRESS      = 'address';
	const COVER_LETTER = 'cover_letter';

	// Extended fields.
	const EDUCATION      = 'education';
	const CERTIFICATIONS = 'certifications';
	const SKILLS         = 'skills';
	const LANGUAGES      = 'languages';
	const EXPERIENCE     = 'experience';
	const VOLUNTEER      = 'volunteer';
}
