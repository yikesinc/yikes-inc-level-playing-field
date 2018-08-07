jQuery( document ).ready( function() {
	'use strict';

	const buttonClass = 'lpf-repeat-button';
	const i18n = window.lpfRepeater || {};
	const repeatFieldsets = document.querySelectorAll( '.lpf-fieldset-repeatable' );
	const repeaterActions = {

		/**
		 * Initialize our functions.
		 */
		init: function() {
			this.addRepeaterButtons();
		},

		/**
		 * Add repeater buttons to each fieldset section.
		 */
		addRepeaterButtons: function() {
			repeatFieldsets.forEach( this.addRepeaterButton );
		},

		/**
		 * Add a repeater button to an individual fieldset section.
		 */
		addRepeaterButton: function( item ) {
			const button = document.createElement( 'button' );

			// Set the necessary properties for the button.
			button.setAttribute( 'type', 'button' );
			button.classList.add( buttonClass );
			button.innerText = i18n.addNew + ' ' + item.dataset.addNewLabel;
			button.addEventListener( 'click', repeaterActions.repeatSection );

			// Add the button to the fieldset element.
			item.appendChild( button );
		},

		/**
		 * Clone the given section.
		 */
		repeatSection: function() {
			const fieldset = this.closest( 'fieldset' );
			const newNode = fieldset.cloneNode( true );

			// Remove the repeat button from the parent.
			fieldset.removeChild( fieldset.querySelector( `.${buttonClass}` ) );

			// Update each input element.
			newNode.querySelectorAll( 'input' ).forEach( function( item ) {
				const regex = new RegExp( /\[(\d+)]/ );
				const id = item.getAttribute( 'id' );
				const match = id.match( regex );

				// Remove any entered input.
				item.value = item.placeholder ? item.placeholder : '';

				// Update input name/IDs with new number.
				if ( match.length > 1 ) {
					const parsed = parseInt( match[1] );
					const newId = isNaN( parsed ) ? 0 : parsed + 1;

					// ID and Name should be the same, so update them both.
					item.setAttribute( 'id', id.replace( regex, `[${newId}]` ) );
					item.setAttribute( 'name', id.replace( regex, `[${newId}]` ) );
				}
			} );

			// Re-hook the button to the listener.
			newNode
				.querySelector( `.${buttonClass}` )
				.addEventListener( 'click', repeaterActions.repeatSection );

			// Insert the new section.
			fieldset.parentElement.insertBefore( newNode, fieldset.nextSibling );
		}
	};

	repeaterActions.init();
} );
