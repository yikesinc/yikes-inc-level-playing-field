jQuery( function ( $ ) {

	const get_message = function() { return document.getElementById( 'new-applicant-message' ).value; }
	const set_message = function( value ) { document.getElementById( 'new-applicant-message' ).value = value; }
	const post_id     = parseInt( document.getElementById( 'post_ID' ).value );

	$( document ).ready( function() {

		$( 'body' ).on( 'click', '#send-new-applicant-message', function() {

			const message = get_message();

			// Validate the message.
			if ( ! validate_message_data( message, post_id ) ) {
				return;
			}

			send_message( message, post_id );
		});

		$( 'body' ).on( 'click', '#conversation-show-all', function() {

			const additional_messages = $( '.conversation-container .hidden' );
			const headline            = $( '#conversation-show-all' );

			// Fade the messages in/out, change the title
			if ( additional_messages.is( ':visible' ) ) {
				additional_messages.fadeOut();
				headline.fadeOut( 'fast', function() { headline.text( messaging_data.strings.show_additional_messages ).fadeIn() });
			} else {
				additional_messages.fadeIn();
				headline.fadeOut( 'fast', function() { headline.text( messaging_data.strings.hide_additional_messages ).fadeIn() });
			}
		});
	});

	function validate_message_data( message_value, post_id ) {
		if ( message_value.length === 0 ) {
			return false;
		}

		if ( isNaN( post_id ) || post_id <= 0 ) {
			return false;
		}

		return true;
	}

	function send_message( message_value, post_id ) {

		const data = {
			nonce  : messaging_data.ajax.send_nonce,
			message: message_value,
			post_id: post_id,
			action : 'send_message'
		}

		$.post( messaging_data.ajax.url, data, function( response ){ send_message_response( response ); } );
	}

	function send_message_response( response ) {
		console.log( response );

		// Clear the message textarea.
		set_message( '' );

		// Show the new message on the message board.
		refresh_message_board( response.data.post_id );
	}

	function refresh_message_board( post_id ) {
		const data = {
			nonce  : messaging_data.ajax.refresh_nonce,
			post_id: post_id,
			action : 'refresh_conversation'
		}

		$.post( messaging_data.ajax.url, data, function( response ){ refresh_message_board_response( response ); } );
	}

	function refresh_message_board_response( response ) {
		$( '#applicant-messaging .inside' ).html( response.data );
	}

});