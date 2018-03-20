<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\Application;


$active_fields = $this->application->get_active_fields();


?>
<form method="POST" id="<?php echo esc_attr( $this->application->get_id() ); ?>">
	<?php wp_nonce_field( 'lpf_application_submit', 'lpf_nonce' ); ?>

	<button type="submit" name="lpf_submit"><?php esc_html_e( 'Submit' ); ?></button>
</form>
