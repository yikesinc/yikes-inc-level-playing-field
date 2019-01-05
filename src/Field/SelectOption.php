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
 * Class SelectOption
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
final class SelectOption implements OptionInterface {

	/** @var string */
	private $label;

	/** @var string */
	private $value;

	/** @var bool */
	private $selected;

	/**
	 * SelectOption constructor.
	 *
	 * @param string $label    The human-friendly label for the option.
	 * @param string $value    The value to store when the option is selected.
	 * @param bool   $selected Whether the option is selected.
	 */
	public function __construct( $label, $value, $selected = false ) {
		$this->label    = $label;
		$this->value    = $value;
		$this->selected = (bool) $selected;
	}

	/**
	 * Render the current option.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		printf(
			'<option value="%1$s" %3$s>%2$s</option>',
			esc_attr( $this->value ),
			esc_html( $this->label ),
			selected( true, $this->selected, false )
		);
	}
}
