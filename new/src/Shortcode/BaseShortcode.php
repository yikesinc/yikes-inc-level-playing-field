<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Shortcode;

use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Renderable;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\View\PostEscapedView;
use Yikes\LevelPlayingField\View\TemplatedView;

/**
 * Abstract class BaseShortcode.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseShortcode implements Renderable, AssetsAware, Service {

	use AssetsAwareness;

	const TAG      = '_baseshortcode_';
	const VIEW_URI = '_baseviewuri_';

	/**
	 * Register the Shortcode.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();

		add_action( 'init', function () {
			add_shortcode( $this->get_tag(), array( $this, 'process_shortcode' ) );
		} );
	}

	/**
	 * Process the shortcode attributes and prepare rendering.
	 *
	 * @since %VERSION%
	 *
	 * @param array|string $atts Attributes as passed to the shortcode.
	 *
	 * @return string Rendered HTML of the shortcode.
	 */
	public function process_shortcode( $atts ) {
		$atts    = $this->process_attributes( $atts );
		$context = $this->get_context( $atts );

		return $this->render( array_merge( $atts, $context ) );
	}

	/**
	 * Render the current Renderable.
	 *
	 * @since %VERSION%
	 *
	 * @param array $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 */
	public function render( array $context = array() ) {
		try {
			$this->enqueue_assets();
			$view = new PostEscapedView( new TemplatedView( $this->get_view_uri() ) );

			return $view->render( $context );
		} catch ( \Exception $exception ) {
			// Don't let exceptions bubble up. Just render an empty shortcode instead.
			return '';
		}
	}

	/**
	 * Process the shortcode attributes.
	 *
	 * Override to add accepted attributes and their default values.
	 *
	 * @since %VERSION%
	 *
	 * @param array|string $atts Raw shortcode attributes passed into the shortcode function.
	 *
	 * @return array Processed shortcode attributes.
	 */
	protected function process_attributes( $atts ) {
		return shortcode_atts( $this->get_default_atts(), $atts, $this->get_tag() );
	}

	/**
	 * Get the context to pass onto the view.
	 *
	 * Override to provide data to the view that is not part of the shortcode
	 * attributes.
	 *
	 * @since %VERSION%
	 *
	 * @param array $atts Array of shortcode attributes.
	 *
	 * @return array Context to pass onto view.
	 */
	protected function get_context( $atts ) {
		return array();
	}

	/**
	 * Get the tag to use for the shortcode.
	 *
	 * @since %VERSION%
	 *
	 * @return string Tag of the shortcode.
	 * @throws MustExtend When the default tag has not been extended.
	 */
	protected function get_tag() {
		if ( self::TAG === static::TAG ) {
			throw MustExtend::default_tag( self::TAG );
		}

		return static::TAG;
	}

	/**
	 * Get the View URI to use for rendering the shortcode.
	 *
	 * @since %VERSION%
	 *
	 * @return string View URI.
	 * @throws MustExtend When the default view URI has not been extended.
	 */
	protected function get_view_uri() {
		if ( self::VIEW_URI === static::VIEW_URI ) {
			throw MustExtend::default_view( self::VIEW_URI );
		}

		return static::VIEW_URI;
	}

	/**
	 * Get the default array of attributes for the shortcode.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	abstract protected function get_default_atts();
}
