<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

/**
 * Interface SettingsFields
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
interface SettingsFields {

	const OPTION_PREFIX = 'lpf_settings_';

	// Setting fields.
	const ADDITIONAL_EMAIL_RECIPIENTS = 'additional_email_recipients';
	const EMAIL_RECIPIENT_ROLES       = 'email_recipient_roles';
	const APPLICATION_SUCCESS_MESSAGE = 'application_success_message';
	const DISABLE_FRONT_END_CSS       = 'disable_front_end_css';
}
