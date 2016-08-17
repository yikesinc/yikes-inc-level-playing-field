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

	/**
	 * Get applicants for all or a specified job/application
	 * @param  int $application_id The Application/Job ID to retreive applicants for. (optional)
	 * @return int                 The number of applicants returned.
	 */
	public function get_applicant_count( $application_id = false ) {
		$application_query_args = array(
			'post_type' => 'applicants',
			'posts_per_page' => -1,
		);
		// If a specified job/application ID was passed in
		if ( $application_id ) {
			// Check for object or ID
			if ( is_object( $application_id ) || is_array( $application_id ) ) {
				$application_id = ( is_object( $application_id ) ) ? $application_id->ID : $application_id['ID'];
			}
			$application_query_args['meta_query'] = array(
				'key' => 'application_id',
				'value' => $application_id,
				'compare' => '=',
			);
		}
		$applicant_query = new WP_Query( $application_query_args );
		// Return the i18n formatted count (integer)
		return absint( number_format_i18n( $applicant_query->found_posts ) );
	}

	/**
	 * Get NEW applicants for all or a specified job/application
	 * Note: NEW means 'new_application' meta set to '1'
	 * @param  int $application_id The Application/Job ID to retreive applicants for. (optional)
	 * @return int                 The number of applicants returned.
	 */
	public function get_new_applicant_count( $application_id = false ) {
		$new_applicant_query_args = array(
			'post_type' => 'applicants',
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'new_applicant',
					'value' => '1',
					'compare' => '=',
				),
			),
		);
		// If a specified job/application ID was passed in
		if ( $application_id ) {
			// Check for object or ID
			if ( is_object( $application_id ) || is_array( $application_id ) ) {
				$application_id = ( is_object( $application_id ) ) ? $application_id->ID : $application_id['ID'];
			}
			$new_applicant_query_args['meta_query'] = array(
				'key' => 'application_id',
				'value' => $application_id,
				'compare' => '=',
			);
		}
		$new_applicant_query = new WP_Query( $new_applicant_query_args );
		// Return the i18n formatted count (integer)
		return absint( number_format_i18n( $new_applicant_query->found_posts ) );
	}

	/**
	 * Generate our new applicant badge
	 * @param  string  $type  The type of badge to return.
	 * @param  integer $count The number of applicants. (optional)
	 * @return mixed          HTML markup for the new applicant count badge.
	 */
	public function get_new_applicants_badge( $type = 'total', $count = false ) {
		switch ( $type ) {
			case 'default':
			case 'total':
				return '<span class="new-applicant-count-badge">' . sprintf( _n( '%s New Applicant', '%s New Applicants', $count, 'yikes-inc-level-playing-field' ), $count ) . '</div>';
				break;
			case 'user-badge':
				return '<span class="new-applicant-count-badge">' . __( 'New Applicant', 'yikes-inc-level-playing-field' ) . '</div>';
				break;
		}
	}

	/**
	 * Obfuscate a string, so admins cannot read the submitted text from the form
	 * @param  string $string String of text to be obfuscated.
	 * @return string         Obfuscated, obscured string.
	 */
	public function obfuscate_string( $string ) {
		// Get the string length, so we can return the same length
		$string_length = strlen( $string );
		// Random set of characters to use in our obfuscation
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$obfuscated_string = '';
		for ( $i = 0; $i < $string_length; $i++ ) {
			$obfuscated_string .= $characters[ rand( 0, $string_length - 1 ) ];
		}
		return $obfuscated_string;
	}

	/**
	 * Blur a string, so admins cannot read the submitted text from the form
	 * Note: Also add a noselect class to prevent users from being able to highlight the string
	 * @param  string $string String of text to be obfuscated.
	 * @return string         Obfuscated, obscured string.
	 */
	public function blur_string( $string ) {
		return '<span class="blur noselect">' . $string . '</span>';
	}

	/**
	 * Generate the status buttons in the admin table
	 * @param  integer   $applicant_id   The applicant ID to retreive the status for.
	 * @return mixed                     HTML content for the status buttns.
	 */
	public function generate_status_buttons( $applicant_id ) {
		$statuses = array(
			__( 'Yes', 'yikes-inc-level-playing-field' ) => 'success',
			__( 'No', 'yikes-inc-level-playing-field' ) => 'danger',
			__( 'Maybe', 'yikes-inc-level-playing-field' ) => 'warning',
		);
		$applicant_status = ( get_post_meta( $applicant_id, 'applicant_status', true ) ) ? get_post_meta( $applicant_id, 'applicant_status', true ) : 'needs-review';
		ob_start();
		// Loop over status and create the button
		foreach ( $statuses as $status_btn_text => $status_btn_class ) {
			// Setup the classes
			$btn_classes = array(
				'yikes-btn',
				'yikes-btn-small',
				'yikes-btn-' . $status_btn_class,
			);
			// Set the inactive class, and button calss
			if ( strtolower( $status_btn_text ) !== $applicant_status ) {
				$btn_classes[] = 'inactive';
			}
			if ( 'needs-review' !== $status_btn_text ) {
				echo wp_kses( sprintf( '<a href="#" onclick="toggleApplicantStatus( this, %s );return false;" data-attr-status="' . strtolower( $status_btn_text ) . '" class="' . implode( ' ', $btn_classes ) . '">' . $status_btn_text . '</a>', $applicant_id  ), array(
					'a' => array(
						'href' => array(),
						'onclick' => array(),
						'data-attr-status' => array(),
						'class' => array(),
					),
				) );
			}
		}
		$buttons = ob_get_contents();
		ob_get_clean();
		return $buttons;
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
	// Look within passed path within the theme - this has priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
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

/** Format Money Values **/
if ( ! function_exists( 'yikes_format_money' ) ) {
	/**
	* Format the salary value into an appropriate format
	* @param  int $value The original value to format
	* @return int        Final formatted value for the money.
	*/
	function yikes_format_money( $value ) {
		// If the PHP function money_format() exists...
		if ( function_exists( 'money_format' ) ) {
			// Set the locale based on the WordPress settings
			setlocale( LC_MONETARY, get_locale() );
			return money_format( '%(#10n', $value );
		}
		// else...
		$currency_sign = get_option( 'yikes_level_playing_field_money_format', '$' );
		return $currency_sign . number_format( $value, 2 );
	}
}
