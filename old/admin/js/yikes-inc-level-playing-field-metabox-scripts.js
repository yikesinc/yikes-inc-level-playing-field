/**
 * YIKES, Inc. Level Playing Field Metabox Scripts
 * @since 1.0.0
 */
jQuery( document ).ready( function() {

	/**
	 * When a link is clicked in the sidebar, toggle the containers
	 * @since 1.0.0
	 */
	jQuery( 'body' ).on( 'click', '.job_data_tabs > li', function() {
		var tab_attr = jQuery( this ).find( 'a' ).attr( 'href' ).replace( '#', '' );
		jQuery( 'li.active' ).removeClass( 'active' );
		jQuery( this ).addClass( 'active' );
		toggle_container_visibility( tab_attr );
	});

});

/**
 * Toggle the visibility of the tabs, show clicked tab - hide the rest
 * @param  string clicked_tab The class of the clicked link, used to toggle visibility
 * @since 1.0.0
 */
function toggle_container_visibility( clicked_tab ) {
	jQuery( '.yikes_lpf_options_panel' ).hide();
	jQuery( '#' + clicked_tab ).show();
}
