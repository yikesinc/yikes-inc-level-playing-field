<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Field\Types;

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

	// Fields for other objects.
	const JOB         = JobManager::SINGULAR_SLUG;
	const APPLICATION = ApplicationManager::SLUG;

	// Complex fields.
	const SCHOOLING      = 'schooling';
	const CERTIFICATIONS = 'certifications';

	// Admin fields.
	const NICKNAME = 'nickname';

	// Fields to make anonymous.
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

	const FIELD_MAP = [
		self::NAME           => Types::TEXT,
		self::EMAIL          => Types::EMAIL,
		self::PHONE          => Types::PHONE,
		self::ADDRESS        => Types::ADDRESS,
		self::COVER_LETTER   => Types::TEXTAREA,
		self::EDUCATION      => Types::SCHOOLING,
		self::CERTIFICATION  => Types::CERTIFICATIONS,
		self::EXPERIENCE     => Types::EXPERIENCE,
		self::VOLUNTEER      => Types::VOLUNTEER,
		self::SCHOOLING      => Types::SCHOOLING,
		self::CERTIFICATIONS => Types::CERTIFICATIONS,
		self::SKILLS         => Types::SKILLS,
	];

	// Meta prefixed fields.
	const META_PREFIXES = [
		self::JOB            => MetaLinks::JOB,
		self::APPLICATION    => MetaLinks::APPLICATION,
		self::EMAIL          => self::META_PREFIX . self::EMAIL,
		self::NAME           => self::META_PREFIX . self::NAME,
		self::COVER_LETTER   => self::META_PREFIX . self::COVER_LETTER,
		self::SCHOOLING      => self::META_PREFIX . self::SCHOOLING,
		self::CERTIFICATIONS => self::META_PREFIX . self::CERTIFICATIONS,
		self::SKILLS         => self::META_PREFIX . self::SKILLS,
		self::EXPERIENCE     => self::META_PREFIX . self::EXPERIENCE,
		self::VOLUNTEER      => self::META_PREFIX . self::VOLUNTEER,
		self::NICKNAME       => self::META_PREFIX . self::NICKNAME,
	];
}
