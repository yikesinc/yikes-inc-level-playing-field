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
	const DESCRIPTION = 'description';
	const TYPE        = 'type';
	const LOCATION    = 'location';
	const ADDRESS     = 'address';
	const APPLICATION = MetaLinks::APPLICATION;

	// Responsibilities.
	const RESPONSIBILITIES = 'responsibilities';
	const SCHEDULE         = 'schedule';
	const REQUIREMENTS     = 'requirements';

	// Qualifications.
	const QUALIFICATIONS = 'qualifications';
	const EDUCATION      = 'education';
	const EXPERIENCE     = 'experience';
	const KNOWLEDGE      = 'knowledge';

	// Properties that should be JSON-encoded.
	const JSON_PROPERTIES = [
		self::META_PREFIX . self::ADDRESS => true,
	];
}
