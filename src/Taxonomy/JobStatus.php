<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Taxonomy;

use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\Roles\Capabilities;

/**
 * Class Status
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class JobStatus extends BaseTaxonomy {

	const SLUG            = 'job_status';
	const ACTIVE_STATUS   = 'active';
	const INACTIVE_STATUS = 'inactive';

	/**
	 * Register the WordPress hooks.
	 *
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();
		add_action( 'init', [ $this, 'default_terms' ] );
	}

	/**
	 * Get the arguments that configure the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_arguments() {
		return [
			'hierarchical'       => false,
			'public'             => false,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'show_admin_column'  => true,
			'show_in_quick_edit' => false,
			'query_var'          => true,
			'show_in_rest'       => true,
			'meta_box_cb'        => [ $this, 'meta_box_cb' ],
			'rewrite'            => [
				'slug' => 'status',
			],
			'capabilities'       => [
				'manage_terms' => Capabilities::MANAGE_JOB_STATUS,
				'edit_terms'   => Capabilities::MANAGE_JOB_STATUS,
				'delete_terms' => Capabilities::MANAGE_JOB_STATUS,
				'assign_terms' => Capabilities::EDIT_JOBS,
			],
			'labels'             => [
				'name'                       => __( 'Status', 'level-playing-field' ),
				'singular_name'              => _x( 'Status', 'taxonomy general name', 'level-playing-field' ),
				'search_items'               => __( 'Search Statuses', 'level-playing-field' ),
				'popular_items'              => __( 'Popular Statuses', 'level-playing-field' ),
				'all_items'                  => __( 'All Statuses', 'level-playing-field' ),
				'parent_item'                => __( 'Parent Status', 'level-playing-field' ),
				'parent_item_colon'          => __( 'Parent Status:', 'level-playing-field' ),
				'edit_item'                  => __( 'Edit Status', 'level-playing-field' ),
				'update_item'                => __( 'Update Status', 'level-playing-field' ),
				'add_new_item'               => __( 'New Status', 'level-playing-field' ),
				'new_item_name'              => __( 'New Status', 'level-playing-field' ),
				'separate_items_with_commas' => __( 'Separate Statuses with commas', 'level-playing-field' ),
				'add_or_remove_items'        => __( 'Add or remove Statuses', 'level-playing-field' ),
				'choose_from_most_used'      => __( 'Choose from the most used Statuses', 'level-playing-field' ),
				'not_found'                  => __( 'No Statuses found.', 'level-playing-field' ),
				'menu_name'                  => __( 'Statuses', 'level-playing-field' ),
			],
		];
	}

	/**
	 * Get the object type(s) to use when registering the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_object_types() {
		return [
			JobManager::SLUG,
		];
	}

	/**
	 * Set up default terms for the taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function default_terms() {
		$terms = [
			'Inactive' => [
				'description' => __( 'Job is inactive', 'level-playing-field' ),
				'slug'        => self::INACTIVE_STATUS,
			],
			'Active'   => [
				'description' => __( 'Job is active', 'level-playing-field' ),
				'slug'        => self::ACTIVE_STATUS,
			],
		];

		foreach ( $terms as $term => $args ) {
			if ( ! term_exists( $term, $this->get_slug() ) ) {
				wp_insert_term( $term, $this->get_slug(), $args );
			}
		}
	}

	/**
	 * Custom metabox callback for this taxonomy.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post The current post object.
	 */
	public function meta_box_cb( $post ) {
		$tax_name = $this->get_slug();
		$taxonomy = get_taxonomy( $tax_name );
		?>
		<div class="tagsdiv">
			<?php
			do_action( "{$tax_name}_metabox_before" ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals
			if ( current_user_can( $taxonomy->cap->assign_terms ) ) {
				$this->term_select( $post );
			}
			// todo: alternate display for user who can't assign terms.
			?>
		</div>
		<?php
	}

	/**
	 * Create term selection drop-down.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Post $post The current post object.
	 */
	protected function term_select( $post ) {
		$tax_name  = $this->get_slug();
		$taxonomy  = get_taxonomy( $tax_name );
		$all_terms = get_terms( [
			'taxonomy'   => $tax_name,
			'hide_empty' => false,
			'orderby'    => 'term_id',
		] );

		$post_terms = get_the_terms( $post->ID, $tax_name );
		$post_terms = $post_terms ? wp_list_pluck( $post_terms, 'term_id', 'slug' ) : [];
		?>
		<label for="<?php echo esc_attr( $tax_name ); ?>">
			<?php echo esc_html( $taxonomy->labels->update_item ); ?>
		</label>
		<select name="tax_input[<?php echo esc_attr( $tax_name ); ?>]"
				id="tax_input[<?php echo esc_attr( $tax_name ); ?>]"
				title="<?php echo esc_attr( $taxonomy->labels->update_item ); ?>">
			<?php
			/** @var \WP_Term $term */
			foreach ( $all_terms as $term ) {
				?>
				<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( array_key_exists( $term->slug, $post_terms ) ); ?>>
					<?php echo esc_html( $term->name ); ?>
				</option>
				<?php
			}
			?>
		</select>
		<?php
	}
}
