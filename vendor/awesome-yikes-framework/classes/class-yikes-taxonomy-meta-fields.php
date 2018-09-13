<?php

/**
 * YIKES_CPT_Meta_Box Class File
 *
 * Create Custom Metaboxes for a taxonomy
 */
class YIKES_Taxonomy_Meta_Boxes {

	/**
	 * Protected variable
	 *
	 * @access protected
	 * @var $data Variable array
	 */
	protected $data;

	/**
	 * @param array $data
	 */
	public function __construct( $data ) {

		if ( ! is_admin() ) {
			return;
		}

		$this->data = $data;

		add_action( 'admin_init', array( $this, 'add_fields' ) );
	}

	/**
	 * Check theme for field file - if it does not exist check framework
	 *
	 * @param array $field The field we're dealing with.
	 */
	public function get_file_template_location( $field ) {

		$field_name_with_dashes = str_replace( '_', '-', $field['type'] );

		if ( file_exists( ( get_template_directory() . '/inc/cpt/cpt-fields/fields/yks-' . $field_name_with_dashes . '.php' ) ) !== false ) {

			return get_template_directory() . '/inc/cpt/cpt-fields/fields/yks-' . $field_name_with_dashes . '.php';

		} elseif ( stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/fields/yks-' . $field_name_with_dashes . '.php' ) !== false ) {

			return stream_resolve_include_path( dirname( dirname( __FILE__ ) ) . '/inc/fields/yks-' . $field_name_with_dashes . '.php' );

		} else {

			return false;
		}
	}

	/**
	 * Add metaboxes.
	 */
	public function add_fields() {

		$this->data['taxonomy'] = is_array( $this->data['taxonomy'] ) ? $this->data['taxonomy'] : array( $this->data['taxonomy'] );

		foreach ( $this->data['taxonomy'] as $taxonomy_slug ) {
			add_action( $taxonomy_slug . '_add_form_fields', array( $this, 'fields_callback' ), 10, 1 );
			add_action( $taxonomy_slug . '_edit_form_fields', array( $this, 'fields_callback' ), 10, 2 );
			add_action( 'edited_' . $taxonomy_slug, array( $this, 'save' ), 10, 2 );
			add_action( 'create_' . $taxonomy_slug, array( $this, 'save' ), 10, 2 );
		}
	}

	/**
	 * Show fields
	 **/
	public function fields_callback( $term_object = null, $taxonomy_slug = null ) {

		// When adding a taxonomy, the fields are not in a table. 
		// When editing a taxonomy, the fields are in a table.
		// If the $term_object is an object, then we're editing a taxonomy and we should format our HTML as a table.
		$is_table = is_object( $term_object );

		// Need to do some variable switching here
		// the hook {$taxonomy}_edit_form_fields passes the term object as the first variable and the taxonomy slug as the second variable
		// the hook {$taxonomy}_add_form_fields passes the taxonomy slug as the first variable
		$taxonomy_slug = is_string( $term_object ) ? $term_object : $taxonomy_slug;

		// Verify nonce
		if ( isset( $_REQUEST['wp_term_meta_nonce'] ) ) {

			if ( ! isset( $_REQUEST['wp_term_meta_nonce'] ) || isset( $_REQUEST['wp_term_meta_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wp_term_meta_nonce'] ) ), basename( __FILE__ ) ) ) {
				return false;
			}
		}

		// Add our nonce value
		echo '<input type="hidden" name="wp_term_meta_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';

		foreach ( $this->data['fields'] as $field ) :

			$this->generate_fields( $field, $taxonomy_slug, $term_object, $is_table );

		endforeach;
	}

	/**
	 * Print field HTML
	 *
	 * @param array $field The field we're dealing with.
	 */
	private function generate_fields( $field, $taxonomy_slug, $term_object, $is_table ) {

		// Check if template for field exists in theme or framework.
		$filepath = $this->get_file_template_location( $field );

		if ( $filepath !== false ) {

			// Set up blank or default values for empty ones.
			$field['name'] = isset( $field['name'] ) ? $field['name'] : '';
			$field['desc'] = isset( $field['desc'] ) ? $field['desc'] : '';
			$field['std']  = isset( $field['std'] ) ? $field['std'] : '';
			$meta          = is_object( $term_object ) ? get_term_meta( $term_object->term_id, $field['id'], true ) : '';
			

			echo $is_table === true ? '<tr class="form-field term-' . esc_attr( $field['id'] ) . '-wrap">' : '<div class="form-field term-' . esc_attr( $field['id'] ) . '-wrap">';

			echo $is_table === true ? '<th scope="row">' : '';
			echo '<label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['name'] ) . '</label>';
			echo $is_table === true ? '</th>' : '';

			echo $is_table === true ? '<td>' : '';
			include $filepath;
			echo $is_table === true ? '</td>' : '';

			do_action( 'yks_render_' . $field['type'], $field, $meta, $is_taxonomy = true );

			echo $is_table === true ? '</tr>' : '</div>';
		}
	}

	/**
	 * Get and return the submitted meta value.
	 *
	 * @param int    $term_id    The taxonomy term's ID.
	 * @param string $field_id   The current field's ID.
	 * @param string $field_type The current field's type.
	 **/
	private function get_saved_value( $term_id, $field_id, $field_type ) {

		// Verify nonce.
		if ( isset( $_REQUEST['wp_term_meta_nonce'] ) ) {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wp_term_meta_nonce'] ) ), basename( __FILE__ ) ) ) {
				return false;
			}
		}

		// Get the raw $_POST data.
		$raw = isset( $_POST[ $field_id ] ) ? $_POST[ $field_id ] : null;

		// Check for our custom save function.
		if ( function_exists( 'yks_save_' . $field_type ) ) {

			$func  = 'yks_save_' . $field_type;
			$value = $func( $raw );

		} else {
			$value = $raw;
		}

		return $value;
	}

	/**
	 * Save data from metabox
	 *
	 * @param protected $term_id Show or not.
	 **/
	public function save( $term_id, $taxonomy = '' ) {

		// Verify nonce.
		if ( ! isset( $_REQUEST['wp_term_meta_nonce'] ) || ( isset( $_REQUEST['wp_term_meta_nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wp_term_meta_nonce'] ) ), basename( __FILE__ ) ) ) ) {
			return $term_id;
		}

		// Check autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $term_id;
		}

		// Capabilities Check??
		// current_user_can...

		foreach ( $this->data['fields'] as $field ) {

			$name = $field['id'];
			$old  = get_term_meta( $term_id, $name, true );
			$new  = $this->get_saved_value( $term_id, $field['id'], $field['type'] );

			if ( ! empty( $new ) && $new !== $old ) {
				update_term_meta( $term_id, $name, $new );
			} elseif ( empty( $new ) && ! empty( $old ) ) {
				delete_term_meta( $term_id, $name );
			}

		}
	}
}

$meta_field_data = apply_filters( 'yks_taxonomy_meta_fields', array() );

foreach ( $meta_field_data as $datum ) {
	new YIKES_Taxonomy_Meta_Boxes( $datum );
}
