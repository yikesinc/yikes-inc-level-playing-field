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
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Textarea extends BaseField {

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		$classes = array_merge( $this->classes, [ 'lpf-field-textarea' ] );
		?>
		<label><?php echo esc_html( $this->label ); ?>
			<textarea name="<?php echo esc_attr( $this->id ); ?>"
					  id="<?php echo esc_attr( $this->id ); ?>"
					  class="<?php esc_attr( join( ' ', $classes ) ); ?>"
					  required="required"
				<?php $this->render_data_attributes(); ?>
			></textarea>
		</label>
		<?php
	}
}
