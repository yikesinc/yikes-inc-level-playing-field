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
	const DESCRIPTION                 = 'description';
	const TYPE                        = 'job_type';
	const LOCATION                    = 'location';
	const ADDRESS                     = 'address';
	const APPLICATION                 = 'application';
	const APPLICATION_SUCCESS_MESSAGE = 'application_success_message';

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

	// Meta prefixed fields.
	const META_PREFIXES = [
		self::DESCRIPTION                 => self::META_PREFIX . self::DESCRIPTION,
		self::TYPE                        => self::META_PREFIX . self::TYPE,
		self::LOCATION                    => self::META_PREFIX . self::LOCATION,
		self::ADDRESS                     => self::META_PREFIX . self::ADDRESS,
		self::APPLICATION                 => MetaLinks::APPLICATION,
		self::RESPONSIBILITIES            => self::META_PREFIX . self::RESPONSIBILITIES,
		self::SCHEDULE                    => self::META_PREFIX . self::SCHEDULE,
		self::REQUIREMENTS                => self::META_PREFIX . self::REQUIREMENTS,
		self::QUALIFICATIONS              => self::META_PREFIX . self::QUALIFICATIONS,
		self::EDUCATION                   => self::META_PREFIX . self::EDUCATION,
		self::EXPERIENCE                  => self::META_PREFIX . self::EXPERIENCE,
		self::KNOWLEDGE                   => self::META_PREFIX . self::KNOWLEDGE,
		self::APPLICATION_SUCCESS_MESSAGE => self::META_PREFIX . self::APPLICATION_SUCCESS_MESSAGE,
	];
}
