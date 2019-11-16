<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    JP, KU, EB, TL
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\TemplateController;

use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Renderable;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\View\PostEscapedView;
use Yikes\LevelPlayingField\View\TemplatedView;

/**
 * Class TemplateController.
 *
 * A class to control which template file is used to display our custom post types.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  JP, KU, EB, TL
 */
abstract class TemplateController implements Renderable, AssetsAware, Service {

	use AssetsAwareness;

	const PRIORITY = 10;
	const VIEW_URI = null;

	/**
	 * Register the current Registerable.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_filter( 'the_content', [ $this, 'set_content' ], static::PRIORITY );
		add_filter( 'template_include', [ $this, 'set_template' ], 99 );
	}

	/**
	 * Check if the current request is for this class' object and supply the current post w/ content.
	 *
	 * @since 1.0.0
	 *
	 * @param  string $content The default post content.
	 *
	 * @return string The post's content, maybe overridden.
	 */
	abstract public function set_content( $content );

	/**
	 * Filters the path of the current template before including it.
	 *
	 * @since 1.0.0
	 *
	 * @param string $template The path of the template to include.
	 *
	 * @return string
	 */
	public function set_template( $template ) {
		return $template;
	}

	/**
	 * Custom logic to determine if the current template request should be filled with your object's content.
	 *
	 * @return bool True if the current request should use your object's content.
	 */
	abstract protected function is_template_request();

	/**
	 * Render the current Renderable.
	 *
	 * @since 1.0.0
	 *
	 * @param array $context Context in which to render.
	 *
	 * @return string Rendered HTML.
	 */
	public function render( array $context = [] ) {
		try {
			$this->enqueue_assets();
			$view = new PostEscapedView( new TemplatedView( $this->get_view_uri() ) );

			return $view->render( $context );
		} catch ( \Exception $e ) {
			return sprintf(
				/* translators: %s refers to the error message */
				esc_html__( 'There was an error displaying the template: %s', 'level-playing-field' ),
				$e->getMessage()
			);
		}
	}

	/**
	 * Get the data that should be passed into the current context.
	 *
	 * @return mixed The data needed for the current context.
	 */
	abstract protected function get_context_data();

	/**
	 * Get the View URI to use for rendering the template's content.
	 *
	 * @since 1.0.0
	 *
	 * @return string View URI.
	 * @throws MustExtend When the default view URI has not been extended.
	 */
	protected function get_view_uri() {
		if ( static::VIEW_URI === null ) {
			throw MustExtend::default_view( self::VIEW_URI );
		}

		return static::VIEW_URI;
	}
}
