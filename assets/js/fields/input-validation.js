// Application Form Validation.
jQuery(document).ready(function($) {
    /**************
     * Variables
     **************/
    let isError = false;
    const submitBtn = $('.lpf-submit');

    // Class name variables
    const generalErrorClass = 'error-prompt';

    /**************
     * Functions
     **************/
    // Regex testing
    const regexTest = (regex, field, type) => {
        const input = field.val();
        const isValid = regex.test(input);
        const errorClass = 'error-' + type.toLowerCase();
        if (! isValid ) {
            if ( field.parent().find('.' + errorClass).length === 0 ) {
                field.before('<span class="error-text ' + errorClass + '">' + type + ' is invalid.</span>');
            }
            field.parent().addClass(generalErrorClass);
            isError = true;
            return true;
        } else {
            field.parent().find('.' + errorClass).remove();
            field.parent().removeClass(generalErrorClass);
            return false;
        }
    }

    /* Empty required input field error checking. */
    const emptyRequired = (field) => {
        // Trim whitespace.
        const trimmedValue = $.trim(field.val());
        // If empty...
        if (! trimmedValue) {
            if ( field.parent().find('.error-empty').length === 0 ) {
                field.before('<span class="error-text error-empty">Field can not be empty.</span>');
                field.parent().addClass(generalErrorClass);
            }
            isError = true;
            return true;
        } else {
            field.parent().find('.error-empty').remove();
            field.parent().removeClass(generalErrorClass);
            return false;
        }
    }

    /* Valid email field error checking. */
    const validEmail = (field) => {
        const regexConstructor = new RegExp('^([a-zA-Z0-9_.+-])+\\@(([a-zA-Z0-9-])+\\.)+([a-zA-Z0-9]{2,4})+$');
        regexTest(regexConstructor, field, 'Email');
    }

    /* Valid United States zipcode field error checking. */
    const validUSZip = (field) => {
        const regexConstructor = new RegExp('(^\\d{5}$)|(^\\d{5}-\\d{4}$)');
        regexTest(regexConstructor, field, 'Zip Code');
    }

    /* Valid year field error checking. */
    const validYear = (field) => {
        const regexConstructor = new RegExp('^(19\\d\\d|2\\d{3})$');
        regexTest(regexConstructor, field, 'Year');
    }

    /* Valid phone number field error checking. */
    const validPhone = (field) => {
        const regexConstructor = new RegExp('^(\\d{10})$');
        return regexTest(regexConstructor, field, 'Phone');
    }

    // Core validation on submit function
    const submitValidation = (event) => {
        const allFields = $('.lpf-field-container .lpf-form-field, .lpf-field-container textarea');
        allFields.each( function() {
            let isFieldEmpty;
            // If type is email, call function to validate email
            if ( $(this).attr('required') !== false && typeof $(this).attr('required') !== typeof undefined ) {
                isFieldEmpty = emptyRequired($(this));
            }
            if ( ! isFieldEmpty ) {
                // If field is email, call function to validate email
                if ( $(this).attr('type') === 'email' ) {
                    validEmail($(this));
                }
                // If field is phone number, call function to validate phone number
                if ( $(this).attr('type') === 'tel' ) {
                    const justDigits = this.value.replace(/\D/g, '');
                    $(this).val(justDigits);
                    // If phone number is valid and there is no error returned...
                    if ( ! validPhone($(this)) ) {
                        this.value = '';
                        const char = {0:'(',3:') ',6:' - '};
                        for (let i = 0; i < justDigits.length; i++) {
                            this.value += (char[i]||'') + justDigits[i];
                        }
                    }
                }
                // If field is postal code/zip, call function to validate 5-digit zip
                if ( $(this).attr('id') === 'application_cpt_address[zip]' ) {
                    validUSZip($(this));
                }
                // If field is year, call function to validate 4-digit year
                if ( $(this).attr('name').indexOf('[year]') > -1 ) {
                    validYear($(this));
                }
            }
        });
        if ( isError ) {
            event.preventDefault();
        }
        isError = false;
    }

    /**************
     * Event Listeners
     **************/
    // When 'Submit' button is clicked...
    submitBtn.on('click', submitValidation);

});