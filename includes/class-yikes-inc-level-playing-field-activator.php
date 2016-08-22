<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Yikes_Inc_Level_Playing_Field
 * @subpackage Yikes_Inc_Level_Playing_Field/includes
 * @author     YIKES, Inc. <plugins@yikesinc.com>
 */
class Yikes_Inc_Level_Playing_Field_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// Create the sidebar file in the active theme root
		self::create_applicant_messanger_sidebar_template();
	}

	public static function create_applicant_messanger_sidebar_template() {
		// Add our widget to the sidebar
		self::wpse_138242_pre_set_widget( 'applicant-messenger', 'wpb_widget', array(
			'title' => 'This was a test during plugin activation',
		) );
		// file_get_contents is not available, abort
		if ( ! function_exists( 'file_get_contents' ) ) {
			return;
		}
		$sidebar_template = locate_template( 'sidebar-applicant-messenger.php', false );
		// template exists, abort
		if ( ! empty( $sidebar_template ) ) {
			return;
		}
		// create the template
		$file_to_create = get_stylesheet_directory() . '/sidebar-applicant-messenger.php';
		$file_contents = file_get_contents( get_stylesheet_directory() . '/sidebar.php' );
		$updated_file_contents = self::update_file_contents( $file_contents );
		file_put_contents( $file_to_create, $updated_file_contents );
	}

	public static function update_file_contents( $contents ) {
		// replace is is_active_sidebar, dynamic_sidebar and get_sidebar
		$contents = preg_replace( '/is_active_sidebar\([^)]*\)/', "is_active_sidebar( 'applicant-messenger' )", $contents );
		$contents = preg_replace( '/dynamic_sidebar\([^)]*\)/', "dynamic_sidebar( 'applicant-messenger' )", $contents );
		$contents = preg_replace( '/get_sidebar\([^)]*\)/', "get_sidebar( 'applicant-messenger' )", $contents );
		return $contents;
	}

	public static function wpse_138242_pre_set_widget( $sidebar, $name, $args = array() ) {
		if ( ! $sidebars = get_option( 'sidebars_widgets' ) ) {
			$sidebars = array();
		}

		// Create the sidebar if it doesn't exist.
		if ( ! isset( $sidebars[ $sidebar ] ) ) {
			$sidebars[ $sidebar ] = array();
		}

		// Check for existing saved widgets.
		if ( $widget_opts = get_option( "widget_$name" ) ) {
			// Get next insert id.
			ksort( $widget_opts );
			end( $widget_opts );
			$insert_id = key( $widget_opts );
		} else {
			// None existing, start fresh.
			$widget_opts = array( '_multiwidget' => 1 );
			$insert_id = 0;
		}

		// Add our settings to the stack.
		$widget_opts[ ++$insert_id ] = $args;
		// Add our widget!
		$sidebars[ $sidebar ][] = "$name-$insert_id";

		update_option( 'sidebars_widgets', $sidebars );
		update_option( "widget_$name", $widget_opts );
	}
}
