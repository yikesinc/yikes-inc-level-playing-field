<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Closure;
use WP_Post;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Renderable;
use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\View\FormEscapedView;
use Yikes\LevelPlayingField\View\TemplatedView;

/**
 * Abstract class BaseMetabox.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseMetabox implements Renderable, Service, AssetsAware {

	use AssetsAwareness;

	const CONTEXT_ADVANCED = 'advanced';
	const CONTEXT_NORMAL   = 'normal';
	const CONTEXT_SIDE     = 'side';

	const PRIORITY_DEFAULT = 'default';
	const PRIORITY_HIGH    = 'high';
	const PRIORITY_LOW     = 'low';
	const PRIORITY         = 10;

	/**
	 * Register the Metabox.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		$this->register_assets();
		$this->register_persistence_hooks();

		add_action( 'add_meta_boxes', function () {
			add_meta_box(
				$this->get_id(),
				$this->get_title(),
				[ $this, 'process_metabox' ],
				$this->get_screen(),
				$this->get_context(),
				$this->get_priority(),
				$this->get_callback_args()
			);
		}, static::PRIORITY );
	}

	/**
	 * Register the persistence hooks to be triggered by a save attempt.
	 *
	 * @since %VERSION%
	 */
	protected function register_persistence_hooks() {
		$closure = $this->get_persistence_closure();
		add_action( 'save_post', $closure );
	}

	/**
	 * Return the persistence closure.
	 *
	 * @since %VERSION%
	 *
	 * @return Closure
	 */
	protected function get_persistence_closure() {
		return function ( $post_id ) {
			// Verify nonce and bail early if it doesn't verify.
			if ( ! $this->verify_nonce() ) {
				return $post_id;
			}

			// Bail early if this is an autosave.
			if ( wp_is_post_autosave( $post_id ) ) {
				return $post_id;
			}

			// Bail early if this is a revision.
			if ( wp_is_post_revision( $post_id ) ) {
				return $post_id;
			}

			// Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}

			// Check if there was a multisite switch before.
			if ( is_multisite() && ms_is_switched() ) {
				return $post_id;
			}

			$this->persist( $post_id );

			return $post_id;
		};
	}

	/**
	 * Verify the nonce and return the result.
	 *
	 * @since %VERSION%
	 *
	 * @return bool Whether the nonce could be successfully verified.
	 */
	protected function verify_nonce() {
		$nonce_name = $this->get_nonce_name();

		if ( ! array_key_exists( $nonce_name, $_POST ) ) {
			return false;
		}

		$result = wp_verify_nonce(
			$_POST[ $nonce_name ],
			$this->get_nonce_action()
		);

		return false !== $result;
	}

	/**
	 * Get the name of the nonce to use.
	 *
	 * @since %VERSION%
	 *
	 * @return string Name of the nonce.
	 */
	protected function get_nonce_name() {
		return "{$this->get_id()}_nonce";
	}

	/**
	 * Get the ID to use for the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string ID to use for the metabox.
	 */
	abstract protected function get_id();

	/**
	 * Get the action of the nonce to use.
	 *
	 * @since %VERSION%
	 *
	 * @return string Action of the nonce.
	 */
	protected function get_nonce_action() {
		return "{$this->get_id()}_action";
	}

	/**
	 * Do the actual persistence of the changed data.
	 *
	 * @since %VERSION%
	 *
	 * @param int $post_id ID of the post to persist.
	 */
	abstract protected function persist( $post_id );

	/**
	 * Get the title to use for the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string Title to use for the metabox.
	 */
	abstract protected function get_title();

	/**
	 * Get the screen on which to show the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string|array|\WP_Screen Screen on which to show the metabox.
	 */
	protected function get_screen() {
		return null;
	}

	/**
	 * Get the context in which to show the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string Context to use.
	 */
	protected function get_context() {
		return static::CONTEXT_ADVANCED;
	}

	/**
	 * Get the priority within the context where the boxes should show.
	 *
	 * @since %VERSION%
	 *
	 * @return string Priority within context.
	 */
	protected function get_priority() {
		return static::PRIORITY_DEFAULT;
	}

	/**
	 * Get the array of arguments to pass to the render callback.
	 *
	 * @since %VERSION%
	 *
	 * @return array Array of arguments.
	 */
	protected function get_callback_args() {
		return [];
	}

	/**
	 * Process the metabox attributes and prepare rendering.
	 *
	 * @since %VERSION%
	 *
	 * @param WP_Post      $post The post object.
	 * @param array|string $atts Attributes as passed to the metabox. The rendered content needs to be echoed.
	 */
	public function process_metabox( $post, $atts ) {
		$atts                = $this->process_attributes( $post, $atts );
		$atts['metabox_id']  = $this->get_id();
		$atts['nonce_field'] = $this->render_nonce();

		echo $this->render( (array) $atts ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Process the metabox attributes.
	 *
	 * @since %VERSION%
	 *
	 * @param WP_Post      $post The post object.
	 * @param array|string $atts Raw metabox attributes passed into the
	 *                           metabox function.
	 *
	 * @return array Processed metabox attributes.
	 */
	abstract protected function process_attributes( $post, $atts );

	/**
	 * Render the nonce.
	 *
	 * @since %VERSION%
	 *
	 * @return string Hidden field with a nonce.
	 */
	protected function render_nonce() {
		ob_start();
		wp_nonce_field( $this->get_nonce_action(), $this->get_nonce_name() );

		return ob_get_clean();
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
	public function render( array $context = [] ) {
		try {
			$this->enqueue_assets();

			$view = new FormEscapedView(
				new TemplatedView( $this->get_view_uri() )
			);

			return $view->render( $context );
		} catch ( \Exception $exception ) {
			// Don't let exceptions bubble up. Just render the exception message
			// into the metabox.
			return sprintf(
				'<pre>%s</pre>',
				$exception->getMessage()
			);
		}
	}

	/**
	 * Get the View URI to use for rendering the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @return string View URI.
	 */
	protected function get_view_uri() {
		return static::VIEW;
	}
}
