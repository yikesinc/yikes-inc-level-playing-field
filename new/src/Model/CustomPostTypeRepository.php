<?php
/**
 * AlainSchlesser.com Speaking Page Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      https://www.alainschlesser.com/
 * @copyright 2017 Alain Schlesser
 */

namespace Yikes\LevelPlayingField\Model;

/**
 * Abstract class CustomPostTypeRepository.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
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
