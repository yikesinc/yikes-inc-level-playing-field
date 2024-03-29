<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\View;

use Yikes\LevelPlayingField\Exception\InvalidURI;
use Yikes\LevelPlayingField\PluginHelper;

/**
 * Class TemplatedView.
 *
 * Looks within the child theme and parent theme folders first for a view,
 * before defaulting to the plugin folder.
 *
 * @since   1.0.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
class TemplatedView extends BaseView {

	use PluginHelper;

	/**
	 * Validate an URI.
	 *
	 * @since 1.0.0
	 *
	 * @param string $uri URI to validate.
	 *
	 * @return string Validated URI.
	 * @throws InvalidURI If an invalid URI was passed into the View.
	 */
	protected function validate( $uri ) {
		$uri = $this->check_extension( $uri, static::VIEW_EXTENSION );

		foreach ( $this->get_locations( $uri ) as $location ) {
			if ( is_readable( $location ) ) {
				return $location;
			}
		}

		if ( ! is_readable( $uri ) ) {
			throw InvalidURI::from_uri( $uri );
		}

		return $uri;
	}

	/**
	 * Get the possible locations for the view.
	 *
	 * @since 1.0.0
	 *
	 * @param string $uri URI of the view to get the locations for.
	 *
	 * @return array Array of possible locations.
	 */
	protected function get_locations( $uri ) {
		/**
		 * Filter the available locations for view templates to be found.
		 *
		 * Locations will be tried in the order provided by the array,
		 * so locations with higher priority should be first.
		 *
		 * @param array $locations
		 */
		$locations = (array) apply_filters( 'lpf_templated_view_locations', [
			trailingslashit( get_stylesheet_directory() ) . "lpf/{$uri}",
			trailingslashit( get_template_directory() ) . "lpf/{$uri}",
		] );

		// Ensure the plugin folder is always available.
		$locations[] = trailingslashit( $this->get_root_dir() ) . $uri;

		return $locations;
	}
}
