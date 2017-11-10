jQuery( function( $ ) {

	// Choose custom_url in dropdown if necessary
	select_custom_url_in_dropdown();

	// Setup the container as drag-and-droppable
	$( '.yks_link_picker_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a, label',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_link_picker_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_link_picker' );
	});

	// If add button is clicked, add another field
	$( '.yks_link_picker_add' ).click( function() {
		var duplicated_field = yks_starter_repeating_fields_duplicate_multiple( this, '.yks_link_picker', false );

		// Remove the value of the input field
		duplicated_field.find( '.yks_link_picker_input' ).val( '' ).hide();

		// De-select the dropdown
		duplicated_field.find( '.yks_link_picker_dropdown' ).val( 'select' );
	});


	// Custom JS to show/hide the input field.
	// If "custom_url" is chosen, show the input field
	// Else, hide the input field
	$( 'body' ).on( 'change', '.yks_link_picker_dropdown', function() {
		if ( $( this ).val() === 'custom_url' ) {
			$( this ).siblings( '.yks_link_picker_input' ).show();
		} else {
			$( this ).siblings( '.yks_link_picker_input' ).hide();
		}
	});
});

// Hacky... I want to save the value as ONLY the URL, so we don't know whether to pre-select "custom_url" or if the field is just empty
function select_custom_url_in_dropdown() {
	jQuery( '.yks_link_picker_input' ).each( function() {
		if ( jQuery( this ).val().length > 0 ) {
			jQuery( this ).siblings( '.yks_link_picker_dropdown' ).val( 'custom_url' );
		}
	});
}