jQuery( function ( $ ) {

	/**
	 * Getters, setters, global values.
	 */
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

		/**
		 * Scroll to the bottom of the messaging container.
		 */
		scroll_to_bottom();

		/**
		 * Handling clicking the send message button.
		 */
		$( 'body' ).on( 'click', '#send-new-applicant-message', function() {

			const message = get_message();

			// Validate the message.
			if ( ! validate_message_data( message ) ) {
				return;
			}

			// Disable interview request button while we wait for a response.
			$( this ).attr( 'disabled', 'disabled' );

			send_message( message, post_id );
		});

		// Admin side functions.
		if ( $( 'body' ).find( '#interview-scheduler' ).length > 0 ) {

			/**
			 * Initialize interview fields when interview scheduler is clicked.
			 */
			$( 'body' ).on( 'click', '#interview-scheduler', function() { 
				const dashicon = $( this ).children( '.dashicons' );
				dashicon.hasClass( 'dashicons-arrow-down' ) ? dashicon.removeClass( 'dashicons-arrow-down' ).addClass( 'dashicons-arrow-up' ) : dashicon.removeClass( 'dashicons-arrow-up' ).addClass( 'dashicons-arrow-down' );
				$( '#interview-scheduler-fields-container, #send-interview-request-button-container' ).toggleClass( 'hidden' ); 
				$( '.new-applicant-message-container' ).toggleClass( 'cursor-disabled' ); 
				$( '#new-applicant-message' ).toggleClass( 'disabled-for-interview' );

				// Enable datepicker.
				$( '.lpf-datepicker' ).datepicker({

					// a minDate of 0 is today so this means don't allow any dates before today.
					minDate: 0
				});

				// Enable timepicker.
				$( '.lpf-timepicker' ).timepicker({
					minTime: '5:00am',
					maxTime: '11:00pm',
					step: 15,
				});
			});


			/**
			 * Handle clicking the interview request button.
			 */
			$( 'body' ).on( 'click', '#send-interview-request', function() {

				const date     = get_interview_date();
				const time     = get_interview_time();
				const location = get_interview_location();
				const message  = get_interview_message();

				// Validate the interview data.
				if ( ! validate_interview_data( date, time, location, message ) ) {
					return;
				}

				// Disable interview request button while we wait for a response.
				$( this ).attr( 'disabled', 'disabled' );

				send_interview_request( date, time, location, message );
			});
		}
	});

	/**
	 * Send an interview request.
	 */
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

		$.post( messaging_data.ajax.url, data, function( response ) {
			send_interview_request_response( response );
		});
	}

	/**
	 * Ensure we have our values for sending an interview request.
	 */
	function validate_interview_data( date, time, location, message ) {
		if ( date.length === 0 || time.length === 0 || location.length === 0 || message.length === 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * Send the interview request response.
	 */
	function send_interview_request_response( response ) {
		// Clear the interview fields.
		set_interview_date( '' );
		set_interview_time( '' );
		set_interview_location( '' );
		set_interview_message( '' );

		// Show the new message on the message board.
		refresh_message_board( response.data.post_id );
	}

	/**
	 * Ensure we have our values for a message.
	 */
	function validate_message_data( message_value ) {
		if ( message_value.length === 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * Send the message.
	 */
	function send_message( message_value, post_id ) {

		const data = {
			nonce  : messaging_data.ajax.send_nonce,
			message: message_value,
			post_id: post_id,
			action : 'send_message'
		};

		$.post( messaging_data.ajax.url, data, function( response ){ send_message_response( response ); } );
	}

	/**
	 * Handle the response after sending a message.
	 */
	function send_message_response( response ) {

		// Clear the message textarea.
		set_message( '' );

		// Show the new message on the message board.
		refresh_message_board( response.data.post_id );
	}

	/**
	 * Fetch the messaging container's HTML.
	 */
	function refresh_message_board( post_id ) {
		const data = {
			nonce     : messaging_data.ajax.refresh_nonce,
			post_id   : post_id,
			action    : 'refresh_conversation',
			is_metabox: messaging_data.is_metabox
		};

		$.post( messaging_data.ajax.url, data, function( response ){
			refresh_message_board_response( response );
			scroll_to_bottom();
		});
	}

	/**
	 * Replace the messaging container with the returned HTML.
	 */
	function refresh_message_board_response( response ) {
		$( '#applicant-messaging' ).html( response.data );
	}

	/**
	 * Scroll to the bottom of the messaging container.
	 */
	function scroll_to_bottom() {
	 	const conversation_container = $( '.conversation-container' );
		conversation_container.animate({ scrollTop: conversation_container.prop( 'scrollHeight' ) - conversation_container.height() }, 1 );
	 }
});