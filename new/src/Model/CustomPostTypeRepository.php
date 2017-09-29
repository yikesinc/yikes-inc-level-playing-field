<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Model;

/**
 * Abstract class CustomPostTypeRepository.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class CustomPostTypeRepository {

	/**
	 * Persist a modified entity to the storage.
	 *
	 * @since 0.1.0
	 *
	 * @param CustomPostTypeEntity $entity Entity instance to persist.
	 */
	public function persist( CustomPostTypeEntity $entity ) {
		wp_insert_post( $entity->get_post_object()->to_array() );
		$entity->persist_properties();
	}
}
