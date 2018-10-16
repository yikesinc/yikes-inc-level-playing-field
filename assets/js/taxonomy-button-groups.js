( function( $ ) {

	const post_id = document.getElementById( 'post_ID' ).value;

	$( document ).ready( function() {
		$( '.tax-btn-group button' ).click( function() {
			const button  = $( this );

			// Remove active class from all buttons, add it to the clicked button.
			button.siblings( 'button' ).removeClass( 'active' );
			button.addClass( 'active' );

			// Send the AJAX request.
			add_post_term( button.data( 'taxonomy' ), button.data( 'value' ) );
		});
	});

	function add_post_term( taxonomy, term ) {
		const data = {
			action: 'lpf_add_post_term',
			tax: taxonomy,
			term: term,
			post_id: post_id,
			nonce: taxonomy_button_group_data.ajax.nonce
		}

		$.post( taxonomy_button_group_data.ajax.url, data, function( response ) { console.log( response ); });
	}

})( jQuery );
