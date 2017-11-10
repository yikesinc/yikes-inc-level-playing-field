<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @license GPL2
 * @wordpress-plugin
 *
 * Plugin Name:  Level Playing Field
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

/*
 * Require PHP 5.6. Checking here allows the plugin to fail gracefully.
 *
 * Note that this file needs to be compatible with PHP 5.2 at a minimum.
 */
if ( version_compare( '5.6.0', PHP_VERSION, '>' ) ) {
	add_action( 'admin_notices', 'lpf_admin_notices' );

	/**
	 * Display admin notice for incompatible PHP version.
	 *
	 * @since %VERSION%
	 */
	function lpf_admin_notices() {
		printf(
			'<div class="error"><p>%s</p></div>',
			sprintf(
				/* translators: %1$s is the required PHP version, %2$s is the current version */
				esc_html__( 'Yikes, Inc. Level Playing Fields requires PHP version %1$s or above. You site is using PHP version %2$s.' ),
				'5.6.0',
				esc_html( PHP_VERSION )
			)
		);
	}

	return;
}

// Bootstrap the plugin.
require_once( dirname( __FILE__ ) . '/src/bootstrap.php' );
