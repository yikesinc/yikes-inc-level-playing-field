<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Taxonomy;

use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

/**
 * Trait JobDropdown
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
trait ApplicantStatusDropdown {

	/**
	 * Output a custom dropdown for the applicant_status taxonomy.
	 *
	 * @since 1.0.0
	 */
	protected function applicant_status_dropdown() {
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
			'echo'            => 1,
		];

		printf(
			'<label class="screen-reader-text" for="%1$s">%2$s</label>',
			esc_attr( ApplicantStatus::SLUG ),
			esc_html__( 'Filter Applicant Statuses', 'level-playing-field' )
		);

		wp_dropdown_categories( $dropdown_options );
	}
}
