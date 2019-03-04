<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

/**
 * Class WYSIWYG
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class WYSIWYG extends BaseField {

	/**
	 * The filter for sanitizing.
	 *
	 * We need to allow HTML so use `FILTER_UNSAFE_RAW`.
	 */
	const SANITIZE = FILTER_UNSAFE_RAW;

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		?>
		<div class="lpf-field-container">
			<label class="lpf-input-label"><?php echo esc_html( $this->label ); ?>
			<?php
				wp_editor( esc_textarea( $this->value ), $this->id, $this->get_editor_settings() );
			?>
			</label>
		</div>
		<?php
	}

	/**
	 * Default editor settings.
	 *
	 * @return array $settings Array of editor settings.
	 */
	protected function get_editor_settings() {
		$classes = array_merge( $this->classes, [ 'lpf-field-wysiwyg' ] );

		$settings = array(
			'media_buttons'    => false,
			'wpautop'          => true,
			'textarea_name'    => $this->id,
			'editor_class'     => join( ' ', $classes ),
			'editor_height'    => '300px',
			'quicktags'        => false,
			'teeny'            => true,
			'drag_drop_upload' => false,
		);

		return apply_filters( 'lpf_application_wysiwyg_field_settings', $settings, $this->id );
	}

	/**
	 * Get the type for use with errors.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_error_type() {
		return 'textarea';
	}
}
