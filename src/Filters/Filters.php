<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Filters;

use Yikes\LevelPlayingField\Service;

/**
 * Class Filters.
 *
 * Define filters to be used throughout the plugin.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 */
class Filters implements Service {

	/**
	 * Register the Admin Page.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_filter( 'lpf_the_content', function( $content ) {
			return $this->lpf_the_content( $content );
		});
	}

	/**
	 * Run content through the functions that WordPress' uses in `the_content` filter.
	 *
	 * `the_content` filter is needed to format WYSIWYG & Block content. However, a lot of plugins/themes hijack this filter, including us.
	 * This function provides the same basic formatting functionality while avoiding the pitfalls of the filter.
	 *
	 * @param string $content Content.
	 *
	 * @return string $content Content.
	 */
	protected function lpf_the_content( $content ) {
		$content = function_exists( 'do_blocks' ) ? do_blocks( $content ) : $content;
		$content = function_exists( 'capital_P_dangit' ) ? capital_P_dangit( $content ) : $content;
		$content = function_exists( 'wptexturize' ) ? wptexturize( $content ) : $content;
		$content = function_exists( 'convert_smilies' ) ? convert_smilies( $content ) : $content;
		$content = function_exists( 'wpautop' ) ? wpautop( $content ) : $content;
		$content = function_exists( 'shortcode_unautop' ) ? shortcode_unautop( $content ) : $content;
		$content = function_exists( 'prepend_attachment' ) ? prepend_attachment( $content ) : $content;
		$content = function_exists( 'wp_make_content_images_responsive' ) ? wp_make_content_images_responsive( $content ) : $content;
		$content = function_exists( 'do_shortcode' ) ? do_shortcode( $content ) : $content;

		if ( class_exists( 'WP_Embed' ) ) {

			// Deal with URLs.
			$embed   = new \WP_Embed();
			$content = method_exists( $embed, 'autoembed' ) ? $embed->autoembed( $content ) : $content;
		}

		return $content;
	}
}
