<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Field;

use Yikes\LevelPlayingField\Exception\InvalidField;

/**
 * Class Hidden
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
class Hidden extends BaseField {

	/**
	 * The value of the hidden field.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $value;

	/**
	 * Whether this field is read-only.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	protected $read_only = true;

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
	 * @since 1.0.0
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

	/**
	 * Validate the raw value.
	 *
	 * This validates by type-casting the values to strings.
	 *
	 * @since 1.0.0
	 *
	 * @throws InvalidField When the raw value is different from the provided value, or empty.
	 */
	protected function validate_raw_value() {
		if ( (string) $this->value !== (string) $this->raw_value ) {
			throw InvalidField::value_invalid(
				static::class,
				__( 'Hidden field values cannot be changed.', 'level-playing-field' )
			);
		}
	}

	/**
	 * Render the error message for the field.
	 *
	 * @since 1.0.0
	 */
	protected function render_error_message() {
		// Don't do anything.
	}

	/**
	 * Get the type for use with errors.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	protected function get_error_type() {
		return '';
	}
}
