<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\Components;

class Field {

	use Disabled;
	use Repeatable;

	protected $type;

	protected $anonymous;

	protected $key;

	protected $required;



	public function get_key() {
		return $this->key;
	}

	public function get_type() {
		return $this->type;
	}

	public function is_anonymous() {
		return $this->anonymous;
	}

	public function is_required() {
		return $this->required;
	}
}
