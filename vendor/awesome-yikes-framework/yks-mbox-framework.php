<?php
/**
 * Yks Mbox Framework
 *
 * Main file to enqueue all files.
 *
 * @link http://www.yikesinc.com
 *
 * @package WordPress
 * @subpackage Component
 * @since 0.1
 **/

if ( ! class_exists( 'YIKES_Awesome_Framework_100', false ) ) {

	class YIKES_Awesome_Framework_100 {

		/**
		 * Current version number
		 *
		 * @var   string
		 */
		const VERSION = '1.0.0';

		/**
		 * Current version hook priority.
		 * Will decrement with each release
		 *
		 * @var   int
		 */
		const PRIORITY = 9998;

		public function __construct() {

			add_action( 'init', array( $this, 'init' ), self::PRIORITY );

		}

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

			// Include our save functions/files
			$this->include_save_files();

			// Metabox/field functionality for CPTs
			require_once YIKES_Awesome_Framework_Path . 'classes/class-yikes-cpt-meta-boxes.php';

			// Metabox/field functionality for options pages
			require_once YIKES_Awesome_Framework_Path . 'classes/class-yikes-page-meta-boxes.php';

			// Metabox/field functionality for taxonomies
			require_once YIKES_Awesome_Framework_Path . 'classes/class-yikes-taxonomy-meta-fields.php';
			
			// Metafield sortable columsn for CPT's
			require_once YIKES_Awesome_Framework_Path . 'classes/class-yikes-sortable-columns.php';

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

			require_once YIKES_Awesome_Framework_Path . 'inc/field-helper-functions.php';
		}

		/**
		* Include all of our save field files/methods, check the theme for any theme-specific fields
		*/
		private function include_save_files() {

			/**
			* Check theme for custom post processing functions && include files && create an array of all files
			*/
			$theme_override_files = array();
			if ( file_exists( ( get_template_directory() . '/inc/cpt/cpt-fields/save-options/' ) ) != false ) {
				foreach ( glob( ( get_template_directory() . '/inc/cpt/cpt-fields/save-options/yks-save-*.php' ) ) as $file ) {
					$theme_override_files[] = $this->yks_cpt_get_filename_from_filepath( $file );
					// Include theme file.
					include( $file );
				}
			}

			/**
			 *  Get post processing functions -- only include file if not in theme.
			 */
			foreach ( glob( dirname( __FILE__ ) . '/inc/save-options/yks-save-*.php' ) as $file ) {

				$filename = $this->yks_cpt_get_filename_from_filepath( $file );

				if ( ! in_array( $filename, $theme_override_files ) ) {

					// Include starter framework file.
					include( $file );
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
		private function yks_cpt_get_filename_from_filepath( $file ) {
			$filename = '';
			$file_array = array();
			$array_count = 0;

			$file_array = explode( '/', $file );
			if ( is_array( $file_array ) ) {
				$array_count = count( $file_array );
				$array_position = $array_count - 1;
				$filename = $file_array[ $array_position ];
			}

			return $filename;
		}

		/**
		 * Adding scripts and styles.
		 */
		public function enqueue_scripts() {

			// JavaScript dependencies
			$yks_mbox_script_array = array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' , 'jquery-ui-datepicker', 'media-upload', 'thickbox', 'wp-color-picker' );

			// CSS dependencies
			$yks_mbox_style_array = array( 'thickbox', 'wp-color-picker' );

			// Register scripts
			wp_register_script( 'jquery-timepicker', YKS_MBOX_URL . 'js/jquery.timepicker.min.js' );
			wp_register_script( 'yks-mbox-scripts', YKS_MBOX_URL . 'js/yks-mboxs.js', $yks_mbox_script_array );
			wp_register_script( 'yks-mbox-repeating-fields', YKS_MBOX_URL . 'js/yks-mboxs-repeating-fields-helper-functions.min.js' );
			wp_localize_script( 'yks-mbox-scripts', 'yks_mbox_ajax_data', array( 
					'ajax_nonce'=> wp_create_nonce( 'ajax_nonce' ), 
					'post_id'	=> get_the_ID(), 
					'ajax_url'	=> esc_url( admin_url( 'admin-ajax.php' ) ),
					'spinner'	=> '<img class="oembed_spinner_gif" src="' . esc_url( admin_url( 'images/loading.gif' ) ) . '">'
				) 
			);
			wp_enqueue_script( 'jquery-timepicker' );
			wp_enqueue_script( 'yks-mbox-scripts' );
			wp_enqueue_script( 'yks-mbox-repeating-fields' );

			// Register styles
			wp_register_style( 'yks-mbox-styles', YKS_MBOX_URL . 'css/style.css', $yks_mbox_style_array );
			wp_register_style( 'jquery-timepicker', YKS_MBOX_URL . 'css/jquery.timepicker.custom.css' );
			wp_enqueue_style( 'yks-mbox-styles' );
			wp_enqueue_style( 'jquery-timepicker' );
	
		}
		
	}

	new YIKES_Awesome_Framework_100();

}