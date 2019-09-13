<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Blocks;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\BlockAsset;
use Yikes\LevelPlayingField\PluginHelper;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\Shortcode\Application as ApplicationShortcode;

/**
 * Class JobDescription
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class Application extends BaseBlock {

	use PluginHelper;

	const BLOCK_SLUG = 'application';

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		$block_script = new BlockAsset(
			static::BLOCK_SLUG,
			$this->get_block_path(),
			[
				'wp-blocks',
				'wp-i18n',
				'wp-element',
				'wp-editor',
			]
		);

		$block_script->add_localization(
			'lpf_application_data',
			[
				'block_name'    => $this->get_block_slug(),
				'jobs_slug'     => ApplicationManager::SLUG,
				'edit_jobs_url' => add_query_arg( [ 'action' => 'edit' ], admin_url( 'post.php' ) ),
				'attributes'    => $this->get_attributes(),
			]
		);

		return [
			$block_script,
		];
	}

	/**
	 * Get the block's title, i18n'ed.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_title() {
		return __( 'Application', 'yikes-level-playing-field' );
	}

	/**
	 * Get the attributes for a block.
	 *
	 * Note: if you don't set the default attributes on the server side, the defaults won't be available when rendering (i.e. in the `render_block()` function).
	 *
	 * @since %VERSION%
	 * @return array
	 */
	protected function get_attributes() {
		$shortcode_atts = ( new ApplicationShortcode() )->get_default_atts();

		return [
			'job_id'                  => [
				'type'    => 'string',
				'default' => $shortcode_atts['job_id'],
			],
		];
	}

	/**
	 * Take the shortcode parameters from the Gutenberg block and render our shortcode.
	 *
	 * @param array  $attributes Block attributes.
	 * @param string $content    Block content.
	 *
	 * @return string The rendered block content.
	 */
	public function render_block( $attributes, $content ) {

		if ( empty( $attributes['job_id'] ) ) {
			return '';
		}

		// We want to run the shortcode directly but we need to return the plaintext shortcode or Gutenberg will autop() the shortcode content.
		return sprintf(
			'[%s id="%s" job_id="%s"]',
			esc_attr( ApplicationShortcode::TAG ),
			esc_attr( $attributes['job_id'] )
		);
	}
}
