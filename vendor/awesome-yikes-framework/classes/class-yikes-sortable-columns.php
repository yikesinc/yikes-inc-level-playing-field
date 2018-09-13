<?php
/**
 * Class File
 *
 * Description.
 *
 * @link http://www.yikesinc.com
 *
 * @package WordPress
 * @subpackage Component
 * @since 0.9
 */

/**
 * Yks_Sortable_Columns_Fieldsx Class File
 *
 * Sortable Columns
 *
 * @since 1.1
 * @see Yks_Sortable_Columns_Fields
 */
class Yks_Sortable_Columns_Fields {
	/**
	 * Protected variable
	 *
	 * @access protected
	 * @var $_column Variable array
	 */
	protected $_column;
	/**
	 * Construct class.
	 *
	 * @param protected $the_column Array.
	 */
	public function __construct( $the_column ) {
		// Vars.
		$this->_column = $the_column;
		add_filter( 'manage_' . $this->_column['post_type'] . '_posts_columns' , array( $this, 'add_columns' ) );
		add_action( 'manage_' . $this->_column['post_type'] . '_posts_custom_column' , array( $this, 'post_type_columns' ), 10, 2 );
		//Sortable
		if ( isset( $this->_column['sortable_column'] ) && false !== $this->_column['sortable_column'] ) {
			add_filter( 'manage_edit-' . $this->_column['post_type'] . '_sortable_columns', array( $this, 'sortable_columns' ) );
			add_action( 'pre_get_posts', array( $this, 'post_type_columns_orderby' ) );
		}

		// Get Admin header
		add_action( 'admin_head', array( $this, 'get_admin_header' ) );

		//admin ajax function
		add_action( 'wp_ajax_yks_star_action_' . $this->_column['post_type'] . '', array( $this, 'get_admin_ajax' ) );

		// Post save actions.	
		add_action( 'save_post', array( $this, 'limit_post_with_meta_single' ), 10, 3 );
	}

	// Add column linked to field.
	public function add_columns( $columns ) {
		// Make sure date column is always last.
		$c_count = count( $columns );
		foreach( $columns as $key => $title ) {
			$rc_count++;
			if ( $key == 'date' ) {
				$new_columns[$this->_column['id']] = __( '' . $this->_column['name'] . '' );
				// Keep track if column is added
				$added = true;
			} else {
				// If column has not been added because of missing date field set flag
				if ( true !== $added ) {
					$added = false;
				}
			}
			$new_columns[$key] = $title;
			// Add column at the end instead of infront of of Date if date column does not exsist.
			if ( $c_count === $rc_count && true !== $added ) {
				$new_columns[$this->_column['id']] = __( '' . $this->_column['name'] . '' );
			}
		
		}
		return $new_columns;
	}
	// add column content
	public function post_type_columns( $column, $post_id ) {
		global $post;
		switch ( $column ) {
			case $this->_column['id']  :
				$value = esc_html( get_post_meta( $post_id, $this->_column['id'], true ) );
				$filepath = $this->get_file_template_location();
				if ( false !== $filepath ) {
					// Do something if you need too.
					include $filepath;
				} else {
					echo $value;
				}
			break;
			
		}
	}
	// Make it sortable
	public function sortable_columns( $columns ) {
		$new_columns = array_merge(
			$columns, array(
				'' . $this->_column['id'] . '' => __( '' . $this->_column['id'] . '' ),
			)
		);
		return $new_columns;
	}
	// how it's sorted
	public function post_type_columns_orderby( $query ) {
		// If not admin return
		if ( ! is_admin() ) {
			return;
		}
		// If not post type return
		if ( ! is_post_type_archive( $this->_column['post_type'] ) ) {
         	return;
    	}
		$orderby = $query->get( 'orderby' );
		// Sort by title and exclude with no star
		if ( $this->_column['id'] === $orderby ) {
			// Set new order vars
			$query->set( 'meta_key', $this->_column['id'] );
			// Check for alternate sort order.  Currently only supports default orderby params from WP_Query.  
			if ( ! empty( $this->_column['column_orderby'] ) ) {
				$query->set( 'orderby', $this->_column['column_orderby'] );
			} else {
				// default order by is meta key.
				$query->set('orderby', 'meta_value_num');
			}
       		
		}
	}
	/**
	 * Check theme for field file - if it does not exist check framework
	 */
	public function get_file_template_location() {
		$field = $this->_column;
		$field_name_with_dashes = str_replace( '_', '-', $field['type'] );

		if ( file_exists( ( get_template_directory() . '/inc/cpt/cpt-fields/sortable-columns/yks-' . $field_name_with_dashes . '.php' ) ) !== false ) {
			return get_template_directory() . '/inc/cpt/cpt-fields/sortable-columns/yks-' . $field_name_with_dashes . '.php';
		} elseif ( stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/sortable-columns/yks-' . $field_name_with_dashes . '.php' ) !== false ) {
			return stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/sortable-columns/yks-' . $field_name_with_dashes . '.php' );
		} else {
			return false;
		}
		return;
	}
	/**
	 * Function to call custom admin headers for custom columns
	 */
	public function get_admin_header() {

		// Get global var
		global $post_type;

		$field = $this->_column;
		$field_name_with_dashes = str_replace( '_', '-', $field['type'] );	

		// Check if the user can edit the page/post.
		if ( 'page' === $post_type ) {
			if ( ! current_user_can( 'edit_pages' ) ) {
				return;
			}
		} elseif ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		// Check for post type
		if ( $field['post_type'] !== $post_type ) {
			return;
		}
		
		// Get file
		if ( file_exists( ( get_template_directory() . '/inc/cpt/cpt-fields/sortable-columns-header/yks-' . $field_name_with_dashes . '.php' ) ) !== false ) {
			$filepath = get_template_directory() . '/inc/cpt/cpt-fields/sortable-columns-header/yks-' . $field_name_with_dashes . '.php';
		} elseif ( stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/sortable-columns-header/yks-' . $field_name_with_dashes . '.php' ) !== false ) {
			$filepath = stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/sortable-columns-header/yks-' . $field_name_with_dashes . '.php' );
		} else {
			return false;
		} 
		// Return
		$my_nonce = wp_create_nonce( 'yks-' . $this->_column['id']. '' );
		include $filepath;
		return;
	}
	/**
	  * Function for custom ajax for columns.
	  */
	public function get_admin_ajax() {
		$field = $this->_column;
		$field_name_with_dashes = str_replace( '_', '-', $field['type'] );
		if ( file_exists( ( get_template_directory() . '/inc/cpt/cpt-fields/sortable-columns-ajax/yks-' . $field_name_with_dashes . '.php' ) ) !== false ) {
			$filepath = get_template_directory() . '/inc/cpt/cpt-fields/sortable-columns-ajax/yks-' . $field_name_with_dashes . '.php';
		} elseif ( stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/sortable-columns-ajax/yks-' . $field_name_with_dashes . '.php' ) !== false ) {
			$filepath = stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/sortable-columns-ajax/yks-' . $field_name_with_dashes . '.php' );
		} else {
			return false;
		} 
		// Return
		check_ajax_referer( 'yks-' . $this->_column['id']. '', 'security' );
		include $filepath;
		exit;		
	}
	/**
	 * Limit how many posts has this value.
	 */
	 public function limit_post_with_meta( $post_id, $post_type, $meta_key, $meta_value ) {
	 	// Get all posts that have value except the one enabled.  
	 	$args = array(
	 			'post_type' => $post_type,
	 			'posts_per_page' => '-1',
	 			'post__not_in' => array( $post_id ),
	 			'meta_key' => $meta_key,
				'meta_value' => $meta_value,
				'meta_compare' => '=',
		 );
	 	$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
	 		while ( $query->have_posts() ) {
	 			$query->the_post();
	 			delete_post_meta( $query->post->ID, $meta_key );
	 		}
		}
	}
	/**
	 * Limit how many posts has this value.
	 */
	 public function limit_post_with_meta_single( $post_id ) {
		// vars from array.
		$field = $this->_column;
		// vars from post.
		$post_type = get_post_type($post_id);
		// Make sure it's the post type.
		if ( $post_type == $field['post_type'] ) {
			// Make sure it's highlighted is selected.
			if ( isset( $field['limit_highlighted'] ) ) {
				// Make sure their is value for field.  
				if ( isset( $_POST[ $field['id'] ] ) && $_POST[ $field['id'] ] == $field['value'] ) {
					// if so delete others with the meta data.
					$this->limit_post_with_meta( $post_id, $post_type, $field['id'], $field['value'] );
				
				}
			}
		}
	}	
}

