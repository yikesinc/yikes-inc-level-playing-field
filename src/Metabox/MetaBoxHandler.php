<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

/**
 * Trait MetaBoxHandler
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
trait MetaBoxHandler {

	protected $lpf_boxes = [];

	protected function add_meta_box( $id, $title, $callback, $screen, $context, $priority, $args ) {
		$this->lpf_boxes[ $id ] = true;
		add_meta_box( $id, $title, $callback, $screen, $context, $priority, $args );

		$this->maybe_remove_meta_boxes();
	}

	protected function maybe_remove_meta_boxes() {
		if ( ! static::REMOVE_META_BOXES ) {
			return;
		}

		global $wp_meta_boxes;

		$cpt_meta_boxes = array_intersect_key( $wp_meta_boxes, array_flip( $this->get_post_types() ) );

		foreach ( $cpt_meta_boxes as $cpt_slug => $contexts ) {
			foreach ( $contexts as $context => $priorities ) {
				foreach ( $priorities as $priority => $meta_boxes ) {
					foreach ( $meta_boxes as $meta_box_id => $meta_box ) {
						if ( ! empty( $meta_box ) && ! isset( $this->lpf_boxes[ $meta_box_id ] ) ) {
							$wp_meta_boxes[ $cpt_slug ][ $context ][ $priority ][ $meta_box_id ] = false;
						}
					}
				}
			}
		}
	}
}
