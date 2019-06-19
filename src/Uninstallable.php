<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

/**
 * Interface Uninstallable.
 *
 * An object that can be uninstalled.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 */
interface Uninstallable {

	/**
	 * Uninstall the Uninstallable component.
	 *
	 * @since %VERSION%
	 */
	public function uninstall();
}
