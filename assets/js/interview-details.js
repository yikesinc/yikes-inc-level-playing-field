/**
 * Interview Details React Update Listener
 *
 * @author Freddie Mixell
 */

 // Scoping var globally.
 let refreshInterviewDetails = null;

  jQuery(document).ready(function( $ ) {

    const applicantID = $( '#post_ID' ).val();
    const widgetLocation = $( "#interview > div.inside" );

    /**
     * Scoping functionality to document.ready
     */ 
    refreshInterviewDetails = function() {
      return $.get( {
        url: wpApiSettings.root + '?id=' + applicantID,
        beforeSend: function( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
        },
        success: handle_success,
        error: handle_error,
      } );
    }

    /**
     * Handle Response from REST API GET Request.
     *
     * @param {object} data
     */
    function handle_success( data ) {
      const { 
        status = '',
        date = '',
        time = '',
        location = '',
        message = '',
      } = data;

      // If the key exists return string dom node.
      const statusEl   = makeLabel( '', status );
      const dateEl     = makeLabel( 'Date:', date );
      const timeEl     = makeLabel( 'Time:', time );
      const locationEl = makeLabel( 'Location:', location );
      const messageEl  = makeLabel( 'Message:', message );

      // Create DOM Nodes from our label function output.
      const parsed = $.parseHTML( statusEl + dateEl + timeEl + locationEl + messageEl );

      widgetLocation.html( parsed );
    }

    /**
     * Handle Errors from REST API GET Request.
     *
     * @param {object} error 
     */
    function handle_error( error ) {
      return console.log( error );
    }

    /**
     * Helper function to make templated labels.
     *
     * @param {string} label 
     * @param {string} info 
     */
    function makeLabel( label = '', info = '' ) {
      if ( ! info ) {
        return '';
      }
      return '<p><span class="label">' + label + '</span>' + info + '</p>';
    }
  
});
