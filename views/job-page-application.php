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

/** @var Application $application */
$application = $this->application;

$active_fields = $application->get_active_fields();

?>
<form method="POST" id="<?php echo esc_attr( $application->get_id() ); ?>">
	<?php
	wp_nonce_field( 'lpf_application_submit', 'lpf_nonce' );
	foreach ( $active_fields as $active_field ) {
		switch ( $active_field ) {
			case 'name':
			case 'email':
			case 'phone':
			default:
				$type = 'text';
				break;
		}
	}
	?>
	<button type="submit" name="lpf_submit"><?php esc_html_e( 'Submit' ); ?></button>
</form>
