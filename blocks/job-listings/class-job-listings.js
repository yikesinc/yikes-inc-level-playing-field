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
      jobs       : {},
      jobsLoaded : false,
      show_toggle: props.exclude.length > 0,
      exclude    : props.exclude
    }

    this.baseJobsEndpoint = `/wp/v2/${lpf_job_listing_data.jobs_slug}`;
    this.setJobsEndpoint( this.props.order, this.props.orderby, this.props.limit );
  }

  /**
   * Run our API calls after the component has mounted. You can't use setState before a component is mounted.
   */
  componentDidMount() {
    this.fetchAllJobs();
  }

  /**
   * Get the WP REST API endpoint for the jobs CPT.
   */
  getJobsEndpoint() {
    return this.jobsEndpoint;
  }

  /**
   * Set the WP REST API endpoint for the jobs CPT.
   */
  setJobsEndpoint( order, orderby, limit ) {
    this.jobsEndpoint = `${this.baseJobsEndpoint}/?order=${order}&orderby=${orderby}&per_page=${limit}`
  }

  /**
   * Fetch all jobs.
   */
  fetchAllJobs() {
    this.setState( { jobsLoaded: false } );

    wp.apiFetch( { path: this.getJobsEndpoint() } ).then( jobs => {
      this.setState( { jobs: jobs, jobsLoaded: true } );
    });
  }
  /**
   * Create the limit options for the dropdown.
   */
  limitLoop() {
    const options = [];
    for ( let ii = 1; ii <= 15; ii++ ) {
      options.push( { label: ii, value: ii } );
    }

    return options;
  }

  /**
   * The inspector controls HTML. This is Gutenberg's sidebar.
   */
  inspectorControls() {

    const limit =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-limit"
          className="blocks-base-control__label"
        >
          { __( 'Limit' ) }
        </label>
        <SelectControl
          id="job-listings-sidebar-limit"
          value={ this.props.limit }
          options={ this.limitLoop() }
          onChange={ ( val ) => { this.props.handleValueControl( val, 'limit' ); this.setJobsEndpoint( this.props.order, this.props.orderby, val ); this.fetchAllJobs(); } }
        />
      </PanelRow>
    );

    const orderby =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-orderby"
          className="blocks-base-control__label"
        >
          { __( 'Order By' ) }
        </label>
        <SelectControl
          id="job-listings-sidebar-orderby"
          value={ this.props.orderby }
          options={ [ { label: __( 'Title' ), value: 'title' }, { 'label': __( 'Date' ), value: 'date' } ] }
          onChange={ ( val ) => { this.props.handleValueControl( val, 'orderby' ); this.setJobsEndpoint( this.props.order, val, this.props.limit ); this.fetchAllJobs(); } }
        />
      </PanelRow>
    );

    const order =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-order"
          className="blocks-base-control__label"
        >
          { __( 'Order' ) }
        </label>
        <SelectControl
          id="job-listings-sidebar-order"
          value={ this.props.order }
          options={ [ { label: __( 'Ascending' ), value: 'asc' }, { 'label': __( 'Descending' ), value: 'desc' } ] }
          onChange={ ( val ) => { this.props.handleValueControl( val, 'order' ); this.setJobsEndpoint( val, this.props.orderby, this.props.limit ); this.fetchAllJobs(); } }
        />
      </PanelRow>
    );

    const exclude =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-exclude"
          className="blocks-base-control__label"
        >
          { __( 'Exclude Jobs' ) }
        </label>
        <FormToggle
          id="job-listings-sidebar-exclude"
          label={ __( 'Exclude Jobs' ) }
          checked={ !! this.state.show_toggle }
          onChange={ ( e ) => this.handleStateExcludeControl( e ) }
        />
      </PanelRow>
    );

    const excludeListings = this.state.show_toggle ? this.renderExcludeListingsFormControl() : '';

    const showApplicationButton =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-show-app-btn"
          className="blocks-base-control__label"
        >
          { __( 'Show Application Button' ) }
        </label>
        <FormToggle
          id="job-listings-sidebar-show-app-btn"
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
        {orderby}
        {order}
        {exclude}
        {excludeListings}
        {showApplicationButton}
        {editButtonText}
      </PanelBody>

    </InspectorControls>

    return inspector_controls;
  }

  /**
   * Render the jobs' exclude sidebar control.
   */
  renderExcludeListingsFormControl() {
    return (
      Object.keys( this.state.jobs ).map( ( job_index ) => {
        const job = this.state.jobs[ job_index ];
        return ([
          <PanelRow key={`job-listings-sidebar-exclude-row-${job.id}`}>
            <label
              htmlFor={`job-listings-sidebar-exclude-${job.id}`}
              key={`job-listings-sidebar-exclude-label-${job.id}`}
              className="blocks-base-control__label"
            >
              { job.title.rendered }
            </label>
            <FormToggle
              id={`job-listings-sidebar-exclude-${job.id}`}
              key={`job-listings-sidebar-exclude-toggle-${job.id}`}
              label={ job.title.rendered }
              checked={ ! this.state.exclude.includes( job.id ) }
              value={ job.id }
              onChange={ ( e ) => this.handleStateExcludeJobControl( e ) }
            />
          </PanelRow>
        ]);
      })
    );
  }

  handleStateExcludeControl( e ) {
    this.setState( { show_toggle: e.target.checked } );

    if ( ! e.target.checked ) {
      this.setState( { 'exclude': [] } );
      this.props.handleValueControl( [], 'exclude' )
    }
  }

  handleStateExcludeJobControl( e ) {

    let excludeProp = this.state.exclude;

    if ( ! event.target.checked ) {
      excludeProp.push( parseInt( event.target.value ) );
    } else {
      excludeProp = excludeProp.filter( function( value, index ) {
        return parseInt( value ) !== parseInt( event.target.value );
      });
    }

    this.setState( { 'exclude': excludeProp } );
    this.props.handleValueControl( excludeProp, 'exclude' );
  }

  /**
   * Render the jobs.
   */
  jobs() {
    return Object.keys( this.state.jobs ).length > 0 ? 
      Object.keys( this.state.jobs ).map( ( job_index ) => {

        const job = this.state.jobs[ job_index ];

        if ( ! this.state.exclude.includes( job.id ) ) {
          return ([
            this.jobHeader( job ),
            this.props.showApplicationButton && job.application ? this.jobListingAppButton( job ) : ''
          ]);
        }
      })
    : `<em>${ __( 'No jobs found...' ) }</em>`;
  }

  /**
   * Render the job's title.
   *
   * @param object job The job object.
   */
  jobHeader( job ) {
    return (
      <h4 key={ `job-listings-title-${ job.id }` }  className="job-page-job-title">
        <a key={ `job-listings-link-${ job.id }` } href={ job.link }>{ job.title.rendered }</a>
      </h4>
    );
  }

  /**
   * Render the job's application button.
   *
   * @param object job The job object.
   */
  jobListingAppButton( job ) {
    return (
      <div key={ `job-listings-app-${ job.id }` } className="job-page-application">
        <a key={ `job-listings-app-link-${ job.id }` } href="">
          <button key={ `job-listings-app-button-${ job.id }` } type="button" className="job-page-application-button">{ this.props.buttonText }</button>
        </a>
      </div>
    );
  }

  /**
   * Render the loading spinner.
   */
  loading() {
    return (
        <div className="loading">
          <span>{ __( 'Loading...' ) }</span>
          <Spinner></Spinner>
        </div>
    );
  }

  /**
   * Render!
   */
  render() {
    return (
      <div className={ this.props.className }>
        { this.props.focus && this.inspectorControls() }
        { this.state.jobsLoaded ? this.jobs() : this.loading() }
      </div>
    );
  }
}
