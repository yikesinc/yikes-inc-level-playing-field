<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Yikes_Level_Playing_Field
 */

namespace Yikes\LevelPlayingField\Tests;

// Load compatibility functions.
require_once __DIR__ . '/compat.php';

// Load and register the autoloader.
use Yikes\LevelPlayingField\Autoloader;

require_once( dirname( __DIR__ ) . '/src/Autoloader.php' );
$ylpf_autoloader = new Autoloader();
$ylpf_autoloader->add_namespace( 'Yikes\\LevelPlayingField', dirname( __DIR__ ) . '/src' );
//$ylpf_autoloader->add_namespace( 'Carbon_Fields', dirname( __DIR__ ) . '/vendor/htmlburger/carbon-fields/core' );
$ylpf_autoloader->register();

// Load the Awesome Framework.
//require_once( dirname( __DIR__ ) . '/vendor/awesome-yikes-framework/yks-mbox-framework.php' );

// Only load WP if we have a tests dir set.
$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/yikes-level-playing-field.php';
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
