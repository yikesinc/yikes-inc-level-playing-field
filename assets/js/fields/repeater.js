jQuery( document ).ready( function() {
	'use strict';

	// Initial variables.
	const repeatButton = 'lpf-repeat-button',
		deleteButton = 'lpf-delete-button',
		i18n = window.lpfRepeater || {},
		repeatFieldsets = document.querySelectorAll( '.lpf-fieldset-repeatable' );

	// Main object to work with.
	const repeater = {

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
		 *
		 * @param {HTMLSelectElement} item
		 */
		addRepeaterButton: function( item ) {
			const button = document.createElement( 'button' );

			// Set the necessary properties for the button.
			button.setAttribute( 'type', 'button' );
			button.classList.add( repeatButton );
			button.innerText = i18n.addNew + ' ' + item.dataset.addNewLabel;
			button.addEventListener( 'click', repeater.repeatSection );

			// Add the button to the fieldset element.
			item.appendChild( button );
		},

		/**
		 * Clone the given section.
		 */
		repeatSection: function() {
			const fieldset = this.closest( 'fieldset' ),
				newNode = fieldset.cloneNode( true ),
				button = newNode.querySelector( `.${deleteButton}` );

			// Remove the repeat button from the parent.
			fieldset.removeChild( fieldset.querySelector( `.${repeatButton}` ) );

			// Update each input element.
			newNode.querySelectorAll( 'input' ).forEach( function( item ) {
				const regex = new RegExp( /\[(\d+)]/ );
				const id = item.getAttribute( 'id' );
				const match = id.match( regex );

				// Remove any entered input.
				item.value = item.placeholder ? item.placeholder : '';

				// Update input name/IDs with new number.
				if ( match.length > 1 ) {
					const parsed = parseInt( match[ 1 ] );
					const newId = isNaN( parsed ) ? 0 : parsed + 1;

					// ID and Name should be the same, so update them both.
					item.setAttribute( 'id', id.replace( regex, `[${newId}]` ) );
					item.setAttribute( 'name', id.replace( regex, `[${newId}]` ) );
				}
			} );

			// Re-hook the button to the listener.
			newNode
				.querySelector( `.${repeatButton}` )
				.addEventListener( 'click', repeater.repeatSection );

			// Add the button to delete the section, or else re-hook the click listener.
			if ( null === button ) {
				repeater.addDeleteButton( newNode );
			} else {
				button.addEventListener( 'click', repeater.deleteSection );
			}

			// Insert the new section.
			fieldset.parentElement.insertBefore( newNode, fieldset.nextElementSibling );
		},

		/**
		 * Add a button to delete a section.
		 * @param {HTMLSelectElement} fieldset
		 */
		addDeleteButton: function( fieldset ) {
			const button = document.createElement( 'button' );

			// Set up the necessary properties for the button.
			button.setAttribute( 'type', 'button' );
			button.classList.add( deleteButton );
			button.innerText = 'X';
			button.addEventListener( 'click', repeater.deleteSection );

			// Add the button to the beginning of the fieldset element.
			fieldset.insertBefore( button, fieldset.querySelector( '.lpf-field-container' ) );
		},

		/**
		 * Delete the given section.
		 */
		deleteSection() {
			const fieldset = this.closest( 'fieldset' ),
				button = fieldset.previousElementSibling.querySelector( repeatButton ),
				next = fieldset.nextElementSibling;

			/*
			 * Add the repeat button to the previous fieldset. But don't add if there's
			 * at least one element after the current one.
			 */
			if ( null === button && null === next ) {
				repeater.addRepeaterButton( fieldset.previousElementSibling );
			}

			// Now remove the fieldset.
			fieldset.parentElement.removeChild( fieldset );
		}
	};

	repeater.init();
} );