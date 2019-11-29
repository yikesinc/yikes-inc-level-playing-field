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

require_once dirname( __DIR__ ) . '/libraries/awesome-yikes-framework/yks-mbox-framework.php';
