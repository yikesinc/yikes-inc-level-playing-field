<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\CustomPostType\ApplicationManager as ApplicationCPT;
use Yikes\LevelPlayingField\Model\ApplicationMeta;

/**
 * Class ApplicationManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicationManager extends AwesomeBaseMetabox {

	/**
	 * Get the prefix for use with meta fields.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_prefix() {
		return ApplicationMeta::META_PREFIX;
	}

	/**
	 * Register meta boxes.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 *
	 * @param array $meta_boxes Array of existing meta boxes.
	 *
	 * @return array The filtered meta boxes.
	 */
	public function register_boxes( $meta_boxes ) {
		$meta_boxes[] = [
			'id'     => $this->prefix_field( 'metabox' ),
			'title'  => __( 'Application Fields' ),
			'pages'  => [ ApplicationCPT::SLUG ],
			'fields' => [
				[
					'name'   => __( 'Basic Info' ),
					'type'   => 'group',
					'fields' => [
						[
							'name'  => __( 'Name (required)' ),
							'id'    => $this->prefix_field( 'name' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
					],
				],
			],
		];

		return $meta_boxes;
	}
}
