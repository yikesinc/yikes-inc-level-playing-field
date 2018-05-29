<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\ListTable;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantManagerCPT;
use Yikes\LevelPlayingField\Model\ApplicantRepository;

/**
 * Class ApplicantManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicantManager extends BasePostType implements AssetsAware {

	use AssetsAwareness;

	const JS_HANDLE       = 'lpf-applicants-admin-script';
	const JS_URI          = 'assets/js/applicants-admin';
	const JS_DEPENDENCIES = array( 'jquery' );
	const JS_VERSION      = false;

	/**
	 * Register hooks.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();
		$this->register_assets();

		add_filter( 'admin_enqueue_scripts', function( $hook ) {

			// This filter should only run on an edit page. Make sure get_current_screen() exists.
			if ( 'edit.php' !== $hook || ! function_exists( 'get_current_screen' ) ) {
				return;
			}

			// Ensure this is a real screen object.
			$screen = get_current_screen();
			if ( ! ( $screen instanceof \WP_Screen ) ) {
				return;
			}

			// Ensure this is the edit screen for the correct post type.
			if ( $this->get_post_type() !== $screen->post_type ) {
				return;
			}

			$this->enqueue_assets();
		} );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {

		$script = new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER );
		
		$script->add_localization( 
			'applicant_admin', 
			[
				// @todo - need to implement a way connecting this to the ExportApplicantsPage constants - ExportApplicantsPage::POST_TYPE and ExportApplicantsPage::PAGE_SLUG;
				'export_url' => add_query_arg( array( 'page' => 'lpf-export-applicants', 'post_type' => 'jobs' )  ),
				'strings'    => [
					'export_button_text' => __( 'Export', 'yikes-level-playing-field' )
				]
			] 
		);

		return [
			$script
		];
	}

	/**
	 * Adjust the columns to display for the list table.
	 *
	 * @since %VERSION%
	 *
	 * @param array $original_columns The original columns.
	 *
	 * @return array
	 */
	public function columns( $original_columns ) {

		// @todo Decide whether we need custom columns for Applicants.
		return $original_columns;
	}

	/**
	 * Output the values for our custom columns.
	 *
	 * @since %VERSION%
	 *
	 * @param string $column_name The column slug.
	 * @param int    $post_id     The post ID.
	 */
	public function column_content( $column_name, $post_id ) {
		
		// @todo Decide whether we need custom column content for Applicants.
	}

	/**
	 * Get the post type.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_post_type() {
		return ApplicantManagerCPT::SLUG;
	}
}
