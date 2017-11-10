<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Metabox;

use Yikes\LevelPlayingField\PluginFactory;
use Yikes\LevelPlayingField\Service;

/**
 * Abstract class AwesomeBaseMetabox.
 *
 * Base class for building metaboxes with the Awesome framework.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField\Metabox
 */
abstract class AwesomeBaseMetabox implements Service {

	/**
	 * Register hooks.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 */
	public function register() {
		// First make sure we've loaded the awesome framework.
		$this->load_awesome_framework();

		add_filter( 'yks_mboxes', array( $this, 'register_boxes' ) );
	}

	/**
	 * Load the Awesome Framework.
	 *
	 * @since %VERSION%
	 */
	protected function load_awesome_framework() {
		require_once( PluginFactory::create()->get_plugin_root() . '/vendor/awesome-yikes-framework/yks-mbox-framework.php' );
	}

	/**
	 * Get the prefixed version of a field's name.
	 *
	 * @since %VERSION%
	 *
	 * @param string $name The field name.
	 *
	 * @return string The field name with prefix.
	 */
	protected function prefix_field( $name ) {
		return $this->get_prefix() . $name;
	}

	/**
	 * Get the prefix for use with meta fields.
	 *
	 * @since %VERSION%
	 * @return string
	 */
	abstract public function get_prefix();

	/**
	 * Register meta boxes.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 *
	 * @param array $meta_boxes Array of existing meta boxes.
	 *
	 * @return array The filtered meta boxes.
	 */
	abstract public function register_boxes( $meta_boxes );
}
