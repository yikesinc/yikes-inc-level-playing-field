jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_select_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_select_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_select' );
	});

	// If add button is clicked, add another field
	$( '.yks_select_add' ).click( function() {
		yks_starter_repeating_fields_duplicate_single( this, '.yks_select' );
	});
});