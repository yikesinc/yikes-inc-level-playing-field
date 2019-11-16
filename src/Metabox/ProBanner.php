<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  KU
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantCPT;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager as ApplicationCPT;
use Yikes\LevelPlayingField\View\View;

/**
 * Class Banner2
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class ProBanner extends BaseMetabox {

	// Base Metabox.
	const BOX_ID   = 'pro-banner';
	const VIEW     = 'views/pro-banner';
	const PRIORITY = 30;

	/**
	 * Do the actual persistence of the changed data.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id ID of the post to persist.
	 */
	protected function persist( $post_id ) {
		// There's no data to save, so intentionally do nothing.
	}

	/**
	 * Get the title to use for the metabox.
	 *
	 * @since 1.0.0
	 *
	 * @return string Title to use for the metabox.
	 */
	protected function get_title() {
		return __( 'Level Playing Field Pro', 'level-playing-field' );
	}

	/**
	 * Get the context in which to show the metabox.
	 *
	 * @since 1.0.0
	 *
	 * @return string Context to use.
	 */
	protected function get_context() {
		return static::CONTEXT_SIDE;
	}

	/**
	 * Process the metabox attributes.
	 *
	 * We don't have any attributes. Return an empty array.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_Post      $post The post object.
	 * @param array|string $atts Raw metabox attributes passed into the
	 *                           metabox function.
	 *
	 * @return array Processed metabox attributes.
	 */
	protected function process_attributes( $post, $atts ) {
		return [];
	}

	/**
	 * Get the screen on which to show the metabox.
	 *
	 * @since 1.0.0
	 *
	 * @return string|array|\WP_Screen Screen on which to show the metabox.
	 */
	protected function get_screen() {
		return $this->get_post_types();
	}

	/**
	 * Get the post types for this metabox.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_post_types() {
		return [ ApplicantCPT::SLUG, ApplicationCPT::SLUG ];
	}
}
