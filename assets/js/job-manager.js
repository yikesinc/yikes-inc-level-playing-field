jQuery( document ).ready( function( $ ) {
	'use strict';

	const $address_div = $( '#job_cpt_meta_address_address-1' ).parents( 'tr' );
	const job_manager_actions = {

		/**
		 * Initialize Job Manager actions.
		 */
		init: function() {
			$( "input[name='job_cpt_meta_location']" ).on( 'change', this.address_div ).change();
			this.deregisterBlocks();
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
					$address_div.fadeIn();
					break;

				default:
					$address_div.fadeOut();
					break;
			}
		},

		/**
		 * Deregister gutenberg blocks.
		 */
		deregisterBlocks: function() {
			if ( typeof lpf_job_manager_data !== 'undefined' && lpf_job_manager_data.disallowed_blocks && typeof wp.blocks !== 'undefined' && typeof wp.blocks.unregisterBlockType === 'function' ) {
				for ( const counter in lpf_job_manager_data.disallowed_blocks ) {
					wp.blocks.unregisterBlockType( lpf_job_manager_data.disallowed_blocks[ counter ] );
				}
			}
		}
	};

	job_manager_actions.init();
} );
