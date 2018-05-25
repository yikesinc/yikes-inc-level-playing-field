<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Kevin Utz / Jeremy Pry
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\AdminPage;

/**
 * Class ExportApplicantsPage
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
class ExportApplicantsPage extends BaseAdminPage {

	/**
	 * Register hooks.
	 *
	 * @since  %VERSION%
	 * @author Jeremy Pry
	 */
	public function register() {
		parent::register();
	}

	/**
	 * Get the title to use for the admin page.
	 *
	 * @since %VERSION%
	 *
	 * @return string The text to be displayed in the title tags of the page when the menu is.
	 */
	protected function get_page_title() {
		return 'Export Applicants';
	}

	/**
	 * Get the text to be used for the menu name.
	 *
	 * @since %VERSION%
	 *
	 * @return string The text to be used for the menu.
	 */
	protected function get_menu_title() {
		return 'Export';
	}

	/**
	 * Get the slug name to refer to this menu by.
	 *
	 * @since %VERSION%
	 *
	 * @return string The slug name to refer to this menu by.
	 */
	protected function get_menu_slug() {
		return 'lpf-export-applicants';
	}

	/**
	 * This function will generate the admin page.
	 *
	 * @since %VERSION%
	 *
	 * @return mixed I don't know yet...
	 */
	public function callback() {

		// @todo - implement an OO way of creating admin page content. 
		echo 'Page Content';
	}
}