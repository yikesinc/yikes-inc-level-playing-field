jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_zip_code_us_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_zip_code_us_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_zip_code_us' );
	});

	// If add button is clicked, add another field
	$( '.yks_zip_code_us_add' ).click( function() {
		yks_starter_repeating_fields_duplicate_multiple( this, '.yks_zip_code_us' );
	});
});