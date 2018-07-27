jQuery( '.tax-btn-group button' ).click( function() {

    // Remove active class from all buttons, add it to the clicked button.
    jQuery( this ).siblings( 'button' ).removeClass( 'active' );
    jQuery( this ).addClass( 'active' );

    // Fill the hidden input field with the active
    jQuery( '.tax-input.' + jQuery( this ).data( 'taxonomy' ) ).val( jQuery( this ).data( 'tax-slug' ) );
});