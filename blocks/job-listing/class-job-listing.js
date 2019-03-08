// Import dependencies
import apiFetch from '@wordpress/api-fetch';

// Get functions / blocks / components
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { RichText, InspectorControls, PlainText } = wp.editor;
const { Spinner, TextControl, PanelBody, PanelRow, FormToggle, SelectControl } = wp.components;
const { Component } = wp.element;

export default class JobListing extends Component {
  constructor( props ) {
    super( ...arguments );
    this.state = {
      jobs      : {},
      job       : this.props.job || {},
      jobID     : this.props.jobID ? this.props.jobID : 0,
      jobsLoaded: false
    }
    
    this.jobsEndpoint = `/wp/v2/${lpf_job_listing_data.jobs_slug}`;

    // Required for the onClick/onChange/etc. functions to have access to `this`.
    this.getJob = this.getJob.bind( this );
  }

  /**
   * Run our API calls after the component has mounted. You can't use setState before a component is mounted.
   */
  componentDidMount() {

    // Fetch the ID of the selected job.
    if ( this.state.jobID && Object.keys( this.state.job ).length === 0 ) {
      wp.apiFetch( { path: `${ this.jobsEndpoint }/${ this.state.jobID }` } ).then( job => {
        this.setState( { job: job } );
      });
    }

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

  getEditJobURL() {
    return `${ lpf_job_listing_data.edit_jobs_url }&post=${ this.state.job.id }`;
  }

  getJob() {
    let job = typeof event.target.value === 'undefined' || typeof this.state.jobs[ event.target.value ] === 'undefined' ? {} : this.state.jobs[ event.target.value ];
    this.setState( { job: job, jobID: event.target.value } );
    this.props.onChangeJob( event.target.value );
  }

  inspectorControls() {

    const jobsDropdown =
    (
      <PanelRow>
        <label
          htmlFor="jobs-sidebar-form-toggle"
          className="blocks-base-control__label"
        >
          { __( 'Select Job' ) }
        </label>
        {this.jobsDropdown()}
      </PanelRow>
    );

    const editJob = this.state.job ?
    (
      <PanelRow>
        <a href={ this.getEditJobURL() }>{ __( 'Edit Job' ) }</a>
      </PanelRow>
    ) : '';

    const showTitle = this.state.job ?
    (
      <PanelRow>
        <label
          htmlFor="lpf-show-title-form-toggle"
          className="blocks-base-control__label"
        >
          { __( 'Show Title' ) }
        </label>
        <FormToggle
          id="lpf-show-title-form-toggle"
          label={ __( 'Show Title' ) }
          checked={ !! this.props.showTitle }
          onChange={ ( e ) => this.props.toggleFormControl( e, 'show_title' ) }
        />
      </PanelRow>
    ) : '';

    const showDescription = this.state.job ?
    (
      <PanelRow>
        <label
          htmlFor="lpf-show-description-form-toggle"
          className="blocks-base-control__label"
        >
          { __( 'Show Description' ) }
        </label>
        <FormToggle
          id="lpf-show-description-form-toggle"
          label={ __( 'Show Description' ) }
          checked={ !! this.props.showDescription }
          onChange={ ( e ) => this.props.toggleFormControl( e, 'show_description' ) }
        />
      </PanelRow>
    ) : '';

    const showJobType = this.state.job ?
    (
      <PanelRow>
        <label
          htmlFor="lpf-show-type-form-toggle"
          className="blocks-base-control__label"
        >
          { __( 'Show Job Type' ) }
        </label>
        <FormToggle
          id="lpf-show-type-form-toggle"
          label={ __( 'Show Type' ) }
          checked={ !! this.props.showJobType }
          onChange={ ( e ) => this.props.toggleFormControl( e, 'show_job_type' ) }
        />
      </PanelRow>
    ) : '';

    const showLocation = this.state.job ?
    (
      <PanelRow>
        <label
          htmlFor="lpf-show-address-form-toggle"
          className="blocks-base-control__label"
        >
          { __( 'Show Location' ) }
        </label>
        <FormToggle
          id="lpf-show-address-form-toggle"
          label={ __( 'Show Location' ) }
          checked={ !! this.props.showLocation }
          onChange={ ( e ) => this.props.toggleFormControl( e, 'show_location' ) }
        />
      </PanelRow>
    ) : '';

    const showApplicationButton = this.state.job ?
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
    ) : '';

    const editJobTypeText = this.props.showJobType ?
    (
      <PanelRow>
        <TextControl
          id="lpf-job-type-text-control"
          label={ __( 'Job Type Text' ) }
          value={ this.props.jobTypeText }
          onChange={ ( val ) => this.props.handleTextControl( val, 'job_type_text' ) }
        />
      </PanelRow>
    ) : '';

    const editLocationText = this.props.showLocation ?
    (
      <PanelRow>
        <TextControl
          id="lpf-location-text-control"
          label={ __( 'Location Text' ) }
          value={ this.props.locationText }
          onChange={ ( val ) => this.props.handleTextControl( val, 'location_text' ) }
        />
      </PanelRow>
    ) : '';

    const editRemoteLocationText = this.props.showLocation && this.state.job.location === 'remote' ?
    (
      <PanelRow>
        <TextControl
          id="lpf-location-text-control"
          label={ __( 'Remote Location Text' ) }
          value={ this.props.remoteLocationText }
          onChange={ ( val ) => this.props.handleTextControl( val, 'remote_location_text' ) }
        />
      </PanelRow>
    ) : '';

    const editButtonText = this.props.showApplicationButton ?
    (
      <PanelRow>
        <TextControl
          id="lpf-button-text-control"
          label={ __( 'Button Text' ) }
          value={ this.props.buttonText }
          onChange={ ( val ) => this.props.handleTextControl( val, 'button_text' ) }
        />
      </PanelRow>
    ) : '';

    const inspector_controls = 
     <InspectorControls>

      <PanelBody title={ __( 'Settings' ) } >
        {jobsDropdown}
        {showTitle}
        {showDescription}
        {showJobType}
        {showLocation}
        {showApplicationButton}
        {editJobTypeText}
        {editLocationText}
        {editRemoteLocationText}
        {editButtonText}
        {editJob}
      </PanelBody>

    </InspectorControls>

    return inspector_controls;
  }

