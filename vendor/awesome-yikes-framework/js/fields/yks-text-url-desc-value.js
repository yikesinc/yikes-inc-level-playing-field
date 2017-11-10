jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_txt_url_desc_value_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_txt_url_desc_value_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_txt_url_desc_value' );
	});

	// If add button is clicked, add another field
	$( '.yks_txt_url_desc_value_add' ).click( function() {
		yks_starter_repeating_fields_duplicate_multiple( this, '.yks_txt_url_desc_value' );
	});
});