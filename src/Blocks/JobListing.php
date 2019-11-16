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
use Yikes\LevelPlayingField\Shortcode\Job as JobShortcode;

/**
 * Class JobDescription
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class JobListing extends BaseBlock {

	use PluginHelper;

	const BLOCK_SLUG = 'job-listing';

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
			'lpf_job_listing_data',
			[
				'block_name'    => $this->get_block_slug(),
				'jobs_slug'     => JobManager::SLUG,
				'edit_jobs_url' => add_query_arg( [ 'action' => 'edit' ], admin_url( 'post.php' ) ),
				'attributes'    => $this->get_attributes(),
			]
		);

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
		return __( 'Job Listing', 'level-playing-field' );
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
		$shortcode_atts = ( new JobShortcode() )->get_default_atts();

		return [
			'job_id'                  => [
				'type'    => 'string',
				'default' => $shortcode_atts['id'],
			],
			'show_title'              => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_title'],
			],
			'show_description'        => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_description'],
			],
			'show_job_type'           => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_job_type'],
			],
			'show_application_button' => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_application_button'],
			],
			'show_location'           => [
				'type'    => 'boolean',
				'default' => $shortcode_atts['show_location'],
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
			'button_text'             => [
				'type'    => 'string',
				'default' => $shortcode_atts['button_text'],
			],
			'description_text'        => [
				'type'    => 'string',
				'default' => $shortcode_atts['description_text'],
			],
			'details_text'            => [
				'type'    => 'string',
				'default' => $shortcode_atts['details_text'],
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
			'[%s id="%s" show_title="%s" show_description="%s" show_job_type="%s" show_application_button="%s" show_location="%s" description_text="%s" details_text="%s" job_type_text="%s" location_text="%s" remote_location_text="%s" button_text="%s"]',
			esc_attr( JobShortcode::TAG ),
			esc_attr( $attributes['job_id'] ),
			esc_attr( $attributes['show_title'] ),
			esc_attr( $attributes['show_description'] ),
			esc_attr( $attributes['show_job_type'] ),
			esc_attr( $attributes['show_application_button'] ),
			esc_attr( $attributes['show_location'] ),
			esc_attr( $attributes['description_text'] ),
			esc_attr( $attributes['details_text'] ),
			esc_attr( $attributes['job_type_text'] ),
			esc_attr( $attributes['location_text'] ),
			esc_attr( $attributes['remote_location_text'] ),
			esc_attr( $attributes['button_text'] )
		);
	}
}
