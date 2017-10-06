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

/**
 * Class Status
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class JobStatus extends BaseTaxonomy {

	const SLUG = 'job_status';

	/**
	 * Register the WordPress hooks.
	 *
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();
		add_action( 'init', array( $this, 'default_terms' ) );
	}

	/**
	 * Get the arguments that configure the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_arguments() {
		return array(
			'hierarchical'      => false,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'show_admin_column' => true,
			'query_var'         => true,
			'meta_box_cb'       => array( $this, 'meta_box_cb' ),
			'rewrite'           => array(
				'slug' => 'status',
			),
			'labels'            => array(
				'name'                       => __( 'Status', 'yikes-level-playing-field' ),
				'singular_name'              => _x( 'Status', 'taxonomy general name', 'yikes-level-playing-field' ),
				'search_items'               => __( 'Search Statuses', 'yikes-level-playing-field' ),
				'popular_items'              => __( 'Popular Statuses', 'yikes-level-playing-field' ),
				'all_items'                  => __( 'All Statuses', 'yikes-level-playing-field' ),
				'parent_item'                => __( 'Parent Status', 'yikes-level-playing-field' ),
				'parent_item_colon'          => __( 'Parent Status:', 'yikes-level-playing-field' ),
				'edit_item'                  => __( 'Edit Status', 'yikes-level-playing-field' ),
				'update_item'                => __( 'Update Status', 'yikes-level-playing-field' ),
				'add_new_item'               => __( 'New Status', 'yikes-level-playing-field' ),
				'new_item_name'              => __( 'New Status', 'yikes-level-playing-field' ),
				'separate_items_with_commas' => __( 'Separate Statuses with commas', 'yikes-level-playing-field' ),
				'add_or_remove_items'        => __( 'Add or remove Statuses', 'yikes-level-playing-field' ),
				'choose_from_most_used'      => __( 'Choose from the most used Statuses', 'yikes-level-playing-field' ),
				'not_found'                  => __( 'No Statuses found.', 'yikes-level-playing-field' ),
				'menu_name'                  => __( 'Statuses', 'yikes-level-playing-field' ),
			),
			'show_in_rest'      => false,
		);
	}

	/**
	 * Get the object type(s) to use when registering the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_object_types() {
		return array(
			JobManager::SLUG,
		);
	}

	/**
	 * Set up default terms for the taxonomy.
	 *
	 * @since %VERSION%
	 */
	public function default_terms() {
		$terms = array(
			'Inactive' => array(
				'description' => __( 'Job is inactive', 'yikes-level-playing-field' ),
				'slug'        => 'inactive',
			),
			'Active'   => array(
				'description' => __( 'Job is active', 'yikes-level-playing-field' ),
				'slug'        => 'active',
			),
		);

		foreach ( $terms as $term => $args ) {
			if ( ! term_exists( $term, $this->get_slug() ) ) {
				wp_insert_term( $term, $this->get_slug(), $args );
			}
		}
	}

	/**
	 * Custom metabox callback for this taxonomy.
	 *
	 * @since %VERSION%
	 *
	 * @param \WP_Post $post The current post object.
	 */
	public function meta_box_cb( $post ) {
		$tax_name = $this->get_slug();
		$taxonomy = get_taxonomy( $tax_name );
		?>
		<div class="tagsdiv">
			<?php
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
	 * @since %VERSION%
	 *
	 * @param \WP_Post $post The current post object.
	 */
	protected function term_select( $post ) {
		$tax_name  = $this->get_slug();
		$taxonomy  = get_taxonomy( $tax_name );
		$all_terms = get_terms( array(
			'taxonomy'   => $tax_name,
			'hide_empty' => false,
			'orderby'    => 'term_id',
		) );

		$post_terms = get_the_terms( $post->ID, $tax_name );
		$post_terms = $post_terms ? wp_list_pluck( $post_terms, 'term_id', 'slug' ) : array();
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
