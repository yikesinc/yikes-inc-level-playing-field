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

require_once __DIR__ . '/bootstrap-autoloader.php';
require_once __DIR__ . '/bootstrap-awesome-framework.php';

( new PluginFactory() )->create()->register();
