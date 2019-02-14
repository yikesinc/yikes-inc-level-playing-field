jQuery( document ).ready( function( $ ) {
	'use strict';

	// Globals.
	let oldNickname, oldNicknameBtns;

	const heading = document.querySelector( '.wp-heading-inline' );
	const nicknameBtns = $( '#edit-nickname-buttons' );
	const nickname = $( '#editable-nick-name' );
	const coverLetter = $( '.cover-letter a' );
	const coverLetterContent = $( '.cover-letter-content' );
	const applicantID = document.getElementById( 'post_ID' ).value;
	const i18n = Object.assign( {}, {
		cancel: '',
		hide: '',
		ok: '',
		title: '',
		view: '',
		nonce: ''
	}, window.applicantManager || {} );

	const applicantActions = {

		/**
		 * Initialize this object.
		 */
		init: function() {
			this.replaceTitle();
			coverLetterContent.hide();
			coverLetter.on( 'click', this.toggleCoverLetter );
			nicknameBtns.on( 'click', '.edit-nickname', this.editNickname );
		},

		/**
		 * Append to the Applicant title string.
		 */
		replaceTitle: function() {
			if ( 0 === i18n.title.length ) {
				return;
			}

			heading.innerHTML = `${i18n.title} ${applicantID}`;
		},

		/**
		 * Handle editing the applicant nickname.
		 */
		editNickname: function() {
			applicantActions.saveNicknameButtons();
			applicantActions.addNicknameButtons();
			applicantActions.addNicknameTextField();

			// Add event listeners to buttons.
			nicknameBtns.children( '.save' ).click( applicantActions.saveNickname );
			nicknameBtns.children( '.cancel' ).click( applicantActions.cancelSaveNickname );
		},

		/**
		 * Revert the nickname buttons and text back to
		 */
		cancelSaveNickname: function() {
			nicknameBtns.html( oldNicknameBtns );
			nickname.html( oldNickname );
		},

		/**
		 * Save the entered nickname.
		 */
		saveNickname: function() {
			const newNickname = nickname.children( 'input' ).val();

			// If nickname hasn't changed.
			if ( newNickname === oldNickname ) {
				nicknameBtns.children( '.cancel' ).click();
				return;
			}

			// Otherwise, trigger ajax and send new nickname to server.
			$.ajax( {
				url: window.ajaxurl,
				type: 'post',
				data: {
					action: 'save_nickname',
					nickname: newNickname,
					id: applicantID,
					nonce: i18n.nonce
				},
				beforeSend: function() {
					nickname.children( 'input' ).prop( 'disabled', true );
				},
				always: function( response, textStatus ) {
					nicknameBtns.html( oldNicknameBtns );
					if ( 'success' === textStatus ) {
						nickname.html( response.responseJSON.data.nickname );
					} else {
						nickname.html( oldNickname );
					}
				}
			} );
		},

		/**
		 * Save the current content of the nickname buttons.
		 */
		saveNicknameButtons: function() {
			oldNicknameBtns = nicknameBtns.html();
			oldNickname = nickname.html();
		},

		/**
		 * Clear the nickname buttons HTML and add the OK and Cancel buttons.
		 */
		addNicknameButtons: function() {
			nicknameBtns.html( '' );
			const okBtn = document.createElement( 'button' );
			okBtn.setAttribute( 'type', 'button' );
			okBtn.classList.add( 'save', 'button', 'button-small' );
			okBtn.innerText = i18n.ok;
			nicknameBtns.append( okBtn );

			const cancelBtn = document.createElement( 'button' );
			cancelBtn.setAttribute( 'type', 'button' );
			cancelBtn.classList.add( 'cancel', 'button-link' );
			cancelBtn.innerText = i18n.cancel;
			nicknameBtns.append( cancelBtn );
		},

		/**
		 * Replace current nickname text with input element to make nickname editable.
		 */
		addNicknameTextField: function() {
			nickname.html( '' );
			const inputNickname = document.createElement( 'input' );
			inputNickname.setAttribute( 'type', 'text' );
			inputNickname.setAttribute( 'id', 'new-nick-name' );
			inputNickname.setAttribute( 'value', oldNickname );
			inputNickname.setAttribute( 'autocomplete', 'off' );
			nickname.append( inputNickname );
		},

		/**
		 * Toggle view of applicant cover letter.
		 */
		toggleCoverLetter: function( event ) {
			event.preventDefault();
			coverLetterContent.toggle();
			if ( coverLetterContent.is( ':visible' ) ) {
				coverLetter.html( i18n.hide );
			} else {
				coverLetter.html( i18n.view );
			}
		}
	};

	applicantActions.init();
} );
