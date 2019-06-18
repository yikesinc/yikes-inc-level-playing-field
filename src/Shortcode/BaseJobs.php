<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Shortcode;

use Yikes\LevelPlayingField\Assets\Asset;
use Yikes\LevelPlayingField\Assets\StyleAsset;

/**
 * Class BaseJobs
 *
 * @since   %VERSION%
 * @package Yikes\LevelPlayingField
 */
abstract class BaseJobs extends BaseShortcode {

	const CSS_HANDLE          = 'lpf-jobs-css';
	const CSS_URI             = 'assets/css/lpf-jobs-frontend';
	const JOB_DETAILS_PARTIAL = 'views/job-details';
	const JOB_APPLY_PARTIAL   = 'views/job-apply-button';

	/**
	 * Get the array of known assets.
	 *
	 * @since %VERSION%
	 *
	 * @return Asset[]
	 */
	protected function get_assets() {
		return [
			new StyleAsset( self::CSS_HANDLE, self::CSS_URI, StyleAsset::DEPENDENCIES, StyleAsset::VERSION, StyleAsset::MEDIA_ALL, true ),
		];
	}
}
