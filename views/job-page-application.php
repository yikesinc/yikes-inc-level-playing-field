<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Field\Hidden;
use Yikes\LevelPlayingField\Form\Factory;
use Yikes\LevelPlayingField\Model\Application;

/** @var Application $application */
$application   = $this->application;
$active_fields = $application->get_active_fields();

// Set up HTML classes.
$base_classes  = [
	'lpf-application',
	sprintf( 'lpf-application-%s', $application->get_id() ),
];
$field_classes = array_merge( [ 'lpf-form-field' ], $base_classes );
$form_classes  = array_merge( [ 'lpf-form' ], $base_classes );

// Set up Form Fields.
// todo: change job_id to actual job, not application, ID.
$fields   = Factory::create( $active_fields, $field_classes );
$fields[] = new Hidden( 'job_id', $application->get_id() );

?>
<form method="POST"
	  id="<?php echo esc_attr( $application->get_id() ); ?>"
	  class="<?php echo esc_attr( join( ' ', $form_classes ) ); ?>"
>
	<?php
	wp_nonce_field( 'lpf_application_submit', 'lpf_nonce' );
	foreach ( $fields as $field ) {
		$field->render();
	}
	?>
	<button type="submit" name="lpf_submit"><?php esc_html_e( 'Submit' ); ?></button>
</form>
