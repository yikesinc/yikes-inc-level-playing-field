// jQuery( function( $ ) {

// 	// Setup the container as drag-and-droppable
// 	$( '.yks_oembed_container' ).sortable({
// 		cancel: 'input, textarea, button, select, option, a',
// 	});

// 	// If delete button is clicked, remove the associated field <li>, update the field counter
// 	$( 'body' ).on( 'click', '.yks_oembed_delete', function() {
// 		yks_starter_repeating_fields_delete( this, '.yks_oembed' );
// 	});

// 	// If add button is clicked, add another field
// 	$( '.yks_oembed_add' ).click( function() {
// 		var duplicated_field = yks_starter_repeating_fields_duplicate_single( this, '.yks_oembed' );

// 		console.log( duplicated_field );
// 		// Need some custom JS logic for handling the preview iFrame

// 		// Remove the preview code
// 		duplicated_field.children( '.embed_wrap' ).children( '.embed_status' ).html( '' ).hide();

// 		// Get the input field ID
// 		var input_id = duplicated_field.children( '.yks_oembed' ).attr( 'id' );

// 		// Set the embed_wrap's ID to the input field ID + '_status'
// 		duplicated_field.children( '.embed_wrap' ).attr( 'id', input_id + '_status' );
// 	});
// });