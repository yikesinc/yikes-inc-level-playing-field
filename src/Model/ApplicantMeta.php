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
 * Interface ApplicantMeta
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface ApplicantMeta {

	const META_PREFIX       = 'applicant_meta_';
	const FORM_FIELD_PREFIX = 'applicant_';

	// Top level sections.
	const BASIC      = 'basic';
	const EDUCATION  = 'education';
	const SKILLS     = 'skills';
	const LANGUAGES  = 'languages';
	const EXPERIENCE = 'experience';
	const VOLUNTEER  = 'volunteer';

	// Basic fields.
	const NAME          = 'name';
	const EMAIL         = 'email';
	const PHONE         = 'phone';
	const ADDRESS       = 'address';
	const COVER_LETTER  = 'cover_letter';
	const INSTITUTION   = 'institution';
	const ORGANIZATION  = 'organization';
	const TYPE          = 'type';
	const YEAR          = 'year';
	const MAJOR         = 'major';
	const DEGREE        = 'degree';
	const CERTIFICATION = 'certification';
	const STATUS        = 'status';
	const SKILL         = 'skill';
	const LANGUAGE      = 'language';
	const PROFICIENCY   = 'proficiency';
	const INDUSTRY      = 'industry';
	const DATES         = 'dates';
	const POSITION      = 'position';

	// Education sub-structure.
	const SCHOOLING      = [
		self::INSTITUTION => 1,
		self::TYPE        => 1,
		self::YEAR        => 1,
		self::MAJOR       => 1,
		self::DEGREE      => 1,
	];
	const CERTIFICATIONS = [
		self::INSTITUTION   => 1,
		self::TYPE          => 1,
		self::YEAR          => 1,
		self::CERTIFICATION => 1,
		self::STATUS        => 1,
	];

	// Structure for other items.
	const SKILLS_STRUCTURE     = [
		self::SKILL       => 1,
		self::PROFICIENCY => 1,
	];
	const LANGUAGES_STRUCTURE  = [
		self::LANGUAGE    => 1,
		self::PROFICIENCY => 1,
	];
	const EXPERIENCE_STRUCTURE = [
		self::ORGANIZATION => 1,
		self::INDUSTRY     => 1,
		self::DATES        => 1,
		self::POSITION     => 1,
	];
	const VOLUNTEER_STRUCTURE  = [
		self::ORGANIZATION => 1,
		self::TYPE         => 1,
		self::DATES        => 1,
		self::POSITION     => 1,
	];

	const ANONYMOUS_FIELDS = [
		self::NAME         => 1,
		self::EMAIL        => 1,
		self::PHONE        => 1,
		self::ADDRESS      => 1,
		self::INSTITUTION  => 1,
		self::YEAR         => 1,
		self::ORGANIZATION => 1,
		self::DATES        => 1,
	];

	// Field types.
	const TYPE_TEXT  = 'text';
	const TYPE_EMAIL = 'email';
	const TYPE_PHONE = 'tel';
	const TYPE_FILE  = 'file';

	const FIELD_MAP = [
		self::NAME         => self::TYPE_TEXT,
		self::EMAIL        => self::TYPE_EMAIL,
		self::PHONE        => self::TYPE_PHONE,
		// todo: address
		self::COVER_LETTER => self::TYPE_FILE,
		// todo: other complex fields
	];
}
