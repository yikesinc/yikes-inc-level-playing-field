const { Component, Fragment } = wp.element;
const { Spinner } = wp.components;
const { __ } = wp.i18n;
const { apiFetch } = wp;
const applicantId = typeof interviewStatus.post !== "undefined" && typeof interviewStatus.post.ID !== "undefined" ? parseInt(interviewStatus.post.ID) : 0;
const nonce = typeof interviewStatus.nonce !== "undefined" ? interviewStatus.nonce : "";

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

const Scheduled = () => {
  return wp.element.createElement(Label, {
    label: "Status:",
    info: "Awaiting Applicant Confirmation"
  });
};

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

const Cancelled = () => {
  return wp.element.createElement(
    Label,
    {
      label: "Status:",
      info: "Cancelled by the applicant."
    }
  );
};

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

class InterviewStatus extends Component {
  constructor() {
    super();
    this.state = {
      target: "",
      nonce: "",
      applicantId: "",
      status: "",
      date: "",
      time: "",
      location: "",
      message: "",
      loading: true,
      error: null,
    };
    this.listenForInterviews = this.listenForInterviews.bind( this );
  }

  componentDidMount() {
    const target = document.querySelector( "#send-interview-request" );
    this.setState(
      {
        target,
        applicantId,
        nonce
      },
      () => {
        const { target } = this.state;
        this.listenForInterviews();
        target.addEventListener( "click", this.listenForInterviews );
      }
    );
  }

  componentWillUnmount() {
    const { target } = this.state;
    target.removeEventListener( "click", this.listenForInterviews );
  }

  listenForInterviews( event ) {
    let timeout = 0;

    if ( typeof event !== "undefined" ) {
      event.preventDefault();
      timeout = 1000;
    }

    const { applicantId, nonce } = this.state;

    this.setState( { loading: true } );

    setTimeout( () => {
      apiFetch( {
        path: `/level-playing-field/v1/interview-status?id=${applicantId}&_wpnonce=${nonce}`
      } )
        .then( ( applicant ) =>  {
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
        } )
    }, timeout );
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

wp.element.render(
  wp.element.createElement( InterviewStatus, null ),
  document.getElementById( "interview" )
);
