jQuery( document ).ready( function( $ ) {
	'use strict';

	const heading = document.querySelector( '.wp-heading-inline' );
    const nicknameBtns = $( '#edit-nickname-buttons' );
	const coverLetter = $( '.cover-letter a' );
    const coverLetterContent = $( '.cover-letter-content' );
	const applicantID = document.getElementById( 'post_ID' ).value;
	const i18n = Object.assign( {}, { title: '' }, window.applicantManager || {}, {ok: 'Ok'}, {cancel: 'Cancel'}, {view: 'View Cover Letter'}, {hide: 'Hide Cover Letter'} );
	const applicantActions = {

		/**
		 * Initialize this object.
		 */
		init: function() {
			this.replaceTitle();
			// hook this.editNickname() to the appropriate button.
			coverLetterContent.hide();
			coverLetter.click( this.toggleCoverLetter );
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
			// todo: handle editing the nick name.
            // Get button group and current nickname.
            const nickname = $( '#editable-nick-name' );
            const rNicknameBtns = nicknameBtns.html();
            const rNickname = nickname.html();

            // Show editable nickname and ok/cancel buttons.
            nicknameBtns.html(`<button type="button" class="save button button-small">${i18n.ok}</button> <button type="button" class="cancel button-link">${i18n.cancel}</button>`);
			nickname.html(`<input type="text" id="new-nick-name" value="${rNickname}" autocomplete="off" />`);

            // Save new nickname.
            nicknameBtns.children('.save').click( function() {
                const newNickname = nickname.children( 'input' ).val();

                // If nickname hasn't changed.
                if ( newNickname === rNickname ) {
                    nicknameBtns.children('.cancel').click();
                    return;
                }

                // Otherwise, trigger ajax and send new nickname to server.
                $.ajax({
                    url: window.applicantManager.url,
                    type: 'post',
                    data: {
                        action: 'save_nickname',
                        nickname: newNickname,
                        ID: window.applicantManager.ID,
                        nonce: window.applicantManager.nonce,
                    },
                    beforeSend: function() {
                        nickname.children('input').prop('disabled', true);
                    },
                    success: function( response ) {
                        nicknameBtns.html( rNicknameBtns );
                        nickname.html( newNickname );
                    },
                    error: function() { // if error occurred
                        console.log('error');
                        nicknameBtns.html( rNicknameBtns );
                        nickname.html( rNickname );
                    }
                })
            });

            // Cancel editing of nickname.
			nicknameBtns.children('.cancel').click( function() {
                nicknameBtns.html( rNicknameBtns );
                nickname.html( rNickname );
            });
		},

        /**
         * Toggle view of applicant cover letter.
         */
        toggleCoverLetter: function( event ) {
            event.preventDefault();
            coverLetterContent.toggle();
            if ( coverLetterContent.is(':visible') ) {
                // todo: Not sure what it means to "localize translation strings" and if it applies here.
            	coverLetter.html(i18n.hide);
            } else {
                // todo: Not sure what it means to "localize translation strings" and if it applies here.
                coverLetter.html(i18n.view);
            }
        }
	};

	applicantActions.init();
} );
