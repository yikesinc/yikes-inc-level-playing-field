// Base Input Field Validation. Trim whitespace and check for empty required fields.

jQuery(document).ready(function($) {
    $(window).load(function(){
        // Variables
        const allFields = $('.lpf-form-field');
        const submitBtn = $('.lpf-submit');
        const submitValidation = (event) => {
            allFields.each( function() {
                // Trim whitespace
                trimmedValue = $.trim(this.value);
                // If empty...
                if (!trimmedValue) {
                    console.log('empty');
                }
            });
            event.preventDefault();
        }

        // When 'Submit' button is clicked...
        submitBtn.on('click', submitValidation);
    });
});