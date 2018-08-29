// Base Input Field Validation. Trim whitespace and check for empty required fields.
jQuery(document).ready(function($) {
    $(window).load(function(){
        // Variables
        const allFields = $('.lpf-form-field:required');
        const submitBtn = $('.lpf-submit');

        // Empty required field error checking.
        const submitValidation = (event) => {
            allFields.each( function() {
                // Trim whitespace
                trimmedValue = $.trim(this.value);
                this.value = trimmedValue;
                // If empty...
                if (!trimmedValue) {
                    console.log('empty');
                    $(this).before('<span class="error-prompt">Field can not be empty.</span>');
                    $(this).addClass('error-empty');
                    event.preventDefault();
                } else {
                    $(this).parent().find('.error-prompt').remove();
                    $(this).removeClass('error-empty');
                }
            });
        }

        // When 'Submit' button is clicked...
        submitBtn.on('click', submitValidation);
    });
});