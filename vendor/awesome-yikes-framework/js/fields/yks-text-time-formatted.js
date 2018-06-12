jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_txt_time_formatted_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_txt_time_formatted_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_txt_time_formatted' );
	});

	// If add button is clicked, add another field
	$( '.yks_txt_time_formatted_add' ).click( function() {
		var duplicated_field = yks_starter_repeating_fields_duplicate_multiple( this, '.yks_txt_time_formatted' );

		// For text_time_formatted, we want the dropdown to always have a value, so default to 12:00 AM
		duplicated_field.children( '.yks_txt_time_formatted_hour' ).val( '00' );
		duplicated_field.children( '.yks_txt_time_formatted_minute' ).val( '00' );
		duplicated_field.children( '.yks_txt_time_formatted_ampm' ).val( '1' );
	});
});