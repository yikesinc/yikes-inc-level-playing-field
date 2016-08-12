(function( $ ) {
	'use strict';

	jQuery( document ).ready( function() {
		// Initialize the popups
		var lightbox = lity();
		// Bind as an event handler
		jQuery( document ).on( 'click', '.apply-now-link', function() {
			lightbox( jQuery( '#yikes-job-application-form' ) );
		});
	});

})( jQuery );
