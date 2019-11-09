<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\CustomPostType\JobManager;

/**
 * Welcome.
 *
 * @package Yikes\LevelPlayingField
 */
class Welcome implements Service {

	const FIRST_TIME_ACTIVATED_FLAG      = 'lpf_first_time_activated';
	const FIRST_TIME_ACTIVATED_QUERY_VAR = 'welcome-to-lpf';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since 1.0.0
	 */
	public function register() {
		add_action( 'admin_notices', [ $this, 'welcome_message' ] );
		add_action( 'init', [ $this, 'welcome_redirect' ], 100, 2 );
	}

	/**
	 * Display a welcome message.
	 */
	public function welcome_message() {
		if ( ! isset( $_GET[ self::FIRST_TIME_ACTIVATED_QUERY_VAR ] ) ) {
			return;
		}
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Thanks for installing Level Playing Field!', 'level-playing-field' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Maybe redirect to the LPF Jobs page when the plugin is activated for the first time.
	 */
	public function welcome_redirect() {

		if ( get_option( self::FIRST_TIME_ACTIVATED_FLAG, false ) ) {
			return;
		}

		update_option( self::FIRST_TIME_ACTIVATED_FLAG, true );

		wp_safe_redirect(
			add_query_arg(
				[
					'post_type'                          => JobManager::SLUG,
					self::FIRST_TIME_ACTIVATED_QUERY_VAR => 'hello',
				],
				admin_url( 'edit.php' )
			)
		);
		exit;
	}
}
