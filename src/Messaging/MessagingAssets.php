<?php
/**
 * YIKES Inc. Level Playing Field Pro Plugin.
 *
 * @package Yikes\LevelPlayingField\Pro
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Messaging;

use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\ScriptAsset;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\REST\APISettings;

trait MessagingAssets {

	use AssetsAwareness {
		enqueue_assets as trait_enqueue_assets;
	}

	/**
	 * The handle for the messaging script.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $messaging_handle = 'lpf-messaging-admin-script';

	/**
	 * The handle for the messaging styles.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $style_handle = 'lpf-messaging-admin-styles';

	/**
	 * The handle for the timepicker script.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $timepicker_handle = 'jquery-timepicker-script';

	/**
	 * Load asset objects for use.
	 *
	 * @since 1.0.0
	 */
	protected function load_assets() {
		$this->assets = [
			$this->messaging_handle  => new ScriptAsset(
				$this->messaging_handle,
				'assets/js/messaging',
				[ 'jquery', 'jquery-ui-datepicker' ],
				false,
				ScriptAsset::ENQUEUE_FOOTER
			),
			$this->timepicker_handle => new ScriptAsset(
				$this->timepicker_handle,
				'/assets/vendor/timepicker/jquery.timepicker',
				[ $this->messaging_handle ],
				false,
				ScriptAsset::ENQUEUE_FOOTER
			),
			$this->style_handle      => new StyleAsset(
				$this->style_handle,
				'/assets/css/messaging'
			),
		];
	}

	/**
	 * Enqueue the known assets.
	 *
	 * @since 1.0.0
	 */
	protected function enqueue_assets() {
		$this->add_script_localization();
		$this->trait_enqueue_assets();
	}

	/**
	 * Add the script localization separately from the registration.
	 *
	 * This is necessary because we need to use get_rest_url(), which isn't available early
	 * on the plugins_loaded hook where asset registration takes place.
	 *
	 * @since 1.0.0
	 */
	protected function add_script_localization() {
		$post_id = isset( $_GET['post'] ) ? filter_var( $_GET['post'], FILTER_SANITIZE_NUMBER_INT ) : 0;
		$this->assets[ $this->messaging_handle ]->add_localization(
			'messaging_data',
			[
				'post'        => [
					'ID' => $post_id,
				],
				'ajax'        => [
					'url'             => admin_url( 'admin-ajax.php' ),
					'send_nonce'      => wp_create_nonce( 'send_message' ),
					'refresh_nonce'   => wp_create_nonce( 'refresh_conversation' ),
					'interview_nonce' => wp_create_nonce( 'send_interview_request' ),
				],
				'is_metabox'  => is_admin(),
				'spinner_url' => admin_url( 'images/spinner-2x.gif' ),
				'api'         => [
					'nonce' => wp_create_nonce( 'wp_rest' ),
					'url'   => get_rest_url( null, '/' . APISettings::LPF_NAMESPACE ),
					'route' => APISettings::INTERVIEW_STATUS_ROUTE . '/',
				],
			]
		);
	}
}
