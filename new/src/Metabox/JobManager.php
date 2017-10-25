<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\CustomPostType\JobManager as JobManagerCPT;
use Yikes\LevelPlayingField\Model\JobMeta;

/**
 * Class JobManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class JobManager extends AwesomeBaseMetabox {

	/**
	 * Get the prefix for use with meta fields.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	public function get_prefix() {
		return JobMeta::META_PREFIX;
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
		$meta_boxes[] = array(
			'id'     => $this->prefix_field( 'metabox' ),
			'title'  => __( 'General Info', 'yikes-level-playing-field' ),
			'pages'  => array( JobManagerCPT::SLUG ),
			'fields' => array(
				array(
					'name'    => __( 'Status', 'yikes-level-playing-field' ),
					'desc'    => __( 'The job status', 'yikes-level-playing-field' ),
					'id'      => $this->prefix_field( 'status' ),
					'type'    => 'select',
					'options' => array(
						array(
							'name'  => __( 'Active', 'yikes-level-playing-field' ),
							'value' => 'active',
						),
						array(
							'name'  => __( 'Inactive', 'yikes-level-playing-field' ),
							'value' => 'inactive',
						),
					),
				),
			),
		);

		return $meta_boxes;
	}
}
