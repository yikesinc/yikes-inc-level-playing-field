<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Taxonomy;

use Yikes\LevelPlayingField\Activateable;
use Yikes\LevelPlayingField\Uninstallable;
use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Service;
use WP_Term_Query;

/**
 * Abstract class BaseTaxonomy.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseTaxonomy implements Activateable, Uninstallable, Service {

	const SLUG               = '_basetax_';
	const SHOW_IN_QUICK_EDIT = true;

	/**
	 * Register the WordPress hooks.
	 *
	 * @author Jeremy Pry
	 */
	public function register() {
		add_action( 'init', [ $this, 'register_taxonomy' ] );
	}

	/**
	 * Activate the service.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		$this->register_taxonomy();
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
	 * Delete all terms.
	 */
	public function uninstall() {
		$wp_term_query_args = [
			'taxonomy'   => static::SLUG,
			'hide_empty' => false,
			'fields'     => 'ids',
		];

		$wp_terms = new WP_Term_Query( $wp_term_query_args );

		if ( ! empty( $wp_terms->terms ) ) {
			foreach ( $wp_terms->terms as $term_id ) {
				wp_delete_term( $term_id, static::SLUG );
			}
		}
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
