<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model\ApplicationComponents;


use Yikes\LevelPlayingField\Model\ApplicantMeta;

class Basic extends BaseComponent implements Component {

	public function get_data() {
		// TODO: Implement get_data() method.
	}

	protected function get_lazy_properties() {
		return [
			ApplicantMeta::NAME         => '',
			ApplicantMeta::EMAIL        => '',
			ApplicantMeta::PHONE        => '',
			ApplicantMeta::ADDRESS      => array(),
			ApplicantMeta::COVER_LETTER => '',
		];
	}

	protected function load_lazy_property( $property ) {

	}
}
