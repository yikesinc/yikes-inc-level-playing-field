<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Assets;

use Yikes\LevelPlayingField\PluginFactory;

/**
 * Class MediaAsset.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Assets
 * @author  Ebonie Butler
 */
final class MediaAsset {

	const IMG_ASSETS_DIR = '/assets/images/';

	const MEDIA_ALL = 'all';

	/**
	 * Get internal image URL.
	 *
	 * @param string $filename filename of image.
	 *
	 * @return string $file_url
	 */
	public function get_image( string $filename = '' ) {
		$file_url = plugins_url( self::IMG_ASSETS_DIR, dirname( __FILE__, 2 ) ) . $filename;

		// Build absolute path to file to confirm file exists.
		$file_path = PluginFactory::create()->get_plugin_root() . self::IMG_ASSETS_DIR . $filename;

		if ( ! file_exists( $file_path ) ) {
			echo '<p><strong>Image Not Found</strong></p>';
			return;
		}
		return $file_url;
	}
}
