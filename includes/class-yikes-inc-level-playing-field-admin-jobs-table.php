<?php
/**
 * Generate the markup for the admin table, to display jobs/applicants on
 * @since 1.0.0
 */
class Link_List_Table extends WP_List_Table {

	// Store our helpers class
	public $helpers;

	/**
	* Constructor, we override the parent to pass our own arguments
	* We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
	*/
	function __construct( $helpers ) {
		$this->helpers = $helpers;
		parent::__construct( array(
			'singular' => 'wp_list_text_link', // Singular label
			'plural'   => 'wp_list_test_links', // Plural label, also this well be one of the table css class
			'ajax'     => false, // We won't support Ajax for this table
		) );
	}

		/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( 'top' === $which ) {
			$count = 1;
			$admin_table_url = admin_url( 'edit.php?post_type=jobs&page=manage-applicants' );
			$page = ( isset( $_GET['view'] ) ) ? $_GET['view'] : 'sort-by-jobs';
			$links = array(
				__( 'Sort by Jobs', 'yikes-inc-level-playing-field' ) => esc_url_raw( add_query_arg( 'view', 'sort-by-jobs', $admin_table_url ) ),
				__( 'All Applicants', 'yikes-inc-level-playing-field' ) => esc_url_raw( add_query_arg( 'view', 'all-applicants', $admin_table_url ) ),
			);
			ob_start(); ?>
			<ul class="subsubsub">
				<?php
					foreach ( $links as $link_text => $link_href ) {
						$current = ( sanitize_title( $link_text ) === $page ) ? 'current' : '';
						echo wp_kses_post( '<li><a class="' . esc_attr( $current ) . '" href="' . esc_attr( $link_href ) . '">' . esc_html( $link_text ) . '</a></li>' );
						if ( $count !== count( $links ) ) {
							echo ' | ';
						}
						$count++;
					}
				?>
			</ul>
			<?php
			$contents = ob_get_contents();
			ob_get_clean();
			echo wp_kses_post( $contents );
		}
		if ( 'bottom' === $which ) {
			//The code that goes after the table is there
			// echo "Hi, I'm after the table";
		}
	}

	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns = array(
			'col_job_id' => __( 'ID', 'yikes-inc-level-playing-field' ),
			'col_job_title' => __( 'Job Title', 'yikes-inc-level-playing-field' ),
			'col_applicant_count' => __( 'Applicant Count', 'yikes-inc-level-playing-field' ),
			'col_company_name' => __( 'Company', 'yikes-inc-level-playing-field' ),
			'col_job_posted_date' => __( 'Job Posted Date', 'yikes-inc-level-playing-field' ),
		);
	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		return $sortable = array(
			'col_job_title' => 'title',
			'col_company_name' => '_company_name',
			'col_job_posted_date' => 'date',
		);
	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		/* -- Preparing your query -- */
		$query = "SELECT * FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'jobs' AND $wpdb->posts.post_status = 'publish'";

		// Testing Data
		if ( isset( $_GET['orderby'] ) ) {
			// print_r( $_GET );
		}

		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = ! empty( $_GET['orderby'] ) ? 'ASC' : 'ASC';
		$order = ! empty( $_GET['order'] ) ? mysql_real_escape_string( $_GET['order'] ) : '';
		if ( ! empty( $orderby ) & ! empty( $order ) ) {
			$query .= ' ORDER BY '. $orderby . ' ' . $order;
		}

		/* -- Pagination parameters -- */
		// Number of elements in your table?
		$totalitems = $wpdb->query( $query ); // return the total number of affected rows
		// How many to display per page?
		$perpage = 5;
		//Which page is this?
		$paged = ! empty( $_GET['paged'] ) ? mysql_real_escape_string( $_GET['paged'] ) : '';
		//Page Number
		if ( empty( $paged ) || ! is_numeric( $paged ) || $paged <= 0 ) {
			$paged = 1;
		}
		//How many pages do we have in total?
		$totalpages = ceil( $totalitems / $perpage );
		//adjust the query to take pagination into account
		if ( ! empty( $paged ) && ! empty( $perpage ) ) {
			$offset = ( $paged - 1 ) * $perpage;
			$query .= ' LIMIT '. (int) $offset . ',' . (int) $perpage;
		}

		/* -- Register the pagination -- */
		$this->set_pagination_args( array(
			'total_items' => $totalitems,
			'total_pages' => $totalpages,
			'per_page' => $perpage,
		) );
		//The pagination links are automatically built according to those parameters

		/* -- Register the Columns -- */
		$columns = $this->get_columns();
		// Pass in column IDs to hide
		$hidden = array(
			'col_job_id',
		);
		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		/* -- Fetch the items -- */
		$this->items = $wpdb->get_results( $query );
	}

	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	function display_rows() {

		//Get the records registered in the prepare_items method
		$jobs = $this->items;

		//Get the columns registered in the get_columns and get_sortable_columns methods
		list( $columns, $hidden ) = $this->get_column_info();

		// print_r( $columns );

		//Loop for each record
		if ( ! empty( $jobs ) ) {
			foreach ( $jobs as $job ) {

				// Get the number of applicants
				$applicant_count = $this->helpers->get_applicant_count( $job );
				$new_applicants_badge = ( $this->helpers->get_new_applicant_count( $job ) > 0 ) ? $this->helpers->get_new_applicants_badge( 'total', $applicant_count ) : '';

				// Setup the action links
				$action_links = $this->get_action_links( $job->ID, $applicant_count );

				// Open the row
				echo '<tr id="record_' . esc_attr( $job->ID ) . '">';

				foreach ( $columns as $column_name => $column_display_name ) {

					// Style attributes for each col
					$class = "class='$column_name column-$column_name'";

					$style = "";

					if ( in_array( $column_name, $hidden ) ) {
						$style = ' style="display:none;"';
					}

					$attributes = $class . $style;

					//Display the cell
					switch ( $column_name ) {
						case 'col_job_id':
							echo '<td '. $attributes . '>' . esc_html( stripslashes( $job->ID ) ) . '</td>';
							break;
						case 'col_job_title':
							echo '<td ' . $attributes . '>' . esc_html( stripslashes( $job->post_title ) ) . wp_kses_post( $action_links ) . '</td>';
							break;
						case 'col_applicant_count':
							echo '<td ' . $attributes . '>' . esc_html( $applicant_count ) . wp_kses_post( $new_applicants_badge ) . '</td>';
							break;
						case 'col_company_name':
							echo '<td ' . $attributes . '>' . esc_html( get_post_meta( $job->ID, '_company_name', true ) ) . '</td>';
							break;
						case 'col_job_posted_date':
							echo '<td ' . $attributes . '>' . esc_html( get_the_date( get_option( 'date_format' ), $job->ID ) ) . '</td>';
							break;
					}
				}

				//Close the row
				echo'</tr>';
			}
		}
	}

	/**
	 * Get the action links to use on this page
	 * @return mixed HTML content of the action links
	 */
	function get_action_links( $job_id, $applicant_count ) {
		$action_link_array = array(
			__( 'View Applicants', 'yikes-inc-level-playing-field' ) => ( $applicant_count > 0 ) ? add_query_arg( array(
				'job' => $job_id,
			), admin_url( 'edit.php?post_type=jobs&page=manage-applicants&view=all-applicants' ) ) : 'disabled',
			__( 'Edit Job', 'yikes-inc-level-playing-field' ) => add_query_arg( array(
				'post' => $job_id,
				'action' => 'edit',
			), admin_url( 'post.php' ) ),
		);
		$count = 1;
		ob_start();
		?>
		<div class="row-actions">
			<?php
			foreach( $action_link_array as $action_link_text => $action_link_href ) {
				$divider = ( $count < count( $action_link_array ) ) ? ' | ' : '';
				if ( 'disabled' === $action_link_href ) {
					echo wp_kses_post( '<span class="' . sanitize_title( $action_link_text ) . '"><a href="#" onclick="return false;" disabled="disabled" class="disabled-action-link">' . $action_link_text . '</a></span>' . $divider );
				} else {
					echo wp_kses_post( '<span class="' . sanitize_title( $action_link_text ) . '"><a href="' . $action_link_href . '">' . $action_link_text . '</a></span>' . $divider );
				}
				$count++;
			}
			?>
		</div>
		<?php
		$content = ob_get_contents();
		ob_get_clean();
		return $content;
	}
}
