<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager as ApplicationCPT;
use Yikes\LevelPlayingField\Model\ApplicationMeta;

/**
 * Class ApplicationManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class ApplicationManager extends AwesomeBaseMetabox implements AssetsAware {

	use AssetsAwareness;

	const CSS_HANDLE = 'lpf-admin-applications-css';
	const CSS_URI    = 'assets/css/lpf-applications-admin';
	const JS_HANDLE  = 'lpf-application-manager-js';
	const JS_URI     = 'assets/js/application-manager';

	/**
	 * Register hooks.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();
		$this->register_assets();

		add_action( 'add_meta_boxes_' . ApplicationCPT::SLUG, function () {
			$this->enqueue_assets();
		} );
	}

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
			'show_names' => false,
			'group'      => true,
			'fields'     => [
				[
					'name'   => __( 'Basic Info', 'yikes-level-playing-field' ),
					'id'     => 'basic',
					'type'   => 'group',
					'fields' => [
						[
							'name' => __( 'Basic Information', 'yikes-level-playing-field' ),
							'desc' => __( 'Check the basic information fields you want included on this Application Form.', 'yikes-level-playing-field' ),
							'id'   => $this->prefix_field( 'info_message' ),
							'type' => 'title',
						],
						[
							'name'       => __( 'Name', 'yikes-level-playing-field' ),
							'desc'       => __( 'Name', 'yikes-level-playing-field' ),
							'id'         => $this->prefix_field( ApplicationMeta::NAME ),
							'type'       => 'checkbox',
							'value'      => 1,
							'desc_type'  => 'inline',
							'attributes' => [
								'checked'  => 'checked',
								'disabled' => 'disabled',
							],
						],
						[
							'name'       => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'       => __( 'Required?', 'yikes-level-playing-field' ),
							'id'         => $this->prefix_field( ApplicationMeta::NAME . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'       => 'toggle',
							'value'      => 1,
							'desc_type'  => 'inline',
							'attributes' => [
								'checked'  => 'checked',
								'disabled' => 'disabled',
							],
						],
						[
							'name'       => __( 'Email Address', 'yikes-level-playing-field' ),
							'desc'       => __( 'Email Address', 'yikes-level-playing-field' ),
							'id'         => $this->prefix_field( ApplicationMeta::EMAIL ),
							'type'       => 'checkbox',
							'value'      => 1,
							'desc_type'  => 'inline',
							'attributes' => [
								'checked'  => 'checked',
								'disabled' => 'disabled',
							],
						],
						[
							'name'       => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'       => __( 'Required?', 'yikes-level-playing-field' ),
							'id'         => $this->prefix_field( ApplicationMeta::EMAIL . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'       => 'toggle',
							'value'      => 1,
							'desc_type'  => 'inline',
							'attributes' => [
								'checked'  => 'checked',
								'disabled' => 'disabled',
							],
						],
						[
							'name'      => __( 'Phone Number', 'yikes-level-playing-field' ),
							'desc'      => __( 'Phone Number', 'yikes-level-playing-field' ),
							'id'        => $this->prefix_field( ApplicationMeta::PHONE ),
							'type'      => 'checkbox',
							'value'     => 1,
							'desc_type' => 'inline',
						],
						[
							'name'  => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'  => __( 'Required?', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( ApplicationMeta::PHONE . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'  => 'toggle',
							'value' => 1,
						],
						[
							'name'      => __( 'Address', 'yikes-level-playing-field' ),
							'desc'      => __( 'Address', 'yikes-level-playing-field' ),
							'id'        => $this->prefix_field( ApplicationMeta::ADDRESS ),
							'type'      => 'checkbox',
							'value'     => 1,
							'desc_type' => 'inline',
						],
						[
							'name'  => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'  => __( 'Required?', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( ApplicationMeta::ADDRESS . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'  => 'toggle',
							'value' => 1,
						],
					],
				],
				[
					'name'   => __( 'Education', 'yikes-level-playing-field' ),
					'id'     => ApplicationMeta::EDUCATION,
					'type'   => 'group',
					'fields' => [
						[
							'name' => __( 'Educational Background', 'yikes-level-playing-field' ),
							'desc' => __( 'Check the educational information you want included on this Application Form.', 'yikes-level-playing-field' ),
							'id'   => $this->prefix_field( ApplicationMeta::EDUCATION . '_message' ),
							'type' => 'title',
						],
						[
							'name'       => __( 'Schooling', 'yikes-level-playing-field' ),
							'desc'       => __( 'Schooling', 'yikes-level-playing-field' ),
							'id'         => $this->prefix_field( ApplicationMeta::SCHOOLING ),
							'type'       => 'checkbox',
							'value'      => 1,
							'desc_type'  => 'inline',
							'attributes' => [
								'class'        => [ 'yks_extra_info' ],
								'data-section' => 'lpf_schooling',
							],
						],
						[
							'name'  => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'  => __( 'Required?', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( ApplicationMeta::SCHOOLING . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'  => 'toggle',
							'value' => 1,
						],
						[
							'desc' => $this->get_schooling_html(),
							'id'   => $this->prefix_field( ApplicationMeta::SCHOOLING . '_message' ),
							'type' => 'message',
						],
						[
							'name'       => __( 'Certifications', 'yikes-level-playing-field' ),
							'desc'       => __( 'Certifications', 'yikes-level-playing-field' ),
							'id'         => $this->prefix_field( ApplicationMeta::CERTIFICATIONS ),
							'type'       => 'checkbox',
							'value'      => 1,
							'desc_type'  => 'inline',
							'attributes' => [
								'class'        => [ 'yks_extra_info' ],
								'data-section' => 'lpf_certification',
							],
						],
						[
							'name'  => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'  => __( 'Required?', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( ApplicationMeta::CERTIFICATIONS . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'  => 'toggle',
							'value' => 1,
						],
						[
							'desc' => $this->get_certification_html(),
							'id'   => $this->prefix_field( ApplicationMeta::CERTIFICATIONS . '_message' ),
							'type' => 'message',
						],
					],
				],
				[
					'name'   => __( 'Skills', 'yikes-level-playing-field' ),
					'id'     => ApplicationMeta::SKILLS,
					'type'   => 'group',
					'fields' => [
						[
							'name' => __( 'Skills', 'yikes-level-playing-field' ),
							'desc' => __( 'Check if you want Skills and Proficiency included on this Application Form.', 'yikes-level-playing-field' ),
							'id'   => $this->prefix_field( ApplicationMeta::SKILLS . '_message' ),
							'type' => 'title',
						],
						[
							'name'      => __( 'Skill and Proficiency', 'yikes-level-playing-field' ),
							'desc'      => __( 'Skill and Proficiency', 'yikes-level-playing-field' ),
							'id'        => $this->prefix_field( ApplicationMeta::SKILLS ),
							'type'      => 'checkbox',
							'value'     => 1,
							'desc_type' => 'inline',
						],
						[
							'name'  => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'  => __( 'Required?', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( ApplicationMeta::SKILLS . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'  => 'toggle',
							'value' => 1,
						],
					],
				],
				[
					'name'   => __( 'Languages', 'yikes-level-playing-field' ),
					'id'     => ApplicationMeta::LANGUAGES,
					'type'   => 'group',
					'fields' => [
						[
							'name' => __( 'Languages', 'yikes-level-playing-field' ),
							'desc' => __( 'Check if you want Languages and Proficiency included on this Application Form.', 'yikes-level-playing-field' ),
							'id'   => $this->prefix_field( ApplicationMeta::LANGUAGES . '_message' ),
							'type' => 'title',
						],
						[
							'name'      => __( 'Language and Proficiency', 'yikes-level-playing-field' ),
							'desc'      => __( 'Language and Proficiency', 'yikes-level-playing-field' ),
							'id'        => $this->prefix_field( ApplicationMeta::LANGUAGES ),
							'type'      => 'checkbox',
							'value'     => 1,
							'desc_type' => 'inline',
						],
						[
							'name'  => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'  => __( 'Required?', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( ApplicationMeta::LANGUAGES . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'  => 'toggle',
							'value' => 1,
						],
					],
				],
				[
					'name'   => __( 'Experience', 'yikes-level-playing-field' ),
					'id'     => ApplicationMeta::EXPERIENCE,
					'type'   => 'group',
					'fields' => [
						[
							'name' => __( 'Experience', 'yikes-level-playing-field' ),
							'desc' => __( 'Check if you want Experience included on this Application Form.', 'yikes-level-playing-field' ),
							'id'   => $this->prefix_field( ApplicationMeta::EXPERIENCE . '_message' ),
							'type' => 'title',
						],
						[
							'name'       => __( 'Experience', 'yikes-level-playing-field' ),
							'desc'       => __( 'Experience', 'yikes-level-playing-field' ),
							'id'         => $this->prefix_field( ApplicationMeta::EXPERIENCE ),
							'type'       => 'checkbox',
							'value'      => 1,
							'desc_type'  => 'inline',
							'attributes' => [
								'class'        => [ 'yks_extra_info' ],
								'data-section' => 'lpf_experience',
							],
						],
						[
							'name'  => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'  => __( 'Required?', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( ApplicationMeta::EXPERIENCE . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'  => 'toggle',
							'value' => 1,
						],
						[
							'desc' => $this->get_experience_html(),
							'id'   => $this->prefix_field( ApplicationMeta::EXPERIENCE . '_message' ),
							'type' => 'message',
						],
					],
				],
				[
					'name'   => __( 'Volunteer Work', 'yikes-level-playing-field' ),
					'id'     => ApplicationMeta::VOLUNTEER,
					'type'   => 'group',
					'fields' => [
						[
							'name' => __( 'Volunteer Work', 'yikes-level-playing-field' ),
							'desc' => __( 'Check if you want Volunteer Work included on this Application Form.', 'yikes-level-playing-field' ),
							'id'   => $this->prefix_field( ApplicationMeta::VOLUNTEER . '_message' ),
							'type' => 'title',
						],
						[
							'name'       => __( 'Volunteer Work', 'yikes-level-playing-field' ),
							'desc'       => __( 'Volunteer Work', 'yikes-level-playing-field' ),
							'id'         => $this->prefix_field( ApplicationMeta::VOLUNTEER ),
							'type'       => 'checkbox',
							'value'      => 1,
							'desc_type'  => 'inline',
							'attributes' => [
								'class'        => [ 'yks_extra_info' ],
								'data-section' => 'lpf_volunteer',
							],
						],
						[
							'name'  => __( 'Required?', 'yikes-level-playing-field' ),
							'desc'  => __( 'Required?', 'yikes-level-playing-field' ),
							'id'    => $this->prefix_field( ApplicationMeta::VOLUNTEER . ApplicationMeta::REQUIRED_SUFFIX ),
							'type'  => 'toggle',
							'value' => 1,
						],
						[
							'desc' => $this->get_volunteer_html(),
							'id'   => $this->prefix_field( ApplicationMeta::VOLUNTEER . '_message' ),
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
			<li><?php esc_html_e( 'Certifying Institution Type', 'yikes-level-playing-field' ); ?></li>
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
		<ul class="lpf_details lpf_experience">
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

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 * @return Asset[]
	 */
	protected function get_assets() {
		return [
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
			new ScriptAsset( self::JS_HANDLE, self::JS_URI ),
		];
	}
}
