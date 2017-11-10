<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @license GPL2
 * @wordpress-plugin
 *
 * Plugin Name:  Level Playing Field 2
 * Plugin URI:   http://www.yikesinc.com
 * Description:  A WordPress plugin to anonymize job applications to fight bias in hiring and employment.
 * Author:       YIKES, Inc.
 * Author URI:   http://www.yikesinc.com
 * Text Domain:  yikes-level-playing-field
 * Domain Path:  /languages
 * Version:      0.2.0
 * Requires PHP: 5.6
 */

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}


// Bootstrap the plugin.
require_once( dirname( __FILE__ ) . '/src/bootstrap.php' );
