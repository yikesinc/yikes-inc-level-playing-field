jQuery( document ).ready( function( $ ) {
	'use strict';

	const $address_div = $( '#job_cpt_meta_address_address-1' ).closest( '.yks-mbox-group-field' );
	const job_manager_actions = {

		/**
		 * Initialize Job Manager actions.
		 */
		init: function() {
			$( "input[name='job_cpt_meta_location']" ).on( 'change', this.address_div ).change();
		},

		/**
		 * Show/hide the Address div based on the selected radio button.
		 *
		 * @param {event} e
		 */
		address_div: function( e ) {
			// We get multiple elements assigned, so make sure we're comparing a checked item.
			if ( ! e.currentTarget.checked ) {
				return;
			}

			switch ( e.currentTarget.value ) {
				case 'address':
					$address_div.show();
					break;

				default:
					$address_div.hide();
					break;
			}
		}
	};

	job_manager_actions.init();
} );
