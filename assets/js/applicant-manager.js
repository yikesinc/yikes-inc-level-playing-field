jQuery( document ).ready( function( $ ) {
	'use strict';

	const heading = document.querySelector( '.wp-heading-inline' );
	const nicknameBtns = document.querySelector( '#edit-nickname-buttons' );
	const nicknameEdit = document.querySelector( '#edit-nickname-buttons .edit-nickname' );
	const coverLetter = document.querySelector( '.cover-letter a' );
    const coverLetterContent = document.querySelector( '.cover-letter-content' );
	const applicantID = document.getElementById( 'post_ID' ).value;
	const i18n = Object.assign( {}, { title: '' }, window.applicantManager || {}, {ok: 'Ok'}, {cancel: 'Cancel'} );
	const applicantActions = {

		/**
		 * Initialize this object.
		 */
		init: function() {
			this.replaceTitle();
			// hook this.editNickname() to the appropriate button.
			coverLetterContent.style.display = 'none';
			coverLetter.addEventListener('click', this.toggleCoverLetter );
			nicknameEdit.addEventListener('click', this.editNickname );
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
			// see editPermalink() in wp-admin/js/post.js
			nicknameBtns.innerHTML = '<button type="button" class="save button button-small">' + i18n.ok + '</button> <button type="button" class="cancel button-link">' + i18n.cancel + '</button>';
			//$el.html( '<input type="text" id="new-post-slug" value="' + slug_value + '" autocomplete="off" />' ).children( 'input' ).keydown( function( e ) {

		},

        /**
         * Toggle view of applicant cover letter.
         */
        toggleCoverLetter: function( event ) {
            event.preventDefault();
            if (coverLetterContent.style.display === 'none') {
                // todo: Not sure what it means to "localize translation strings" and if it applies here.
            	coverLetter.innerHTML = 'Hide Cover Letter';
                coverLetterContent.style.display = 'block';
            } else {
                // todo: Not sure what it means to "localize translation strings" and if it applies here.
                coverLetter.innerHTML = 'View Cover Letter';
                coverLetterContent.style.display = 'none';
            }
        }
	};

	applicantActions.init();
} );
