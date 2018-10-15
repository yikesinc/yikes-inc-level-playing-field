( function( $ ) {

	// Perhaps we should throw some type of error here.
	if ( typeof options_data === 'undefined' ) {
		return;
	}

	console.log( options_data );

	var options = JSON.parse( options_data.options );

	console.log( options );

	$( '#lpf-settings-save' ).click( function() {
		save( get_options() );
	});

	function save( opts ) {
		console.log( opts );

		const data = {
			nonce: options_data.ajax.save_nonce,
			action: options_data.ajax.save_action,
			options: opts,
		}

		$.post( options_data.ajax.url, data, function( response ) { handle_save_response( response ); });
	}

	function handle_save_response( response ) {

		console.log( response );

		if ( typeof response.success === 'undefined' ) {
			handle_save_response_failure();
		}

		if ( response.success === false ) {
			handle_save_response_failure();	
		}

		handle_save_response_success();
	}

	function handle_save_response_failure() {
		console.log( 'failure' );
	}

	function handle_save_response_success() {
		console.log( 'success' );
	}

	function get_options() {

		$( '.settings-field' ).each( function() {
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

	function convert_dashes( name ) {
		return replace_all( name, '-', '_' );
	} 

	function replace_all( target, search, replacement ) {
		return target.split( search ).join( replacement );
	}

})( jQuery );
