<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

use Yikes\LevelPlayingField\Exception\MustExtend;

/**
 * Class BaseInput
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class BaseInput extends BaseField {

	/**
	 * The Input type.
	 *
	 * @see https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input
	 */
	const TYPE = '_basefield_';

	/**
	 * Get the type of field.
	 *
	 * @since %VERSION%
	 * @return string
	 * @throws MustExtend When the type is not defined.
	 */
	protected function get_type() {
		if ( self::TYPE === static::TYPE ) {
			throw MustExtend::default_type( self::TYPE );
		}

		return static::TYPE;
	}

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 * @throws MustExtend When the TYPE constant is not properly defined.
	 */
	public function render() {
		$type      = $this->get_type();
		$classes   = array_merge( $this->classes, [ "lpf-field-{$type}" ] );
		$has_error = ! empty( $this->error_message );
		?>
		<div class="lpf-field-container">
			<label class="lpf-input-label <?php echo $has_error ? 'error-prompt' : ''; ?>">
				<?php $this->render_label(); ?>
				<?php $this->render_error_message(); ?>
				<input type="<?php echo esc_attr( $type ); ?>"
					   class="<?php echo esc_attr( join( ' ', $classes ) ); ?>"
					   name="<?php echo esc_attr( $this->id ); ?>"
					   id="<?php echo esc_attr( $this->id ); ?>"
					   value="<?php echo esc_attr( $this->value ); ?>"
					<?php $this->render_extra_attributes(); ?>
				/>
			</label>
		</div>
		<?php
	}

	/**
	 * Render any additional attributes.
	 *
	 * @since %VERSION%
	 */
	protected function render_extra_attributes() {
		$this->render_required();
		$this->render_data_attributes();
	}

	/**
	 * Render the label for the field.
	 *
	 * @since %VERSION%
	 */
	protected function render_label() {
		echo esc_html( $this->label );
	}

	/**
	 * Render the error message for the field.
	 *
	 * @since %VERSION%
	 * @throws MustExtend When the type hasn't been defined correctly.
	 */
	protected function render_error_message() {
		if ( empty( $this->error_message ) ) {
			return;
		}

		printf(
			'<span class="error-text error-%1$s">%2$s</span>',
			esc_attr( $this->get_error_type() ),
			esc_html( $this->error_message )
		);
	}

	/**
	 * Get the type for use with errors.
	 *
	 * @since %VERSION%
	 * @return string
	 * @throws MustExtend When the type hasn't been defined correctly.
	 */
	protected function get_error_type() {
		return $this->get_type();
	}
}
