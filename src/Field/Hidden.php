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
 * Class Hidden
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Hidden extends BaseField {

	/**
	 * The value of the hidden field.
	 *
	 * @since %VERSION%
	 * @var string
	 */
	protected $value;

	/**
	 * Hidden constructor.
	 *
	 * @param string $id      The ID for the field.
	 * @param string $value   The value for the field.
	 * @param array  $classes Array of classes to apply to the field.
	 */
	public function __construct( $id, $value, array $classes = [] ) {
		parent::__construct( $id, '', $classes, true );
		$this->value = $value;
	}

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		$classes = array_merge( $this->classes, [ 'lpf-field-hidden' ] );
		?>
		<input type="hidden"
			   class="<?php echo esc_attr( join( ' ', $classes ) ); ?>"
			   name="<?php echo esc_attr( $this->id ); ?>"
			   id="<?php echo esc_attr( $this->id ); ?>"
			   value="<?php echo esc_attr( $this->value ); ?>"
			<?php $this->render_data_attributes(); ?>
		/>
		<?php
	}
}
