<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Kevin Utz
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

?>

<!-- Banners -->
<div class="banners-inner">
	<p>
		<?php esc_html_e( 'Leave a review!', 'level-playing-field' ); ?>
	</p>
	<div class="banners-star-container">
		<a href="https://wordpress.org/support/plugin/level-playing-field/reviews/?rate=5#new-post" target="_blank">
			<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
		</a>
	</div>

	<div class="banners-tweet-container">
		<p>
			<?php esc_html_e( 'Tweet about Level Playing Field', 'level-playing-field' ); ?>
		</p>
		<p>
			<a class="button button-primary banners-tweet-button" href="https://twitter.com/intent/tweet?text=I use Level Playing Field by @yikesinc to receive anonymized job applications to fight bias in hiring and employment. #WordPress &url=https://wordpress.org/plugins/level-playing-field/" target="_blank" data-size="large">
				<?php esc_html_e( 'Tweet', 'level-playing-field' ); ?>
			</a>
		</p>
	</div>
</div>
