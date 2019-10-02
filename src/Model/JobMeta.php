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
	const TYPE                        = 'job_type';
	const LOCATION                    = 'location';
	const ADDRESS                     = 'address';
	const APPLICATION                 = 'application';
	const APPLICATION_SUCCESS_MESSAGE = 'application_success_message';
	const APPLICATION_PAGE            = 'application_page';

	// Properties that should be JSON-encoded.
	const JSON_PROPERTIES = [
		self::META_PREFIX . self::ADDRESS => true,
	];

	// Meta prefixed fields.
	const META_PREFIXES = [
		self::TYPE                        => self::META_PREFIX . self::TYPE,
		self::LOCATION                    => self::META_PREFIX . self::LOCATION,
		self::ADDRESS                     => self::META_PREFIX . self::ADDRESS,
		self::APPLICATION                 => MetaLinks::APPLICATION,
		self::APPLICATION_SUCCESS_MESSAGE => self::META_PREFIX . self::APPLICATION_SUCCESS_MESSAGE,
		self::APPLICATION_PAGE            => self::META_PREFIX . self::APPLICATION_PAGE,
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
