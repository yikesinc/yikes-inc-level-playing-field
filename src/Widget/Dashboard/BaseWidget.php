<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Exception\MustExtend;
use Yikes\LevelPlayingField\Service;

/**
 * Abstract class BaseWidget.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Ebonie Butler
 */
abstract class BaseWidget implements Service {

	const SLUG = '_basewidget_';

	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widget' ] );
	}

	/**
	 * Add custom dashboard widget.
	 */
	public function add_dashboard_widget() {
		wp_add_dashboard_widget(
			$this->get_slug(),
			$this->get_title(),
			[ $this, 'render' ]
		);
	}

	/**
	 * Get the slug to use for the dashboard widget.
	 *
	 * @since %VERSION%
	 *
	 * @return string widget slug.
	 * @throws MustExtend When the default slug has not been extended.
	 */
	protected function get_slug() {
		if ( self::SLUG === static::SLUG ) {
			throw MustExtend::default_slug( self::SLUG );
		}
		return static::SLUG;
	}

	/**
	 * Get the title of the dashboard widget.
	 *
	 * @since %VERSION%
	 */
	abstract public function get_title();

	/**
	 * Render widget to dashboard.
	 *
	 * @since %VERSION%
	 */
	abstract public function render();
}
