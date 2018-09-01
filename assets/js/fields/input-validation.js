// Application Form Validation.
jQuery(document).ready(function($) {
    // Variables
    var isError = false;
    const submitBtn = $('.lpf-submit');
    const allFields = $('.lpf-form-field');

    // Class name variables
    const errorClass = 'error-prompt';

    /* Empty required input field error checking. */
    const emptyRequired = (field) => {
        // Trim whitespace.
        const trimmedValue = $.trim(field.val());
        // If empty...
        if (! trimmedValue) {
            if ( field.parent().find('.error-empty').length === 0 ) {
                field.before('<span class="error-text error-empty">Field can not be empty.</span>');
                field.parent().addClass(errorClass);
            }
            return true;
        } else {
            field.parent().find('.error-empty').remove();
            field.parent().removeClass(errorClass);
            return false;
        }
    }
    /* Valid email field error checking. */
    const validEmail = (field) => {
    // If type is email, call function to validate email
        const emailInput = field.val();
        const regexEmail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        const isEmailValid = regexEmail.test(emailInput);
        if (! isEmailValid ) {
            if ( field.parent().find('.error-email').length === 0 ) {
                field.before('<span class="error-text error-email">Email is invalid.</span>');
            }
            field.parent().addClass(errorClass);
            return true;
        } else {
            field.parent().find('.error-email').remove();
            field.parent().removeClass(errorClass);
            return false;
        }
    }
    const submitValidation = (event) => {
        allFields.each( function() {
            isError = emptyRequired($(this));
            if ( $(this).attr('type') === 'email' && ! isError ) {
                isError = validEmail($(this));
                console.log(isError);
            }
        });
        console.log(isError);
        if ( isError ) {
            event.preventDefault();
        }
    }

    // When 'Submit' button is clicked...
    submitBtn.on('click', submitValidation);

});