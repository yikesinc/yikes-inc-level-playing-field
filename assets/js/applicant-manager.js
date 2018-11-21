jQuery( document ).ready( function( $ ) {
    'use strict';

    // Globals.
    const heading = document.querySelector( '.wp-heading-inline' );
    const nicknameBtns = $( '#edit-nickname-buttons' );
    const nickname = $( '#editable-nick-name' );
    let rNicknameBtns = nicknameBtns.html();
    let rNickname = nickname.html();
    const coverLetter = $( '.cover-letter a' );
    const coverLetterContent = $( '.cover-letter-content' );
    const applicantID = document.getElementById( 'post_ID' ).value;
    const i18n = Object.assign( {}, { cancel: '', hide: '', ok: '', title: '', view: ''  }, window.applicantManager || {});
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
            // Save current nickname and button set for revert.
            rNicknameBtns = nicknameBtns.html();
            rNickname = nickname.html();

            // Replace current button set with ok/cancel buttons.
            nicknameBtns.html('');
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

            // Replace current nickname text with input element to make nickname editable.
            nickname.html('');
            const inputNickname = document.createElement( 'input' );
            inputNickname.setAttribute( 'type', 'text' );
            inputNickname.setAttribute( 'id', 'new-nick-name' );
            inputNickname.setAttribute( 'value', rNickname );
            inputNickname.setAttribute( 'autocomplete', 'off' );
            nickname.append( inputNickname );

            // Save new nickname.
            nicknameBtns.children('.save').click( applicantActions.saveNickname );

            // Cancel editing of nickname.
            nicknameBtns.children('.cancel').click( function() {
                nicknameBtns.html( rNicknameBtns );
                nickname.html( rNickname );
            });
        },

        saveNickname: function() {
            const newNickname = nickname.children( 'input' ).val();

            // If nickname hasn't changed.
            if ( newNickname === rNickname ) {
                nicknameBtns.children('.cancel').click();
                return;
            }

            // Otherwise, trigger ajax and send new nickname to server.
            $.ajax({
                url: window.ajaxurl,
                type: 'post',
                data: {
                    action: 'save_nickname',
                    nickname: newNickname,
                    id: applicantID,
                    nonce: window.applicantManager.nonce,
                },
                beforeSend: function() {
                    nickname.children('input').prop('disabled', true);
                },
                success: function( response ) {
                    nicknameBtns.html( rNicknameBtns );
                    nickname.html( newNickname );
                },
                error: function() {
                    nicknameBtns.html( rNicknameBtns );
                    nickname.html( rNickname );
                }
            })
        },

        /**
         * Toggle view of applicant cover letter.
         */
        toggleCoverLetter: function( event ) {
            event.preventDefault();
            coverLetterContent.toggle();
            if ( coverLetterContent.is(':visible') ) {
                coverLetter.html(i18n.hide);
            } else {
                coverLetter.html(i18n.view);
            }
        }
    };

    applicantActions.init();
} );
