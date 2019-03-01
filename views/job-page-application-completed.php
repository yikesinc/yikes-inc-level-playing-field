<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Settings\Settings;
use Yikes\LevelPlayingField\Settings\SettingsFields;

printf(
	'<p>%s</p>',
	esc_html( ( new Settings() )->get_setting( SettingsFields::APPLICATION_SUCCESS_MESSAGE ) )
);
