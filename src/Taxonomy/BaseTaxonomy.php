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

	const SLUG               = '_basetax_';
	const SHOW_IN_QUICK_EDIT = true;

	/**
	 * Register the WordPress hooks.
	 *
	 * @author Jeremy Pry
	 */
	public function register() {
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_filter( 'quick_edit_show_taxonomy', array( $this, 'quick_edit_show_taxonomy' ), 10, 3 );
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
	 * Decide whether a taxonomy is editable in the quick and bulk edit screens.
	 *
	 * @author Kevin Utz
	 *
	 * @param bool   $show_in_quick_edit Whether this taxonomy is shown in the quick and bulk edit screens.
	 * @param string $taxonomy_slug      The taxonomy's slug.
	 * @param string $post_type          The post type.
	 *
	 * @return bool
	 *
	 * @throws MustExtend When the default slug has not been extended.
	 */
	public function quick_edit_show_taxonomy( $show_in_quick_edit, $taxonomy_slug, $post_type ) {
		if ( $this->get_slug() !== $taxonomy_slug ) {
			return $show_in_quick_edit;
		}

		return static::SHOW_IN_QUICK_EDIT;
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
