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
use Yikes\LevelPlayingField\Model\ApplicantMeta as Meta;
use Yikes\LevelPlayingField\Model\ApplicationMeta as AMeta;

/** @var Application $application */
$application = $this->application;

$active_fields = $application->get_active_fields();
$field_classes = [
	'lpf-form-field',
	'lpf-application',
	sprintf( 'lpf-application-%s', $application->get_id() ),
];

?>
<form method="POST" id="<?php echo esc_attr( $application->get_id() ); ?>">
	<?php wp_nonce_field( 'lpf_application_submit', 'lpf_nonce' ); ?>
	<input type="hidden" name="application_id" value="<?php echo esc_attr( $application->get_id() ); ?>" />

	<?php
	foreach ( $active_fields as $field ) {
		$type        = isset( Meta::FIELD_MAP[ $field ] ) ? Meta::FIELD_MAP[ $field ] : 'text';
		$field_label = ucwords( str_replace( [ '-', '_' ], ' ', str_replace( AMeta::META_PREFIX, '', $field ) ) );
		?>

		<label><?php echo esc_html( $field_label ); ?>
		<input type="<?php echo esc_attr( $type ); ?>"
			   class="<?php echo esc_attr( join( ' ', array_merge( $field_classes, [ "lpf-field-{$type}" ] ) ) ); ?>"
			   name="<?php echo esc_attr( $field ); ?>"
			   id="<?php esc_attr( $field ); ?>" />
		</label>
		<?php
	}
	?>
	<button type="submit" name="lpf_submit"><?php esc_html_e( 'Submit' ); ?></button>
</form>
