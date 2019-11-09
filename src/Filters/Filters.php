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
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 */
class Filters implements Service {

	/**
	 * Register the Admin Page.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_filter( 'lpf_the_content', function( $content ) {
			return $this->the_content( $content );
		} );
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
	protected function the_content( $content ) {
		$content = function_exists( 'do_blocks' ) ? do_blocks( $content ) : $content;
		$content = capital_P_dangit( $content );
		$content = wptexturize( $content );
		$content = convert_smilies( $content );
		$content = wpautop( $content );
		$content = shortcode_unautop( $content );
		$content = prepend_attachment( $content );
		$content = wp_make_content_images_responsive( $content );
		$content = do_shortcode( $content );
		$embed   = new \WP_Embed();
		$content = $embed->autoembed( $content );

		return $content;
	}
}
