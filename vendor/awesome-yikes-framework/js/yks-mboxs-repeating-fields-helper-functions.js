/**
* [For single field types] Take a current field's name and replace the trailing counter digit
*
* Field names look like $field['id']_$field['type']_[$counter], e.g. field_id_textsmall[1]
* We'll replace [1] with the variable number
*
* @param string | name	 | A field name
* @param string | number | A number to replace the field name's number
* @return string|		 | The new name
*/
function yks_starter_repeating_fields_parse_name( name, number ) {
	var base_name = name.substring( 0, name.lastIndexOf( '[' ) );
	return base_name + '[' + number + ']';
}

/**
* [For multiple field types] Take a current field's name and replace the trailing counter digit
*
* Field names look like $field['id']_$field['type']_[$type2_$counter], e.g. field_id_desc_value[desc_1]
* We'll replace the number in [desc_1] with the variable number
* Note: The difference between multiple names and single IDs is that multiple names requires the trailing ']'
*
* @param string | name	 | A field name
* @param string | number | A number to replace the field name's number
* @return string|		 | The new name
*/
function yks_starter_repeating_fields_parse_name_multiple( name, number ) {
	var base_name = name.substring( 0, name.lastIndexOf( '_' ) );
	return base_name + '_' + number + ']';
}

/**
* Take a current field's ID and replace the trailing counter digit
*
* Field IDs look like $field['id']_$field['type']_$counter, e.g. field_id_textsmall_1
* We'll replace 1 with the variable number
* Note: There is no difference in the structure of IDs for multiple fields vs. single fields
*
* @param string | id	 | A field ID
* @param string | number | A number to replace the field id's number
* @return string|		 | The new ID
*/
function yks_starter_repeating_fields_parse_id( id, number ) {
	var base_id = id.substring( 0, id.lastIndexOf( '_' ) );
	return base_id + '_' + number;
}

/**
* Duplicate a single field
*
* Note: Use the class 'yks_no_duplicate' to exclude HTML items from being duplicated
*
* @param string | element	| A reference to the specific add button that was clicked
* @param string | name_type	| The field's name, with the initial class period (e.g. for textsmall, .yks_txt_small)
* @return object| new_field | Return the jQuery object referencing the duplicated field
*/
function yks_starter_repeating_fields_duplicate_single( element, name_type, wipe_val ) {

	// Default wipe_val to true
	if ( typeof( wipe_val ) === 'undefined' ) {
		wipe_val = true;
	}

	// Get the number of fields, get the new number of fields
	var field_count		= jQuery( element ).data( 'field-count' );
	var new_field_count = parseInt( field_count ) + 1;

	// Find the last field's <li>
	var last_field = jQuery( element ).parents( name_type + '_add_container' ).siblings( name_type + '_container' ).children( name_type + '_field' ).last();

	// Get the name and ID of the last input field
	var last_input_field_name	= jQuery( last_field ).find( name_type ).attr( 'name' );
	var last_input_field_id		= jQuery( last_field ).find( name_type ).attr( 'id' );

	// Get the name and ID of the new input field
	var new_input_field_name	= yks_starter_repeating_fields_parse_name( last_input_field_name, new_field_count );
	var new_input_field_id		= yks_starter_repeating_fields_parse_id( last_input_field_id, new_field_count );

	// Clone the field - creating the new <li>
	var new_field = jQuery( last_field ).clone();

	// For the new input field, update the ID and name attributes, wipe out the value, remove 'checked' and 'selected' attrs
	jQuery( new_field ).find( name_type ).attr( 'name', new_input_field_name ).attr( 'id', new_input_field_id ).removeAttr( 'checked' ).removeAttr( 'selected' );
	if ( wipe_val === true ) {
		jQuery( new_field ).find( name_type ).val( '' );
	}

	// Remove any fields we don't want to copy - these are denoted with the class 'yks_no_duplicate'
	jQuery( new_field ).find( '.yks_no_duplicate' ).remove();

	// Always show the delete button for the new field (if we're cloning the first element, the delete button is not visible)
	jQuery( new_field ).find( name_type + '_delete' ).css( 'display', '' );

	// Append our new field to the <ul>
	jQuery( element ).parents( name_type + '_add_container' ).siblings( name_type + '_container' ).append( new_field );

	// Update the field counter
	jQuery( element ).data( 'field-count', new_field_count );

	return new_field;
}

