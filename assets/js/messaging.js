jQuery( function ( $ ) {

	const get_message            = function() { return document.getElementById( 'new-applicant-message' ).value; }
	const set_message            = function( value ) { document.getElementById( 'new-applicant-message' ).value = value; }
	const get_interview_date     = function() { return document.getElementById( 'interview-date' ).value; }
	const set_interview_date     = function( value ) { document.getElementById( 'interview-date' ).value = value; }
	const get_interview_time     = function() { return document.getElementById( 'interview-time' ).value; }
	const set_interview_time     = function( value ) { document.getElementById( 'interview-time' ).value = value; }
	const get_interview_location = function() { return document.getElementById( 'interview-location' ).value; }
	const set_interview_location = function( value ) { document.getElementById( 'interview-location' ).value = value; }
	const get_interview_message  = function() { return document.getElementById( 'interview-message' ).value; }
	const set_interview_message  = function( value ) { document.getElementById( 'interview-message' ).value = value; }
	const post_id                = typeof messaging_data.post !== 'undefined' && typeof messaging_data.post.ID !== 'undefined' ? parseInt( messaging_data.post.ID ) : 0;

	if ( 0 === post_id ) {
		return;
	}

	$( document ).ready( function() {

		$( 'body' ).on( 'click', '#send-new-applicant-message', function() {

			const message = get_message();

			// Validate the message.
			if ( ! validate_message_data( message ) ) {
				return;
			}

			send_message( message, post_id );
		});

		// Admin side functions.
		if ( $( '#interview-scheduler' ).length > 0 ) {

			$( '#interview-scheduler' ).click( function() { 
				const dashicon = $( this ).children( '.dashicons' );
				dashicon.hasClass( 'dashicons-arrow-down' ) ? dashicon.removeClass( 'dashicons-arrow-down' ).addClass( 'dashicons-arrow-up' ) : dashicon.removeClass( 'dashicons-arrow-up' ).addClass( 'dashicons-arrow-down' );
				$( '#interview-scheduler-fields-container, #send-interview-request-button-container' ).toggleClass( 'hidden' ); 
				$( '.new-applicant-message-container' ).toggleClass( 'cursor-disabled' ); 
				$( '#new-applicant-message' ).toggleClass( 'disabled-for-interview' ); 
			});
			$( '.lpf-datepicker' ).datepicker({});
			$( '.lpf-timepicker' ).timepicker({
				minTime: '5:00am',
				maxTime: '11:00pm',
				step: 15,
			});
			$( '#send-interview-request' ).click( function() {
				const date     = get_interview_date();
				const time     = get_interview_time();
				const location = get_interview_location();
				const message  = get_interview_message();

				// Validate the interview data.
				if ( ! validate_interview_data( date, time, location, message ) ) {
					return;
				}

				send_interview_request( date, time, location, message );
			});
		}
	});

	function send_interview_request( date, time, location, message ) {
		const data = {
			nonce   : messaging_data.ajax.interview_nonce,
			date    : date,
			time    : time,
			location: location,
			message : message,
			post_id : post_id,
			action  : 'send_interview_request'
		};

		$.post( messaging_data.ajax.url, data, function( response ){ send_interview_request_response( response ); } );
	}

	function validate_interview_data( date, time, location, message ) {

		if ( date.length === 0 || time.length === 0 || location.length === 0 || message.length === 0 ) {
			return false;
		}

		return true;
	}

	function send_interview_request_response( response ) {
		// Clear the interview fields.
		set_interview_date( '' );
		set_interview_time( '' );
		set_interview_location( '' );
		set_interview_message( '' );

		// Show the new message on the message board.
		refresh_message_board( response.data.post_id );
	}

	function validate_message_data( message_value ) {
		if ( message_value.length === 0 ) {
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
		};

		$.post( messaging_data.ajax.url, data, function( response ){ send_message_response( response ); } );
	}

	function send_message_response( response ) {

		// Clear the message textarea.
		set_message( '' );

		// Show the new message on the message board.
		refresh_message_board( response.data.post_id );
	}

	function refresh_message_board( post_id ) {
		const data = {
			nonce     : messaging_data.ajax.refresh_nonce,
			post_id   : post_id,
			action    : 'refresh_conversation',
			is_metabox: messaging_data.is_metabox
		};

		$.post( messaging_data.ajax.url, data, function( response ){ refresh_message_board_response( response ); } );
	}

	function refresh_message_board_response( response ) {
		$( '#applicant-messaging' ).html( response.data );
	}
});