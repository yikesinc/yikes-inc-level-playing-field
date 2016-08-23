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
		handle: '.button'
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
		cancel: false,
		revert: "invalid",
		start: function( event, ui ) {
			console.log( 'Active' );
		},
		stop: function( event, ui ) {
			console.log( 'Stopped' );
		}
	});
	jQuery( "#droppable" ).droppable({
		drop: function( event, ui ) {
			jQuery( ui.draggable ).clone().appendTo( jQuery(this) );
			enable_sortable_items();
		}
	});
}
