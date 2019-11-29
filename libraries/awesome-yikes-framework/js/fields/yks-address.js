jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_address_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_address_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_address' );
	});

	// If add button is clicked, add another field
	$( '.yks_address_add' ).click( function() {
		yks_starter_repeating_fields_duplicate_multiple( this, '.yks_address' );
	});
});