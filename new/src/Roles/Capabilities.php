<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Roles;

/**
 * Interface Capabilities
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface Capabilities {

	const CAP_PREFIX = 'lpf_';
	const VIEW_ANONYMIZED_APPLICATIONS = self::CAP_PREFIX . 'view_anonymized_applications';
	const MESSAGE_APPLICANTS = self::CAP_PREFIX . 'message_applicants';
	const UNANONYMIZE = self::CAP_PREFIX . 'unanonymize';
}
