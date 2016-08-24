jQuery( document ).ready( function() {
	// Enable sorting
	enable_sortable_items();
	// Initialize drag and drop
	initialize_drag_and_drop_functionality();
});

/**
 * Enable the sortable function on our fields
 * @since 1.0.0
 */
function enable_sortable_items() {
	jQuery( "#droppable" ).sortable({
		placeholder: "ui-state-highlight",
		handle: '.button',
		revert: true
	});
	jQuery( "#droppable" ).disableSelection();
	console.log( 'sortable enabled' );
}

/**
 * Initialize the drag and drop functionality on our elements
 * @since 1.0.0
 */
function initialize_drag_and_drop_functionality() {
	jQuery( ".draggable" ).draggable({
		helper: "clone",
		connectToSortable: "#droppable",
		cancel: false,
		revert: "invalid",
		start: function( event, ui ) {
			var dropped_item = get_dragged_item( ui );
		},
		stop: function( event, ui ) {
			console.log( 'Stopped dragging item.' );
		}
	});
	jQuery( "#droppable" ).droppable({
		// Hover over drop zone
		over: function( event, ui ) {
			var dropped_item = get_dragged_item( ui );
			dropped_item.css( 'width', '500px' );
		},
		// Drop the item
		drop: function( event, ui ) {
			console.log( ui );
			var dropped_item = get_dragged_item( ui );
			dropped_item.css( 'width', '500px' );
			setTimeout( function() {
				dropped_item.fadeTo( 'fast', 0, function() {
					dropped_item.replaceWith ( script_data.preloader );
					// Run our ajax function to retreive the new form field markup
					// Mock AJAX Temporarily in place.
					get_and_add_application_field( ui );
				});
			}, 500);
		}
	});
}

/**
 * Get the dragged item
 * @param  array ui Array of data related to our dragged item.
 * @return object   jQuery object of the dragged item, for reference.
 */
function get_dragged_item( ui ) {
	return jQuery( ui.helper[0] );
}

/**
 * Initialize the tabs inside of the container
 * @since 1.0.0
 */
function initialize_tabs() {
	// initialize the tabs in this container
	jQuery( '.tab-container' ).tabs();
}

/**
 * AJAX Request to get the field markup to add to the form
 * @param array ui Array data from the dragged field. Use this to get the field type.
 * @return mixed HTML content to add to the application builder.
 */
function get_and_add_application_field( ui ) {
	var data = {
		'action': 'my_action',
		'field_type': jQuery( ui.helper[0] ).attr( 'data-type' ),
	};
	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post( ajaxurl, data, function( response ) {
		jQuery( '.application_builder_preloader' ).replaceWith( response );
		// initialize the tabs in this container
		initialize_tabs();
		// re-enable sorting
		enable_sortable_items();
	});
}
