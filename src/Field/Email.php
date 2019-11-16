<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

/**
 * Class Email
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class Email extends BaseInput {
	const TYPE     = 'email';
	const SANITIZE = FILTER_SANITIZE_EMAIL;
}
