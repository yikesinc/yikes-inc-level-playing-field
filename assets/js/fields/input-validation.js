// Application Form Validation.
jQuery( document ).ready( function( $ ) {

	const labelErrorClass = 'lpf-input-label-error';
	const inputErrorClass = 'lpf-form-field-error';

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
			if ( field.siblings( '.' + errorClass ).length === 0 ) {
				let message = i18n.errors.invalid.replace( '%TYPE%', type );
				field.after( `<span class="error-text ${errorClass}">${message}</span>` );
			}
			field.addClass( inputErrorClass ).siblings( 'label' ).addClass( labelErrorClass ).addClass( errorPrompt );
			isError = true;
		} else {
			field.removeClass( inputErrorClass ).siblings( 'label' ).removeClass( labelErrorClass ).siblings( `.${errorClass}` ).remove();
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
		const fieldLabel   = field.siblings( 'label' );

		// If empty...
		if ( !trimmedValue ) {
			if ( fieldLabel.siblings( errorClass ).length === 0 ) {
				field.addClass( inputErrorClass ).after( `<span class="error-text error-empty">${i18n.errors.empty}</span>` );
				fieldLabel.addClass( errorPrompt ).addClass( labelErrorClass );
			}
			isError = true;
			return true;
		} else {
			field.removeClass( inputErrorClass );
			fieldLabel.siblings( errorClass ).remove();
			fieldLabel.removeClass( errorPrompt ).removeClass( labelErrorClass );
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
			let isFieldEmpty = true;
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

				// If field is phone number, parse special characters from input (numbers only).
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
			// Scroll to first field with error.
			$('html, body').animate({
    			scrollTop: $('.lpf-field-container .error-text').first().parents('.lpf-field-container').offset().top
			},200);

		}

		isError = false;
	};

	/**
	 * Toggle the end date's required attr and visibility based on the to-the-present field being checked.
	 *
	 * @param event The click event that triggered this.
	 */
	const toggleEndDate = ( event ) => {
		const element = $( event.target );
		const checked = event.target.checked;
		const endDate = element.closest( '.lpf-fieldset-container' ).find( 'input[name$="[end_date]"]' );
		const edLabel = endDate.closest( '.lpf-field-container' );

		if ( checked ) {
			edLabel.addClass( 'lpf-disabled' );
			endDate.prop( 'required', false ).prop( 'disabled', true ).val( '' );
		} else {
			edLabel.removeClass( 'lpf-disabled' );
			endDate.prop( 'required', true ).prop( 'disabled', false );
		}
	};

	/**
	 * Show/hide degree & major fields if schooling type is high-school.
	 *
	 * @param event The click event that triggered this.
	 */
	const toggleSchoolingFields = ( event ) => {
		const selected = $( event.target );
		const fields = selected.parents( '.lpf-fieldset-container' ).find( 'input[id$="[major]"], input[id$="[degree]"]' );
		if ( selected.val() === 'high_school' ) {
			fields.val('N/A').parent('.lpf-input-label').hide();
		} else {
			fields.val('').parent('.lpf-input-label').show();
		}
	};


	/**
	 * Initialization.
	 */
	let isError = false;
	const $submitBtn = $( '.lpf-submit' );
	const errorPrompt = 'error-prompt';
	const i18n = $.extend( true, {
		// These are defaults in case the window object is missing.
		// See src/Shortcode/Application.php for localized strings.
		errors: {
			empty: 'This field is required.',
			invalid: '%TYPE% is invalid.'
		}
	}, ( window.lpfInputValidation || {} ) );

	// If schooling type field is 'High School'.
	const schoolingFields = $( '.lpf-fieldset-schooling select[id$="[type]"]' );
	schoolingFields.each(function() {
		if ( $(this).val() === 'high_school' ) {
			$(this).parents('.lpf-fieldset-schooling').find( 'input[id$="[major]"], input[id$="[degree]"]' ).val('N/A').parent('.lpf-input-label').hide();
		}
	});

	/**
	 * Event Listeners.
	 */
	 // When Schooling Institution type field is changed.
	$( 'body' ).on( 'change', '.lpf-fieldset-schooling select[id$="[type]"]', toggleSchoolingFields );

	// When 'Submit' button is clicked.
	$submitBtn.on( 'click', submitValidation );

	// When a to-the-present checkbox is clicked.
	$( 'body' ).on( 'click', 'input[type="checkbox"][name$="[present_position]"]', toggleEndDate );
} );
