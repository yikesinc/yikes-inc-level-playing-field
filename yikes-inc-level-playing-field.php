<?php
/**
 * 		Plugin Name:       Level Playing Field
 * 		Plugin URI:        http://www.yikesinc.com
 * 		Description:       A WordPress plugin to anonymize job applications to fight bias in hiring and employment.
 * 		Version:           0.1
 * 		Author:            YIKES, Inc.
 * 		Author URI:        http://www.yikesinc.com
 * 		License:           GPL-3.0+
 *		License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * 		Text Domain:       yikes-inc-level-playing-field
 * 		Domain Path:       /languages
 *
 * 		Level Playing Field by YIKES Inc. is free software: you can redistribute it and/or modify
 * 		it under the terms of the GNU General Public License as published by
 * 		the Free Software Foundation, either version 2 of the License, or
 * 		any later version.
 *
 * 		Level Playing Field by YIKES Inc. is distributed in the hope that it will be useful,
 * 		but WITHOUT ANY WARRANTY; without even the implied warranty of
 * 		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * 		GNU General Public License for more details.
 *
 * 		You should have received a copy of the GNU General Public License
 *		along with Level Playing Field by YIKES Inc. If not, see <http://www.gnu.org/licenses/>.
 *
 *		We at YIKES Inc. embrace the open source philosophy on a daily basis. We donate company time back to the WordPress project,
 *		and constantly strive to improve the WordPress project and community as a whole. We eat, sleep and breathe WordPress.
 *
 *		"'Free software' is a matter of liberty, not price. To understand the concept, you should think of 'free' as in 'free speech,' not as in 'free beer'."
 *		- Richard Stallman
 *
**/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * 	Define path constant to our plugin directory.
 *
 * 	@since 6.0.0
 *	@return void
 */
if ( ! defined( 'YIKES_LEVEL_PLAYING_FIELD_PATH' ) ) {
	define( 'YIKES_LEVEL_PLAYING_FIELD_PATH' , trailingslashit( plugin_dir_path( __FILE__ ) ) );
}
/**
 * 	Define URL constant to our plugin directory.
 *
 * 	@since 6.0.0
 *	@return void
 */
if ( ! defined( 'YIKES_LEVEL_PLAYING_FIELD_URL' ) ) {
	define( 'YIKES_LEVEL_PLAYING_FIELD_URL' , trailingslashit( plugin_dir_url( __FILE__ ) ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-yikes-inc-level-playing-field-activator.php
 */
register_activation_hook( __FILE__, 'activate_yikes_inc_level_playing_field' );
function activate_yikes_inc_level_playing_field() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yikes-inc-level-playing-field-activator.php';
	Yikes_Inc_Level_Playing_Field_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-yikes-inc-level-playing-field-deactivator.php
 */
register_deactivation_hook( __FILE__, 'deactivate_yikes_inc_level_playing_field' );
function deactivate_yikes_inc_level_playing_field() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-yikes-inc-level-playing-field-deactivator.php';
	Yikes_Inc_Level_Playing_Field_Deactivator::deactivate();
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-yikes-inc-level-playing-field.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_yikes_inc_level_playing_field() {

	$plugin = new Yikes_Inc_Level_Playing_Field();
	$plugin->run();

}
run_yikes_inc_level_playing_field();
