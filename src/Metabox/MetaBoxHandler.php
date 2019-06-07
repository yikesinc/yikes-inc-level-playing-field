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

	/**
	 * Add a meta box.
	 *
	 * @see https://developer.wordpress.org/reference/functions/add_meta_box/. Documentation copied directly from WP's core method.
	 *
	 * @param string                 $id       Meta box ID (used in the 'id' attribute for the meta box).
	 * @param string                 $title    Title of the meta box.
	 * @param callable               $callback Function that fills the box with the desired content. The function should echo its output.
	 * @param string|array|WP_Screen $screen   Optional. The screen or screens on which to show the box (such as a post type, 'link', or 'comment').
	 *                                         Accepts a single screen ID, WP_Screen object, or array of screen IDs. Default is the current screen.
	 *                                         If you have used add_menu_page() or add_submenu_page() to create a new screen (and hence screen_id),
	 *                                         make sure your menu slug conforms to the limits of sanitize_key() otherwise the 'screen' menu may not correctly render on your page.
	 * @param string                 $context  Optional. The context within the screen where the boxes should display. Available contexts vary from screen to screen.
	 *                                         Post edit screen contexts include 'normal', 'side', and 'advanced'. Comments screen contexts include 'normal' and 'side'.
	 *                                         Menus meta boxes (accordion sections) all use the 'side' context. Global default is 'advanced'.
	 * @param string                 $priority Optional. The priority within the context where the boxes should show ('high', 'low'). Default 'default'.
	 * @param array                  $args     Optional. Data that should be set as the $args property of the box array (which is the second parameter passed to your callback). Default null.
	 */
	protected function add_meta_box( $id, $title, $callback, $screen, $context, $priority, $args ) {
		$this->lpf_boxes[ $id ] = true;

		add_meta_box( $id, $title, $callback, $screen, $context, $priority, $args );

		$this->maybe_remove_meta_boxes();
	}

	/**
	 * Maybe remove metaboxes from post types.
	 */
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
