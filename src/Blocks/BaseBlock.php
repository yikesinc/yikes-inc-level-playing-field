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

/**
 * Class BaseBlock
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseBlock implements Service, AssetsAware {

	use AssetsAwareness;

	const BASE_SLUG  = 'ylpf/';
	const BLOCK_PATH = 'assets/js/blocks/';
	const BLOCK_FILE = '/index';

	/**
	 * Register the current Registerable.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$this->register_assets();

		// Register the block type.
		add_action( 'init', function() {
			register_block_type( $this->get_block_slug(), $this->get_block_args() );
		} );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	abstract protected function get_assets();

	/**
	 * Get the slug for use with registering the block.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_block_slug() {
		return static::BASE_SLUG . static::BLOCK_SLUG;
	}

	/**
	 * Get the path for the main JS block file.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_block_path() {
		return static::BLOCK_PATH . static::BLOCK_SLUG . static::BLOCK_FILE;
	}

	/**
	 * Get the block's category.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_category() {
		return static::CATEGORY;
	}

	/**
	 * Get the block's title, i18n'ed.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	abstract protected function get_title();

	/**
	 * Get the attributes for a block.
	 *
	 * Note: if you don't set the default attributes on the server side, the defaults won't be available when rendering (i.e. in the `render_block()` function).
	 *
	 * @since %VERSION%
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
	 */
	abstract public function render_block( $attributes, $content );

	/**
	 * Get the arguments used when registering a block.
	 *
	 * @since %VERSION%
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
}
