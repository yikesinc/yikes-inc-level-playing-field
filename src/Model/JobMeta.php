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
 * Interface JobMeta
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface JobMeta {

	// Prefixes.
	const META_PREFIX       = 'job_cpt_meta_';
	const FORM_FIELD_PREFIX = 'job_cpt_';

	// General fields.
	const DESCRIPTION = self::META_PREFIX . 'description';
	const TYPE        = self::META_PREFIX . 'type';
	const LOCATION    = self::META_PREFIX . 'location';
	const ADDRESS     = self::META_PREFIX . 'address';

	// Responsibilities.
	const RESPONSIBILITIES = self::META_PREFIX . 'responsibilities';
	const SCHEDULE         = self::META_PREFIX . 'schedule';
	const REQUIREMENTS     = self::META_PREFIX . 'requirements';

	// Qualifications.
	const QUALIFICATIONS = self::META_PREFIX . 'qualifications';
	const EDUCATION      = self::META_PREFIX . 'education';
	const EXPERIENCE     = self::META_PREFIX . 'experience';
	const KNOWLEDGE      = self::META_PREFIX . 'knowledge';
}
