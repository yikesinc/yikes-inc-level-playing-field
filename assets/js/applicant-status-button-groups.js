( function( $ ) {

	const post_id = document.getElementById( 'post_ID' ).value;

	if ( post_id.length === 0 ) {
		console.log( 'could not find a post ID: ' + post_id );
		return;
	}

	$( document ).ready( function() {
		$( '.tax-btn-group button' ).click( function() {
			const button = $( this );

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
			nonce: taxonomy_button_group_data.ajax.nonce
		};

		$.post( taxonomy_button_group_data.ajax.url, data, function( response ) { console.log( response ); });
	}

})( jQuery );
