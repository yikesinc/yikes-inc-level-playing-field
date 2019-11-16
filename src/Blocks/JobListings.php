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
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Taxonomy\JobCategory;
use Yikes\LevelPlayingField\Taxonomy\JobStatus;
use Yikes\LevelPlayingField\Shortcode\AllJobs as JobsShortcode;

/**
 * Class JobDescription
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class JobListings extends BaseBlock {

	use PluginHelper;

	const BLOCK_SLUG = 'job-listings';

	/**
	 * Load asset objects for use.
	 *
	 * @since 1.0.0
	 */
	protected function load_assets() {
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
				'block_name'             => $this->get_block_slug(),
				'jobs_slug'              => JobManager::SLUG,
				'edit_jobs_url'          => add_query_arg( [ 'action' => 'edit' ], admin_url( 'post.php' ) ),
				'attributes'             => $this->get_attributes(),
				'job_categories_slug'    => JobCategory::SLUG,
				'job_status_slug'        => JobStatus::SLUG,
				'job_status_active_slug' => JobStatus::ACTIVE_STATUS,
			]
		);

		// todo: add styles.
		$this->assets = [
			$block_script,
		];
	}

	/**
	 * Get the block's title, i18n'ed.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_title() {
		return __( 'Job Listings', 'level-playing-field' );
	}

	/**
	 * Get the attributes for a block.
	 *
	 * Note: if you don't set the default attributes on the server side, the defaults won't be available when rendering (i.e. in the `render_block()` function).
	 *
	 * @since 1.0.0
	 * @return array
	 */
	protected function get_attributes() {
		$shortcode_atts = ( new JobsShortcode() )->get_default_atts();

		return [
			'limit'                   => [
				'type'    => 'string',
				'default' => $shortcode_atts['limit'],
			],
			'show_desc'               => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_desc'],
			],
			'desc_type'               => [
				'type'    => 'string',
				'default' => $shortcode_atts['desc_type'],
			],
			'show_details'            => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_details'],
			],
			'details_text'            => [
				'type'    => 'string',
				'default' => $shortcode_atts['details_text'],
			],
			'job_type_text'           => [
				'type'    => 'string',
				'default' => $shortcode_atts['job_type_text'],
			],
			'location_text'           => [
				'type'    => 'string',
				'default' => $shortcode_atts['location_text'],
			],
			'remote_location_text'    => [
				'type'    => 'string',
				'default' => $shortcode_atts['remote_location_text'],
			],
			'show_application_button' => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_application_button'],
			],
			'button_text'             => [
				'type'    => 'string',
				'default' => $shortcode_atts['button_text'],
			],
			'grouped_by_cat'          => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['grouped_by_cat'],
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
			'[%s limit="%s" show_desc="%s" desc_type="%s" show_details="%s" details_text="%s" job_type_text="%s" location_text="%s" remote_location_text="%s" grouped_by_cat="%s" order="%s" orderby="%s" exclude="%s" cat_exclude_ids="%s" show_application_button="%s" button_text="%s"]',
			esc_attr( JobsShortcode::TAG ),
			esc_attr( $attributes['limit'] ),
			esc_attr( $attributes['show_desc'] ),
			esc_attr( $attributes['desc_type'] ),
			esc_attr( $attributes['show_details'] ),
			esc_attr( $attributes['details_text'] ),
			esc_attr( $attributes['job_type_text'] ),
			esc_attr( $attributes['location_text'] ),
			esc_attr( $attributes['remote_location_text'] ),
			esc_attr( $attributes['grouped_by_cat'] ),
			esc_attr( $attributes['order'] ),
			esc_attr( $attributes['orderby'] ),
			esc_attr( implode( ',', $attributes['exclude'] ) ),
			esc_attr( implode( ',', $attributes['cat_exclude_ids'] ) ),
			esc_attr( $attributes['show_application_button'] ),
			esc_attr( $attributes['button_text'] )
		);
	}
}
