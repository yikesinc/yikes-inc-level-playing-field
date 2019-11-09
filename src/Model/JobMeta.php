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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface JobMeta {

	// Prefixes.
	const META_PREFIX       = 'job_cpt_meta_';
	const FORM_FIELD_PREFIX = 'job_cpt_';

	// General fields.
	const TYPE                        = 'job_type';
	const LOCATION                    = 'location';
	const ADDRESS                     = 'address';
	const APPLICATION                 = 'application';
	const APPLICATION_SUCCESS_MESSAGE = 'application_success_message';
	const APPLICATION_PAGE            = 'application_page';

	// Responsibilities.
	const RESPONSIBILITIES = 'responsibilities';
	const SCHEDULE         = 'schedule';
	const REQUIREMENTS     = 'requirements';

	// Qualifications.
	const QUALIFICATIONS = 'qualifications';
	const EDUCATION      = 'education';
	const EXPERIENCE     = 'experience';
	const SKILLS         = 'skills';

	// Compensation.
	const COMPENSATION = 'compensation';

	// Company Details.
	const NAME     = 'company_name';
	const DESC     = 'company_desc';
	const LOGO     = 'company_logo';
	const WEBSITE  = 'company_website';
	const TWITTER  = 'company_twitter';
	const FACEBOOK = 'company_facebook';
	const LINKEDIN = 'company_linkedin';

	// Properties that should be JSON-encoded.
	const JSON_PROPERTIES = [
		self::META_PREFIX . self::ADDRESS => true,
		self::META_PREFIX . self::LOGO    => true,
	];

	// Meta prefixed fields.
	const META_PREFIXES = [
		self::TYPE                        => self::META_PREFIX . self::TYPE,
		self::LOCATION                    => self::META_PREFIX . self::LOCATION,
		self::ADDRESS                     => self::META_PREFIX . self::ADDRESS,
		self::APPLICATION                 => MetaLinks::APPLICATION,
		self::APPLICATION_SUCCESS_MESSAGE => self::META_PREFIX . self::APPLICATION_SUCCESS_MESSAGE,
		self::APPLICATION_PAGE            => self::META_PREFIX . self::APPLICATION_PAGE,
		self::RESPONSIBILITIES            => self::META_PREFIX . self::RESPONSIBILITIES,
		self::SCHEDULE                    => self::META_PREFIX . self::SCHEDULE,
		self::REQUIREMENTS                => self::META_PREFIX . self::REQUIREMENTS,
		self::QUALIFICATIONS              => self::META_PREFIX . self::QUALIFICATIONS,
		self::EDUCATION                   => self::META_PREFIX . self::EDUCATION,
		self::EXPERIENCE                  => self::META_PREFIX . self::EXPERIENCE,
		self::SKILLS                      => self::META_PREFIX . self::SKILLS,
		self::COMPENSATION                => self::META_PREFIX . self::COMPENSATION,
		self::NAME                        => self::META_PREFIX . self::NAME,
		self::DESC                        => self::META_PREFIX . self::DESC,
		self::LOGO                        => self::META_PREFIX . self::LOGO,
		self::WEBSITE                     => self::META_PREFIX . self::WEBSITE,
		self::TWITTER                     => self::META_PREFIX . self::TWITTER,
		self::FACEBOOK                    => self::META_PREFIX . self::FACEBOOK,
		self::LINKEDIN                    => self::META_PREFIX . self::LINKEDIN,

	];

	// Fields to expose in REST API.
	const REST_FIELDS = [
		self::TYPE,
		self::LOCATION,
		self::ADDRESS,
		self::APPLICATION,
		self::APPLICATION_PAGE,
	];
}
