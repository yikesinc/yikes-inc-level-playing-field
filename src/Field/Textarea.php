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
 * Class Textarea
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class Textarea extends BaseField {

	/**
	 * Render the field.
	 *
	 * @since 1.0.0
	 */
	public function render() {
		$classes = array_merge( $this->classes, [ 'lpf-field-textarea' ] );
		?>
		<div class="lpf-field-container">
			<label class="lpf-input-label"><?php $this->render_label(); ?></label>
			<textarea name="<?php echo esc_attr( $this->id ); ?>"
					  id="<?php echo esc_attr( $this->id ); ?>"
					  class="<?php esc_attr( join( ' ', $classes ) ); ?>"
					  rows="10"
					<?php $this->render_required(); ?>
				<?php $this->render_data_attributes(); ?>
				><?php echo esc_textarea( $this->value ); ?></textarea>
		</div>
		<?php
	}

	/**
	 * Get the type for use with errors.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_error_type() {
		return 'textarea';
	}
}
