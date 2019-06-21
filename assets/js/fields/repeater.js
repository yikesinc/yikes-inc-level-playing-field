jQuery( document ).ready( function( $ ) {
	'use strict';

	// Initial variables.
	const repeatButton = 'lpf-repeat-button',
		deleteButton = 'lpf-delete-button',
		i18n = window.lpfRepeater || {},
		repeatFieldsets = document.querySelectorAll( '.lpf-fieldset-repeatable' ),
		repeatableFieldContainer = 'lpf-fieldset-container',
		repeatableFieldNumber = 'lpf-fieldset-number',
		datePickers = 'lpf-datepicker';

	// Main object to work with.
	const repeater = {

		/**
		 * Initialize our functions.
		 */
		init: function() {
			this.addRepeaterButtons();
			document.querySelectorAll( `.${deleteButton}` ).forEach( this.hookDeletButton );
			this.initializeDatePickers();
		},

		/**
		 * Initialize datepicker fields w/ the jQuery UI Datepicker.
		 */
		initializeDatePickers: function() {
			if ( $( `.${datePickers}` ).length === 0 ) {
				return;
			}

			const yearRange = "1950:" + ( new Date().getFullYear() + 1 );
			$( `.${datePickers}` ).removeClass( 'hasDatepicker' ).datepicker({
				changeYear: true,
				changeMonth: true,
				yearRange: yearRange,
				dateFormat: 'yy-mm-dd'
			});
		},

		/**
		 * Add repeater buttons to each fieldContainer section.
		 */
		addRepeaterButtons: function() {
			repeatFieldsets.forEach( this.addRepeaterButton );
		},

		hookDeletButton: function( item ) {
			item.addEventListener( 'click', repeater.deleteSection );
		},

		/**
		 * Add a repeater button to an individual fieldContainer section.
		 *
		 * @param {HTMLSelectElement} item
		 */
		addRepeaterButton: function( item ) {
			const existing = item.querySelector( `.${repeatButton}` );

			// If we have an existing button, hook the listener and return.
			if ( null !== existing ) {
				existing.addEventListener( 'click', repeater.repeatSection );
				return;
			}

			// Set up the new button.
			const button = document.createElement( 'button' );
			button.setAttribute( 'type', 'button' );
			button.classList.add( repeatButton );
			button.innerText = i18n.addNew + ' ' + item.dataset.addNewLabel;
			button.addEventListener( 'click', repeater.repeatSection );

			// Add the button to the fieldContainer element.
			item.appendChild( button );
		},

		/**
		 * Clone the given section.
		 */
		repeatSection: function() {
			const fieldContainer = this.closest( `.${repeatableFieldContainer}` ),
				newNode = fieldContainer.cloneNode( true ),
				button = newNode.querySelector( `.${deleteButton}` ),
				number = parseInt( fieldContainer.querySelector( `.${repeatableFieldNumber}` ).textContent ) + 1,
				counterElement = newNode.querySelector( `.${repeatableFieldNumber}` ),
				replaceRegex = new RegExp( /\[(\d+)]/ );

			// Remove the repeat button from the parent.
			fieldContainer.removeChild( fieldContainer.querySelector( `.${repeatButton}` ) );

			// Check if there are any datepickers to initialize.
			const hasDatepicker = newNode.querySelectorAll( `.${datePickers}` ).length > 0;
			
			// Remove any current instances of datepickers.
			if ( hasDatepicker ) {
				$( `.${datePickers}` ).datepicker( 'destroy' );
			}

			// Update each input element.
			newNode.querySelectorAll( 'input, select' ).forEach( function( item ) {
				const id    = item.getAttribute( 'id' );
				const match = id.match( replaceRegex );

				// Remove any entered input.
				item.value = repeater.getRepeatValue( item );

				// Update input name/IDs with new number.
				if ( match.length > 1 ) {
					const parsed = parseInt( match[ 1 ] );
					const newId = isNaN( parsed ) ? 0 : parsed + 1;

					// ID and Name should be the same, so update them both.
					item.setAttribute( 'id', id.replace( replaceRegex, `[${newId}]` ) );
					item.setAttribute( 'name', id.replace( replaceRegex, `[${newId}]` ) );
				}
			} );

			// Update each label.
			newNode.querySelectorAll( 'label' ).forEach( function( item ) {
				const labelFor = item.getAttribute( 'for' );
				const match    = labelFor.match( replaceRegex );

				// Update label's for attribute with new number.
				if ( match.length > 1 ) {
					const parsed = parseInt( match[ 1 ] );
					const newId = isNaN( parsed ) ? 0 : parsed + 1;

					// ID and Name should be the same, so update them both.
					item.setAttribute( 'for', labelFor.replace( replaceRegex, `[${newId}]` ) );
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
				repeater.hookDeletButton( button )
			}

			// Update the field number.
			counterElement.textContent = number;

			// Insert the new section.
			fieldContainer.parentElement.insertBefore( newNode, fieldContainer.nextElementSibling );

			if ( hasDatepicker ) {
				repeater.initializeDatePickers();
			}
		},

		/**
		 * Add a button to delete a section.
		 * @param {HTMLSelectElement} fieldContainer
		 */
		addDeleteButton: function( fieldContainer ) {
			const button = document.createElement( 'button' );

			// Set up the necessary properties for the button.
			button.setAttribute( 'type', 'button' );
			button.classList.add( deleteButton );
			button.innerText = 'X';
			repeater.hookDeletButton( button );

			// Add the button to the beginning of the fieldContainer element.
			fieldContainer.insertBefore( button, fieldContainer.querySelector( '.lpf-fieldset-label' ) );
		},

		/**
		 * Delete the given section.
		 */
		deleteSection: function() {
			const fieldContainer = this.closest( `.${repeatableFieldContainer}` ),
				button = fieldContainer.previousElementSibling.querySelector( repeatButton ),
				next = fieldContainer.nextElementSibling;

			/*
			 * Add the repeat button to the previous fieldset. But don't add if there's
			 * at least one element after the current one.
			 */
			if ( null === button && null === next ) {
				repeater.addRepeaterButton( fieldContainer.previousElementSibling );
			}

			const fieldset = fieldContainer.parentElement;

			// Now remove the fieldContainer.
			fieldset.removeChild( fieldContainer );

			// Update the counters.
			repeater.updateLabelCounters( fieldset );
		},

		/**
		 * Loop through a set of fields and update the incremental label, e.g. Schooling 1.
		 */
		updateLabelCounters: function( fieldset ) {
			fieldset.querySelectorAll( `.${repeatableFieldNumber}` ).forEach( function( item, index ) {
				item.textContent = index + 1;
			} );
		},

		/**
		 * Return the value for a duplicated field.
		 * @param item HTML Field.
		 * @return The default value for the duplicated field.
		 */
		getRepeatValue: function( item ) {
			if ( item.type === 'checkbox' ) {
				return item.value;
			}
			return item.placeholder ? item.placeholder : '';
		}
	};

	repeater.init();
} );
