(function( $ ) {
	'use strict';

	jQuery( document ).ready( function() {
		// If no apply now link is present, abort to avoid JS errors
		if ( ! jQuery( '.apply-now-link' ).length ) {
			return;
		}
		// Initialize the popups
		var lightbox = lity();
		// Bind as an event handler
		jQuery( document ).on( 'click', '.apply-now-link', function() {
			lightbox( jQuery( '#yikes-job-application-form' ) );
		});
	});

})( jQuery );
