<?php
/**
 * Custom Application Shortcode
 * @since 1.0.0
 * Shortcode: [lpf-application]
 */

// Render our application
function render_application_shortcode( $atts ) {

	// Parse the shortcode attributes
	$atts = shortcode_atts( array(
		'application' => false,
		'custom' => 'shortcode',
	), $atts, 'level-playing-field-application' );

	$helpers = new Yikes_Inc_Level_Playing_Field_Helper( 'test', '1.0' );
	$application_fields = $helpers->get_application_fields( '1.0' );

	if ( $application_fields ) {
		?>
		<form class="yikes-lpf-form yikes-lpf-section yikes-lpf-group">
			<?php
			foreach ( $application_fields as $app_field ) {
				// render the feild
				$helpers->render_field( $app_field );
			}
			?>
			<input type="submit" class="<?php echo esc_attr( apply_filters( 'yikes_level_playing_field_submit_button_class', 'yikes-lpf-submit' ) ); ?>" value="<?php esc_attr_e( 'Apply', 'yikes-inc-level-playing-field' ); ?>" />
		</form>
		<?php
	}

	// return the shortcode
	return wp_kses_post( '<strong>Level Playing Field Application Shortcode</strong>' );
}
add_shortcode( 'lpf-application', 'render_application_shortcode' );
