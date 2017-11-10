jQuery( function( $ ) {

	// Setup the container as drag-and-droppable
	$( '.yks_multicheck_container' ).sortable({
		cancel: 'input, textarea, button, select, option, a, label',
	});

	// If delete button is clicked, remove the associated field <li>, update the field counter
	$( 'body' ).on( 'click', '.yks_multicheck_delete', function() {
		yks_starter_repeating_fields_delete( this, '.yks_multicheck' );
	});

	// If add button is clicked, add another field
	$( '.yks_multicheck_add' ).click( function() {
		var duplicated_field = yks_starter_repeating_fields_duplicate_multiple( this, '.yks_multicheck', false );

		yks_starter_repeating_fields_update_label_for_attr( duplicated_field.children( 'label' ), '.yks_multicheck' );
		yks_starter_repeating_fields_add_braces_to_name( duplicated_field.children( 'label' ), '.yks_multicheck' );
	});
});

/**
* For checkboxes, we need to add the [] to the end of the name
*
* @param string | element	| A reference to the specific delete icon that was clicked
* @param string | name_type	| The field's name, with the initial class period (e.g. for textsmall, .yks_txt_small)
*/
function yks_starter_repeating_fields_add_braces_to_name( element, name_type ) {
	jQuery.each( element, function() {

		// Get the name of the input field
		var input_name = jQuery( this ).children( name_type ).attr( 'name' );

		// Set the name of the input field, + '[]'
		jQuery( this ).children( name_type ).attr( 'name', input_name + '[]' );
	});
}