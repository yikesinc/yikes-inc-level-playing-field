<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Messaging;

use Yikes\LevelPlayingField\Service;

/**
 * Abstract class BaseMessaging.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseMessaging implements Service {

	const HOOK_PRIORITY              = 10;
	const BOX_PRIORITY               = 'low';
	const CONTEXT                    = 'advanced';
	const POST_TYPE                  = '';
	const BOX_ID                     = 'applicant-messaging';
	const BOX_TITLE                  = 'Messaging';
	const SHOW_DEFAULT_COMMENT_BOXES = false;

	/**
	 * Register our hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'add_meta_boxes', [ $this, 'meta_box' ], static::HOOK_PRIORITY, 2 );
		add_action( 'admin_menu', [ $this, 'remove_default_comments_meta_boxes' ], 1 );
	}

	/**
	 * Create the messaging meta box.
	 *
	 * @since %VERSION%
	 *
	 * @param string $post_type The post type.
	 * @param object $post      WP_Post.
	 */
	public function meta_box( $post_type, $post ) {

		if ( static::POST_TYPE !== $post_type ) {
			return;
		}

		add_meta_box(
			static::BOX_ID,
			static::BOX_TITLE,
			[ $this, 'create_meta_box' ],
			$this->screen(),
			static::CONTEXT,
			static::BOX_PRIORITY,
			[ $post ]
		);
	}

	/**
	 * Return the screen that this metabox should appear on.
	 *
	 * @since %VERSION%
	 *
	 * @return string | array | WP_Screen.
	 */
	abstract protected function screen();

	/**
	 * Create the metabox.
	 *
	 * @since %VERSION%
	 *
	 * @param WP_Post $post The post object.
	 */
	abstract public function create_meta_box( $post );

	/**
	 * Remove the default comments' meta boxes.
	 *
	 * @since %VERSION%
	 *
	 * @return string | array | WP_Screen.
	 */
	public function remove_default_comments_meta_boxes() {

		if ( static::SHOW_DEFAULT_COMMENT_BOXES === true ) {
			return;
		}

		// Removes comments' status & comments' meta boxes.
		remove_meta_box( 'commentstatusdiv', static::POST_TYPE, 'normal' );
		remove_meta_box( 'commentsdiv', static::POST_TYPE, 'normal' );
	}
}
