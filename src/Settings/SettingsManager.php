<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Settings;

use Yikes\LevelPlayingField\Exception\Exception;
use Yikes\LevelPlayingField\Service;

/**
 * Class SettingsManager.
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
final class SettingsManager implements Service {

	/**
	 * Register the hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_action( 'wp_ajax_lpf_save_settings', function() {
			$this->save();
		} );
	}

	/**
	 * AJAX handler to save our settings.
	 *
	 * @since 1.0.0
	 */
	private function save() {

		// Handle nonce.
		if ( ! check_ajax_referer( 'save_settings', 'nonce', false ) ) {
			wp_send_json_error( [
				'reason' => __( 'An error occurred: Failed to validate the nonce.', 'level-playing-field' ),
			], 400 );
		}

		// Fetch our current settings.
		$settings = new Settings();

		// Get the posted settings and process them.
		$posted_settings = wp_unslash( $_POST['settings'] );
		$failures        = [];
		foreach ( $posted_settings as $key => $value ) {
			try {
				$settings->$key = $value;
			} catch ( Exception $e ) {
				$failures[ $key ] = $e->getMessage();
			}
		}

		if ( ! empty( $failures ) ) {
			wp_send_json_error( [
				'reason'   => __( 'Failure: some settings failed to save.', 'level-playing-field' ),
				'failures' => $failures,
			], 400 );
		} else {
			wp_send_json_success( [
				'reason' => __( 'Success: Settings Saved.', 'level-playing-field' ),
			], 200 );
		}
	}
}
