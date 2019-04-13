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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicationSuccessMessage extends BaseSetting {

	const SLUG = SettingsFields::APPLICATION_SUCCESS_MESSAGE;

	/**
	 * Get the default value for the setting.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_default() {
		return __( 'Congratulations! Your form has been successfully submitted.', 'yikes-level-playing-field' );
	}
}
