jQuery( document ).ready( function() {
	// Enable sorting
	enable_sortable_items();
	// Initialize drag and drop
	initialize_drag_and_drop_functionality();
	// Initialize tabs
	initialize_tabs();

	// When a user clicks on the 'title' bar of the form field (slide toggle it)
	jQuery( 'body' ).on( 'click', '.yikes_admin_icons', function() {
		jQuery( this ).next().stop().slideToggle();
	});
});

/**
 * Enable the sortable function on our fields
 * @since 1.0.0
 */
function enable_sortable_items() {
	jQuery( "#droppable" ).sortable({
		placeholder: "ui-state-highlight ui-sortable-placeholder",
		revert: true,
		start: function( event, ui ) {
			// When sorting has started, collapse the element being sorted
			var dropped_item = get_dragged_item( ui );
			jQuery( dropped_item ).find( '.interior_container' ).hide();
		},
		stop: function( event, ui ) {
			// not sure
		},
	});
	jQuery( "#droppable" ).disableSelection();
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
			// Hide all expanded containers when a new one is added (avoid long pages)
			toggle_form_field_expandable_content();
			// Replace the content
			setTimeout( function() {
				dropped_item.fadeTo( 'fast', 0, function() {
					dropped_item.replaceWith ( '<div class="placeholder-preloader">' + script_data.preloader + '</div>' );
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
	// We should also re-initialize our tooltips
	jQuery( '.yikes_tooltip' ).each( function() {
		jQuery( this ).tipso({
			background        : '#add8e6',
			titleBackground   : '#333333',
			color             : '#333333',
			titleColor        : '#ffffff',
			titleContent      : jQuery( this ).attr( 'data-tipso-title'),
			showArrow         : true,
			position          : 'bottom-left',
		});
	});
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
		jQuery( '.placeholder-preloader' ).replaceWith( response );
		// initialize the tabs in this container
		initialize_tabs();
		// re-enable sorting
		enable_sortable_items();
	});
}

/**
 * Toggle the nearest hidden container visibility
 * @param  object clicked_element The element that the user clicked to trigger this function (passed in using 'this')
 * @return {[type]}                 [description]
 */
function toggleClosestHiddenContainer( clicked_element ) {
	jQuery( clicked_element ).closest( 'div' ).find( '.hidden_section' ).slideToggle();
	return;
}

/**
 * Toggle the visibile pattern containier section
 * @param  {[type]} clicked_radio_button [description]
 * @return {[type]}                      [description]
 */
function togglePatternContainer( clicked_radio_button, pattern ) {
	var pattern_class = ( 'custom' === pattern ) ? '.custom-pattern' : '.standard-pattern';
	var no_checked_pattern = ( 'custom' === pattern ) ? '.standard-pattern' : '.custom-pattern';
	jQuery( no_checked_pattern ).slideToggle( 'fast', function() {
		jQuery( pattern_class ).slideToggle();
	});
}

/**
 * When a user clicks the x on the form field, we need to delete it
 * @param  object clicked_button The clicked x, to use as reference.
 * @since 1.0.0
 */
function delete_this_application_form_field( clicked_button ) {
	if ( confirm( 'Are you sure you want to delete this form field?') ) {
		jQuery( clicked_button ).parents( '.yikes-field-container' ).css( 'background', '#F2DEDE' ).fadeOut( 'medium', function() {
			jQuery( this ).remove();
			// If the application builder is now empty,
			// set the #droppable to empty so our background appears
			if ( jQuery( '#droppable' ).find( '.yikes-field-container' ).length <= 0 ) {
				jQuery( '#droppable' ).html( '' );
			}
		});
		return;
	}
}

/**
 * Collapse all visible .
 */
function toggle_form_field_expandable_content() {
	jQuery( '.yikes-field-container' ).find( '.interior_container' ).slideUp();
}
