<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package   Yikes\LevelPlayingField
 * @author    Ebonie Butler
 * @license   GPL2
 */

namespace Yikes\LevelPlayingField\Widget\Dashboard;

use Yikes\LevelPlayingField\Assets\AssetsAware;
use Yikes\LevelPlayingField\Assets\AssetsAwareness;
use Yikes\LevelPlayingField\Assets\StyleAsset;
use Yikes\LevelPlayingField\Assets\ScriptAsset;

/**
 * Applicant Status Dashboard Widget.
 *
 * @package    Yikes\LevelPlayingField
 * @subpackage Widget
 */
class Status extends BaseWidget implements AssetsAware {

	use AssetsAwareness;
	const SLUG  = 'yikes_lpf_widget';
	const TITLE = 'Applicants';

	// Define the CSS file.
	const CSS_HANDLE = 'lpf-dashboard-widget-css';
	const CSS_URI    = 'assets/css/lpf-dashboard-widget';

	// Define the JavaScript file.
	const JS_HANDLE       = 'lpf-dashboard-widget-script';
	const JS_URI          = 'assets/js/dashboard-widget';
	const JS_DEPENDENCIES = [ 'jquery' ];
	const JS_VERSION      = false;


	/**
	 * Register the WordPress hooks.
	 *
	 * @since %VERSION%
	 */
	public function register() {
		parent::register();
		$this->register_assets();
		$this->enqueue_assets();
	}

	/**
	 * Output the widget content.
	 *
	 * @since %VERSION%
	 */
	public function render() {
		?>
		<h3>Job Title</h3>
		<a href="#">Regional Manager</a>
		<h3>New</h3>
		<a href="#">2</a>
		<h3>Total</h3>
		<a href="#">32</a>
		<?php
	}

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {

		return [
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI ),
			new ScriptAsset( self::JS_HANDLE, self::JS_URI, self::JS_DEPENDENCIES, self::JS_VERSION, ScriptAsset::ENQUEUE_FOOTER ),
		];
	}
}
