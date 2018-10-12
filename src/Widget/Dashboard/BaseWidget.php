<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Service;

/**
 * Abstract class BaseCustomPostType.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 *
 * @property string $slug The CPT slug.
 */
abstract class BaseWidget implements Service {

	const PREFIX = '_basewdgt_';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'wp_dashboard_setup', array( $this, $this->get_prefix() . 'add_widget' ) );
	}

	/**
	 * Get the prefix to use for the function name.
	 *
	 * @since %VERSION%
	 *
	 * @return string Custom post type slug.
	 * @throws MustExtend When the default slug has not been extended.
	 */
	protected function get_prefix() {
		if ( self::PREFIX === static::PREFIX ) {
			throw MustExtend::default_slug( self::PREFIX );
		}

		return static::PREFIX;
	}
}
