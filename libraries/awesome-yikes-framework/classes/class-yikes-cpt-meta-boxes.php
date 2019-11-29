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
 * YIKES_CPT_Meta_Box Class File
 *
 * Create Custom Metaboxes for CPTs
 */
class YIKES_CPT_Meta_Boxes {
	/**
	 * Protected variable
	 *
	 * @access protected
	 * @var $_mbox Variable array
	 */
	protected $_mbox;

	/**
	 * Construct class.
	 *
	 * @param protected $mbox Array.
	 */
	public function __construct( $mbox ) {

		if ( ! is_admin() ) {
			return;
		}

		$this->_mbox = $mbox;

		add_action( 'admin_menu', array( &$this, 'yks_add' ) );
		add_action( 'save_post', array( &$this, 'yks_save' ) );

		add_filter( 'yks_display_now', array( &$this, 'yks_check_show' ), 10, 2 );
		add_filter( 'yks_display_now', array( &$this, 'yks_check_hide' ), 9, 2 );
	}

	/**
	 * Add metaboxes.
	 */
	public function yks_add() {

		$this->_mbox['context']  = empty( $this->_mbox['context'] ) ? 'normal' : $this->_mbox['context'];
		$this->_mbox['priority'] = empty( $this->_mbox['priority'] ) ? 'high' : $this->_mbox['priority'];
		$this->_mbox['show_on']  = empty( $this->_mbox['show_on'] ) ? array( 'key' => false, 'value' => false ) : $this->_mbox['show_on'];
		$this->_mbox['hide_on']  = empty( $this->_mbox['hide_on'] ) ? array( 'key' => false, 'value' => false ) : $this->_mbox['hide_on'];
		$this->_mbox['pages']    = is_array( $this->_mbox['pages'] ) ? $this->_mbox['pages'] : array( $this->_mbox['pages'] );

		foreach ( $this->_mbox['pages'] as $page ) {
			if ( apply_filters( 'yks_display_now', true, $this->_mbox ) ) {
				add_meta_box( $this->_mbox['id'], $this->_mbox['title'], array( &$this, 'fields_callback' ), $page, $this->_mbox['context'], $this->_mbox['priority'] );
			}
		}
	}

