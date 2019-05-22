import LPFNotices from '../functions/notices.js';
( function( $ ) {

	const notices = new LPFNotices();
	const post_id = document.getElementById( 'post_ID' ).value;

	if ( post_id.length === 0 ) {
		console.log( 'could not find a post ID: ' + post_id );
		return;
	}

	$( document ).ready( function() {
		$( '.tax-btn-group button' ).click( function() {
			const button = $( this );

			// Remove any notices we added to the page.
			notices.remove_notices();

			// Remove active class from all buttons, add it to the clicked button.
			button.addClass( 'active' ).siblings( 'button' ).removeClass( 'active' );

			// Send the AJAX request.
			add_post_term( button.data( 'value' ) );
		});
	});

	function add_post_term( term ) {
		const data = {
			action: 'lpf_add_post_term',
			term: term,
			post_id: post_id,
			nonce: taxonomy_button_group_data.nonce
		};

		$.post( window.ajaxurl, data ).always( function( response, successText ) {
			if ( 'error' === successText ) {
				add_post_term_failure( response.responseJSON );
			}
		});
	}

	function add_post_term_failure( response ) {
		if ( response.success !== true && typeof response.data === 'object' && typeof response.data.reason === 'string' ) {
			$( '.tax-btn-group' ).parent().after( notices.admin_notice( 'error', response.data.reason ) );
		}
	}
})( jQuery );
