<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

// Don't allow loading outside of WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Load and register the autoloader.
require_once __DIR__ . '/Autoloader.php';
$ylpf_autoloader = new Autoloader();
$ylpf_autoloader->add_namespace( __NAMESPACE__, __DIR__ );
$ylpf_autoloader->register();
