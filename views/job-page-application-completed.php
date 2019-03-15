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
use Yikes\LevelPlayingField\Model\JobRepository;

$job = ( new JobRepository() )->find( $this->job_id );
$msg = ! empty( $job->get_application_success_message() ) ? $job->get_application_success_message() : ( new Settings() )->get_setting( SettingsFields::APPLICATION_SUCCESS_MESSAGE );

printf( '<p>%s</p>', esc_html( $msg ) );
