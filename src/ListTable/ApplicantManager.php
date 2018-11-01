<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz / Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\ListTable;

use WP_Post;
use Yikes\LevelPlayingField\AdminPage\ExportApplicantsPage;
use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\CustomPostType\ApplicantManager as ApplicantManagerCPT;
use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\Model\MetaLinks;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Roles\Capabilities;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;

/**
 * Class ApplicantManager
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class ApplicantManager extends BasePostType implements AssetsAware {

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

		add_filter( 'admin_enqueue_scripts', $this->get_enqueue_function(), 10, 2 );
		add_filter( 'post_date_column_status', $this->get_post_status_function(), 10, 2 );
		add_filter( 'post_row_actions', $this->get_row_actions_function(), 10, 2 );
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	private function get_assets() {
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
		static $columns = null;

		// This method is called multiple times. Let's only generate new columns once.
		if ( null === $columns ) {
			$status_tax = get_taxonomy( ApplicantStatus::SLUG );
			$columns    = [
				'cb'                           => $original_columns['cb'],
				'id'                           => _x( 'ID', 'column heading', 'yikes-level-playing-field' ),
				'job_title'                    => _x( 'Job Title', 'column heading', 'yikes-level-playing-field' ),
				'avatar'                       => _x( 'Avatar', 'column heading', 'yikes-level-playing-field' ),
				'nickname'                     => _x( 'Nick Name', 'column heading', 'yikes-level-playing-field' ),
				"taxonomy-{$status_tax->name}" => $status_tax->label,
				'date'                         => $original_columns['date'],
				'viewed'                       => _x( 'Viewed by', 'column heading', 'yikes-level-playing-field' ),
			];

			// Only show the view column if the user can edit.
			if ( current_user_can( Capabilities::EDIT_APPLICANTS ) ) {
				// The column has no header, so use an empty string.
				$columns['view'] = '';
			}
		}

		return $columns;
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
		/** @var Applicant[] $applicants */
		static $applicants     = [];
		static $applicant_repo = null;
		static $job_repo       = null;
		static $job_titles     = [];

		// Set up the two repositories only once.
		if ( null === $job_repo ) {
			$job_repo = new JobRepository();
		}
		if ( null === $applicant_repo ) {
			$applicant_repo = new ApplicantRepository();
		}

		// Cache the applicant object for subsequent columns.
		if ( ! isset( $applicants[ $post_id ] ) ) {
			$applicants[ $post_id ] = $applicant_repo->find( $post_id );
		}

		switch ( $column_name ) {
			case 'id':
				echo esc_html( $post_id );
				break;

			case 'job_title':
				$job_id = $applicants[ $post_id ]->get_job_id();
				if ( ! isset( $job_titles[ $job_id ] ) ) {
					try {
						$job_titles[ $job_id ] = $job_repo->find( $job_id )->get_title();
					} catch ( InvalidPostID $e ) {
						echo esc_html__( 'No job set for applicant.', 'yikes-level-playing-field' );
						break;
					}
				}
				echo esc_html( $job_titles[ $job_id ] );
				break;

			case 'avatar':
				echo $applicants[ $post_id ]->get_avatar_img(); // XSS ok.
				break;

			case 'nickname':
				echo esc_html( $applicants[ $post_id ]->get_nickname() );
				break;
			case 'viewed':
				$viewed = $applicants[ $post_id ]->viewed_by() === 0 ? 'No one' : get_user_meta( $applicants[ $post_id ]->viewed_by() )['nickname'][0];
				echo esc_html( $viewed );
				break;
			case 'view':
				if ( current_user_can( Capabilities::EDIT_APPLICANT, $post_id ) ) {
					printf(
						'<a href="%1$s" aria-label="%2$s">%3$s</a>',
						esc_url( get_edit_post_link( $post_id ) ),
						/* translators: %s is the applicant ID */
						esc_attr( sprintf( __( 'Edit Applicant &#8220;%s&#8221;', 'yikes-level-playing-field' ), $post_id ) ),
						esc_html__( 'View', 'yikes-level-playing-field' )
					);
				}
				break;
		}
	}

	/**
	 * Output custom dropdowns for filtering.
	 *
	 * @since %VERSION%
	 *
	 * @param string $which The location of the extra table nav markup: 'top' or 'bottom' for WP_Posts_List_Table,
	 *                      'bar' for WP_Media_List_Table.
	 */
	protected function create_custom_dropdowns( $which ) {
		if ( 'top' === $which ) {
			$this->applicant_status_dropdown_filter();
			$this->jobs_dropdown_filter();
			$this->viewed_dropdown_filter();
		}
	}

	/**
	 * Output a custom dropdown for the applicant_status taxonomy.
	 *
	 * @since %VERSION%
	 */
	private function applicant_status_dropdown_filter() {
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
			esc_html__( 'Filter Applicant Statuses', 'yikes-level-playing-field' )
		);

		wp_dropdown_categories( $dropdown_options );
	}

	/**
	 * Output a custom dropdown for the viewed status.
	 *
	 * @since %VERSION%
	 */
	private function viewed_dropdown_filter() {
		global $typenow;
		if ( 'applicants' === $typenow ) {
			global $wpdb;
			// Get meta key.
			$meta_key = ApplicantMeta::META_PREFIXES['viewed'];
			// Get current selected view.
			$current_viewed = isset( $_GET[ApplicantMeta::VIEWED] ) ? $_GET[ApplicantMeta::VIEWED] : 'all';
			// Query for all unique views.
			$result = $wpdb->get_col(
				$wpdb->prepare( "
			SELECT DISTINCT meta_value FROM $wpdb->postmeta
			WHERE meta_key = '%s' 
			ORDER BY meta_value",
					$meta_key
				)
			);
			?>
			<select name="<?php echo ApplicantMeta::VIEWED; ?>" id="<?php echo ApplicantMeta::VIEWED; ?>">
				<option value="all" <?php selected( 'all', $current_viewed ); ?>><?php _e( 'All Viewed', 'yikes-level-playing-field' ); ?></option>
				<option value="none" <?php selected( 'none', $current_viewed ); ?>><?php _e( 'No One Viewed', 'yikes-level-playing-field' ); ?></option>
				<?php foreach( $result as $user_id ) { ?>
					<option value="<?php echo esc_attr( $user_id ); ?>" <?php selected( $user_id, $current_viewed ); ?>><?php echo esc_html( get_user_meta( $user_id )['nickname'][0] ); ?></option>
				<?php } ?>
			</select>
		<?php }
	}

	/**
	 * Output a custom dropdown for the available jobs.
	 *
	 * @since %VERSION%
	 */
	private function jobs_dropdown_filter() {
		// @todo: make the dropdown filter for jobs.
		global $typenow;
		if ( $typenow == 'applicants' ) {
			global $wpdb;
			$meta_key = MetaLinks::JOB;
			$current_job = isset( $_GET[ $meta_key ] ) ? $_GET[ $meta_key ] : 'all';
			$result = $wpdb->get_col(
				$wpdb->prepare( "
			SELECT DISTINCT meta_value FROM $wpdb->postmeta
			WHERE meta_key = '%s' 
			ORDER BY meta_value",
					$meta_key
				)
			);
			?>
			<select name="<?php echo $meta_key ?>" id="<?php echo $meta_key ?>">
				<option value="all" <?php selected( 'all', $current_job ); ?>><?php _e( 'All Jobs', 'yikes-level-playing-field' ); ?></option>
				<?php foreach( $result as $job_id ) { ?>
					<option value="<?php echo esc_attr( $job_id ); ?>" <?php selected( $job_id, $current_job ); ?>><?php echo esc_html( get_the_title( $job_id ) ); ?></option>
				<?php } ?>
			</select>
		<?php }
	}

	/**
	 * Modifies current query variables.
	 *
	 * @since %VERSION%
	 *
	 * @param array $original_query The original array of query variables.
	 *
	 * @return array The filtered array of query variables.
	 */
	public function custom_query_vars( $original_query ) {
		global $pagenow;
		// Get the post type
		$post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';

		if ( is_admin() && $pagenow === 'edit.php' && $post_type == 'applicants' ) {
			$meta_query = array();
			if ( isset( $_GET[ ApplicantMeta::VIEWED ] ) && $_GET[ ApplicantMeta::VIEWED ] !== 'all' && isset( $_GET[ MetaLinks::JOB ] ) && $_GET[ MetaLinks::JOB ] !== 'all' ) {
				$meta_query['relation'] = 'AND';
			}
			if ( isset( $_GET[ ApplicantMeta::VIEWED ] ) && $_GET[ ApplicantMeta::VIEWED ] !== 'all' ) {
				if ( $_GET[ ApplicantMeta::VIEWED ] === 'none' ) {
					$meta_query[] = array(
						'key'     => ApplicantMeta::META_PREFIXES['viewed'],
						'compare' => 'NOT EXISTS',
					);
				} else {
					$meta_query[] = array(
						'key'     => ApplicantMeta::META_PREFIXES['viewed'],
						'value'   => $_GET[ApplicantMeta::VIEWED],
						'compare' => '=',
					);
				}
			}
			if ( isset( $_GET[ MetaLinks::JOB ] ) && $_GET[ MetaLinks::JOB ] !== 'all' ) {
				$meta_query[] = array(
					'key'     => MetaLinks::JOB,
					'value'   => $_GET[MetaLinks::JOB],
					'compare' => '=',
				);
			}
			$original_query->query_vars['meta_query'] = $meta_query;
		}
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

	/**
	 * Get the function used to enqueue our assets.
	 *
	 * @since %VERSION%
	 * @return \Closure
	 */
	private function get_enqueue_function() {
		return function( $hook ) {

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
		};
	}

	/**
	 * Get the function that filters the post status display.
	 *
	 * @since %VERSION%
	 * @return \Closure
	 */
	private function get_post_status_function() {
		/**
		 * @param string  $status The post status string.
		 * @param WP_Post $post   The post object.
		 *
		 * @return string
		 */
		return function( $status, $post ) {
			if ( $this->get_post_type() !== $post->post_type ) {
				return $status;
			}

			return '';
		};
	}

	/**
	 * Get the function to filter the row action links.
	 *
	 * @since %VERSION%
	 * @return \Closure
	 */
	private function get_row_actions_function() {
		/**
		 * @param array $actions Row action links.
		 * @param WP_Post $post The post object.
		 * @return array
		 */
		return function( $actions, $post ) {
			if ( $this->get_post_type() !== $post->post_type ) {
				return $actions;
			}

			return [];
		};
	}
}
