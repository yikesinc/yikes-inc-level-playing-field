<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * All media assets should be defined as CONSTs in this class prior to usage.
 * Sample usage: <img src="<?php echo ( new MediaAsset() )->get_image( MediaAsset::BANNER ); ?>">
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
	const IMG_NOT_FOUND  = '';

	const BANNER = 'banner-1544x500-rtl.png';

	/**
	 * Get internal image URL.
	 *
	 * @param string $filename filename of image.
	 *
	 * @return string $file_url
	 */
	public function get_image( string $filename ) {
		$file_url = PluginFactory::create()->get_plugin_url() . self::IMG_ASSETS_DIR . $filename;

		// Build absolute path to file to confirm file exists.
		$file_path = PluginFactory::create()->get_plugin_root() . self::IMG_ASSETS_DIR . $filename;

		if ( ! file_exists( $file_path ) ) {
			return IMG_NOT_FOUND;
		}
		return $file_url;
	}
}
