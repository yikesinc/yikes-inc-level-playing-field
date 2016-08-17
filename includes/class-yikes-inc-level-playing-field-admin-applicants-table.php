<?php
/**
 * Generate the markup for the admin table, to display jobs/applicants on
 * @since 1.0.0
 */
class Link_List_Table extends WP_List_Table {

	// Store the helpers class
	private $helpers;

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
			// Sub Nav - Switch Table
			?>
			<form id="applicants-filter" action="<?php echo admin_url( 'edit.php?post_type=jobs&page=manage-applicants&view=all-applicants' ); ?>" method="GET">
				<ul class="subsubsub"><?php
				foreach ( $links as $link_text => $link_href ) {
					$current = ( sanitize_title( $link_text ) === $page ) ? 'current' : '';
					echo wp_kses_post( '<li><a class="' . esc_attr( $current ) . '" href="' . esc_attr( $link_href ) . '">' . esc_html( $link_text ) . '</a></li>' );
					if ( $count != count( $links ) ) {
						echo ' | ';
					}
					$count++;
				}
				?></ul>
				<div class="tablenav top">
					<!-- Filtering Options -->
					<div class="alignleft actions">
						<?php
							$statuses = $this->helpers->get_applicant_statuses();
							echo '<label class="screen-reader-text" for="cat_id">' . __( 'Filter by Applicant Status' ) . '</label>';
							echo '<select name="applicant_status" id="applicant_status" class="postform">';
								foreach ( $statuses as $applicant_status_text => $applicant_status_class ) {
									echo '<option value="' . strtolower( $applicant_status_text ) . '">' . $applicant_status_text . '</option>';
								}
							echo '</select>';
							submit_button( __( 'Filter Applicants' ), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) );
						?>
					</div>
				<?php
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
			'col_applicant_id' => __( 'ID', 'yikes-inc-level-playing-field' ),
			'col_applicant_name' => __( 'Name', 'yikes-inc-level-playing-field' ),
			'col_link_url' => __( 'Url', 'yikes-inc-level-playing-field' ),
			'col_link_description' => __( 'Description', 'yikes-inc-level-playing-field' ),
			'col_applicant_submitted_date' => __( 'Submission Date', 'yikes-inc-level-playing-field' ),
			'col_applicant_status' => __( 'Applicant Status', 'yikes-inc-level-playing-field' ),
		);
	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	public function get_sortable_columns() {
		return $sortable = array(
			'col_applicant_id' => 'link_id',
			'col_applicant_submitted_date' => 'date',
			'col_applicant_status' => 'applicant_status',
		);
	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		/* -- Preparing your query -- */
		$query = "SELECT * FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'applicants' AND $wpdb->posts.post_status = 'publish'";

		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = ! empty( $_GET['orderby'] ) ? mysql_real_escape_string( $_GET['orderby'] ) : 'ASC';
		$order = ! empty( $_GET['order'] ) ? mysql_real_escape_string( $_GET['order'] ) : '';
		if ( ! empty( $orderby ) && ! empty( $order ) ) {
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
			'col_applicant_id',
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
		$applicants = $this->items;

		//Get the columns registered in the get_columns and get_sortable_columns methods
		list( $columns, $hidden ) = $this->get_column_info();

		// print_r( $columns );

		//Loop for each record
		if ( ! empty( $applicants ) ) {
			foreach ( $applicants as $applicant ) {

				// Open the row
				echo '<tr id="record_' . esc_attr( $applicant->ID ) . '">';

				$applicant_name = ( $applicant->post_title && ! WP_DEBUG ) ? $this->helpers->blur_string( $applicant->post_title ) : $applicant->post_title;
				$new_applicant_badge = ( get_post_meta( $applicant->ID, 'new_applicant', true ) ) ? $this->helpers->get_new_applicants_badge( 'user-badge' ) : '';

				foreach ( $columns as $column_name => $column_display_name ) {

					// Style attributes for each col
					$class = "class='$column_name column-$column_name'";

					$style = "";

					if ( in_array( $column_name, $hidden ) ) {
						$style = ' style="display:none;"';
					}

					$attributes = $class . $style;

					//edit link
					$editlink  = '/wp-admin/link.php?action=edit&link_id=' . (int) $applicant->ID;

					//Display the cell
					switch ( $column_name ) {
						case 'col_applicant_id':
							echo '<td '. $attributes . '>' . esc_html( stripslashes( $applicant->ID ) ) . '</td>';
							break;
						case 'col_applicant_name':
							echo '<td ' . $attributes . '>' . wp_kses_post( stripslashes( $applicant_name ) . $new_applicant_badge ) . '</td>';
							break;
						case 'col_link_url':
							echo '<td ' . $attributes . '>' . esc_html( stripslashes( isset( $applicant->link_url ) ? $applicant->link_url : '' ) ) . '</td>';
							break;
						case 'col_link_description':
							echo '<td ' . $attributes . '>' . esc_html( isset( $applicant->link_description ) ? $applicant->link_description : '' ) . '</td>';
							break;
						case 'col_applicant_submitted_date':
							echo '<td ' . $attributes . '>' . esc_html( get_the_date( get_option( 'date_format' ), $applicant->ID ) ) . '</td>';
							break;
						case 'col_applicant_status':
							echo '<td ' . $attributes . '>' . $this->helpers->generate_status_buttons( $applicant->ID ) . '</td>';
							break;
					}
				}

				//Close the row
				echo'</tr>';
			}
		}
	}
}
