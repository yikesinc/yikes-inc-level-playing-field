<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/public
 * @author     YIKES, Inc. <plugins@yikesinc.com>
 */
class Yikes_Inc_Level_Playing_Field_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Helper functions, to make accessible to this class
	 */
	private $helpers;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		// Load and instantiate our helper functions (class)
		$this->helpers = new Yikes_Inc_Level_Playing_Field_Helper( $this->plugin_name, $this->version );

		/* Register our Shortcodes */
		// Application Shortcode
		add_shortcode( 'lpf-application', array( $this, 'render_application_shortcode' ) );

		/* Template Loader */
		add_filter( 'template_include', array( $this, 'template_loader' ) );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yikes_Inc_Level_Playing_Field_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yikes_Inc_Level_Playing_Field_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/min/yikes-inc-level-playing-field-public.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Yikes_Inc_Level_Playing_Field_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Yikes_Inc_Level_Playing_Field_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/min/yikes-inc-level-playing-field-public.min.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Render the Job Application Form
	 * @param  array $atts  The shortcode attributes.
	 * @return string       HTML markup for th eform
	 */
	public function render_application_shortcode( $atts ) {

		// Parse the shortcode attributes
		$atts = shortcode_atts( array(
			'application' => false,
			'custom' => 'shortcode',
		), $atts, 'level-playing-field-application' );

		$application_fields = $this->helpers->get_application_fields( (int) $atts['application'] );

		if ( $application_fields ) {
			?>
			<form class="yikes-lpf-form yikes-lpf-section yikes-lpf-group">
				<?php
				foreach ( $application_fields as $app_field ) {
					// render the feild
					$this->helpers->render_field( $app_field );
				}
				?>
				<input type="submit" class="<?php echo esc_attr( apply_filters( 'yikes_level_playing_field_submit_button_class', 'yikes-lpf-submit' ) ); ?>" value="<?php esc_attr_e( 'Apply', 'yikes-inc-level-playing-field' ); ?>" />
			</form>
			<?php
		}

		// return the shortcode
		return wp_kses_post( '<strong>Level Playing Field Application Shortcode</strong>' );
	}

	/**
	 * Load the job posting template.
	 *
	 * Handles template usage so that we can use our own templates instead of the themes.
	 *
	 * Templates are in the 'templates' folder. Level Playing Field looks for theme.
	 * overrides in /theme/level-playing-field/ by default.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public function template_loader( $template ) {
		$find = array( 'single-job.php' );
		$file = '';
		// if embed, abort - return default template
		if ( is_embed() ) {
			return $template;
		}
		if ( is_single() && get_post_type() === 'jobs' ) {
			$file 	= 'single-job.php';
			$find[] = $file;
			$find[] = $this->helpers->template_path() . $file;
		} elseif ( $this->helpers->is_job_taxonomy() ) {
			$term   = get_queried_object();
			if ( is_tax( 'job_categories' ) || is_tax( 'job_tags' ) ) {
				$file = 'taxonomy-' . $term->taxonomy . '.php';
			} else {
				$file = 'archive-jobs.php';
			}
			$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = $this->helpers->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = $this->helpers->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = $file;
			$find[] = $this->helpers->template_path() . $file;
		} elseif ( is_post_type_archive( 'product' ) ) {
			$file 	= 'archive-product.php';
			$find[] = $file;
			$find[] = $this->helpers->template_path() . $file;
		}
		if ( $file ) {
			$template       = locate_template( array_unique( $find ) );
			if ( ! $template || YIKES_LEVEL_PLAYING_FIELD_TEMPLATE_DEBUG_MODE ) {
				$template = YIKES_LEVEL_PLAYING_FIELD_PATH . '/templates/' . $file;
			}
		}
		return $template;
	}
}
