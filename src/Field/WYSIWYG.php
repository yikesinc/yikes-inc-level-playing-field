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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class WYSIWYG extends BaseField {

	/**
	 * The filter for sanitizing.
	 *
	 * We need to allow certain HTML tags so use `FILTER_CALLBACK` and supply our own sanitization function (in this case, wp_kses_post).
	 */
	const SANITIZE = FILTER_CALLBACK;

	/**
	 * Render the field.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		?>
		<div class="lpf-field-container">
			<label class="lpf-input-label"><?php $this->render_label(); ?>
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

		if ( $this->is_required() ) {
			array_push( $classes, 'lpf-field-required' );
		}

		$settings = [
			'media_buttons'    => false,
			'wpautop'          => true,
			'textarea_name'    => $this->id,
			'editor_class'     => join( ' ', $classes ),
			'editor_height'    => '300px',
			'quicktags'        => false,
			'teeny'            => true,
			'drag_drop_upload' => false,
		];

		return apply_filters( 'lpf_application_wysiwyg_field_settings', $settings, $this->id );
	}

	/**
	 * Get the type for use with errors.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_error_type() {
		return 'wysiwyg';
	}

	/**
	 * Return options to use when sanitizing a submitted value.
	 *
	 * We're allowing the same HTML tags in a cover letter that are allowed in a post, ergo use wp_kses_post for sanitization.
	 *
	 * @link  http://php.net/manual/en/function.filter-var.php
	 * @see   filter_var()
	 * @since 1.0.0
	 * @return array A sanitization callback function.
	 */
	protected function get_filter_options() {
		return [ 'options' => 'wp_kses_post' ];
	}
}
