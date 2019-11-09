<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\RequiredPages;

/**
 * Class ApplicationFormPage
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class ApplicationFormPage extends BaseRequiredPage {

	const PAGE_SLUG      = 'lpf-application-page';
	const POST_TITLE     = 'Application Form';
	const POST_STATUS    = 'publish';
	const POST_TYPE      = 'page';
	const COMMENT_STATUS = 'closed';
	const PING_STATUS    = 'closed';
	const META_INPUT     = [ self::PAGE_SLUG => true ];

	/**
	 * Get the array of post attributes.
	 *
	 * @since 1.0.0
	 *
	 * @return array $post_array The array of post attributes.
	 */
	protected function get_post_array() {
		return [
			'post_title'     => static::POST_TITLE,
			'post_name'      => static::PAGE_SLUG,
			'post_status'    => static::POST_STATUS,
			'post_type'      => static::POST_TYPE,
			'comment_status' => static::COMMENT_STATUS,
			'ping_status'    => static::PING_STATUS,
			'meta_input'     => static::META_INPUT,
		];
	}

	/**
	 * Get the post state string.
	 *
	 * Note: this variable cannot be used as a class constant because it requires the string be run through a translation function.
	 *
	 * @since 1.0.0
	 *
	 * @return string $post_state The description for this post in the list table.
	 */
	protected function get_post_state() {
		return __( 'Level Playing Field\'s Application Form Page', 'level-playing-field' );
	}
}
