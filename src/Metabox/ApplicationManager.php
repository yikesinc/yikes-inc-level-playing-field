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
		$application_boxes = [
			'id'         => $this->prefix_field( 'metabox' ),
			'title'      => __( 'Basic Info', 'yikes-level-playing-field' ),
			'pages'      => [ ApplicationCPT::SLUG ],
			'show_names' => true,
			'group'      => true,
			'fields'     => [
				[
					'name'   => __( 'Basic Info', 'yikes-level-playing-field' ),
					'id'     => 'basic',
					'type'   => 'group',
					'fields' => [
						[
							'desc' => __( 'Check the fields you want included on this Application Form', 'yikes-level-playing-field' ),
							'type' => 'message',
						],
						[
							'name' => __( 'Basic Info', 'yikes-level-playing-field' ),
							'type' => 'title',
						],
						[
							'name'  => __( 'Name (required)', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'name' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
						[
							'name'  => __( 'Email Address (required)', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'email' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
						[
							'name'  => __( 'Phone Number', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'phone' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
						[
							'name'  => __( 'Address', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'address' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
						[
							'name'  => __( 'Cover Letter', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'cover_letter' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
					],
				],
				[
					'name'   => __( 'Education', 'yikes-level-playing-field' ),
					'id'     => 'education',
					'type'   => 'group',
					'fields' => [
						[
							'name'  => __( 'Schooling', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'schooling' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
						[
							'desc' => $this->get_schooling_html(),
							'type' => 'message',
						],
						[
							'name'  => __( 'Certifications', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'certifications' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
						[
							'desc' => $this->get_certification_html(),
							'type' => 'message',
						],
					],
				],
				[
					'name'   => __( 'Skills', 'yikes-level-playing-field' ),
					'id'     => 'skills',
					'type'   => 'group',
					'fields' => [
						[
							'name'  => __( 'Skill and Proficiency', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'skills' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
					],
				],
				[
					'name'   => __( 'Languages', 'yikes-level-playing-field' ),
					'id'     => 'languages',
					'type'   => 'group',
					'fields' => [
						[
							'name'  => __( 'Language and Proficiency', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'languages' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
					],
				],
				[
					'name'   => __( 'Experience', 'yikes-level-playing-field' ),
					'id'     => 'experience',
					'type'   => 'group',
					'fields' => [
						[
							'name'  => __( 'Experience', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'experience' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
						[
							'desc' => $this->get_experience_html(),
							'type' => 'message',
						],
					],
				],
				[
					'name'   => __( 'Volunteer Work', 'yikes-level-playing-field' ),
					'id'     => 'volunteer',
					'type'   => 'group',
					'fields' => [
						[
							'name'  => __( 'Volunteer Work', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( 'volunteer' ),
							'type'  => 'checkbox',
							'value' => 1,
						],
						[
							'desc' => $this->get_volunteer_html(),
							'type' => 'message',
						],
					],
				],
			],
		];

		/**
		 * Filter the Application Manager metabox fields.
		 *
		 * @param array $application_boxes The Application metabox array.
		 */
		$application_boxes = apply_filters( 'lpf_application_metabox_fields', $application_boxes );
		$meta_boxes[]      = $application_boxes;

		return $meta_boxes;
	}

	/**
	 * Get the HTML for the Schooling details.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_schooling_html() {
		ob_start();
		?>
		<ul class="lpf_details lpf_schooling">
			<li><?php esc_html_e( 'Institution', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Institution Type', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Graduation Year', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Major', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Degree', 'yikes-level-playing-field' ); ?></li>
		</ul>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get the HTML for the Certification details.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_certification_html() {
		ob_start();
		?>
		<ul class="lpf_details lpf_certification">
			<li><?php esc_html_e( 'Certifying Institution', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Year Certified', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Certification Type', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Certification Status', 'yikes-level-playing-field' ); ?></li>
		</ul>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get the HTML for the Experience details.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_experience_html() {
		ob_start();
		?>
		<ul class="lpf_details lpf_certification">
			<li><?php esc_html_e( 'Organization', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Industry', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Dates', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Position', 'yikes-level-playing-field' ); ?></li>
		</ul>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get the HTML for the Experience details.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_volunteer_html() {
		ob_start();
		?>
		<ul class="lpf_details lpf_volunteer">
			<li><?php esc_html_e( 'Organization', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Organization Type', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Dates', 'yikes-level-playing-field' ); ?></li>
			<li><?php esc_html_e( 'Position', 'yikes-level-playing-field' ); ?></li>
		</ul>
		<?php

		return ob_get_clean();
	}
}
