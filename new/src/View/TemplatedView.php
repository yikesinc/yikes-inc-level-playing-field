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

/**
 * Class TemplatedView.
 *
 * Looks within the child theme and parent theme folders first for a view,
 * before defaulting to the plugin folder.
 *
 * @since   0.1.0
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
final class TemplatedView extends BaseView {

	/**
	 * Validate an URI.
	 *
	 * @since 0.1.0
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
	 * @since 0.1.0
	 *
	 * @param string $uri URI of the view to get the locations for.
	 *
	 * @return array Array of possible locations.
	 */
	protected function get_locations( $uri ) {
		return array(
			trailingslashit( STYLESHEETPATH ) . $uri,
			trailingslashit( TEMPLATEPATH ) . $uri,
			trailingslashit( dirname( __DIR__, 2 ) ) . $uri,
		);
	}
}
