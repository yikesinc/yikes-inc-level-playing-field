jQuery( document ).ready( function( $ ) {
	'use strict';

	const $extraInfoFields = $( '.yks_extra_info' );
	const application_manager_actions = {

		/**
		 * Initialize Job Manager actions.
		 */
		init: function() {
			$extraInfoFields.on( 'change', this.displayExtraInfo ).change();
		},

		/**
		 * Show/hide the extra info span based on the checkbox.
		 */
		displayExtraInfo: function( e ) {
			const $this = $( this );
			const $info = $( '.' + $this.data( 'section' ) );
			if ( $this.is( ':checked' ) ) {
				$info.show();
			} else {
				$info.hide();
			}
		}
	};

	application_manager_actions.init();
} );
