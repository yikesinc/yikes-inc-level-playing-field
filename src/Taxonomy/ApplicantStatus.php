<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Taxonomy;

use WP_Error;
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\Roles\Capabilities;

/**
 * Class ApplicantStatus
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class ApplicantStatus extends BaseTaxonomy implements AssetsAware {

	use AssetsAwareness;

	const SLUG              = 'applicant_status';
	const DEFAULT_TERM_NAME = 'Pending';
	const DEFAULT_TERM_SLUG = 'pending';
	const JS_HANDLE         = 'lpf-applicant-status-button-groups-script';
	const JS_URI            = 'assets/js/applicant-status-button-groups';
	const JS_DEPENDENCIES   = [ 'jquery' ];
	const JS_VERSION        = false;
	const CSS_HANDLE        = 'lpf-applicant-status-button-groups-style';
	const CSS_URI           = 'assets/css/applicant-status-button-groups';

	/**
	 * Register the WordPress hooks.
	 *
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();
		$this->register_assets();
		add_action( 'init', [ $this, 'default_terms' ] );

		// Possibly enqueue our assets.
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

		// Filter the terms to only the default list.
		add_filter( 'pre_insert_term', function( $term, $taxonomy ) {
			// Return early if this isn't the correct taxonomy.
			if ( $this->get_slug() !== $taxonomy ) {
				return $term;
			}

			if ( ! array_key_exists( $term, $this->get_default_terms() ) ) {
				return new WP_Error(
					'lpf_invalid_term',
					sprintf(
						/* translators: %s refers to the term that was attempted to be inserted. */
						__( 'The term "%s" is not a valid Applicant status.', 'level-playing-field' ),
						$term
					)
				);
			}

			return $term;
		}, 10, 2 );

		// AJAX handler for changing the post's term.
		add_action( 'wp_ajax_lpf_add_post_term', function() {
			$this->add_post_term();
		});
	}

	/**
	 * Set up default terms for the taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function default_terms() {
		$terms = $this->get_default_terms();

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
			if ( current_user_can( $taxonomy->cap->assign_terms ) ) {
				$this->term_select( $post );
			} else {

				// For users who cannot edit the taxonomy, show the assigned term.
				$statuses    = get_the_terms( $post->ID, $tax_name );
				$status_name = is_array( $statuses ) && isset( $statuses[0] )
					? $statuses[0]->name
					: static::DEFAULT_TERM_NAME;
				printf( '<strong>%s</strong>', esc_html( $status_name ) );
			}
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
		$all_terms = get_terms( [
			'taxonomy'   => $tax_name,
			'hide_empty' => false,
			'orderby'    => 'term_id',
		] );

		$post_terms = get_the_terms( $post->ID, $tax_name );
		$post_terms = $post_terms ? wp_list_pluck( $post_terms, 'slug', 'term_id' ) : [];

		// Set the default term.
		$selected_term = '';
		if ( empty( $post_terms ) ) {
			$default_term  = get_term_by( 'slug', self::DEFAULT_TERM_SLUG, self::SLUG );
			$selected_term = $default_term->term_id;
		}
		?>
		<!-- Button group for selecting applicant status -->
		<div id="applicant-status">
			<div class="tax-btn-group">
				<?php
				foreach ( $all_terms as $term ) {
					$selected_bool = array_key_exists( $term->term_id, $post_terms ) ? $term->term_id : false;
					?>
					<button
						type="button"
						data-value="<?php echo esc_attr( $term->term_id ); ?>"
						class="<?php echo false !== $selected_bool ? 'active' : ''; ?>"
					>
						<?php echo esc_html( $term->name ); ?>
					</button>
					<?php
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Load asset objects for use.
	 *
	 * @since 1.0.0
	 */
	protected function load_assets() {
		$script = new ScriptAsset(
			self::JS_HANDLE,
			self::JS_URI,
			self::JS_DEPENDENCIES,
			self::JS_VERSION,
			ScriptAsset::ENQUEUE_FOOTER
		);

		$script->add_localization(
			'taxonomy_button_group_data',
			[
				'nonce' => wp_create_nonce( 'add_post_terms' ),
			]
		);

		$this->assets = [
			$script,
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
			'show_in_quick_edit' => false,
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

	/**
	 * Get the default terms that we're setting.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	private function get_default_terms() {
		return [
			static::DEFAULT_TERM_NAME => [
				'description' => __( 'Acceptance is pending', 'level-playing-field' ),
				'slug'        => static::DEFAULT_TERM_SLUG,
			],
			'Yes'                     => [
				'description' => __( 'Accept the applicant', 'level-playing-field' ),
				'slug'        => 'yes',
			],
			'No'                      => [
				'description' => __( 'Do not accept the applicant', 'level-playing-field' ),
				'slug'        => 'no',
			],
			'Maybe'                   => [
				'description' => __( 'Maybe accept the applicant', 'level-playing-field' ),
				'slug'        => 'maybe',
			],
		];
	}

	/**
	 * Assign a term to the post.
	 *
	 * @since 1.0.0
	 */
	private function add_post_term() {

		// Handle nonce.
		if ( ! isset( $_POST['nonce'] ) || ! check_ajax_referer( 'add_post_terms', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'level-playing-field' ),
			], 403 );
		}

		// Sanitize vars.
		$term    = isset( $_POST['term'] ) ? filter_var( wp_unslash( $_POST['term'] ), FILTER_SANITIZE_NUMBER_INT ) : 0;
		$post_id = isset( $_POST['post_id'] ) ? filter_var( wp_unslash( $_POST['post_id'] ), FILTER_SANITIZE_NUMBER_INT ) : 0;

		if ( empty( $term ) || empty( $post_id ) ) {
			wp_send_json_error( [
				'reason'  => __( 'An error occurred: the term or post ID failed validation.', 'level-playing-field' ),
				'term'    => $term,
				'post_id' => $post_id,
			], 400 );
		}

		$result = wp_set_post_terms( $post_id, $term, static::SLUG );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( [
				/* translators: the placeholder is an error message returned by the WP_Error object */
				'reason'   => sprintf( __( 'An error occurred: %s', 'level-playing-field' ), $result->get_error_message() ),
				'wp_error' => $result,
			], 422 );
		} elseif ( false === $result ) {
			wp_send_json_error( [
				'reason'  => __( 'An error occurred: Failed to set post term.', 'level-playing-field' ),
				'term'    => $term,
				'post_id' => $post_id,
			], 422 );
		} else {
			wp_send_json_success( [
				'reason' => __( 'Term successfully set.', 'level-playing-field' ),
			], 200 );
		}
	}
}
