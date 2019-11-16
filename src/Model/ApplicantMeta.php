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
 * @since   1.0.0
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
	const NAME             = 'name';
	const EMAIL            = 'email';
	const PHONE            = 'phone';
	const ADDRESS          = 'address';
	const COVER_LETTER     = 'cover_letter';
	const INSTITUTION      = 'institution';
	const ORGANIZATION     = 'organization';
	const TYPE             = 'type';
	const YEAR             = 'year';
	const YEAR_DURATION    = 'year_duration';
	const MAJOR            = 'major';
	const DEGREE           = 'degree';
	const CERTIFICATION    = 'certification';
	const CERT_TYPE        = 'certification_type';
	const STATUS           = 'status';
	const SKILL            = 'skill';
	const LANGUAGE         = 'language';
	const PROFICIENCY      = 'proficiency';
	const INDUSTRY         = 'industry';
	const DATES            = 'dates';
	const POSITION         = 'position';
	const START_DATE       = 'start_date';
	const END_DATE         = 'end_date';
	const PRESENT_POSITION = 'present_position';

	// Address Fields.
	const LINE_1  = 'address-1';
	const LINE_2  = 'address-2';
	const CITY    = 'city';
	const STATE   = 'state';
	const COUNTRY = 'country';
	const ZIP     = 'zip';

	// Fields for other objects.
	const JOB         = JobManager::SINGULAR_SLUG;
	const APPLICATION = ApplicationManager::SINGULAR_SLUG;
	const ANONYMIZER  = 'anonymizer';

	// Complex fields.
	const SCHOOLING      = 'schooling';
	const CERTIFICATIONS = 'certifications';
	const INTERVIEW      = 'interview';

	// Admin fields.
	const NICKNAME         = 'nickname';
	const ANONYMIZED       = 'anonymized';
	const VIEWED           = 'viewed';
	const INTERVIEW_STATUS = 'interview_status';
	const DATE             = 'date';
	const TIME             = 'time';
	const LOCATION         = 'location';
	const MESSAGE          = 'message';
	const GUID             = 'guid';

	// Fields to make anonymous.
	const ANONYMOUS_FIELDS = [
		self::NAME         => 1,
		self::PHONE        => 1,
		self::LINE_1       => 1,
		self::LINE_2       => 1,
		self::ZIP          => 1,
		self::INSTITUTION  => 1,
		self::YEAR         => 1,
		self::ORGANIZATION => 1,
		self::DATES        => 1,
		self::START_DATE   => 1,
		self::END_DATE     => 1,
	];

	const FIELD_MAP = [
		self::NAME           => Types::TEXT,
		self::EMAIL          => Types::EMAIL,
		self::PHONE          => Types::PHONE,
		self::ADDRESS        => Types::ADDRESS,
		self::COVER_LETTER   => Types::WYSIWYG,
		self::EDUCATION      => Types::SCHOOLING,
		self::CERTIFICATION  => Types::CERTIFICATIONS,
		self::EXPERIENCE     => Types::EXPERIENCE,
		self::VOLUNTEER      => Types::VOLUNTEER,
		self::SCHOOLING      => Types::SCHOOLING,
		self::CERTIFICATIONS => Types::CERTIFICATIONS,
		self::SKILLS         => Types::SKILLS,
		self::LANGUAGES      => Types::LANGUAGES,
	];

	// Meta prefixed fields.
	const META_PREFIXES = [
		self::JOB              => MetaLinks::JOB,
		self::APPLICATION      => MetaLinks::APPLICATION,
		self::EMAIL            => self::META_PREFIX . self::EMAIL,
		self::NAME             => self::META_PREFIX . self::NAME,
		self::PHONE            => self::META_PREFIX . self::PHONE,
		self::COVER_LETTER     => self::META_PREFIX . self::COVER_LETTER,
		self::SCHOOLING        => self::META_PREFIX . self::SCHOOLING,
		self::CERTIFICATIONS   => self::META_PREFIX . self::CERTIFICATIONS,
		self::SKILLS           => self::META_PREFIX . self::SKILLS,
		self::EXPERIENCE       => self::META_PREFIX . self::EXPERIENCE,
		self::VOLUNTEER        => self::META_PREFIX . self::VOLUNTEER,
		self::NICKNAME         => self::META_PREFIX . self::NICKNAME,
		self::ANONYMIZED       => self::META_PREFIX . self::ANONYMIZED,
		self::VIEWED           => self::META_PREFIX . self::VIEWED,
		self::ADDRESS          => self::META_PREFIX . self::ADDRESS,
		self::ANONYMIZER       => self::META_PREFIX . self::ANONYMIZER,
		self::INTERVIEW        => self::META_PREFIX . self::INTERVIEW,
		self::INTERVIEW_STATUS => self::META_PREFIX . self::INTERVIEW_STATUS,
		self::GUID             => self::META_PREFIX . self::GUID,
		self::LANGUAGES        => self::META_PREFIX . self::LANGUAGES,
	];
}
