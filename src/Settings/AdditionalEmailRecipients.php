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
 * Class AdditionalEmailRecipients
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class AdditionalEmailRecipients extends BaseSetting {

	const SLUG = SettingsFields::ADDITIONAL_EMAIL_RECIPIENTS;

	/**
	 * Get the default value for the setting.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_default() {
		return get_option( 'admin_email' );
	}
}
