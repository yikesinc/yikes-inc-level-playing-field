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
use Yikes\LevelPlayingField\Exception\InvalidPostID;
use Yikes\LevelPlayingField\Model\Applicant;
use Yikes\LevelPlayingField\Model\ApplicantMeta;
use Yikes\LevelPlayingField\Model\ApplicantRepository;
use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Model\JobDropdown;
use Yikes\LevelPlayingField\Model\MetaLinks;
use Yikes\LevelPlayingField\Model\PostTypeApplicant;
use Yikes\LevelPlayingField\Roles\Capabilities;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatus;
use Yikes\LevelPlayingField\Taxonomy\ApplicantStatusDropdown;
use Yikes\LevelPlayingField\Comment\ApplicantMessageRepository;

/**
 * Class ApplicantManager
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class ApplicantManager extends BasePostType {

	use ApplicantStatusDropdown;
	use JobDropdown;
	use PostTypeApplicant;

	/**
	 * Register hooks.
	 *
	 * @since  1.0.0
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();

		add_filter( 'post_date_column_status', $this->get_post_status_function(), 10, 2 );
		add_filter( 'post_row_actions', $this->get_row_actions_function(), 10, 2 );
	}

	/**
	 * Adjust the columns to display for the list table.
	 *
	 * @since 1.0.0
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
				'id'                           => _x( 'ID', 'column heading', 'level-playing-field' ),
				'job_title'                    => _x( 'Job Title', 'column heading', 'level-playing-field' ),
				'avatar'                       => _x( 'Avatar', 'column heading', 'level-playing-field' ),
				'nickname'                     => _x( 'Nick Name', 'column heading', 'level-playing-field' ),
				"taxonomy-{$status_tax->name}" => $status_tax->label,
				'new_messages'                 => _x( 'New Messages', 'column heading', 'level-playing-field' ),
				'date'                         => $original_columns['date'],
				'viewed'                       => _x( 'Viewed by', 'column heading', 'level-playing-field' ),
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
	 * @since 1.0.0
	 *
	 * @param string $column_name The column slug.
	 * @param int    $post_id     The post ID.
	 */
	public function column_content( $column_name, $post_id ) {

		/** @var Applicant[] $applicants */
		static $applicants     = [];
		static $applicant_repo = null;
		static $job_repo       = null;
		static $msg_repo       = null;
		static $job_titles     = [];

		// Set up the two repositories only once.
		if ( null === $job_repo ) {
			$job_repo = new JobRepository();
		}
		if ( null === $applicant_repo ) {
			$applicant_repo = new ApplicantRepository();
		}
		if ( null === $msg_repo ) {
			$msg_repo = new ApplicantMessageRepository();
		}

		// Cache the applicant object for subsequent columns.
		if ( ! isset( $applicants[ $post_id ] ) ) {
			$applicants[ $post_id ] = $applicant_repo->find( $post_id );
		}

		switch ( $column_name ) {
			case 'id':
				if ( current_user_can( Capabilities::EDIT_APPLICANT, $post_id ) ) {
					printf(
						'<a href="%1$s" aria-label="%2$s">%3$s</a>',
						esc_url( get_edit_post_link( $post_id ) ),
						/* translators: %s is the applicant ID */
						esc_attr( sprintf( __( 'Edit Applicant &#8220;%s&#8221;', 'level-playing-field' ), $post_id ) ),
						esc_html( $post_id )
					);
					break;
				}
				echo esc_html( $post_id );
				break;

			case 'job_title':
				$job_id = $applicants[ $post_id ]->get_job_id();
				if ( ! isset( $job_titles[ $job_id ] ) ) {
					try {
						$job_titles[ $job_id ] = $job_repo->find( $job_id )->get_title();
					} catch ( InvalidPostID $e ) {
						echo esc_html__( 'No job set for applicant.', 'level-playing-field' );
						break;
					}
				}
				echo esc_html( $job_titles[ $job_id ] );
				break;

			case 'avatar':
				echo $applicants[ $post_id ]->get_avatar_img(); // phpcs:ignore WordPress.Security.EscapeOutput
				break;

			case 'nickname':
				echo esc_html( $applicants[ $post_id ]->get_nickname() );
				break;

			case 'new_messages':
				echo esc_html( count( $msg_repo->find_new_applicant_messages( $post_id ) ) );
				break;

			case 'viewed':
				$viewed_by = $applicants[ $post_id ]->get_viewed_by() === 0
					? _x( 'No one', 'No one has viewed applicant submission', 'level-playing-field' )
					: get_user_meta( $applicants[ $post_id ]->get_viewed_by(), 'nickname', true );
				echo esc_html( $viewed_by );
				break;

			case 'view':
				if ( current_user_can( Capabilities::EDIT_APPLICANT, $post_id ) ) {
					printf(
						'<a href="%1$s" aria-label="%2$s">%3$s</a>',
						esc_url( get_edit_post_link( $post_id ) ),
						/* translators: %s is the applicant ID */
						esc_attr( sprintf( __( 'Edit Applicant &#8220;%s&#8221;', 'level-playing-field' ), $post_id ) ),
						esc_html__( 'View', 'level-playing-field' )
					);
				}
				break;
		}
	}

	/**
	 * Output custom dropdowns for filtering.
	 *
	 * @since 1.0.0
	 *
	 * @param string $which The location of the extra table nav markup: 'top' or 'bottom' for WP_Posts_List_Table,
	 *                      'bar' for WP_Media_List_Table.
	 */
	protected function create_custom_dropdowns( $which ) {
		if ( 'top' === $which ) {
			$this->jobs_dropdown_filter();
			$this->viewed_dropdown_filter();
			$this->applicant_status_dropdown();
		}
	}

	/**
	 * Output a custom dropdown for the viewed status.
	 *
	 * @since 1.0.0
	 */
	private function viewed_dropdown_filter() {
		global $wpdb;
		$meta_key       = ApplicantMeta::META_PREFIXES[ ApplicantMeta::VIEWED ];
		$current_viewed = isset( $_GET[ ApplicantMeta::VIEWED ] ) ? filter_var( $_GET[ ApplicantMeta::VIEWED ], FILTER_SANITIZE_STRING ) : 'all';

		// Query for all unique views.
		$result = $wpdb->get_col(
			$wpdb->prepare( "
				SELECT DISTINCT meta_value FROM $wpdb->postmeta
				WHERE meta_key = %s
				ORDER BY meta_value",
				$meta_key
			)
		);
		?>
		<select name="<?php echo esc_attr( ApplicantMeta::VIEWED ); ?>" id="<?php echo esc_attr( ApplicantMeta::VIEWED ); ?>">
			<option value="all" <?php selected( 'all', $current_viewed ); ?>><?php esc_html_e( 'All Viewed', 'level-playing-field' ); ?></option>
			<option value="none" <?php selected( 'none', $current_viewed ); ?>><?php esc_html_e( 'No One Viewed', 'level-playing-field' ); ?></option>
			<?php foreach ( $result as $user_id ) { ?>
				<option value="<?php echo esc_attr( $user_id ); ?>" <?php selected( $user_id, $current_viewed ); ?>><?php echo esc_html( get_user_meta( $user_id, 'nickname', true ) ); ?></option>
			<?php } ?>
		</select>
		<?php
	}

	/**
	 * Output a custom dropdown for the available jobs.
	 *
	 * @since 1.0.0
	 */
	private function jobs_dropdown_filter() {
		$jobs        = ( new JobRepository() )->find_all();
		$current_job = isset( $_GET[ MetaLinks::JOB ] ) ? $_GET[ MetaLinks::JOB ] : 'all';
		echo $this->job_dropdown( $jobs, $current_job ); // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Modifies current query variables.
	 *
	 * @since 1.0.0
	 *
	 * @param \WP_Query $query Query object.
	 */
	public function custom_query_vars( $query ) {
		if ( ! $query->is_main_query() || ! is_admin() ) {
			return;
		}

		$screen = get_current_screen();

		// Check if current page is edit page for post type applicant.
		if ( "edit-{$this->get_post_type()}" !== $screen->id ) {
			return;
		}

		$meta_query = [];
		if ( isset( $_GET[ ApplicantMeta::VIEWED ] ) && 'all' !== $_GET[ ApplicantMeta::VIEWED ] && isset( $_GET[ MetaLinks::JOB ] ) && 'all' !== $_GET[ MetaLinks::JOB ] ) {
			$meta_query['relation'] = 'AND';
		}
		if ( isset( $_GET[ ApplicantMeta::VIEWED ] ) && 'all' !== $_GET[ ApplicantMeta::VIEWED ] ) {
			if ( 'none' === $_GET[ ApplicantMeta::VIEWED ] ) {
				$meta_query[] = [
					'key'     => ApplicantMeta::META_PREFIXES['viewed'],
					'compare' => 'NOT EXISTS',
				];
			} else {
				$meta_query[] = [
					'key'     => ApplicantMeta::META_PREFIXES['viewed'],
					'value'   => $_GET[ ApplicantMeta::VIEWED ],
					'compare' => '=',
				];
			}
		}
		if ( isset( $_GET[ MetaLinks::JOB ] ) && 'all' !== $_GET[ MetaLinks::JOB ] ) {
			$meta_query[] = [
				'key'     => MetaLinks::JOB,
				'value'   => $_GET[ MetaLinks::JOB ],
				'compare' => '=',
			];
		}
		$query->set( 'meta_query', $meta_query );
	}

	/**
	 * Get the function that filters the post status display.
	 *
	 * @since 1.0.0
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
	 * @since 1.0.0
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
