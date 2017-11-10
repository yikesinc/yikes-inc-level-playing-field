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

namespace Yikes\LevelPlayingField;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Load and register the autoloader.
require_once( __DIR__ . '/src/Autoloader.php' );
$ylpf_autoloader = new Autoloader();
$ylpf_autoloader->add_namespace( __NAMESPACE__, __DIR__ . '/src' )->register();

// Kick it off.
PluginFactory::create()->register();
