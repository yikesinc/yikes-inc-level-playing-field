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
 * Class DeleteOnUninstall
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class DeleteOnUninstall extends BaseSetting {

	const SLUG     = SettingsFields::DELETE_ON_UNINSTALL;
	const SANITIZE = FILTER_VALIDATE_BOOLEAN;

	/**
	 * Get the default value for the setting.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	protected function get_default() {
		return true;
	}
}