/**
* Duplicate multiple fields
*
* @param string | element	| A reference to the specific add button that was clicked
* @param string | name_type	| The field's name, with the initial class period (e.g. for textsmall, .yks_txt_small)
* @param bool   | wipe_val	| A boolean indicating whether we should wipe out the value of the duplicated field
*/
function yks_starter_repeating_fields_duplicate_multiple( element, name_type, wipe_val ) {

	// Default wipe_val to true
	if ( typeof( wipe_val ) === 'undefined' ) {
		wipe_val = true;
	}

	// Get the number of fields, get the new number of fields
	var field_count		= jQuery( element ).data( 'field-count' );
	var new_field_count = parseInt( field_count ) + 1;

	// Find the last field's <li>
	var last_field = jQuery( element ).parents( name_type + '_add_container' ).siblings( name_type + '_container' ).children( name_type + '_field' ).last();

	// Because there are multiple input fields, these need to be arrays
	var new_input_field_names	= [];
	var new_input_field_ids		= [];

	// Get the name and ID of the new input fields based on the old one
	jQuery( last_field ).find( name_type ).each( function() {
		new_input_field_names.push( yks_starter_repeating_fields_parse_name_multiple( jQuery( this ).attr( 'name' ), new_field_count ) );
		new_input_field_ids.push( yks_starter_repeating_fields_parse_id( jQuery( this ).attr( 'id' ), new_field_count ) );
	});

	// Clone the field - creating the new <li>
	var new_field = jQuery( last_field ).clone();

	// For the new input fields, update the ID and name attributes, wipe out the value, remove 'checked' and 'selected' attrs
	jQuery( new_field ).find( name_type ).each( function( index, field ) {
		jQuery( this ).attr( 'name', new_input_field_names[index] ).attr( 'id', new_input_field_ids[index] ).removeAttr( 'checked' ).removeAttr( 'selected' );
		if ( wipe_val === true ) {
			jQuery( this ).val( '' );
		}
	});

	// Always show the delete button for the new field (if we're cloning the first element, the delete button is not visible)
	jQuery( new_field ).find( name_type + '_delete' ).css( 'display', '' );

	// Append our new field to the <ul>
	jQuery( element ).parents( name_type + '_add_container' ).siblings( name_type + '_container' ).append( new_field );

	// Update the field counter
	jQuery( element ).data( 'field-count', new_field_count );
	
	return new_field;
}

/**
* Delete a field
*
* @param string | element	| A reference to the specific delete icon that was clicked
* @param string | name_type	| The field's name, with the initial class period (e.g. for textsmall, .yks_txt_small)
*/
function yks_starter_repeating_fields_delete( element, name_type ) {

	// Get and update the field count
	// var field_count = parseInt( jQuery( element ).parent( name_type + '_field' ).parent( name_type + '_container' ).siblings( name_type + '_add_container' ).children( name_type + '_add' ).data( 'field-count' ) );
	// jQuery( element ).parent( name_type + '_field' ).parents( name_type + '_container' ).siblings( name_type + '_add_container' ).children( name_type + '_add' ).data( 'field-count', field_count - 1 );

	// Remove the <li>
	jQuery( element ).parent( name_type + '_field' ).remove();
}

/**
* Set a field label's 'for' attribute to the ID of the input field.
*
* This assumes you have multiple <label> fields wrapping input fields, e.g.
* 	 <label>
*		 <input>
*	 </label>
* 	 <label>
*		 <input>
*	 </label>
*
* @param string | element	| A reference to the specific delete icon that was clicked
* @param string | name_type	| The field's name, with the initial class period (e.g. for textsmall, .yks_txt_small)
*/
function yks_starter_repeating_fields_update_label_for_attr( element, name_type ) {
	jQuery.each( element, function() {

		// Get the ID of the input field in this div
		var input_id = jQuery( this ).children( name_type ).attr( 'id' );

		// Set the ID of the label equal to the ID of the input field
		jQuery( this ).attr( 'for', input_id );
	});
}