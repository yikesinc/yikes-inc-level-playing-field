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
use Yikes\LevelPlayingField\PluginHelpers;
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;
use Yikes\LevelPlayingField\Shortcode\AllJobs as JobsShortcode;

/**
 * Class JobDescription
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class JobListings extends BaseBlock {

	use PluginHelpers;

	const BLOCK_SLUG = 'job-listings';
	const CATEGORY   = 'widgets';

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
			'lpf_job_listings_data',
			[
				'block_name'          => $this->get_block_slug(),
				'jobs_slug'           => JobManager::SLUG,
				'edit_jobs_url'       => add_query_arg( [ 'action' => 'edit' ], admin_url( 'post.php' ) ),
				'attributes'          => $this->get_attributes(),
				'job_categories_slug' => JobCategory::SLUG,
			]
		);

		// todo: add styles.
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
		return __( 'Job Listings', 'yikes-level-playing-field' );
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
		$shortcode_atts = ( new JobsShortcode() )->get_default_atts();

		return [
			'limit'                   => [
				'type'    => 'string',
				'default' => $shortcode_atts['limit'],
			],
			'show_application_button' => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_application_button'],
			],
			'button_text'             => [
				'type'    => 'string',
				'default' => $shortcode_atts['button_text'],
			],
			'orderby'                 => [
				'type'    => 'string',
				'default' => $shortcode_atts['orderby'],
			],
			'order'                   => [
				'type'    => 'string',
				'default' => $shortcode_atts['order'],
			],
			'exclude'                 => [
				'type'    => 'object',
				'default' => $shortcode_atts['exclude'],
			],
			'cat_exclude_ids'         => [
				'type'    => 'object',
				'default' => $shortcode_atts['cat_exclude_ids'],
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

		if ( empty( $attributes['limit'] ) ) {
			return '';
		}

		// We want to run the shortcode directly but we need to return the plaintext shortcode or Gutenberg will autop() the shortcode content.
		return sprintf(
			'[%s limit="%s" order="%s" orderby="%s" exclude="%s" cat_exclude_ids="%s" show_application_button="%s" button_text="%s"]',
			esc_attr( JobsShortcode::TAG ),
			esc_attr( $attributes['limit'] ),
			esc_attr( $attributes['order'] ),
			esc_attr( $attributes['orderby'] ),
			esc_attr( implode( ',', $attributes['exclude'] ) ),
			esc_attr( implode( ',', $attributes['cat_exclude_ids'] ) ),
			esc_attr( $attributes['show_application_button'] ),
			esc_attr( $attributes['button_text'] )
		);
	}
}
