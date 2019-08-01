/**
 * Interview Details React Update Listener
 *
 * @author Freddie Mixell
 */

  jQuery(document).ready(function( $ ) {

    const target = $( "#send-interview-request" );
    const applicantID = $( '#post_ID' ).value;
    const widgetLocation = $( "#interview" );

    // Listening for interview requests.
    target.on( 'click', function handle_interview_submit( event ) {
  
      event.preventDefault();

      // Build our request.
      const req = {
        url: wpApiSettings.root + '/?id=' + applicantID,
        beforeSend: function( xhr ) {
          xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
        },
        success: function( data ) {
          const { 
            status = '',
            date = '',
            time = '',
            location = '',
            message = '',
           } = data;

          const statusEl   = makeLabel( 'Status:', status );
          const dateEl     = makeLabel( 'Date:', date );
          const timeEl     = makeLabel( 'Time:', time );
          const locationEl = makeLabel( 'Location:', location );
          const messageEl  = makeLabel( 'Message:', message );

          widgetLocation.replaceWith( statusEl + dateEl + timeEl + locationEl + messageEl );
        },
      }

      // Execute our request and update widget.
      $.get( req );
  
      // Done listening for interview requests.
      target.off( 'click', handle_interview_submit );
    
    } );

    // Helper function to make templated labels.
    function makeLabel( label = '', info = '' ) {
      if ( ! info ) {
        return '';
      }
      return '<p><span class="label">' + label + '</span>' + info + '</p>';
    }
  
});
