<?php
/**
 * YIKES Inc. Level Playing Field Plugin.
 *
 * @package Yikes\LevelPlayingField
 * @author  Jeremy Pry
 * @license GPL2
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Model\JobRepository;
use Yikes\LevelPlayingField\Settings\ApplicationSuccessMessage;

// Only run this within WordPress.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$job = ( new JobRepository() )->find( $this->job_id );
$msg = ! empty( $job->get_application_success_message() )
	? $job->get_application_success_message()
	: ( new ApplicationSuccessMessage() )->get();

printf( '<p>%s</p>', nl2br( esc_html( $msg ) ) );