	/**
	 * Determines whether the metabox should display for this post.
	 *
	 * @param bool  $display Show (true) or hide (false).
	 * @param array $mbox The metabox values.
	 *
	 * @return bool
	 */
	public function yks_check_show( $display, $mbox ) {

		if ( 'id' !== $mbox['show_on']['key'] ) {
			return $display;
		}

		$post_id = $this->check_show_or_hide_get_id();

		if ( empty( $post_id ) ) {
			return false;
		}

		// Force the value to be an array.
		$mbox['show_on']['value'] = ! is_array( $mbox['show_on']['value'] ) ? array( $mbox['show_on']['value'] ) : $mbox['show_on']['value'];

		// If current page id is in the included array, display the metabox.
		if ( in_array( $post_id, $mbox['show_on']['value'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Determines whether the metabox should be hidden for this post.
	 *
	 * @param bool  $display Show (true) or hide (false).
	 * @param array $mbox The metabox values.
	 *
	 * @return bool
	 */
	public function yks_check_hide( $display, $mbox ) {

		if ( 'id' !== $mbox['hide_on']['key'] ) {
			return $display;
		}

		$post_id = $this->check_show_or_hide_get_id();

		if ( empty( $post_id ) ) {
			return true;
		}

		// Force the value to be an array.
		$mbox['hide_on']['value'] = ! is_array( $mbox['hide_on']['value'] ) ? array( $mbox['hide_on']['value'] ) : $mbox['hide_on']['value'];

		// If current page id is in the included array, hide the metabox.
		if ( in_array( $post_id, $mbox['hide_on']['value'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check if our post ID is in the $_GET/$_POST vars
	 */
	private function check_show_or_hide_get_id() {

		if ( isset( $_GET['post'] ) ) {

			return absint( $_GET['post'] );

		} elseif ( isset( $_REQUEST['wp_meta_box_nonce'] ) ) {

			if ( isset( $_REQUEST['wp_meta_box_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wp_meta_box_nonce'] ) ), basename( __FILE__ ) ) ) {

				if ( isset( $_POST['post_ID'] ) ) {
					return absint( $_POST['post_ID'] );
				}
			}
		}
	}

	/**
	 * Show fields
	 **/
	public function fields_callback() {

		/**
		 * Verify nonce
		 */
		if ( isset( $_REQUEST['wp_meta_box_nonce'] ) ) {

			if ( ! isset( $_REQUEST['wp_meta_box_nonce'] ) || isset( $_REQUEST['wp_meta_box_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wp_meta_box_nonce'] ) ), basename( __FILE__ ) ) ) {
				return false;
			}
		}

		$group_mbox = isset( $this->_mbox['group'] ) && $this->_mbox['group'] === true;

		$this->create_field_opening_html( $group_mbox );

		if ( $group_mbox === true ) {
			$group_headers = $this->create_group_headers();
		}

		foreach ( $this->_mbox['fields'] as $field ) :

			$group_field = $field['type'] === 'group';

			if ( $group_field && ! isset( $first_group_field_processed ) ) {
				echo $group_headers;
				$first_group_field_processed = true;
			}

			// Put each grouped field's fields in a container, hide all of them except the first.
			if ( $group_field ) {
				$style = isset( $style ) ? 'style="display: none"' : '';
				echo '<div class="yks-mbox-group ' . esc_attr( $field['id'] ) . '" ' . $style . '>';
			}

			// For fields that are in a 'grouped' metabox but not in a group.
			if ( ! $group_field && $group_mbox === true ) {
				echo '<div class="yks-mbox-group yks-mbox-groupless-group">';
			}

			$this->generate_fields( $field );

			// For fields that are in a 'grouped' metabox but not in a group, we need to close the div when we're at the end.
			if ( ! $group_field && $group_mbox === true ) {
				echo '</div>';
			}

			// Close the group-specific div.
			if ( $group_field ) {
				echo '<hr>';
				echo '</div>';
			}

		endforeach;

		$this->create_field_closing_html( $group_mbox );
	}

	/**
	 * Create the opening HTML for our fields
	 *
	 * @param bool $group Whether this is field is of the type group.
	 */
	private function create_field_opening_html( $group ) {

		// Add a nonce.
		echo '<input type="hidden" name="wp_meta_box_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';

		if ( $group === true ) {
			echo '<div class="group yks_mbox">';
		} else {
			echo '<table class="form-table yks_mbox">';
		}
	}

	/**
	 * For groups, display the group tabs
	 */
	private function create_group_headers() {
		$group_headers  = '';
		$group_headers .= '<div class="group-tabs-list-container">';
		$group_headers .= '<ul class="group-tabs-list">';

		foreach ( $this->_mbox['fields'] as $field ) {

			if ( $field['type'] !== 'group' ) {
				continue;
			}

			$active = isset( $active ) ? '' : 'active';

			$group_headers .= '<li class="' . $active . ' group-tabs-list-item">';
			$group_headers .= '<a data-tab="' . esc_attr( $field['id'] ) . '">' . $field['name'] . '</a>';
			$group_headers .= '</li>';
		}

		$group_headers .= '</ul>';
		$group_headers .= '</div>';

		return $group_headers;
	}

	/**
	 * Close the HTML for groups
	 *
	 * @param bool $group Whether this is field is of the type group.
	 */
	private function create_field_closing_html( $group ) {

		if ( $group === true ) {
			echo '</div>';
		} else {
			echo '</table>';
		}
	}

	/**
	 * Print field HTML
	 *
	 * @param array $field The field we're dealing with.
	 */
	private function generate_fields( $field ) {
		global $post;

		if ( $field['type'] === 'group' ) {

			foreach ( $field['fields'] as $field ) {
				echo '<div class="yks-mbox-group-field yks-mbox-' . $field['type'] . '">';
				$this->generate_fields( $field );
				echo '</div>';
			}

			return;
		}

		// Check if template for field exists in theme or framework.
		$filepath = yikes_get_file_template_location( $field );

		if ( $filepath !== false ) {

			// Set up blank or default values for empty ones.
			$field['name'] = isset( $field['name'] ) ? $field['name'] : '';
			$field['desc'] = isset( $field['desc'] ) ? $field['desc'] : '';
			$field['std']  = isset( $field['std'] ) ? $field['std'] : '';
			$meta          = isset( $field['id'] ) ? get_post_meta( $post->ID, $field['id'], true ) : '';

			echo '<tr>';

			if ( 'title' === $field['type'] ) {
				echo '<td colspan="2">';
			} else {

				if ( $this->_mbox['show_names'] ) {
					echo '<th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['name'] ) . '</label></th>';
				}
				echo '<td>';
			}
			include $filepath;
			do_action( 'yks_render_' . $field['type'], $field, $meta );
			echo '</td>','</tr>';
		}
	}

	/**
	 * Get and return the submitted meta value.
	 *
	 * @param int    $post_id Get post id.
	 * @param string $field_id Get field id.
	 * @param string $field_type Get field type.
	 * @param bool   $validate trigger.
	 **/
	private function yks_get_submit_meta( $post_id, $field_id, $field_type, $validate = true ) {

		/**
		 * Verify nonce
		 */
		if ( isset( $_REQUEST['wp_meta_box_nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wp_meta_box_nonce'] ) ), basename( __FILE__ ) ) ) {
				return false;
			}
		}

		/**
		 * Get the raw $_POST data.
		 */
		$raw = isset( $_POST[ $field_id ] ) ? $_POST[ $field_id ] : null;

		/**
		 * Check to prevent saving of fields that only contain spaces and/or new lines.
		 */
		if ( ! is_null( $raw ) && ! is_array( $raw ) ) {
			$is_not_empty = preg_replace( '/\s+|&nbsp;/', '', wp_strip_all_tags( $raw ) );
			$raw = $is_not_empty ? $raw : $is_not_empty;
		}

		/**
		 * Retrieve function that validate and sanitizes data based on field type.
		 */
		if ( function_exists( 'yks_save_' . $field_type ) ) {

			$func = 'yks_save_' . $field_type;

			if ( 'hidden_pass' === $field_type ) {
				$value = $func( $raw, $post_id, $field_id );
			} else {
				$value = $func( $raw );
			}
		} else {
			$value = $raw;
		}

		return $value;
	}

	/**
	 * Save data from metabox
	 *
	 * @param protected $post_id Show or not.
	 **/
	public function yks_save( $post_id ) {

		$post_type = get_post_type( $post_id );

		// Verify nonce.
		if ( ! isset( $_REQUEST['wp_meta_box_nonce'] ) || ( isset( $_REQUEST['wp_meta_box_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wp_meta_box_nonce'] ) ), basename( __FILE__ ) ) ) ) {
			return $post_id;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check if the user can edit the page/post.
		if ( 'page' === $post_type ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		/**
		 * Get the post types applied to the metabox group and compare it to the post type of the content.
		 */
		$meta_type = $this->_mbox['pages'];
		$type_comp = in_array( $post_type, $meta_type, true );

		foreach ( $this->_mbox['fields'] as $field ) :

			$name = $field['id'];

			$old = get_post_meta( $post_id, $name, true );
			$new = $this->yks_get_submit_meta( $post_id, $field['id'], $field['type'] );

			// Check if we're saving a taxonomy.
			if ( false !== $type_comp && in_array( $field['type'], array( 'taxonomy_select', 'taxonomy_radio', 'taxonomy_multicheck' ), true ) ) :

				$new = wp_set_object_terms( $post_id, $this->yks_get_submit_meta( $post_id, $field['id'], $field['type'], false ), $field['taxonomy'] );

			elseif ( $field['type'] ) :
				switch ( $field['type'] ) :
					case 'group':
						foreach ( $field['fields'] as $f ) {

							$name = $f['id'];
							$old  = get_post_meta( $post_id, $name, true );
							$new  = $this->yks_get_submit_meta( $post_id, $f['id'], $f['type'] );

							// Check if we're saving a taxonomy.
							if ( false !== $type_comp && in_array( $f['type'], array( 'taxonomy_select', 'taxonomy_radio', 'taxonomy_multicheck' ), true ) ) {
								$new = wp_set_object_terms( $post_id, $this->yks_get_submit_meta( $post_id, $f['id'], $f['type'], false ), $f['taxonomy'] );
							}

							if ( ! empty( $new ) && $new !== $old ) {
								update_post_meta( $post_id, $name, $new );
							} elseif ( empty( $new ) && ! empty( $old ) ) {
								delete_post_meta( $post_id, $name );
							}
						}
						break;

					default:
						if ( ! empty( $new ) && $new !== $old ) {
							update_post_meta( $post_id, $name, $new );
						} elseif ( empty( $new ) && ! empty( $old ) ) {
							delete_post_meta( $post_id, $name );
						}
						break;
				endswitch;
			endif;
		endforeach;
	}
}

$mboxs = apply_filters( 'yks_mboxes', array() );

foreach ( $mboxs as $mbox ) {
	new YIKES_CPT_Meta_Boxes( $mbox );
}
