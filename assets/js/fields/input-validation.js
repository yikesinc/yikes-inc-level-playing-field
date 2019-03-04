// Application Form Validation.
jQuery( document ).ready( function( $ ) {

	let isError = false;
	const $submitBtn = $( '.lpf-submit' );
	const errorPrompt = 'error-prompt';
	const i18n = $.extend( true, {
		// These are defaults in case the window object is missing.
		// See src/Shortcode/Application.php for localized strings.
		errors: {
			empty: 'Field cannot be empty.',
			invalid: '%TYPE% is invalid.'
		}
	}, ( window.lpfInputValidation || {} ) );

	/**
	 * Test a field value against a regular expression.
	 *
	 * @param {RegExp} regex
	 * @param {jQuery} field
	 * @param {string} type
	 * @returns {boolean}
	 */
	const regexTest = ( regex, field, type ) => {
		const input = field.val();
		const isValid = regex.test( input );
		const errorClass = `error-${type.toLowerCase()}`;

		if ( !isValid ) {
			if ( field.parent().find( '.' + errorClass ).length === 0 ) {
				let message = i18n.errors.invalid.replace( '%TYPE%', type );
				field.before( `<span class="error-text ${errorClass}">${message}</span>` );
			}
			field.parent().addClass( errorPrompt );
			isError = true;
			return true;
		} else {
			field.parent().find( `.${errorClass}` ).remove();
			field.parent().removeClass( errorPrompt );
			return false;
		}
	};

	/**
	 * Empty required input field error checking.
	 *
	 * @param {jQuery} field
	 * @returns {boolean}
	 */
	const emptyRequired = ( field ) => {
		// Trim whitespace.
		const trimmedValue = $.trim( getVal( field ) );
		const errorClass   = '.error-empty';
		const fieldLabel   = field.parents( 'label' );

		// If empty...
		if ( !trimmedValue ) {
			if ( fieldLabel.find( errorClass ).length === 0 ) {
				field.before( `<span class="error-text error-empty">${i18n.errors.empty}</span>` );
				fieldLabel.addClass( errorPrompt );
			}
			isError = true;
			return true;
		} else {
			fieldLabel.find( errorClass ).remove();
			fieldLabel.removeClass( errorPrompt );
			return false;
		}
	};

	const getVal = ( field ) => {
		return ! field.hasClass( 'lpf-field-wysiwyg' ) ? field.val() : ( tinymce ? tinymce.get( field.attr( 'id' ) ).getContent() : '' );
	}

	/**
	 * Valid email field error checking.
	 *
	 * @param {jQuery} field
	 */
	const validEmail = ( field ) => {
		const regex = new RegExp( '^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\\.)+([a-zA-Z0-9]{2,4})+$' );
		regexTest( regex, field, 'Email' );
	};

	/**
	 * Valid United States zipcode field error checking.
	 *
	 * @param {jQuery} field
	 */
	const validUSZip = ( field ) => {
		const regex = new RegExp( '(^\\d{5}$)|(^\\d{5}-\\d{4}$)' );
		regexTest( regex, field, 'Zip Code' );
	};

	/**
	 * Valid year field error checking.
	 *
	 * @param {jQuery} field
	 */
	const validYear = ( field ) => {
		const regex = new RegExp( '^(19\\d\\d|2\\d{3})$' );
		regexTest( regex, field, 'Year' );
	};

	/**
	 * Core validation on submit function
	 *
	 * @param event
	 */
	const submitValidation = ( event ) => {
		const allFields = $( '.lpf-field-container .lpf-form-field, .lpf-field-container textarea' );

		allFields.each( function() {
			let isFieldEmpty;
			let $this = $( this );

			// Don't let required fields be empty.
			if ( $this.prop( 'required' ) === true || $this.hasClass( 'lpf-field-required' ) ) {
				isFieldEmpty = emptyRequired( $this );
			}

			if ( ! isFieldEmpty ) {

				// If field is email, call function to validate email
				if ( $this.attr( 'type' ) === 'email' ) {
					validEmail( $this );
				}

				// If field is phone number, call function to validate phone number
				if ( $this.attr( 'type' ) === 'tel' ) {
					const justDigits = this.value.replace( /\D/g, '' );
					$this.val( justDigits );
				}

				// If field is postal code/zip, call function to validate 5-digit zip
				if ( $this.attr( 'id' ) === 'application_cpt_address[zip]' ) {
					validUSZip( $this );
				}

				// If field is year, call function to validate 4-digit year
				if ( $this.attr( 'name' ).indexOf( '[year]' ) > -1 ) {
					validYear( $this );
				}
			}
		} );

		if ( isError ) {
			event.preventDefault();
		}

		isError = false;
	};

	// When 'Submit' button is clicked...
	$submitBtn.on( 'click', submitValidation );

	/**
	 * Toggle the end date's required attr and visibility based on the to-the-present field being checked.
	 *
	 * @param event The click event that triggered this.
	 */
	const toggleEndDate = ( event ) => {
		const element = $( event.target );
		const checked = event.target.checked;
		const endDate = element.closest( '.lpf-fieldset' ).find( 'input[name$="[end_date]"]' );
		const edLabel = endDate.closest( '.lpf-field-container' );

		if ( checked ) {
			edLabel.addClass( 'lpf-disabled' );	
			endDate.prop( 'required', false );
		} else {
			edLabel.removeClass( 'lpf-disabled' );
			endDate.prop( 'required', true );
		}
	};

	// When a to-the-present checkbox is clicked.
	$( 'body' ).on( 'click', 'input[type="checkbox"][name$="[present_position]"]', toggleEndDate );
} );
