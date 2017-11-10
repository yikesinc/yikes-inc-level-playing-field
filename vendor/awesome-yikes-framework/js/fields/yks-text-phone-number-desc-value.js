jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_phone_number_desc_value_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_phone_number_desc_value_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_phone_number_desc_value' );
	});

	// If add button is clicked, add another field
	$( '.yks_phone_number_desc_value_add' ).click( function() {
		yks_starter_repeating_fields_duplicate_multiple( this, '.yks_phone_number_desc_value' );
	});

	// Listen for input into our phone fields
	$( 'body' ).on( 'keyup', '.yks_phone_number', function ( event ) {

		// Get the field number (1st? 2nd? 3rd?)
		var field_number = parseInt( $( this ).data( 'phone-field-num' ) );

		// Get the current value
		var current_value = $( this ).val();

		// Run a reg-ex to keep value strictly numeric
		var numeric_value = current_value.replace( /[^0-9\.]/g, '' );

		// If the current value is not strictly numeric, then replace the value with the numeric one
		if ( current_value != numeric_value ) {
		 	$( this ).val( numeric_value );
		}

		// Do not deal with the third field any longer
		if ( field_number === 3 ) {
			return;
		}

		// Allows a user to shift+tab into our field
		if ( typeof( event.key ) !== 'undefined' && ( event.key === 'Shift' || event.key === 'Tab' ) ) {
			return;
		}

		// Get the next field
		var next_field = field_number + 1;

		if ( numeric_value.length === 3 ) {
			$( this ).siblings( '.yks_phone_number_' + next_field ).focus();
		}
	});
});