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
			var drop_zone_width = jQuery( '#droppable' ).width() + 'px';
			dropped_item.css( 'width', drop_zone_width ).fadeTo( 'fast', 0, function() {
				jQuery( '.ui-state-highlight' ).remove();
				jQuery( this ).replaceWith ( script_data.preloader );
				// Run our ajax function to retreive the new form field markup
				// Mock AJAX Temporarily in place.
				setTimeout( function() {
					jQuery( '.application_builder_preloader' ).replaceWith( '<h2>This would be the results...</h2>' );
				}, 1200 );
			});
			enable_sortable_items();
		}
	});
}

function get_dragged_item( ui ) {
	return jQuery( ui.helper[0] );
}
