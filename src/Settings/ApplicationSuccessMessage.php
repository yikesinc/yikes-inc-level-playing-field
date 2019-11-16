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
 * Class ApplicationSuccessMessage
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class ApplicationSuccessMessage extends BaseSetting {

	const SLUG = SettingsFields::APPLICATION_SUCCESS_MESSAGE;

	/**
	 * Get the default value for the setting.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_default() {
		return __( 'Thank you, your application has been successfully submitted.', 'level-playing-field' );
	}
}
