jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_txt_date_year_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_txt_date_year_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_txt_date_year' );
	});

	// If add button is clicked, add another field
	$( '.yks_txt_date_year_add' ).click( function() {
		var duplicated_field = yks_starter_repeating_fields_duplicate_single( this, '.yks_txt_date_year' );

		// For datepicker fields, we need to remove the hasDatepicker class or the datepicker will not initialize
		duplicated_field.children( '.yks_txt_date_year' ).removeClass( 'hasDatepicker' );
	});
});