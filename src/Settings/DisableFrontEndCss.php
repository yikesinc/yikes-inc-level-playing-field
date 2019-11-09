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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class DisableFrontEndCss extends BaseSetting {

	const SLUG = SettingsFields::DISABLE_FRONT_END_CSS;

	/**
	 * Get the plugin value.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get() {
		return (array) parent::get();
	}

	/**
	 * Get the default value for the setting.
	 *
	 * @since 1.0.0
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
