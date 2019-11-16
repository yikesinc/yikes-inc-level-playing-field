<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry / Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Blocks;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Exception\MustExtend;

/**
 * Class BaseBlock
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class BaseBlock implements Service, AssetsAware {

	use AssetsAwareness;

	const BASE_SLUG  = 'ylpf/';
	const BLOCK_PATH = 'assets/js/blocks/';
	const BLOCK_FILE = '/index';
	const BLOCK_SLUG = '__BLOCKSLUG__';
	const CATEGORY   = 'lpf-blocks';
	const PRIORITY   = 10;

	/**
	 * Register the current Registerable.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_assets();

		// Create LPF block category.
		add_filter( 'block_categories', [ $this, 'set_block_category' ], static::PRIORITY, 2 );

		// Register the block type.
		add_action( 'init', function() {
			register_block_type( $this->get_block_slug(), $this->get_block_args() );
		} );

		// Enqueue our blocks manually instead of relying on WP.
		add_action( 'enqueue_block_editor_assets', function() {
			$this->enqueue_assets();
		}, 2 );
	}

	/**
	 * Load asset objects for use.
	 *
	 * @since 1.0.0
	 */
	abstract protected function load_assets();

	/**
	 * Get the slug for use with registering the block.
	 *
	 * @since 1.0.0
	 * @return string
	 * @throws MustExtend When the default slug has not been extended.
	 */
	public function get_block_slug() {
		if ( static::BLOCK_SLUG === self::BLOCK_SLUG ) {
			throw MustExtend::default_slug( self::BLOCK_SLUG );
		}
		return static::BASE_SLUG . static::BLOCK_SLUG;
	}

	/**
	 * Get the path for the main JS block file.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_block_path() {
		return static::BLOCK_PATH . static::BLOCK_SLUG . static::BLOCK_FILE;
	}

	/**
	 * Get the block's category.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_category() {
		return static::CATEGORY;
	}

	/**
	 * Get the block's title, i18n'ed.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	abstract protected function get_title();

	/**
	 * Get the attributes for a block.
	 *
	 * Note: if you don't set the default attributes on the server side, the defaults won't be available when rendering (i.e. in the `render_block()` function).
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_attributes() {
		return [];
	}

	/**
	 * Take the shortcode parameters from the Gutenberg block and render something.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 *
	 * @return string The rendered block content.
	 */
	abstract public function render_block( $attributes, $content );

	/**
	 * Get the arguments used when registering a block.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_block_args() {
		return [
			'editor_script'   => static::BLOCK_SLUG,
			'category'        => $this->get_category(),
			'title'           => $this->get_title(),
			'attributes'      => $this->get_attributes(),
			'render_callback' => [ $this, 'render_block' ],
		];
	}

	/**
	 * Filter the default array of block categories.
	 *
	 * @param array   $categories Array of block categories.
	 * @param WP_Post $post               Post being loaded.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function set_block_category( $categories, $post ) {
		foreach ( $categories as $category ) {
			if ( static::CATEGORY === $category['slug'] ) {
				return $categories;
			}
		}

		$categories = array_merge(
			$categories,
			[
				[
					'slug'  => static::CATEGORY,
					'title' => __( 'Level Playing Field', 'level-playing-field' ),
				],
			]
		);

		return $categories;
	}
}
