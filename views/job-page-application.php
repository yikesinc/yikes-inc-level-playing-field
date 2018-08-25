<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 *
 * phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
 */

namespace Yikes\LevelPlayingField;

/**
 * These variables are included here for easy visiblity, but they
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
<form method="POST"
	  id="<?php echo esc_attr( $application->get_id() ); ?>"
	  class="<?php echo esc_attr( join( ' ', $form_classes ) ); ?>"
>
	<?php $form->render(); ?>
	<button type="submit" name="lpf_submit"><?php esc_html_e( 'Submit', 'yikes-level-playing-field' ); ?></button>
</form>

<?php

// Display an edit link for the application.
$link = get_edit_post_link( $application->get_id(), 'link' );
if ( $link ) {
	printf(
		'<br/><p><a href="%1$s">%2$s</a></p>',
		esc_url( $link ),
		esc_html__( 'Edit Application', 'yikes-level-playing-field' )
	);
}
