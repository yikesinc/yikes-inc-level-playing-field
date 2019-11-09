<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField\Model;

use Yikes\LevelPlayingField\CustomPostType\ApplicantManager;
use Yikes\LevelPlayingField\CustomPostType\ApplicationManager;
use Yikes\LevelPlayingField\CustomPostType\JobManager;

/**
 * Interface MetaLinks
 *
 * @since   1.0.0
 * @package Yikes\LevelPlayingField
 */
interface MetaLinks {

	const JOB         = '_' . JobManager::SINGULAR_SLUG . '_id';
	const APPLICATION = '_' . ApplicationManager::SINGULAR_SLUG . '_id';
	const APPLICANT   = '_' . ApplicantManager::SINGULAR_SLUG . '_id';
}
