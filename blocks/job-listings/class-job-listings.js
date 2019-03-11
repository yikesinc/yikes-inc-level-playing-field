// Import dependencies.
import apiFetch from '@wordpress/api-fetch';

// Get functions / blocks / components.
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.editor;
const { Spinner, TextControl, PanelBody, PanelRow, FormToggle, SelectControl } = wp.components;
const { Component } = wp.element;

export default class JobListing extends Component {
  constructor( props ) {
    super( ...arguments );
    this.state = {
      jobs      : {},
      jobsLoaded: false
    }
    
    this.jobsEndpoint = `/wp/v2/${lpf_job_listing_data.jobs_slug}`;
  }

  /**
   * Run our API calls after the component has mounted. You can't use setState before a component is mounted.
   */
  componentDidMount() {

    // Fetch all jobs.
    wp.apiFetch( { path: this.jobsEndpoint } ).then( jobs => {
      this.setState( { jobs: this.convertJobs( jobs ), jobsLoaded: true } );
    });
  }

  /**
   * Convert an array of jobs to an object of jobs with the structure { job_id: job_object };
   */
  convertJobs( jobs ) {
    const jobsObject = {};
    for ( const ii in jobs ) {
      jobsObject[ jobs[ii].id ] = jobs[ii];
    }
    return jobsObject;
  }

  limitLoop() {
    const options = [];
    for ( let ii = 1; ii <= 15; ii++ ) {
      options.push( { label: ii, value: ii } );
    }

    return options;
  }

  inspectorControls() {

    const limit =
    (
      <PanelRow>
        <SelectControl
            label="Limit"
            value={ this.props.limit }
            options={ this.limitLoop() }
            onChange={ ( val ) => { this.props.handleValueControl( val, 'limit' ) } }
        />
      </PanelRow>
    );

    const showApplicationButton =
    (
      <PanelRow>
        <label
          htmlFor="lpf-show-application-button-form-toggle"
          className="blocks-base-control__label"
        >
          { __( 'Show Application Button' ) }
        </label>
        <FormToggle
          id="lpf-show-application-button-form-toggle"
          label={ __( 'Show Application Button' ) }
          checked={ !! this.props.showApplicationButton }
          onChange={ ( e ) => this.props.toggleFormControl( e, 'show_application_button' ) }
        />
      </PanelRow>
    );

    const editButtonText = this.props.showApplicationButton ?
    (
      <PanelRow>
        <TextControl
          id="lpf-button-text-control"
          label={ __( 'Button Text' ) }
          value={ this.props.buttonText }
          onChange={ ( val ) => this.props.handleValueControl( val, 'button_text' ) }
        />
      </PanelRow>
    ) : '';

    const inspector_controls = 
     <InspectorControls>

      <PanelBody title={ __( 'Settings' ) } >
        {limit}
        {showApplicationButton}
        {editButtonText}
      </PanelBody>

    </InspectorControls>

    return inspector_controls;
  }

  jobs() {
    let counter = 1;
    return Object.keys( this.state.jobs ).length > 0 ? 
      Object.keys( this.state.jobs ).map( ( job_id ) => {
        if ( counter > this.props.limit ) {
          return;
        }
        counter++;

        const job = this.state.jobs[ job_id ];

        return (
          [this.jobHeader( job ),
          this.props.showApplicationButton ? this.jobListingAppButton( job ) : '']
        );
      })
    : '<em>No jobs found...</em>';
  }

  jobHeader( job ) {
    return (
      <h4 key={ `job-listings-title-${ job.id }` }  className="job-page-job-title">
        <a key={ `job-listings-link-${ job.id }` } href={ job.link }>{ job.title.rendered }</a>
      </h4>
    );
  }

  jobListingAppButton( job ) {
    return (
      <div key={ `job-listings-app-${ job.id }` } className="job-page-application">
        <a key={ `job-listings-app-link-${ job.id }` } href="">
          <button key={ `job-listings-app-button-${ job.id }` } type="button" className="job-page-application-button">{ this.props.buttonText }</button>
        </a>
      </div>
    );
  }

  loading() {
    return (
        <div className="loading">
          <span>{ __( 'Loading...' ) }</span>
          <Spinner></Spinner>
        </div>
    );
  }

  render() {
    return (
      <div className={ this.props.className }>
        { this.props.focus && this.inspectorControls() }
        { this.state.jobsLoaded ? this.jobs() : this.loading() }
      </div>
    );
  }
}
