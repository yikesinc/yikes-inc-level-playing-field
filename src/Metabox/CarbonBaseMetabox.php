<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\Service;

/**
 * Abstrct Class CarbonBaseMetabox
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class CarbonBaseMetabox implements Service {

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'carbon_fields_register_fields', [ $this, 'register_fields' ] );
	}

	/**
	 * Register fields using Carbon Fields.
	 *
	 * @since %VERSION%
	 */
	abstract public function register_fields();
}
