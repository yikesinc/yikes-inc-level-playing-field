(function( $ ) {
	'use strict';


})( jQuery );

/**
 * Update the applicant status based on the button clicked
 * @since 1.0.0
 */
function toggleApplicantStatus( clicked_button, applicant_id ) {
	jQuery( clicked_button ).parent().find( 'a' ).addClass( 'inactive' );
	jQuery( clicked_button ).removeClass( 'inactive' );

	var data = {
		'action': 'update_applicant_status',
		'applicant_status': jQuery( clicked_button ).attr( 'data-attr-status' ),
		'applicant_id': applicant_id
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post( ajaxurl, data, function( response ) {
		if ( ! response.success ) {
			alert( 'An error was encountered. Please try again.' );
		}
		console.log( response );
	});
}
