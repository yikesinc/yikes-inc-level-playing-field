( function( $ ) {

	/*=include ../functions/notices.js */
	const notices = new class_lpf_notices();

	// Perhaps we should throw some type of error here.
	if ( typeof settings_data === 'undefined' ) {
		return;
	}

	var settings = JSON.parse( settings_data.settings );

	$( '#lpf-settings-save' ).click( function() {

		// Clear any notices.
		notices.remove_notices();

		// Save the settings.
		save( get_settings() );
	});

	/**
	 * Send the AJAX call.
	 */
	function save( opts ) {
		const data = {
			nonce  : settings_data.ajax.save_nonce,
			action : settings_data.ajax.save_action,
			settings: opts,
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
	 * Loop through each settings field, get the value, and add to settings array in the format
	 * Object: [ name ][ id ] = value;
	 * String: [ name ]       = value;
	 */
	function get_settings() {
		$( '.settings-field' ).each( function() {
			const value = get_value( this );
			const name  = convert_dashes( this.name );

			// Add the value to the array.
			if ( 'object' === typeof settings[ name ] ) {
				settings[ name ][ this.id ] = value;
			} else {
				settings[ name ] = value;
			}
		});

		return settings;
	}

	/**
	 * Get the value from a field based on the element type.
	 */
	function get_value( element ) {
		switch ( element.type ) {

			case 'textarea':
			case 'text':
				return element.value;	
			case 'checkbox':
				return element.checked;
		}		
	}

	/**
	 * Convert all dashes to underscores.
	 */
	function convert_dashes( name ) {
		return name.replace( /-/g, '_' );
	}

})( jQuery );
