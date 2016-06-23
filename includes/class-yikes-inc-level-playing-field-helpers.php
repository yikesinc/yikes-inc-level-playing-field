<?php

/**
 * Helper Functions for Level Playing Field
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 */
class Yikes_Inc_Level_Playing_Field_Helper {
	/**
	 * The ID of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 */
	protected $version;

	// Constructor
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Helper function to retreive a set of application fields
	 * from a specific job listing
	 * @param  int   $job_id   Job ID to retreive applications from (eg: 2)
	 * @return array           The fields for the given job application, or false/empty array
	 */
	public function get_application_fields( $job_id = false ) {
		if ( ! $job_id ) {
			return false;
		}
		// include default fields
		$default_fields = include_once( YIKES_LEVEL_PLAYING_FIELD_PATH . 'includes/templates/default-application-fields.php' );
		// return & filter results
		return apply_filters( 'yikes_level_playing_field_application_fields', $default_fields, $job_id );
	}

	/**
	 * Render a field based on the passed in field data
	 * @param  array   $field_data   Field data to use when rendering this field
	 * @return string								 HTML markup for the new form field.
	 */
	public function render_field( $field_data ) {
		if ( $field_data ) {
			// check for custom_atts
			$custom_atts = ( isset( $field_data['custom_atts'] ) ) ? $field_data['custom_atts'] : false;
			$custom_atts_string = ( $custom_atts ) ? $this->build_custom_atts_string( $custom_atts ) : '';
			// Print the label
			echo '<div class="yikes-lpf-form-section yikes-lpf-col yikes-lpf-form-column-' . esc_attr( $this->get_application_columns() ) . '"><label>' . wp_kses_post( $field_data['label'] );
			// switch over each field type
			switch ( $field_data['type'] ) {
				default:
				case 'text':
					echo '<input type="' . esc_attr( $field_data['type'] ) . '" class="' . esc_attr( $field_data['class'] ) . '" name ="' . esc_attr( $field_data['name'] ) . '" ' . wp_kses_post( $custom_atts_string ) . ' />';
					break;
			}
			echo '</label></div>';
		}
	}

	/**
	 * Build our custom attributes string for the input field (if set)
	 * @param  array   $atts_array Array of data to be used when building the form field
	 * @return string             The string of custom field attributes
	 */
	public function build_custom_atts_string( $atts_array ) {
		$atts_string = '';
		if ( $atts_array && ! empty( $atts_array ) ) {
			foreach ( $atts_array as $key => $val ) {
				$atts_string .= ' ' . $key . '="' . $val . '"';
			}
		}
		return $atts_string;
	}

	public function get_application_columns() {
		return '2'; // this should be set on the settings page/shortcode attributes
	}
}
