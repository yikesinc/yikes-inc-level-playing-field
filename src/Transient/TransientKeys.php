<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Transient;

/**
 * Interface TransientKeys
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface TransientKeys {

	const PREFIX           = 'lpf_';
	const EMAILS_TRANSIENT = self::PREFIX . 'from_applicant_emails_';
}
