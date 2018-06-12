jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_radio_inline_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_radio_inline_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_radio_inline' );
	});

	// If add button is clicked, add another field
	$( '.yks_radio_inline_add' ).click( function() {
		var duplicated_field = yks_starter_repeating_fields_duplicate_multiple( this, '.yks_radio_inline', false );

		yks_starter_repeating_fields_update_label_for_attr( duplicated_field.children( 'label' ), '.yks_radio_inline' );
	});
});