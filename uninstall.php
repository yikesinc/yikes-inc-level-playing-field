<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://www.yikesinc.com
 * @since      1.0.0
 *
 * @package    Yikes_Inc_Level_Playing_Field
 */

namespace Yikes\LevelPlayingField;

use Yikes\LevelPlayingField\Comment\Comment;
use Yikes\LevelPlayingField\RequiredPages\ApplicantMessagingPage;
use Yikes\LevelPlayingField\RequiredPages\ApplicationFormPage;
use Yikes\LevelPlayingField\Settings\DeleteOnUninstall;
use Yikes\LevelPlayingField\Settings\Settings;

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once __DIR__ . '/src/bootstrap-autoloader.php';

if ( ( new DeleteOnUninstall() )->get() ) {

	// Delete comments.
	global $wpdb;
	$wpdb->query( $wpdb->prepare(
		"DELETE FROM {$wpdb->comments} WHERE comment_agent = %s",
		Comment::AGENT
	) );

	// Delete required pages.
	wp_trash_post( ( new ApplicantMessagingPage() )->get_page_id( ApplicantMessagingPage::PAGE_SLUG ) );
	wp_trash_post( ( new ApplicationFormPage() )->get_page_id( ApplicationFormPage::PAGE_SLUG ) );
	delete_option( ApplicantMessagingPage::PAGE_SLUG );
	delete_option( ApplicationFormPage::PAGE_SLUG );

	// Delete options.
	( new Settings() )->uninstall();
}
