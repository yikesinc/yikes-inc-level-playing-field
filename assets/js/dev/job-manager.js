jQuery( document ).ready( function( $ ) {
	'use strict';

	const postboxHandles = $( '.postbox .hndle' );
	const metaboxSortables = $( '.meta-box-sortables' );
	const $address_div = $( '#job_cpt_meta_address_address-1' ).parents( 'tr' );
	const job_manager_actions = {

		/**
		 * Initialize Job Manager actions.
		 */
		init: function() {
			this.disableMetaboxSorting();
			$( "input[name='job_cpt_meta_location']" ).on( 'change', this.address_div ).change();
			this.deregisterBlocks();

			wp.hooks.addFilter(
			    'blocks.registerBlockType',
			    'ylpf',
			    this.filterParagraphPlaceholderText
			);
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
		 * Don't allow metaboxes to be sortable.
		 */
		disableMetaboxSorting: function() {
			if ( ! lpf_job_manager_data.mbox_sort ) {
				metaboxSortables.sortable({ disabled: true });
				postboxHandles.css( 'cursor', 'pointer' );
			}
		},

		/**
		 * Deregister gutenberg blocks.
		 */
		deregisterBlocks: function() {
			if ( typeof lpf_job_manager_data !== 'undefined' && lpf_job_manager_data.disallowed_blocks && typeof wp.blocks !== 'undefined' && typeof wp.blocks.unregisterBlockType === 'function' ) {
				for ( const counter in lpf_job_manager_data.disallowed_blocks ) {

					// Check if a disallowed block is registered.
					const block_slugs  = wp.blocks.getBlockTypes().map( block => block.name );
					const block_exists = block_slugs.filter( name => name === lpf_job_manager_data.disallowed_blocks[ counter ] );

					// Unregister it.
					if ( block_exists.length > 0 ) {
						wp.blocks.unregisterBlockType( lpf_job_manager_data.disallowed_blocks[ counter ] );
					}
				}
			}
		},

		/**
		 * Filter the core paragraph block placeholder text.
		 */
		filterParagraphPlaceholderText: function( settings, name ) {
			if ( name !== 'core/paragraph' ) {
				return settings;
			}

			// Translation Function WordPress Core
			const { __ } = wp.i18n;

			// Destructuring job_desc_placeholder adding default if empty with translation
			const { job_desc_placeholder = __('Enter job description.', 'yikes-level-playing-field') } = lpf_job_manager_data;

			settings.attributes.placeholder = { ...settings.attributes.placeholder, default: job_desc_placeholder };

			return settings;
		}
	};

	job_manager_actions.init();
} );