if ( is_admin() ) {
	// Vars.	
	$yks_mboxs_columns = apply_filters( 'yks_mboxes', array() );
	// Function
	function Execute_Yks_Sortable_Columns_Fields( $mboxs ) {
		// Loop thru meta boxes
		foreach ( $mboxs as $mbox ) {
			// Make sure it has an ID
			if ( $mbox['id'] ) {
				// Make sure it has fields
				if ( $mbox['fields'] ) {
					// loop through all fields.   
					foreach ( $mbox['fields'] as $field ) {
						// for non group fields
						if ( "group" !=  $field['type'] ) {

							$the_column = '';

							// Make sure it's a sortable field
							// Make it's not a taxonomy field.  Taxonomies fields not supported.  
							// You can enable columns through WP core you don't need this object.  
							if  ( isset( $field['column'] ) && false != $field['column'] && empty( $field['taxaonomy'] ) ) {
								// Check if repeating only works with singular
								$field_repeating = ( isset( $field['repeating'] ) && $field['repeating'] === true ) ? true : false;
								if ( true != $field_repeating ) {
								// Loop through all post types 
									foreach($mbox['pages'] as $the_post_type ) {
										/* Global Options */
										$the_column = array();
										$the_column = $field;
										if ( ! empty( $the_column ) ) {
											$the_column['post_type'] = $the_post_type;
											New Yks_Sortable_Columns_Fields( $the_column );
										}
									}
								}
							}
						// for group fields.
						} else {
							foreach ( $field['fields'] as $groupfield ) {
								// Do stuff
								$the_column = '';
								// Make sure it's a sortable field
								// Make it's not a taxonomy field.  Taxonomies fields not supported.  
								// You can enable columns through WP core you don't need this object.  
								if  ( isset( $groupfield['column'] ) && false != $groupfield['column'] && empty( $groupfield['taxaonomy'] ) ) {
									// Check if repeating only works with singular
									$field_repeating = ( isset( $groupfield['repeating'] ) && $groupfield['repeating'] === true ) ? true : false;
									if ( true != $field_repeating ) {
									// Loop through all post types 
										foreach($mbox['pages'] as $the_post_type ) {
											/* Global Options */
											$the_column = array();
											$the_column = $groupfield;
											if ( ! empty( $the_column ) ) {
												$the_column['post_type'] = $the_post_type;
												New Yks_Sortable_Columns_Fields( $the_column );
											}
										}
									}
								}
						
							}
						}
					}
				}
			}
		}
	}
	/**
	 * execute action. 
	 */
	Execute_Yks_Sortable_Columns_Fields( $yks_mboxs_columns );
}