
//Custom Function to force text field with only numeric characters including fractions
jQuery.fn.forceNumericFractions = function () {

	return this.each( function() {
		jQuery( this ).keydown( function ( e ) {
			var key = e.which || e.keyCode;

			if ( !e.shiftKey && !e.altKey && !e.ctrlKey &&
				
				// Numbers   
				key >= 48 && key <= 57 ||

				// Numeric keypad
				key >= 96 && key <= 105 ||

				// Period and minus on keypad
				key == 190 || key == 109 || key == 110 ||

				// Backspace and Tab and Enter
				key == 8 || key == 9 || key == 13 ||

				// Home and End
				key == 35 || key == 36 ||

				// Left and right arrows
				key == 37 || key == 39 ||

				// Del and Ins
				key == 46 || key == 45) {
				
				return true;
			} else {
				return false;
			}
		});
	});
}

// Custom Function to force text field with only numeric characters no fractions
jQuery.fn.forceNumeric = function() {

	return this.each( function() {
		jQuery( this ).keydown( function( e ) {
			var key = e.which || e.keyCode;
			if ( !e.shiftKey && !e.altKey && !e.ctrlKey &&

				// Numbers   
				key >= 48 && key <= 57 ||

				// Numeric keypad
				key >= 96 && key <= 105 ||

				// Minus on keypad
				key == 109 || key == 110 ||

				// Backspace and Tab and Enter
				key == 8 || key == 9 || key == 13 ||

				// Home and End
				key == 35 || key == 36 ||

				// Left and right arrows
				key == 37 || key == 39 ||

				// Del and Ins
				key == 46 || key == 45 ) {

				return true;
			} else {
				return false;
			}
		}); 
	});
}
		 
jQuery( document ).ready( function( $ ) {
	'use strict';

	// Force numeric value for money and number fields
	$( '.yks_txt_money' ).forceNumericFractions();
	$( '.yks_txt_number' ).forceNumeric();

	/**** jQuery timepicker ****/

		// Initialize the time picker when the field is focused on
		$( 'body' ).on( 'focus', '.yks_time_pick', function() {
			$( this ).timepicker({
				minTime: '07:00',
				maxTime: '22:00',
				timeFormat: 'h:i A',
				separator: ':',
				step: 30
			});
		});

		// Hide the dropdown when a time is selected
		$( 'body' ).on( 'changeTime', '.yks_time_pick', function() { $( this ).timepicker( 'hide' ); });


	/**** jQuery UI datepicker ****/

		// Initialize the standard jQuery UI datepicker when a '.yks_date_pick' field is focused on
		// For more options see http://jqueryui.com/demos/datepicker/#option-dateFormat
		$( 'body' ).on( 'focus', '.yks_date_pick', function() {

			// Initialize the datepicker
			$( this ).datepicker();

			// Remove the yks_datepicker_year_only class -- we don't want year-only fields to conflict with normal date fields
			$( '#ui-datepicker-div' ).removeClass( 'yks_datepicker_year_only' );

			// Wrap datepickers in a unique class to narrow the scope of jQuery UI CSS and prevent conflicts
			if ( $( '.yks_container' ).length === 0 ) {
				$( '#ui-datepicker-div' ).wrap( '<div class="yks_container" />' );
			}
		});

		// jQuery UI datepicker for year only
		$( 'body' ).on( 'focus', '.yks_year_pick', function() {

			var input_field = this;

			$( this ).datepicker({ 
				dateFormat: 'yy-mm-dd',
				stepMonths: 12,
				monthNames: ["","","","","","","","","","","",""],
				changeYear: true,
				yearRange: '1950:2025',
				beforeShow:
					function( input, inst ) {
						
						// Default to the currently selected date
						$( this ).datepicker( 'option', 'defaultDate', $( this ).val() + '-01-01' );

						// This class will hide the months/days portion of the datepicker
						$( '#ui-datepicker-div' ).addClass( 'yks_datepicker_year_only' );
					},
			    onClose: 
			    	function( dateText, inst ) { 
						
			    		// After the calendar is closed, set the value of the input field as the chosen date
						var year = $( '.ui-datepicker-year :selected' ).val();
						$( input_field ).val( year );
					}
		    });

		    if ( $( '.yks_container' ).length === 0 ) {
				$( '#ui-datepicker-div' ).wrap( '<div class="yks_container" />' );
			}
		});	
	

	/**** Initialize color picker ****/

		if ( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ) {
			$( 'input:text.yks_color_pick' ).wpColorPicker();
		}

	/**** File and image upload handling ****/

		$( '.yks_img_up' ).change( function () {
			var formfield = $( this ).attr( 'name' );
			$( '#' + formfield + '_id' ).val( "" );

		});

		$( 'body' ).on( 'click', '.yks_img_up_button', function( e ) {
			var clicked = $( this );
			var send_attachment_bkp = wp.media.editor.send.attachment;
			wp.media.editor.send.attachment = function( a, image ) {
				var preview_html = '';
                switch (image.type) {
                    case 'image':
                        preview_html = '<img src="' + image.url + '">';
                        break;
                    case 'video':
                        preview_html = '<span class="dashicons dashicons-media-video"></span>';
                        break;
					default:
                        preview_html = '<span class="dashicons dashicons-media-default"></span>';
                }
				clicked.siblings( '.yks_img_up' ).val( image.url );
				clicked.siblings( '.yks_img_up_id' ).val( image.id );
                clicked.siblings( '.yks_img_up_type' ).val( image.type );
				clicked.siblings( '.yks_upstat' ).html( '<div class="img_status">' + preview_html + '<a class="yks_hide_ubutton" data-switch="single">Remove Image</a></div>' );
			};
			wp.media.editor.open( clicked );
			return false;
		});	

		$( 'body' ).on( 'click', '.yks_hide_ubutton', function( e ) {
			e.preventDefault();

			var associated_field = $( this ).data( 'field-id' );
			var action			 = $( this ).data( 'oembed' );
			var data_switch		 = $( this ).data( 'switch' ); 

			/**** yks_oembed ****/
			if ( action === 'delete' ) {

				// Wipe the input field
				$( this ).parents( '.embed_status' ).parents( '.yks_upstat' ).siblings( '.yks_oembed' ).val( '' );

				// Remove the preview (removes the .embed_wrap div)
				$( this ).parents( '.embed_status' ).remove();
			}

			/**** yks_file ****/
			if ( data_switch === 'single' ) {

				// Wipe the input field values for ID and URL
				$( this ).parents( '.img_status' ).parents( '.yks_upstat' ).siblings( '.yks_file' ).val( '' );

				// Remove the preview container
				$( this ).parents( '.img_status' ).remove();
			}
		});


	 /**** oEmbed AJAX Listener ****/

	 	// When the embed field is focused-out, fire off an AJAX call
	 	$( 'body' ).on( 'blur', '.yks_oembed', function( e ) {
	 		check_embed_url_ajax( jQuery( this ), e );
	 	});

	 /**** Select2 ****/
	 	if ( jQuery( '.select2_init' ).length > 0 && typeof jQuery.fn.select2 === 'function' ) {
	 		$( '.select2_init' ).select2();
	 	}

	/**** Groups ****/
		$( 'body' ).on( 'click', '.group-tabs-list-item > a', function() {
			var tab = $( this ).data( 'tab' );

			if ( typeof tab === 'undefined' ) {
				return;
			}

			$( this ).parent( '.group-tabs-list-item' ).siblings( '.group-tabs-list-item' ).removeClass( 'active' );
			$( this ).parent( '.group-tabs-list-item' ).addClass( 'active' );

			$( this ).parents( '.group.yks_mbox' ).children( '.yks-mbox-group' ).not( '.yks-mbox-groupless-group' ).hide();
			$( this ).parents( '.group.yks_mbox' ).children( '.yks-mbox-group.' + tab ).show();
		});
});

