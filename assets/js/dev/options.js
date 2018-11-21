( function( $ ) {

	/*=include ../functions/notices.js */
	const notices = new class_lpf_notices();

	// Perhaps we should throw some type of error here.
	if ( typeof options_data === 'undefined' ) {
		return;
	}

	var options = JSON.parse( options_data.options );

	$( '#lpf-options-save' ).click( function() {

		// Clear any notices.
		notices.remove_notices();

		// Save the options.
		save( get_options() );
	});

	/**
	 * Send the AJAX call.
	 */
	function save( opts ) {
		const data = {
			nonce  : options_data.ajax.save_nonce,
			action : options_data.ajax.save_action,
			options: opts,
		};

		$.post( window.ajaxurl, data ).always( function( response, successText ) {
			if ( 'error' === successText ) {
				handle_save_response_failure( response.responseJSON.data );
			} else if ( 'success' === successText ) {
				handle_save_response_success( response.data );
			}
		});
	}

	/**
	 * Handle a failed response.
	 */
	function handle_save_response_failure( response ) {
		$( '#notice-container' ).html( notices.admin_notice( 'error', response.reason ) );
	}

	/**
	 * Handle a successful response.
	 */
	function handle_save_response_success( response ) {
		$( '#notice-container' ).html( notices.admin_notice( 'success', response.reason ) );
	}

	/**
	 * Loop through each options field, get the value, and add to options array in the format
	 * Object: [ name ][ id ] = value;
	 * String: [ name ]       = value;
	 */
	function get_options() {
		$( '.options-field' ).each( function() {
			const value = get_value( this );
			const name  = convert_dashes( this.name );

			// Add the value to the array.
			if ( 'object' === typeof options[ name ] ) {
				options[ name ][ this.id ] = value;
			} else if ( 'string' === typeof options[ name ] ) {
				options[ name ] = value;
			}
		});

		return options;
	}

	/**
	 * Get the value from a field based on the element type.
	 */
	function get_value( element ) {
		switch ( element.type ) {

			case 'textarea':
			case 'text':
				return element.value;	
			break;

			case 'checkbox':
				return element.checked;
			break;
		}		
	}

	/**
	 * Convert all dashes to underscores.
	 */
	function convert_dashes( name ) {
		return replace_all( name, '-', '_' );
	} 

	/**
	 * Replace all `search` with `replacement` in `target`.
	 */
	function replace_all( target, search, replacement ) {
		return target.split( search ).join( replacement );
	}

})( jQuery );
