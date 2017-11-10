jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_checkbox_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a, label',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_checkbox_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_checkbox' );
	});

	// If add button is clicked, add another field
	$( '.yks_checkbox_add' ).click( function() {
		yks_starter_repeating_fields_duplicate_single( this, '.yks_checkbox', false );
	});
});