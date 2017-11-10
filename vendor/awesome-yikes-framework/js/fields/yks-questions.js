jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_questions_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a, label',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_questions_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_questions' );
	});

	// If add button is clicked, add another field
	$( '.yks_questions_add' ).click( function() {
		var duplicated_field = yks_starter_repeating_fields_duplicate_multiple( this, '.yks_questions', true );

		// We remove the values from the input fields when we duplicate
		// For the 'required' checkbox, we need to re-add the value (and the value always equals 1)
		duplicated_field.children( '.yks_questions_required' ).val( '1' );
	});
});