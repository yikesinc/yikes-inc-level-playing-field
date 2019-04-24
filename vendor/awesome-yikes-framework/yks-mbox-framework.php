<?php
/**
 * YIKES' Awesome Framework.
 *
 * Main file to enqueue all files.
 *
 * @link http://www.yikesinc.com
 *
 * @package WordPress
 * @subpackage Component
 * @since 0.1
 **/

if ( ! class_exists( 'YIKES_Awesome_Framework_101', false ) ) {

	/**
	 * Class YIKES_Awesome_Framework_100.
	 */
	class YIKES_Awesome_Framework_101 {

		/**
		 * Current version number
		 *
		 * @var   string
		 */
		const VERSION = '1.0.1';

		/**
		 * Current version hook priority.
		 * Will decrement with each release
		 *
		 * @var   int
		 */
		const PRIORITY = 9997;

		/**
		 * Constructor. Define hooks.
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'init' ), self::PRIORITY );
		}

		/**
		 * Initialize everything.
		 */
		public function init() {

			if ( class_exists( 'YIKES_CPT_Meta_Boxes', false ) || class_exists( 'YIKES_Page_Meta_Boxes', false ) ) {
				return;
			}

			if ( ! defined( 'YIKES_Awesome_Framework_Version' ) ) {
				define( 'YIKES_Awesome_Framework_Version', self::VERSION );
			}

			if ( ! defined( 'YIKES_Awesome_Framework_Path' ) ) {
				define( 'YIKES_Awesome_Framework_Path', trailingslashit( dirname( __FILE__ ) ) );
			}

			// Including YKS_MBOX_URL for legacy support
			if ( ! defined( 'YIKES_Awesome_Framework_URI' ) && ! defined( 'YKS_MBOX_URL' ) ) {

				if ( strtoupper( substr( PHP_OS, 0, 3 ) ) === 'WIN' ) {
					define( 'YKS_MBOX_URL', trailingslashit( str_replace( DIRECTORY_SEPARATOR, '/', str_replace( str_replace( '/', DIRECTORY_SEPARATOR, WP_CONTENT_DIR ), content_url(), dirname( __FILE__ ) ) ) ) );
				} else {
					define( 'YKS_MBOX_URL', apply_filters( 'yks_meta_box_url', trailingslashit( str_replace( WP_CONTENT_DIR, content_url(), dirname( __FILE__ ) ) ) ) );
				}

				define( 'YIKES_Awesome_Framework_URI', YKS_MBOX_URL );
			}

			// Include our save functions/files.
			$this->include_save_files();

			// Metabox/field functionality for CPTs.
			require_once YIKES_Awesome_Framework_Path . 'classes/class-yikes-cpt-meta-boxes.php';

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

			require_once YIKES_Awesome_Framework_Path . 'inc/field-helper-functions.php';
		}

		/**
		 * Include all of our save field files/methods, check the theme for any theme-specific fields.
		 */
		private function include_save_files() {

			// Check theme for custom post processing functions && include files && create an array of all files.
			$theme_override_files = array();
			if ( file_exists( ( get_template_directory() . '/inc/cpt/cpt-fields/save-options/' ) ) !== false ) {
				foreach ( glob( ( get_template_directory() . '/inc/cpt/cpt-fields/save-options/yks-save-*.php' ) ) as $file ) {
					$theme_override_files[] = $this->get_filename_from_filepath( $file );
					// Include theme file.
					include $file;
				}
			}

			$custom_field_locations = apply_filters( 'yikes-awesome-framework-custom-field-directories', array() );

			if ( ! empty( $custom_field_locations ) ) {

				foreach ( $custom_field_locations as $dir ) {

					foreach ( glob( ( $dir . '/save-options/yks-save-*.php' ) ) as $file ) {

						$theme_override_files[] = $this->get_filename_from_filepath( $file );

						// Include theme file.
						include $file;
					}
				}
			}

			// Get post processing functions -- only include file if not in theme.
			foreach ( glob( dirname( __FILE__ ) . '/inc/save-options/yks-save-*.php' ) as $file ) {

				$filename = $this->get_filename_from_filepath( $file );

				if ( ! in_array( $filename, $theme_override_files, true ) ) {

					// Include starter framework file.
					include $file;
				}
			}
		}

		/**
		 * Gets the name of a file from the full filepath/filename
		 *
		 * @param string $file Full filepath and file.
		 *
		 * @return string $filename filename
		 */
		private function get_filename_from_filepath( $file ) {
			$filename    = '';
			$file_array  = array();
			$array_count = 0;

			$file_array = explode( '/', $file );
			if ( is_array( $file_array ) ) {
				$array_count    = count( $file_array );
				$array_position = $array_count - 1;
				$filename       = $file_array[ $array_position ];
			}

			return $filename;
		}

		/**
		 * Adding scripts and styles.
		 */
		public function enqueue_scripts() {

			// JavaScript dependencies.
			$script_dependencies = [ 'jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-datepicker', 'media-upload', 'thickbox', 'wp-color-picker' ];

			// CSS dependencies.
			$style_dependencies = [ 'thickbox', 'wp-color-picker' ];

			// Register scripts.
			wp_enqueue_script( 'yks-mbox-scripts', YKS_MBOX_URL . 'js/yks-mboxs.min.js', $script_dependencies, self::VERSION, true );
			wp_enqueue_script( 'yks-mbox-repeating-fields', YKS_MBOX_URL . 'js/yks-mboxs-repeating-fields-helper-functions.min.js', [], self::VERSION, true );
			wp_localize_script(
				'yks-mbox-scripts',
				'yks_mbox_ajax_data',
				[
					'ajax_nonce' => wp_create_nonce( 'ajax_nonce' ),
					'post_id'    => get_the_ID(),
					'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
					'spinner'    => '<img class="oembed_spinner_gif" src="' . esc_url( admin_url( 'images/loading.gif' ) ) . '">',
				]
			);

			// Register styles.
			wp_enqueue_style( 'yks-mbox-styles', YKS_MBOX_URL . 'css/style.min.css', $style_dependencies, self::VERSION, 'all' );
		}
	}
	new YIKES_Awesome_Framework_101();
}