  jobsDropdown() {
    return Object.keys( this.state.jobs ).length > 0 ? 
      <select 
        value={ this.state.jobID } 
        onChange={ this.getJob } 
        className="lpf-jobs-dropdown"
      >
        <option key="lpf-select-job-id" value="">{ __( 'Select Job' ) }</option>
        { Object.keys( this.state.jobs ).map( ( job_id ) => {
          return <option key={ 'lpf-select-job-' + job_id } value={ job_id }>{ this.state.jobs[ job_id ].title.rendered }</option>
        })}
      </select> 
    : '<em>No jobs found...</em>';
  }

  jobListing() {
    return (
      <div className="job-page-job">
        { this.props.showTitle ? this.jobListingTitle() : '' }
        { this.jobListingMeta() }
        { this.props.showApplicationButton ? this.jobListingAppButton() : '' }
      </div>
    );
  }

  jobListingTitle() {
    return <h4 className="job-page-job-title">{ this.state.job.title.rendered }</h4>
  }

  jobListingMeta() {
    return (
      <div className="job-page-job-meta">
        { this.props.showDescription ? this.jobDescription() : '' }
        { this.props.showJobType ? this.jobType() : '' }
        { this.props.showLocation ? this.jobLocation() : '' }
      </div>
    );
  }

  jobDescription() {
    return <div className="job-page-job-description"><p>{ this.state.job.description }</p></div>
  }

  jobType() {
    return <div className="job-page-job-type">{ this.props.jobTypeText } { this.state.job.type }</div>
  }

  jobLocation() {
    return (
      <div className="job-page-job-address">
        <span className="lpf-location">{ this.props.locationText }</span>&nbsp;
        { this.state.job.location === 'remote' ? this.remoteLocation() : this.jobAddress() }
      </div>
    );
  }

  remoteLocation() {
    return <span className="lpf-remote-location">{ this.props.remoteLocationText }</span>
  }

  jobAddress() {
    return (
        <address className="lpf-address">
          <div className="lpf-address1">{ this.state.job.address['address-1'] }</div>
          <div className="lpf-address2">{ this.state.job.address['address-2'] }</div>
          <span className="lpf-city">{ this.state.job.address['city'] }</span>
          &nbsp;<span className="lpf-state">{ this.state.job.address['state'] }</span>
          { ( this.state.job.address['state'] || this.state.job.address['city'] ) && ( this.state.job.address['zip'] || this.state.job.address['country'] ) ? <span className="lpf-city-state-comma">,</span> : '' }
          <div className="lpf-country">{ this.state.job.address['country'] }</div>
          <div className="lpf-zip">{ this.state.job.address['zip'] }</div>
        </address>
    );
  }

  jobListingAppButton() {
    return (
      <div className="job-page-application">
        <a href=""><button type="button" className="job-page-application-button">{ this.props.buttonText }</button></a>
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
        { this.state.jobsLoaded ? this.jobsDropdown() : this.loading() }
        <hr/>
        { this.state.job.id ? this.jobListing() : '' }
      </div>
    );
  }
}
