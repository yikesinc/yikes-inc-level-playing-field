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
		$type    = $this->get_type();
		$classes = array_merge( $this->classes, [ "lpf-field-{$type}" ] );
		?>
		<label><?php echo esc_html( $this->label ); ?>
			<input type="<?php echo esc_attr( $type ); ?>"
				   class="<?php echo esc_attr( join( ' ', $classes ) ); ?>"
				   name="<?php echo esc_attr( $this->id ); ?>"
				   id="<?php echo esc_attr( $this->id ); ?>"
				<?php $this->render_required(); ?>
				<?php $this->render_data_attributes(); ?>
			/>
		</label>
		<?php
	}
}
