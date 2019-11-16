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
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
abstract class AnonymousCustomPostTypeEntity extends CustomPostTypeEntity {

	/**
	 * Anonymizer object.
	 *
	 * @since 1.0.0
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

	/**
	 * Property setter.
	 *
	 * This ensures that certain properties are anonymized if needed.
	 *
	 * @since 1.0.0
	 *
	 * @param string $property The property name to set.
	 * @param mixed  $value    The value for the property.
	 */
	public function __set( $property, $value ) {
		$this->{$property} = array_key_exists( $property, $this->get_anonymous_properties() )
			? $this->anonymizer->anonymize( $value )
			: $value;
	}

	/**
	 * Return the list of properties that should be anonymized.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	abstract protected function get_anonymous_properties();
}
