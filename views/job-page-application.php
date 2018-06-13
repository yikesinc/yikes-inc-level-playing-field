<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Form\Factory;
use Yikes\LevelPlayingField\Model\Application;

/** @var Application $application */
$application = $this->application;

$active_fields = $application->get_active_fields();
$field_classes = [
	'lpf-form-field',
	'lpf-application',
	sprintf( 'lpf-application-%s', $application->get_id() ),
];

$fields = Factory::create( $active_fields, $field_classes );

?>
<form method="POST" id="<?php echo esc_attr( $application->get_id() ); ?>">
	<?php wp_nonce_field( 'lpf_application_submit', 'lpf_nonce' ); ?>
	<input type="hidden" name="application_id" value="<?php echo esc_attr( $application->get_id() ); ?>" />

	<?php
	foreach ( $fields as $field ) {
		$field->render();
	}
	?>
	<button type="submit" name="lpf_submit"><?php esc_html_e( 'Submit' ); ?></button>
</form>
