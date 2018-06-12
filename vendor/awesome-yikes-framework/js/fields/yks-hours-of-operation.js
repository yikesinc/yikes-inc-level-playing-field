jQuery( function( $ ) {

	// Toggle the 'disabled' class on the hours of operation time input fields when the "closed" checkbox is clicked
	jQuery( '.yks_hours_of_operation_closed_override' ).click( function() {
		jQuery( this ).siblings( '.yks_hours_of_operation' ).toggleClass( 'disabled' );
	});
});