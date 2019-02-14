<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Options;

/**
 * Interface OptionFields
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface OptionFields {

	const OPTION_PREFIX = 'lpf_options_';

	// Option fields.
	const ADDITIONAL_EMAIL_RECIPIENTS = 'additional_email_recipients';
	const EMAIL_RECIPIENT_ROLES       = 'email_recipient_roles';
}
