<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Blocks;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\PluginFactory;

/**
 * Class JobDescription
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class JobDescription extends BaseBlock {

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		$block = new ScriptAsset(
			'lpf-job-description',
			'assets/js/blocks/job-description/index',
			[
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			],
			filemtime( PluginFactory::create()->get_plugin_root() . '/blocks/job-description/index.js' )
		);

		// todo: add styles.
		return [
			$block,
		];
	}

	/**
	 * Get the slug for use with registering the block.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_block_slug() {
		return static::SLUG_BASE . 'job-description';
	}

	/**
	 * Get the arguments used when registering a block.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_block_args() {
		return [
			'editor_script' => 'lpf-job-description',
			'category'      => 'ylpf-job',
			'title'         => __( 'Job Description', 'yikes-level-playing-field' ),
		];
	}
}
