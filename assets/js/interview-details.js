/**
 * Interview Details React Update Listener
 *
 * @author Freddie Mixell
 */

const { Component, Fragment } = wp.element;
const { Spinner } = wp.components;
const { __ } = wp.i18n;
const { apiFetch } = wp;
const applicantId = typeof interviewStatus.post !== "undefined" && typeof interviewStatus.post.ID !== "undefined" ? parseInt(interviewStatus.post.ID) : 0;
const nonce = typeof interviewStatus.nonce !== "undefined" ? interviewStatus.nonce : "";

/**
 * Listening for interview request submission to update metabox.
 */

window.addEventListener('DOMContentLoaded', () => {

  // Button to listen for interview requests
  const target = document.querySelector( "#send-interview-request" );

  /**
   * Callback function that renders react to refresh interview status.
   * 
   * @param {Object} event
   */
  target.addEventListener( 'click', function handle_interview_submit( event ) {

    event.preventDefault();

    wp.element.render(
      wp.element.createElement( InterviewStatus, null ),
      document.getElementById( "interview" )
    );

    target.removeEventListener( 'click', handle_interview_submit, true);
  
  }, true );

});

/**
 * Label component abstraction.
 *
 * @param {string} label
 * @param {string} info
 */
const Label = ( { label, info } ) => {
  return wp.element.createElement(
    "p",
    null,
    wp.element.createElement(
      "span",
      {
        className: "label"
      },
      __( label, "yikes-level-playing-field" )
    ),
    __( info, "yikes-level-playing-field" )
  );
};

/**
 * Displays label component with scheduled status.
 */
const Scheduled = () => {
  return wp.element.createElement(Label, {
    label: "Status:",
    info: "Awaiting Applicant Confirmation"
  });
};

/**
 * Fragment with 3 labels used for confirmed status.
 *
 * @param {string} location 
 * @param {string} message
 */
const Confirmed = ( { location, message } ) => {
  return wp.element.createElement(
    Fragment,
    null,
    wp.element.createElement(Label, {
      label: "Status:",
      info: "Confirmed"
    }),
    wp.element.createElement(Label, {
      label: "Location:",
      info: location
    }),
    wp.element.createElement(Label, {
      label: "Message:",
      info: message
    })
  );
};

/**
 * Displays label component for cancelled status.
 */
const Cancelled = () => {
  return wp.element.createElement(
    Label,
    {
      label: "Status:",
      info: "Cancelled by the applicant."
    }
  );
};

/**
 * Displays label component for no interview status.
 */
const NoInterview = () => {
  return wp.element.createElement(
    Label,
    {
      label: "Status:",
      info: "An interview has not been scheduled."
    },
  );
};

const DisplayError = ( { message = "An error occured." } ) => wp.element.createElement( "p", null, message );

/**
 * Chooses which components to rendered based off props passed in.
 *
 * @param {string} status
 * @param {string} location
 * @param {string} date
 * @param {string} time
 * @param {string} message
 * @param {bool} loading
 * @param {Object} error
 */
const DynamicStatus = ( { status, location, date, time, message, loading, error } ) => {

  if ( error !== null ) {
    const { message } = error;
    return wp.element.createElement(DisplayError, null, message);
  }

  if ( loading ) {
    return wp.element.createElement(Spinner, null);
  }

  if ( status === "scheduled" || status === "confirmed" ) {
    return wp.element.createElement(
      Fragment,
      null,
      status === "scheduled" ? wp.element.createElement( Scheduled, null ) : null,
      status === "confirmed"
        ? wp.element.createElement( Confirmed, {
            location: location,
            message: message
          } )
        : null,
      wp.element.createElement( Label, {
        label: "Date:",
        info: date
      } ),
      wp.element.createElement( Label, {
        label: "Time:",
        info: time
      } ),
    );
  }

  if ( status === "cancelled" ) {
    return wp.element.createElement( Cancelled, null );
  } else {
    return wp.element.createElement( NoInterview, null );
  }
};

/**
 * Handle stateful logic and pass down to function components.
 */
class InterviewStatus extends Component {
  constructor() {
    super();
    this.state = {
      status: "",
      date: "",
      time: "",
      location: "",
      message: "",
      loading: true,
      error: null,
    };
    this.fetchInterviews = this.fetchInterviews.bind( this );
    this.handleStatusResponse = this.handleStatusResponse.bind( this );
  }

  componentDidMount() {
    // Fetch interview data on load.
    this.fetchInterviews();
  }

  /**
   * Fetch interview request status from rest api with nonce for validation.
   */
  fetchInterviews() {

    this.setState( { loading: true } );

    const endpoint = { path: `/level-playing-field/v1/interview-status?id=${applicantId}&_wpnonce=${nonce}` };
    const timeout = 1000;

    setTimeout(() => { 
      apiFetch( endpoint )
        .then( (applicant) => this.handleStatusResponse(applicant) )},
      timeout
    );

  }

  handleStatusResponse(applicant) {
    if ( ! applicant.success ) {
      const { message } = applicant.data;
      return this.setState( { error: message, loading: false } );
    }

    const { status, date, time, location, message } = applicant.data;
    return this.setState( {
      status,
      date,
      time,
      location,
      message,
      loading: false,
    } );
  }

  render() {
    return wp.element.createElement(
      "div",
      {
        className: `inside ${this.state.loading ? 'loading' : ''}`
      },
      wp.element.createElement( DynamicStatus, this.state )
    );
  }
}

