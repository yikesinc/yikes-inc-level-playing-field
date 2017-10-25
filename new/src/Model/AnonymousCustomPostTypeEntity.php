<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use WP_Post;
use Yikes\LevelPlayingField\Tools\AnonymizerInterface;

/**
 * Class AnonymousCustomPostTypeEntity
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class AnonymousCustomPostTypeEntity extends CustomPostTypeEntity {

	/**
	 * Anonymizer object.
	 *
	 * @since %VERSION%
	 * @var AnonymizerInterface
	 */
	protected $anonymizer;

	/**
	 * AnonymousCustomPostTypeEntity constructor.
	 *
	 * @param WP_Post             $post       The post object.
	 * @param AnonymizerInterface $anonymizer Anonymizer object.
	 */
	public function __construct( WP_Post $post, AnonymizerInterface $anonymizer ) {
		$this->anonymizer = $anonymizer;
		parent::__construct( $post );
	}


	public function __get( $property ) {
		$value = parent::__get( $property );



		return array_key_exists( $property, $this->get_anonymous_properties() )
			? $this->anonymizer->anonymize( $value )
			: $value;
	}

	/**
	 * Return the list of properties that should be anonymized.
	 *
	 * @since %VERSION%
	 * @return array
	 */
	abstract protected function get_anonymous_properties();
}
