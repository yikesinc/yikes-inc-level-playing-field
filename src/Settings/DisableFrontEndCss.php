<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

use Yikes\LevelPlayingField\Shortcode\Application;
use Yikes\LevelPlayingField\Shortcode\BaseJobs;

/**
 * Class DisableFrontEndCss
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class DisableFrontEndCss extends BaseSetting {

	const SLUG = SettingsFields::DISABLE_FRONT_END_CSS;

	/**
	 * Get the default value for the setting.
	 *
	 * @since %VERSION%
	 * @return mixed
	 */
	protected function get_default() {
		return [
			Application::CSS_HANDLE => false,
			BaseJobs::CSS_HANDLE    => false,
		];
	}

	/**
	 * Sanitize the setting value.
	 *
	 * @since %VERSION%
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
			$item = boolval( $item );
		}

		return $value;
	}
}
