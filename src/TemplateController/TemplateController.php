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
use Yikes\LevelPlayingField\View\FormEscapedView;
use Yikes\LevelPlayingField\View\PostEscapedView;
use Yikes\LevelPlayingField\View\TemplatedView;

/**
 * Class TemplateController.
 *
 * A class to control which template file is used to display our custom post types.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  JP, KU, EB, TL
 */
abstract class TemplateController implements Renderable, AssetsAware, Service {

	use AssetsAwareness;

	const PRIORITY = 10;
	const VIEW_URI = NULL;

	public function register() {

		add_filter( 'template_include', [ $this, 'set_content' ], static::PRIORITY, 1 );
	}

	/**
	 * Check if the current request is for this class' object and supply the current post w/ content.
	 *
	 * @since %VERSION%
	 *
	 * @param  string $template The default template file WordPress is handing us.
	 * @return string The text to be used for the menu.
	 */
	abstract public function set_content( $template );

	/**
	 * Custom logic to determine if the current template request should be filled with your object's content.
	 *
	 * @return bool True if the current request should use your object's content.
	 */
	abstract protected function is_template_request();

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
	 * Get the data that should be passed into the current context.
	 *
	 * @return mixed The data needed for the current context.
	 */
	abstract protected function get_context_data();

	/**
	 * Get the View URI to use for rendering the template's content.
	 *
	 * @since %VERSION%
	 *
	 * @return string View URI.
	 * @throws MustExtend When the default view URI has not been extended.
	 */
	protected function get_view_uri() {
		if ( static::VIEW_URI === NULL ) {
			throw MustExtend::default_view( self::VIEW_URI );
		}

		return static::VIEW_URI;
	}
}
