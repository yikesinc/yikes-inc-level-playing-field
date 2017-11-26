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
}
