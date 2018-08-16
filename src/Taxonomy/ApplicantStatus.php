<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Taxonomy;

use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\Roles\Capabilities;
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;

/**
 * Class ApplicantStatus
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ApplicantStatus extends BaseTaxonomy implements AssetsAware {

	use AssetsAwareness;

	const SLUG              = 'applicant_status';
	const DEFAULT_TERM_NAME = 'Pending';
	const DEFAULT_TERM_SLUG = 'pending';
	const JS_HANDLE         = 'lpf-taxonomy-button-groups-script';
	const JS_URI            = 'assets/js/taxonomy-button-groups';
	const JS_DEPENDENCIES   = [ 'jquery' ];
	const JS_VERSION        = false;
	const CSS_HANDLE        = 'lpf-taxonomy-button-groups-style';
	const CSS_URI           = 'assets/css/taxonomy-button-groups';

	/**
	 * Register the WordPress hooks.
	 *
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();
		$this->register_assets();
		add_action( 'init', [ $this, 'default_terms' ] );

		add_filter( 'admin_enqueue_scripts', function( $hook ) {

			// This filter should only run on an edit page. Make sure get_current_screen() exists.
			if ( ( 'post-new.php' !== $hook && 'post.php' !== $hook ) || ! function_exists( 'get_current_screen' ) ) {
				return;
			}

			// Ensure this is a real screen object.
			$screen = get_current_screen();
			if ( ! ( $screen instanceof \WP_Screen ) ) {
				return;
			}

			// Ensure this is the edit screen for the correct post type.
			if ( ApplicantManager::SLUG !== $screen->post_type ) {
				return;
			}

			$this->enqueue_assets();
		} );
	}

	/**
	 * Set up default terms for the taxonomy.
	 *
	 * @since %VERSION%
	 */
	public function default_terms() {
		$terms = [
			static::DEFAULT_TERM_NAME => [
				'description' => __( 'Acceptance is pending', 'yikes-level-playing-field' ),
				'slug'        => static::DEFAULT_TERM_SLUG,
			],
			'Yes'                     => [
				'description' => __( 'Accept the applicant', 'yikes-level-playing-field' ),
				'slug'        => 'yes',
			],
			'No'                      => [
				'description' => __( 'Do not accept the applicant', 'yikes-level-playing-field' ),
				'slug'        => 'no',
			],
			'Maybe'                   => [
				'description' => __( 'Maybe accept the applicant', 'yikes-level-playing-field' ),
				'slug'        => 'maybe',
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
			} else {

				// For users who cannot edit the taxonomy, show the assigned term.
				$statuses    = get_the_terms( $post->ID, $tax_name );
				$status_name = is_array( $statuses ) && isset( $statuses[0] ) ? $statuses[0]->name : static::DEFAULT_TERM_NAME;
				printf( '<strong>%s</strong>', esc_html( $status_name ) );
			}
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
		$all_terms = get_terms( [
			'taxonomy'   => $tax_name,
			'hide_empty' => false,
			'orderby'    => 'term_id',
		] );

		$post_terms = get_the_terms( $post->ID, $tax_name );
		$post_terms = $post_terms ? wp_list_pluck( $post_terms, 'term_id', 'slug' ) : [];
		?>

		<!-- Button group for selecting applicant status -->
		<div class="tax-btn-group">
			<?php
			foreach ( $all_terms as $term ) {
				$selected_bool = array_key_exists( $term->term_id, $post_terms ) ? $term->term_id : false;
				if ( $selected_bool ) {
					$selected_term = $term->term_id;
				}
				?>
				<button
					type="button" 
					data-value="<?php echo esc_attr( $term->term_id ); ?>" 
					data-taxonomy="<?php echo esc_attr( $tax_name ); ?>" 
					class="<?php echo false !== $selected_bool ? 'active' : ''; ?>"
				>
					<?php echo esc_html( $term->name ); ?>
				</button>
				<?php
			}
			?>
		</div>

		<!-- Hidden input to hold our taxonomy choice -->
		<input 
			type="hidden" 
			class="tax-input <?php echo esc_attr( $tax_name ); ?>" 
			name="tax_input[<?php echo esc_attr( $tax_name ); ?>]" 
			id="tax_input[<?php echo esc_attr( $tax_name ); ?>]" 
			value="<?php echo esc_attr( $selected_term ); ?>"
		/>
		
		<?php
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		return [
			new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER ),
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
		];
	}

	/**
	 * Get the arguments that configure the taxonomy.
	 *
	 * @author Jeremy Pry
	 * @return array
	 */
	protected function get_arguments() {
		return [
			'hierarchical'       => true,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => false,
			'show_admin_column'  => true,
			'show_in_quick_edit' => true,
			'query_var'          => true,
			'meta_box_cb'        => [ $this, 'meta_box_cb' ],
			'rewrite'            => [
				'slug' => 'status',
			],
			'capabilities'       => [
				'manage_terms' => Capabilities::MANAGE_APPLICANT_STATUS,
				'edit_terms'   => Capabilities::MANAGE_APPLICANT_STATUS,
				'delete_terms' => Capabilities::MANAGE_APPLICANT_STATUS,
				'assign_terms' => Capabilities::EDIT_APPLICANTS,
			],
			'labels'             => [
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
			],
			'show_in_rest'       => false,
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
			ApplicantManager::SLUG,
		];
	}
}
