console.log( 'hola' );
/* Applicants Admin List Page JS */
jQuery( function ( $ ) {

	// Make sure we have our localized data
	if ( 'undefined' === typeof applicant_admin ) {
		return;
	}

	// Add the export button to the Applicants page.
	let add_new_button = $( '.page-title-action' ).filter( ':first' );
	let export_button  = add_new_button.find( '.applicant-export' );

	if ( 0 === export_button.length ) {
		add_new_button.after( '<a href="' + applicant_admin.export_url + '" class="page-title-action applicant-export">' + applicant_admin.strings.export_button_text + '</a>' );
	}
} );