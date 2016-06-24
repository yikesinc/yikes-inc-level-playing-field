<?php
// If accessed Directly - Abort!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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

	/**
	 * Return the number of columns for the application
	 * @return int Returns the number of columns for the application form.
	 */
	public function get_application_columns() {
		return '2'; // this should be set on the settings page/shortcode attributes
	}

	/**
	 * Store the template path for Level Playing Field
	 * @return string Path to the template directory.
	 */
	public function template_path() {
		return apply_filters( 'yikes_level_playing_field_template_path', 'level-playing-field/' );
	}

	/**
	* is_product_taxonomy - Returns true when viewing a job taxonomy archive.
	* @return bool
	*/
	public function is_job_taxonomy() {
		return is_tax( get_object_taxonomies( 'jobs' ) );
	}
}

/**
 * Core Functions - accessible outside of any classes
 * eg: used on signle job posting pages etc.
 */

/**
* Get template part (for templates like the shop-loop).
*
* LPF_TEMPLATE_DEBUG_MODE will prevent overrides in themes from taking priority.
*
* @access public
* @param mixed $slug
* @param string $name (default: '')
*/
function lpf_get_template_part( $slug, $name = '' ) {
	$template = '';
	// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php
	if ( $name && ! LPF_TEMPLATE_DEBUG_MODE ) {
		$template = locate_template( array( "{$slug}-{$name}.php", YIKES_LEVEL_PLAYING_FIELD_PATH . "{$slug}-{$name}.php" ) );
	}
	// Get default slug-name.php
	if ( ! $template && $name && file_exists( YIKES_LEVEL_PLAYING_FIELD_PATH . "templates/{$slug}-{$name}.php" ) ) {
		$template = YIKES_LEVEL_PLAYING_FIELD_PATH . "templates/{$slug}-{$name}.php";
	}
	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
	if ( ! $template && ! LPF_TEMPLATE_DEBUG_MODE ) {
		$template = locate_template( array( "{$slug}.php", YIKES_LEVEL_PLAYING_FIELD_PATH . "{$slug}.php" ) );
	}
	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'yikes_level_playing_field_get_template_part', $template, $slug, $name );
	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function lpf_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}

	$located = lpf_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );
		return;
	}
	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'lpf_get_template', $located, $template_name, $args, $template_path, $default_path );
	do_action( 'woocommerce_before_template_part', $template_name, $template_path, $located, $args );
	include( $located );
	do_action( 'woocommerce_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function lpf_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = apply_filters( 'yikes_level_playing_field_template_path', 'level-playing-field/' );
	}
	if ( ! $default_path ) {
		$default_path = YIKES_LEVEL_PLAYING_FIELD_PATH . 'templates/';
	}
	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name
		)
	);
	// Get default template/
	if ( ! $template || WC_TEMPLATE_DEBUG_MODE ) {
		$template = $default_path . $template_name;
	}
	// Return what we found.
	return apply_filters( 'yikes_level_playing_field_locate_template', $template, $template_name, $template_path );
}

/**
 * Adds extra post classes for products.
 *
 * @since 1.0.0
 * @param array $classes
 * @param string|array $class
 * @param int $post_id
 * @return array
 */
function lpf_job_classes( $classes, $class = '', $post_id = '' ) {
	if ( ! $post_id || 'jobs' !== get_post_type( $post_id ) ) {
		return $classes;
	}
	$job = get_post( $post_id );
	if ( $job ) {
		$classes[] = 'cpt-job';
	}
	if ( false !== ( $key = array_search( 'hentry', $classes ) ) ) {
		unset( $classes[ $key ] );
	}
	return $classes;
}

/**
 * Get the job title template
 * @return mixed
 */
function yikes_level_playing_field_single_job_title() {
	lpf_get_template( 'single-job/title.php' );
}
add_action( 'yikes_level_playing_field_single_job_summary', 'yikes_level_playing_field_single_job_title', 5 );

/**
 * Ger the job description template
 * @return mixed
 */
function yikes_level_playing_field_single_job_content() {
	lpf_get_template( 'single-job/content.php' );
}
add_action( 'yikes_level_playing_field_after_single_job_summary', 'yikes_level_playing_field_single_job_content', 10 );
