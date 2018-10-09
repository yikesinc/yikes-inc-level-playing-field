jQuery( document ).ready( function( $ ) {
	'use strict';

	const heading = document.querySelector( '.wp-heading-inline' );
	const applicantID = document.getElementById( 'post_ID' ).value;
	const i18n = Object.assign( {}, { title: '' }, window.applicantManager || {} );
	const applicantActions = {

		/**
		 * Initialize this object.
		 */
		init: function() {
			this.replaceTitle();
			// hook this.editNickname() to the appropriate button.
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
		}
	};

	applicantActions.init();
} );
