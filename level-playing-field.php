<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @license GPL2
 * @wordpress-plugin
 *
 * Plugin Name:  Level Playing Field
 * Plugin URI:   http://www.yikesplugins.com
 * Description:  A WordPress plugin to anonymize job applications to fight bias in hiring and employment.
 * Author:       YIKES, Inc.
 * Author URI:   http://www.yikesinc.com
 * Text Domain:  yikes-level-playing-field
 * Domain Path:  /languages
 * Version:      1.0.1
 * Requires PHP: 5.6
 * License:      GPL-2.0+
 *
 * The code in this plugin is a derivative work of the code written by Alain Schlesser in various
 * libraries, which is licensed GPLv2. This code is therefore also licensed under the terms of
 * the GNU Public License, version 2.
 *
 * Copyright 2016-2017 Alain Schlesser
 * @link http://www.alainschlesser.com/
 * @link https://github.com/schlessera/as-speaking
 * @link https://github.com/schlessera/better-settings-v1
 *
 * Copyright 2017-2018 YIKES, Inc.
 *
 * Level Playing Field is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Level Playing Field is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Level Playing Field. If not, see <http://www.gnu.org/licenses/>.
 *
 * We at YIKES, Inc. embrace the open source philosophy on a daily basis. We donate company time back to the
 * WordPress project, and constantly strive to improve the WordPress project and community as a whole.
 *
 * "'Free software' is a matter of liberty, not price. To understand the concept, you should think of 'free'
 * as in 'free speech,' not as in 'free beer'."
 * - Richard Stallman
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
	add_action( 'admin_notices', 'lpf_php_version_notice' );

	/**
	 * Display admin notice for incompatible PHP version.
	 *
	 * @since 1.0.0
	 */
	function lpf_php_version_notice() {
		printf(
			'<div class="error"><p>%s</p></div>',
			sprintf(
				/* translators: %1$s is the required PHP version, %2$s is the current version */
				esc_html__( 'Level Playing Field requires PHP version %1$s or above. Your site is using PHP version %2$s.', 'level-playing-field' ),
				'5.6.0',
				esc_html( PHP_VERSION )
			)
		);
	}

	return;
}

/*
 * Require WordPress 4.8+. Checking here allows for graceful failure.
 */
if ( version_compare( '4.8', $GLOBALS['wp_version'], '>' ) ) {
	add_action( 'admin_notices', 'lpf_wp_version_notice' );

	/**
	 * Display admin notice for incompatible WP version.
	 *
	 * @since 1.0.0
	 */
	function lpf_wp_version_notice() {
		printf(
			'<div class="error"><p>%s</p></div>',
			sprintf(
				/* translators: %1$s is the required WP version, %2$s is the current version */
				esc_html__( 'Level Playing Field requires WordPress version %1$s or above. Your site is using WordPress version %2$s.', 'level-playing-field' ),
				'4.8',
				esc_html( $GLOBALS['wp_version'] )
			)
		);
	}

	return;
}

// Bootstrap the plugin.
require_once dirname( __FILE__ ) . '/src/load.php';
