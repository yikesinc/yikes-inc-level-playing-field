jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_file_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_file_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_file' );
	});

	// If add button is clicked, add another field
	$( '.yks_file_add' ).click( function() {
		var duplicated_field = yks_starter_repeating_fields_duplicate_multiple( this, '.yks_file' );

		// We need to remove the preview box (NOT THE yks_upstat - the elements within yks_upstat)
		duplicated_field.children( '.yks_upstat' ).children( '.img_status' ).remove();
	});
});