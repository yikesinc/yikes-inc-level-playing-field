<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Taxonomy;

use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Service;

/**
 * Abstract class BaseTaxonomy.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseTaxonomy implements Service {

	const SLUG = '_basetax_';

	/**
	 * Register the WordPress hooks.
	 *
	 * @author Jeremy Pry
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_taxonomy' ] );
	}

	/**
	 * Register the taxonomy.
	 *
	 * @author Jeremy Pry
	 */
	public function register_taxonomy() {
		register_taxonomy( $this->get_slug(), $this->get_object_types(), $this->get_arguments() );
	}

	/**
	 * Get the slug for the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return string
	 * @throws MustExtend When the default slug has not been extended.
	 */
	public function get_slug() {
		if ( self::SLUG === static::SLUG ) {
			throw MustExtend::default_slug( self::SLUG );
		}

		return static::SLUG;
	}

	/**
	 * Get the arguments that configure the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	abstract protected function get_arguments();

	/**
	 * Get the object type(s) to use when registering the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	abstract protected function get_object_types();
}
