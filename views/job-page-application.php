<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Form\Application as ApplicationForm;
use Yikes\LevelPlayingField\Model\Application;

/** @var Application $application */
$application = $this->application;

/**
 * Set up the classes we'll use for the form and the individual fields.
 */
$base_classes  = [
	'lpf-application',
	sprintf( 'lpf-application-%s', $application->get_id() ),
];
$form_classes  = array_merge( [ 'lpf-form' ], $base_classes );
$field_classes = array_merge( [ 'lpf-form-field' ], $base_classes );

/**
 * The application form can be customized with classes for both the form itself
 * and the individual fields. The class instance below uses the default
 * classes.
 *
 * @see \Yikes\LevelPlayingField\Form\ApplicationForm
 */
$form = new ApplicationForm( $application, $field_classes );

?>
<form method="POST"
	  id="<?php echo esc_attr( $application->get_id() ); ?>"
	  class="<?php echo esc_attr( join( ' ', $form_classes ) ); ?>"
>
	<?php
	wp_nonce_field( 'lpf_application_submit', 'lpf_nonce' );
	foreach ( $form->fields as $field ) {
		$field->render();
	}
	?>
	<button type="submit" name="lpf_submit"><?php esc_html_e( 'Submit' ); ?></button>
</form>