// AJAX call for oEmbed
function check_embed_url_ajax( element, e ) {

	// Get the input field value
	var oembed_url = element.val();
	var field_id = element.attr( 'id' );

	// If the length is 0, remove the preview HTML
	if ( oembed_url.length === 0 ) {
		jQuery( '#' + field_id + '_status' ).html( '' );
		return;
	}

	// Add a spinner
	jQuery( '#' + field_id + '_status' ).html( yks_mbox_ajax_data.spinner );

	jQuery.post({
		dataType : 'json',
		url : yks_mbox_ajax_data.ajax_url,
		data : {
			'action': 'yks_oembed_handler',
			'oembed_url': oembed_url,
			'field_id': field_id,
			'post_id': yks_mbox_ajax_data.post_id,
			'yks_ajax_nonce': yks_mbox_ajax_data.ajax_nonce
		},
		success: function ( response ) {
			console.log( response );
			if ( typeof( response.result !== 'undefined' ) ) {
				if ( response.result === false ) {

					// We failed - could not get the embed code for the URL
					jQuery( '#' + field_id + '_status' ).html( '<p> URL is not a valid oEmbed URL. </p>' );

				} else {

					// Wrap our embed HTML in a div w/ class embed_status, and append our delete icon to the embed HTML
					var embed_html = '';
					embed_html += '<div class="embed_status">'
					embed_html +=	response.result;
					embed_html += 	'<span class="yks_hide_ubutton" data-field-id="' + field_id + '" data-oembed="delete" title="Remove Embed Preview"></span>';
					embed_html += '</div>';

					// Add the embed code to the status div (below the input field)
					jQuery( '#' + field_id + '_status' ).html( embed_html );
				}
			}
		}
	});
}