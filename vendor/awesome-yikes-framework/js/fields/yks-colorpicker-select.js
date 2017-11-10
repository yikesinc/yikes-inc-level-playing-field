jQuery( function( $ ) {

	// When the dropdown value changes, update the corresponding preview box
	$( 'body' ).on( 'change', '.yks_colorpicker_select', function() {
		var color = $( this ).val();
		$( this ).siblings( '.yks_colorpicker_select_preview' ).css( 'background-color', color );
	});
});