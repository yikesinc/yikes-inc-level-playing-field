<?php
/**
 * 		Plugin Name:       Level Playing Field by YIKES Inc.
 * 		Plugin URI:        http://www.yikesinc.com
 * 		Description:       Description here.
 * 		Version:           0.1
 * 		Author:            YIKES
 * 		Author URI:        http://www.yikesinc.com
 * 		License:           GPL-3.0+
 *		License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * 		Text Domain:       yikes-inc-level-playing-field
 * 		Domain Path:       /languages
 *
 * 		Level Playing Field by YIKES Inc. is free software: you can redistribute it and/or modify
 * 		it under the terms of the GNU General Public License as published by
 * 		the Free Software Foundation, either version 2 of the License, or
 * 		any later version.
 *
 * 		Level Playing Field by YIKES Inc. is distributed in the hope that it will be useful,
 * 		but WITHOUT ANY WARRANTY; without even the implied warranty of
 * 		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * 		GNU General Public License for more details.
 *
 * 		You should have received a copy of the GNU General Public License
 *		along with Easy Forms for MailChimp. If not, see <http://www.gnu.org/licenses/>.
 *
 *		We at YIKES Inc. embrace the open source philosophy on a daily basis. We donate company time back to the WordPress project,
 *		and constantly strive to improve the WordPress project and community as a whole. We eat, sleep and breath WordPress.
 *
 *		"'Free software' is a matter of liberty, not price. To understand the concept, you should think of 'free' as in 'free speech,' not as in 'free beer'."
 *		- Richard Stallman
 *
**/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// must include plugin.php to use is_plugin_active()
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( ! is_plugin_active( 'yikes-inc-easy-mailchimp-extender/yikes-inc-easy-mailchimp-extender.php' ) ) {
	deactivate_plugins( '/yikes-inc-easy-mailchimp-zip-lookup-extension/yikes-inc-easy-mailchimp-zip-lookup-extension.php' );
	add_action( 'admin_notices' , 'yikes_inc_mailchimp_zip_lookup_display_activation_error' );
}

function yikes_inc_mailchimp_zip_lookup_display_activation_error() {
	?>
		<!-- hide the 'Plugin Activated' default message -->
		<style>
		#message.updated {
			display: none;
		}
		</style>
		<!-- display our error message -->
		<div class="error">
			<p><?php _e( 'Easy MailChimp Zip Lookup Extension by Yikes Inc. could not be activated because the base plugin is not installed and active.', 'yikes-inc-easy-mailchimp-zip-lookup-extension' ); ?></p>
			<p><?php printf( __( 'Please install and activate %s before activating this extension.', 'yikes-inc-easy-mailchimp-zip-lookup-extension' ) , '<a href="' . esc_url_raw( admin_url( 'plugin-install.php?tab=search&type=term&s=Yikes+Inc.+Easy+MailChimp+Forms' ) ) . '" title="Yikes Inc. Easy MailChimp Forms">Yikes Inc. Easy MailChimp Forms</a>' ); ?></p>
		</div>
	<?php
}
/* end plugin base active check */

/*
*	Enqueue custom js file wherever our shortcode is used
*/
function enqueue_google_geocode_api_with_yikes_mailchimp( $form_id ) {
	// enqueue our geocode js file
	wp_enqueue_script( 'mailchimp-geocode-example', plugin_dir_url(__FILE__) . '/js/gecode-mailchimp.js', array( 'jquery' ), 'all' );
	// localize the script to pass in some PHP
	wp_localize_script( 'mailchimp-geocode-example', 'admin_data', array(
		'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
		'preloader' => esc_url( admin_url( 'images/wpspin_light.gif' ) ),
		'red_x' => esc_url( trailingslashit( plugin_dir_url(__FILE__) ) . 'img/red-x.png' ),
		'green_checkmark' => esc_url( trailingslashit( plugin_dir_url(__FILE__) ) . 'img/green-check.png' ),
	) );
}
add_action( 'yikes-mailchimp-shortcode-enqueue-scripts-styles' , 'enqueue_google_geocode_api_with_yikes_mailchimp' );

/*
*	Disable the submit button on load
* 	for our gecoding form example (only if an address field is setup)
*/
function disable_form_submit_button( $submit_button, $form_id ) {
	global $wpdb;
	$form_results = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'yikes_easy_mc_forms WHERE id = ' . intval( $form_id ) . '', ARRAY_A );
	$field_results = json_decode( $form_results[0]['fields'], true );
	foreach( $field_results as $field ) {
		if( $field['type'] == 'address' ) {
			$button = '<input type="submit" value="Submit" class="yikes-easy-mc-submit-button yikes-easy-mc-submit-button-' . intval( $form_id ) . '" disabled>';
		} else {
			$button = $submit_button;
		}
	}
	return $button;
}
add_action( 'yikes-mailchimp-form-submit-button' , 'disable_form_submit_button', 10, 2 );

/*
*	Hide all address fields other than Zip
*/
function hide_address_input_fields() {
	?>
		<style>
			label.yikes-mailchimp-country-field,
			label.yikes-mailchimp-state-field,
			label.yikes-mailchimp-city-field,
			label.yikes-mailchimp-addr1-field,
			label.yikes-mailchimp-addr2-field {
				display: none !important;
			}
		</style>
	<?php
}
add_action( 'wp_print_scripts' , 'hide_address_input_fields' );

/*
*	Geocode AJAX handler
*	(API request etc.)
*/
function geocode_MailChimp_zip() {
	// catch the user entered zip
	$user_zip_code = $_POST['zip_code'];
	// setup our address
    $geocode_api_url = esc_url_raw( 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $user_zip_code . '&sensor=true' );
	// setup our request arguments
	$geocode_arguments = array(
		'timeout' => 120
	);
	// submit our request
	$geocode_response = wp_remote_get( $geocode_api_url, $geocode_arguments );
	// confirm there is no error
	if( is_wp_error( $geocode_response ) ) {
		return $geocode_response->getMessage();
	}
	// grab the response body
	$geocode_response_body = json_decode( wp_remote_retrieve_body( $geocode_response ), true );
	// ensure we have a response
	if( $geocode_response_body ) {
		// setup our response variables (City, State, Country)
		$formatted_address = $geocode_response_body['results'][0]['formatted_address'];
		$formatted_address_array = explode( ', ', $formatted_address );
		$state_zip = explode( ' ', $formatted_address_array[1] );
		$city = $formatted_address_array[0];
		$state = $state_zip[0];
		$country = $formatted_address_array[2];
		// submit the json response back to our js handler
		wp_send_json( array(
			'city' => $city,
			'state' => $state,
			'country' => substr( $country, 0, 2 ) // pass first two letters of country (ie: US)
		) );
	}
	// kill it
	wp_die();
}
add_action( 'wp_ajax_geocode_zip', 'geocode_MailChimp_zip' );

// end :) - Enjoy!
