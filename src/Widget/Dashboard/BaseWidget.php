<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Renderable;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\View\PostEscapedView;
use Yikes\LevelPlayingField\View\TemplatedView;

/**
 * Abstract class BaseWidget.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Ebonie Butler
 */
abstract class BaseWidget implements Renderable, AssetsAware, Service {

	use AssetsAwareness;

	const SLUG     = '_basewidget_';
	const VIEW_URI = '_baseviewuri_';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		$this->register_assets();

		add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widget' ] );
	}

	/**
	 * Add custom dashboard widget.
	 */
	public function add_dashboard_widget() {
		wp_add_dashboard_widget(
			$this->get_slug(),
			$this->get_title(),
			[ $this, 'process_widget' ]
		);
	}

	/**
	 * Process the shortcode attributes and prepare rendering.
	 *
	 * @since 1.0.0
	 *
	 * @return string Rendered HTML of the shortcode.
	 */
	public function process_widget() {
		return $this->render( $this->get_context() );
	}

	/**
	 * Get the slug to use for the dashboard widget.
	 *
	 * @since 1.0.0
	 *
	 * @return string widget slug.
	 * @throws MustExtend When the default slug has not been extended.
	 */
	protected function get_slug() {
		if ( self::SLUG === static::SLUG ) {
			throw MustExtend::default_slug( self::SLUG );
		}
		return static::SLUG;
	}

	/**
	 * Get the View URI to use for rendering the dashboard widget.
	 *
	 * @since 1.0.0
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
	 * Render the current Renderable.
	 *
	 * @since 1.0.0
	 *
	 * @param array $context Context in which to render.
	 */
	public function render( array $context = [] ) {
		try {
			$this->enqueue_assets();
			$view = new PostEscapedView( new TemplatedView( $this->get_view_uri() ) );

			echo $view->render( $context ); // phpcs:ignore WordPress.Security.EscapeOutput
		} catch ( \Exception $exception ) {
			// Don't let exceptions bubble up. Just render an empty widget instead.
			return;
		}
	}

	/**
	 * Get the title of the dashboard widget.
	 *
	 * @since 1.0.0
	 */
	abstract public function get_title();

	/**
	 * Get the context to pass onto the view.
	 *
	 * Override to provide data to the view that is not part of the shortcode
	 * attributes.
	 *
	 * @since 1.0.0
	 *
	 * @return array Context to pass onto view.
	 */
	protected function get_context() {
		return [];
	}
}
