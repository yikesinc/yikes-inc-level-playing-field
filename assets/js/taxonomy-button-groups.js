( function( $ ) {

	$( document ).ready( function() {
		$( '.tax-btn-group button' ).click( function() {
			const button = $( this );

			// Remove active class from all buttons, add it to the clicked button.
			button.siblings( 'button' ).removeClass( 'active' );
			button.addClass( 'active' );

			// Fill the hidden input field with the active
			$( '.tax-input.' + button.data( 'taxonomy' ) ).val( button.data( 'value' ) );
		});
	});

})( jQuery );
