<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\ListTable;

use YIKES\LevelPlayingField\AdminPage\ExportApplicantsPage as ExportApplicantsPage;
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantManagerCPT;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

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
	const JS_DEPENDENCIES = [ 'jquery' ];
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
				'export_url' => add_query_arg( [
					'page'      => ExportApplicantsPage::PAGE_SLUG,
					'post_type' => ExportApplicantsPage::POST_TYPE,
				] ),
				'strings'    => [
					'export_button_text' => __( 'Export', 'yikes-level-playing-field' ),
				],
			]
		);

		return [
			$script,
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
	 * Output custom dropdowns for filtering.
	 *
	 * @since %VERSION%
	 *
	 * @param string $which The location of the extra table nav markup: 'top' or 'bottom' for WP_Posts_List_Table, 'bar' for WP_Media_List_Table.
	 */
	protected function create_custom_dropdowns( $which ) {
		if ( 'top' === $which ) {
			$this->applicant_status_dropdown_filter();
		}
	}

	/**
	 * Output a custom dropdown for the applicant_status taxonomy.
	 *
	 * @since %VERSION%
	 */
	protected function applicant_status_dropdown_filter() {
		$taxonomy = get_taxonomy( ApplicantStatus::SLUG );

		// Make sure we have the taxonomy.
		if ( ! is_object( $taxonomy ) ) {
			return;
		}

		$dropdown_options = [
			'show_option_all' => $taxonomy->labels->all_items,
			'hide_empty'      => false,
			'hierarchical'    => $taxonomy->hierarchical,
			'show_count'      => false,
			'orderby'         => 'name',
			'selected'        => get_query_var( ApplicantStatus::SLUG ),
			'name'            => ApplicantStatus::SLUG,
			'taxonomy'        => ApplicantStatus::SLUG,
			'value_field'     => 'slug',
		];

		printf(
			'<label class="screen-reader-text" for="%1$s">%2$s</label>',
			esc_attr( ApplicantStatus::SLUG ),
			esc_html( $taxonomy->labels->filter_items_list )
		);

		wp_dropdown_categories( $dropdown_options );
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
