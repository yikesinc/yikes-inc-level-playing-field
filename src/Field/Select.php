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
use Yikes\LevelPlayingField\Exception\InvalidOption;

/**
 * Class Select
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class Select extends BaseField {

	/** @var OptionInterface[] */
	private $options = [];

	/**
	 * Select constructor.
	 *
	 * @param string            $id       The field ID.
	 * @param string            $label    The field label.
	 * @param array             $classes  Array of field classes.
	 * @param bool              $required Whether the field is required.
	 * @param OptionInterface[] $options  The options for the select element.
	 *
	 * @throws InvalidField When the provided ID is invalid.
	 * @throws InvalidOption When a provided option is invalid.
	 */
	public function __construct( $id, $label, array $classes, $required = true, $options = [] ) {
		parent::__construct( $id, $label, $classes, $required );

		foreach ( $options as $option ) {
			if ( ! $option instanceof OptionInterface ) {
				throw InvalidOption::from_option( $option );
			}
		}

		$this->options = $options;
	}

	/**
	 * Render the field.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		$classes   = array_merge( $this->classes, [ 'lpf-field-select' ] );
		$has_error = ! empty( $this->error_message );
		?>
		<div class="lpf-field-container">
			<label class="lpf-select-label <?php echo $has_error ? 'error-prompt' : ''; ?>">
				<?php $this->render_label(); ?>
				<?php $this->render_error_message(); ?>
				<select id="<?php echo esc_attr( $this->id ); ?>"
						class="<?php echo esc_attr( join( ' ', $classes ) ); ?>"
						name="<?php echo esc_attr( $this->id ); ?>"
						<?php $this->render_extra_attributes(); ?>
				>
					<?php $this->render_options(); ?>
				</select>
			</label>
		</div>
		<?php
	}

	/**
	 * Render the options for the select element.
	 *
	 * @since %VERSION%
	 */
	private function render_options() {
		foreach ( $this->options as $option ) {
			$option->render( $this->value );
		}
	}

	/**
	 * Get the type for use with errors.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	protected function get_error_type() {
		// todo: customize the "type" for errors?
		return 'select';
	}
}
