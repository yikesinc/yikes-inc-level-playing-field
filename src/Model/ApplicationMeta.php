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
	const NAME         = self::META_PREFIX . 'name';
	const EMAIL        = self::META_PREFIX . 'email';
	const PHONE        = self::META_PREFIX . 'phone';
	const ADDRESS      = self::META_PREFIX . 'address';
	const COVER_LETTER = self::META_PREFIX . 'cover_letter';
}
