<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

use Yikes\LevelPlayingField\Roles\HiringManager;
use Yikes\LevelPlayingField\Roles\HumanResources;

/**
 * Class EmailRecipientRoles
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class EmailRecipientRoles extends BaseSetting {

	const SLUG = SettingsFields::EMAIL_RECIPIENT_ROLES;

	/**
	 * Get the default value for the setting.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_default() {
		return [
			HiringManager::SLUG  => false,
			HumanResources::SLUG => false,
		];
	}

	/**
	 * Sanitize the setting value.
	 *
	 * @since 1.0.0
	 *
	 * @param mixed $value The value to sanitize.
	 *
	 * @return mixed The sanitized value.
	 */
	protected function sanitize( $value ) {
		if ( ! is_array( $value ) ) {
			return $this->get();
		}

		foreach ( $value as &$item ) {
			$item = filter_var( $item, FILTER_VALIDATE_BOOLEAN );
		}

		return $value;
	}
}
