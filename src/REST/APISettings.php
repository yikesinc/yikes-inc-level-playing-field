<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Freddie Mixell
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\REST;

/**
 * Interface APISettings
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface APISettings {

	// API SETTINGS.
	const API_VERSION   = 1;
	const LPF_NAMESPACE = 'yikes-level-playing-field/v' . self::API_VERSION;

	// API ROUTES.
	const INTERVIEW_STATUS_ROUTE = '/interview-status';

}
