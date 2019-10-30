<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * These variables are included here for easy visibility, but they
 * can also be used as $this->var_name directly.
 */

/** @var \Yikes\LevelPlayingField\Model\Application $application */
$application = $this->application;

/**
 * The application form can be customized with classes for both the form itself
 * and the individual fields. The class instance below uses the default
 * classes.
 *
 * To replace the default classes with your own classes, call $form->set_field_classes()
 * with an array of your own classes to use for each field.
 *
 * @see \Yikes\LevelPlayingField\Form\Application
 * @var \Yikes\LevelPlayingField\Form\Application $form
 */
$form = $this->application_form;

/** @var array $form_classes */
$form_classes = $this->form_classes

?>
<?php if ( $form->has_errors() ) : ?>
	<div class="lpf-form-errors">
		<?php esc_html_e( 'Your application has errors. Please correct the errors below before resubmitting.', 'level-playing-field' ); ?>
	</div>
<?php endif; ?>
<form method="POST"
	id="<?php echo esc_attr( $application->get_id() ); ?>"
	class="<?php echo esc_attr( join( ' ', $form_classes ) ); ?>"
>
	<?php $form->render(); ?>
	<button class="lpf-submit" type="submit" name="lpf_submit">
		<?php esc_html_e( 'Submit Application', 'level-playing-field' ); ?>
	</button>
</form>

<?php

// Display an edit link for the application.
$application_link = get_edit_post_link( $application->get_id(), 'link' );
if ( $application_link ) {
	printf(
		'<br/><p><a href="%1$s">%2$s</a></p>',
		esc_url( $application_link ),
		esc_html__( 'Edit Application', 'level-playing-field' )
	);
}

// Display an edit link for the Job.
$job_link = get_edit_post_link( $this->job_id, 'link' );
if ( $job_link ) {
	printf(
		'<p><a href="%1$s">%2$s</a></p>',
		esc_url( $job_link ),
		esc_html__( 'Edit Job', 'level-playing-field' )
	);
}
