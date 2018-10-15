<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\AdminPage;

use Yikes\LevelPlayingField\Service;
use Yikes\LevelPlayingField\Roles\Capabilities;
use Yikes\LevelPlayingField\CustomPostType\JobManager;
use Yikes\LevelPlayingField\View\AdminView;
use Yikes\LevelPlayingField\Exception\MustExtend;

/**
 * Abstract class BaseAdminPage.
 *
 * @since   %VERSION%
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 */
abstract class BaseAdminPage implements Service {

	const PARENT_SLUG = 'edit.php?post_type=' . JobManager::SLUG;
	const PAGE_SLUG   = '';
	const PRIORITY    = 10;

	/**
	 * Register the Admin Page.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		add_action( 'admin_menu', function () {
			add_submenu_page(
				$this->get_parent_slug(),
				$this->get_page_title(),
				$this->get_menu_title(),
				$this->get_capability(),
				$this->get_menu_slug(),
				$this->get_callback()
			);
		}, $this->get_priority() );
	}

	/**
	 * Get the parent slug for the admin page.
	 *
	 * @since %VERSION%
	 *
	 * @return string The slug name for the parent menu (or the file name of a standard WordPress admin page).
	 */
	protected function get_parent_slug() {
		return static::PARENT_SLUG;
	}

	/**
	 * Get the title to use for the admin page.
	 *
	 * @since %VERSION%
	 *
	 * @return string The text to be displayed in the title tags of the page when the menu is.
	 */
	abstract protected function get_page_title();

	/**
	 * Get the text to be used for the menu name.
	 *
	 * @since %VERSION%
	 *
	 * @return string The text to be used for the menu.
	 */
	abstract protected function get_menu_title();

	/**
	 * Get the capability required for this menu to be displayed to the user.
	 *
	 * @since %VERSION%
	 *
	 * @return string Capability required for this menu to be displayed to the user.
	 */
	protected function get_capability() {
		return apply_filters( 'lpf_admin_page_capability', Capabilities::VIEW_ADMIN_PAGES, static::PAGE_SLUG );
	}

	/**
	 * Get the slug name to refer to this menu by.
	 *
	 * @since %VERSION%
	 * @throws MustExtend When the default type has not been extended.
	 * @return string The slug name to refer to this menu by.
	 */
	protected function get_menu_slug() {
		if ( self::PAGE_SLUG === static::PAGE_SLUG ) {
			throw MustExtend::default_type( self::PAGE_SLUG );
		}
		return static::PAGE_SLUG;
	}

	/**
	 * Get the function to be called to output the content for this page.
	 *
	 * @since %VERSION%
	 *
	 * @return callable The function to be called to output the content for this page.
	 */
	protected function get_callback() {
		return [ $this, 'callback' ];
	}

	/**
	 * Display the admin page's HTML.
	 *
	 * @since %VERSION%
	 */
	public function callback() {
		echo ( new AdminView( static::VIEW_URI ) )->render( $this->get_context() );
	}

	/**
	 * Get the priority for this admin page.
	 *
	 * @since %VERSION%
	 *
	 * @return int The priority.
	 */
	protected function get_priority() {
		return static::PRIORITY;
	}

	/**
	 * Include the variables required for this admin page.
	 *
	 * @since %VERSION%
	 *
	 * @return array $context The context.
	 */
	protected function get_context() {
		return [];
	}
}
