// Get functions / blocks / components.
const { __ } = wp.i18n;
const { InspectorControls } = wp.editor;
const { Spinner, TextControl, PanelBody, PanelRow, FormToggle, SelectControl } = wp.components;
const { Component, RawHTML } = wp.element;

export default class JobListing extends Component {
  constructor( props ) {
    super( ...arguments );
    this.state = {
      jobs                   : {},
      jobsLoaded             : false,
      jobCategories          : {},
      jobStatusActiveTermID  : 0,
      show_exclude_toggle    : props.exclude.length > 0,
      exclude                : props.exclude,
      show_exclude_cat_toggle: props.cat_exclude_ids.length > 0,
      catExclude             : props.cat_exclude_ids
    };

    this.jobsCategoryEndpoint = `/wp/v2/${lpf_job_listings_data.job_categories_slug}/?hide_empty=true`;
    this.baseJobsEndpoint     = `/wp/v2/${lpf_job_listings_data.jobs_slug}`;
    this.jobsStatusEndpoint   = `/wp/v2/${lpf_job_listings_data.job_status_slug}/?slug=${lpf_job_listings_data.job_status_active_slug}`;

  }

  /**
   * Run our API calls after the component has mounted. You can't use setState before a component is mounted.
   */
  componentDidMount() {
    this.fetchActiveJobStatusId();
    this.fetchAllCategories();
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
  setJobsEndpoint( order, orderby ) {
    this.jobsEndpoint = `${this.baseJobsEndpoint}/?${lpf_job_listings_data.job_status_slug}=${this.state.jobStatusActiveTermID}&order=${order}&orderby=${orderby}`;
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
   * Fetch all job categories.
   */
  fetchAllCategories() {
    wp.apiFetch( { path: this.jobsCategoryEndpoint } ).then( cats => {
      this.setState( { jobCategories: cats } );
    });
  }

  /**
   * Fetch ID of job status of active.
   */
  fetchActiveJobStatusId() {
    wp.apiFetch( { path: this.jobsStatusEndpoint } ).then( statuses => {
      this.setState( { jobStatusActiveTermID: statuses[0].id } );
      this.setJobsEndpoint( this.props.order, this.props.orderby );
      this.fetchAllJobs();
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
          { __( 'Limit', 'yikes-level-playing-field' ) }
        </label>
        <SelectControl
          id="job-listings-sidebar-limit"
          value={ this.props.limit }
          options={ this.limitLoop() }
          onChange={ ( val ) => { this.props.handleValueControl( val, 'limit' ); } }
        />
      </PanelRow>
    );

    const showDesc =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-show-desc"
          className="blocks-base-control__label"
        >
          { __( 'Show Description', 'yikes-level-playing-field' ) }
        </label>
        <FormToggle
          id="job-listings-sidebar-show-desc"
          label={ __( 'Show Description', 'yikes-level-playing-field' ) }
          checked={ !! this.props.showDesc }
          onChange={ ( e ) => this.props.toggleFormControl( e, 'show_desc' ) }
        />
      </PanelRow>
    );

    const selectDescType = this.props.showDesc ?
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-desc-type"
          className="blocks-base-control__label"
        >
          { __( 'Description Type', 'yikes-level-playing-field' ) }
        </label>
        <SelectControl
          id="job-listings-sidebar-desc-type"
          value={ this.props.descType }
          options={ [ { label: __( 'Excerpt', 'yikes-level-playing-field' ), value: 'excerpt' }, { 'label': __( 'Full', 'yikes-level-playing-field' ), value: 'full' } ] }
          onChange={ ( val ) => { this.props.handleValueControl( val, 'desc_type' ); } }
        />
      </PanelRow>
    ) : '';

    const showDetails =
    (
        <PanelRow>
            <label
                htmlFor="job-listings-sidebar-show-details"
                className="blocks-base-control__label"
            >
                { __( 'Show Details', 'yikes-level-playing-field' ) }
            </label>
            <FormToggle
                id="job-listings-sidebar-show-details"
                label={ __( 'Show Details', 'yikes-level-playing-field' ) }
                checked={ !! this.props.showDetails }
                onChange={ ( e ) => this.props.toggleFormControl( e, 'show_details' ) }
            />
        </PanelRow>
    );

    const detailsText = this.props.showDetails ?
    (
      <PanelRow>
        <TextControl
          id="lpf-details-text-control"
          label={ __( 'Details Text', 'yikes-level-playing-field' ) }
          value={ this.props.detailsText }
          onChange={ ( val ) => this.props.handleValueControl( val, 'details_text' ) }
        />
      </PanelRow>
    ) : '';

    const jobTypeText = this.props.showDetails ?
    (
      <PanelRow>
        <TextControl
          id="lpf-job-type-text-control"
          label={ __( 'Job Type Text', 'yikes-level-playing-field' ) }
          value={ this.props.jobTypeText }
          onChange={ ( val ) => this.props.handleValueControl( val, 'job_type_text' ) }
        />
      </PanelRow>
    ) : '';

    const locationText = this.props.showDetails ?
    (
      <PanelRow>
        <TextControl
          id="lpf-location-text-control"
          label={ __( 'Location Text', 'yikes-level-playing-field' ) }
          value={ this.props.locationText }
          onChange={ ( val ) => this.props.handleValueControl( val, 'location_text' ) }
        />
      </PanelRow>
    ) : '';

    const remoteLocationText = this.props.showDetails ?
      (
        <PanelRow>
          <TextControl
            id="lpf-remote-location-text-control"
            label={ __( 'Remote Location Text', 'yikes-level-playing-field' ) }
            value={ this.props.remoteLocationText }
            onChange={ ( val ) => this.props.handleValueControl( val, 'remote_location_text' ) }
          />
        </PanelRow>
      ) : '';

    const groupByCategory =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-group-by-category"
          className="blocks-base-control__label"
        >
          { __( 'Group By Category', 'yikes-level-playing-field' ) }
        </label>
        <FormToggle
          id="job-listings-sidebar-group-by-category"
          label={ __( 'Group By Category', 'yikes-level-playing-field' ) }
          checked={ !! this.props.groupedByCat }
          onChange={ ( e ) => this.props.toggleFormControl( e, 'grouped_by_cat' ) }
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
          { __( 'Order By', 'yikes-level-playing-field' ) }
        </label>
        <SelectControl
          id="job-listings-sidebar-orderby"
          value={ this.props.orderby }
          options={ [ { label: __( 'Title', 'yikes-level-playing-field' ), value: 'title' }, { 'label': __( 'Date', 'yikes-level-playing-field' ), value: 'date' } ] }
          onChange={ ( val ) => { this.props.handleValueControl( val, 'orderby' ); this.setJobsEndpoint( this.props.order, val ); this.fetchAllJobs(); } }
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
          { __( 'Order', 'yikes-level-playing-field' ) }
        </label>
        <SelectControl
          id="job-listings-sidebar-order"
          value={ this.props.order }
          options={ [ { label: __( 'Ascending', 'yikes-level-playing-field' ), value: 'asc' }, { 'label': __( 'Descending', 'yikes-level-playing-field' ), value: 'desc' } ] }
          onChange={ ( val ) => { this.props.handleValueControl( val, 'order' ); this.setJobsEndpoint( val, this.props.orderby ); this.fetchAllJobs(); } }
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
          { __( 'Exclude Jobs', 'yikes-level-playing-field' ) }
        </label>
        <FormToggle
          id="job-listings-sidebar-exclude"
          label={ __( 'Exclude Jobs', 'yikes-level-playing-field' ) }
          checked={ !! this.state.show_exclude_toggle }
          onChange={ ( e ) => this.handleStateExcludeControl( e ) }
        />
      </PanelRow>
    );

    const excludeListings = this.state.show_exclude_toggle ? this.renderExcludeListingsFormControl() : '';

    const excludeByCategory =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-exclude-category"
          className="blocks-base-control__label"
        >
          { __( 'Exclude Categories', 'yikes-level-playing-field' ) }
        </label>
        <FormToggle
          id="job-listings-sidebar-exclude-category"
          label={ __( 'Exclude Categories', 'yikes-level-playing-field' ) }
          checked={ !! this.state.show_exclude_cat_toggle }
          onChange={ ( e ) => this.handleStateExcludeCatControl( e ) }
        />
      </PanelRow>
    );

    const excludeCategories = this.state.show_exclude_cat_toggle ? this.renderExcludeCategoriesFormControl() : '';

    const showApplicationButton =
    (
      <PanelRow>
        <label
          htmlFor="job-listings-sidebar-show-app-btn"
          className="blocks-base-control__label"
        >
          { __( 'Show Application Button', 'yikes-level-playing-field' ) }
        </label>
        <FormToggle
          id="job-listings-sidebar-show-app-btn"
          label={ __( 'Show Application Button', 'yikes-level-playing-field' ) }
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
          label={ __( 'Button Text', 'yikes-level-playing-field' ) }
          value={ this.props.buttonText }
          onChange={ ( val ) => this.props.handleValueControl( val, 'button_text' ) }
        />
      </PanelRow>
    ) : '';

    return (
      <InspectorControls>
        <PanelBody title={ __( 'Settings', 'yikes-level-playing-field' ) } >
          {limit}
          {showDesc}
          {selectDescType}
          {showDetails}
          {detailsText}
          {jobTypeText}
          {locationText}
          {remoteLocationText}
          {groupByCategory}
          {orderby}
          {order}
          {exclude}
          {excludeListings}
          {excludeByCategory}
          {excludeCategories}
          {showApplicationButton}
          {editButtonText}
        </PanelBody>
      </InspectorControls>
    );
  }

  /**
   * Render the jobs' exclude sidebar control.
   */
  renderExcludeListingsFormControl() {
    return (
      Object.keys( this.state.jobs ).map( ( job_index ) => {
        const job = this.state.jobs[ job_index ];
        return ([
          <PanelRow className="job-listings-exclude-subpanel" key={`job-listings-sidebar-exclude-row-${job.id}`}>
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

  /**
   * Handle the exclude sidebar control.
   */
  handleStateExcludeControl( e ) {
    this.setState( { show_exclude_toggle: e.target.checked } );

    if ( ! e.target.checked ) {
      this.setState( { 'exclude': [] } );
      this.props.handleValueControl( [], 'exclude' )
    }
  }

  /**
   * Handle the individual jobs' exclude sidebar control.
   */
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
   * Render the categories' exclude sidebar control.
   */
  renderExcludeCategoriesFormControl() {
    return (
      Object.keys( this.state.jobCategories ).map( ( cat_index ) => {
        const category = this.state.jobCategories[ cat_index ];
        return ([
          <PanelRow className="job-listings-exclude-subpanel" key={`job-category-sidebar-exclude-row-${category.id}`}>
            <label
              htmlFor={`job-category-sidebar-exclude-${category.id}`}
              key={`job-category-sidebar-exclude-label-${category.id}`}
              className="blocks-base-control__label"
            >
              { category.name }
            </label>
            <FormToggle
              id={`job-category-sidebar-exclude-${category.id}`}
              key={`job-category-sidebar-exclude-toggle-${category.id}`}
              label={ category.name }
              checked={ ! this.state.catExclude.includes( category.id ) }
              value={ category.id }
              onChange={ ( e ) => this.handleStateExcludeCategoryControl( e ) }
            />
          </PanelRow>
        ]);
      })
    );
  }

  /**
   * Handle the exclude categories (catExclude) sidebar control.
   */
  handleStateExcludeCatControl( e ) {
    this.setState( { show_exclude_cat_toggle: e.target.checked } );

    if ( ! e.target.checked ) {
      this.setState( { 'catExclude': [] } );
      this.props.handleValueControl( [], 'catExclude' )
    }
  }

  /**
   * Handle the individual categories' exclude sidebar control.
   */
  handleStateExcludeCategoryControl( e ) {

    let excludeProp = this.state.catExclude;

    if ( ! event.target.checked ) {
      excludeProp.push( parseInt( event.target.value ) );
    } else {
      excludeProp = excludeProp.filter( function( value, index ) {
        return parseInt( value ) !== parseInt( event.target.value );
      });
    }

    this.setState( { 'catExclude': excludeProp } );
    this.props.handleValueControl( excludeProp, 'cat_exclude_ids' );
  }

  /**
   * Check if the job has a category that we're excluding.
   */
  jobHasExcludedCategory( categories ) {
    const intersect = categories.filter( value => this.state.catExclude.includes( value ) );
    return intersect.length > 0;
  }

  /**
   * Filter
   */
  jobsByCategory() {
    return (
      <div className="lpf-jobs-by-category-list">
        {
          Object.keys(this.state.jobCategories).map((cat_index) => {
            const jobCategory = this.state.jobCategories[cat_index];
            return (
              <div key={`job-listings-category-${ jobCategory.id }`} className="lpf-jobs-by-category-list">
                <h3 className="lpf-jobs-by-category-header">{jobCategory.name}</h3>
                { this.jobListing( jobCategory.id ) }
              </div>
            );
          })
        }
      </div>
    );
  }

  /**
   * Render the jobs list.
   */
  jobs() {
    const jobsHTML = this.props.groupedByCat ? this.jobsByCategory() : this.jobListing();
    return Object.keys( this.state.jobs ).length > 0 ? jobsHTML : <em>{ __( 'No jobs found...', 'yikes-level-playing-field' ) }</em>;
  }

  /**
   * Render each job.
   */
  jobListing( categoryId = false ) {
    let counter = 1;
    return (
      <ul className="lpf-jobs-list">
        {
          Object.keys(this.state.jobs).map((job_index) => {
            const job = this.state.jobs[job_index];
            let hasCategoryId = categoryId ? job.job_category.includes( categoryId ) : true;
            const exclude = this.state.exclude.includes(job.id) || this.jobHasExcludedCategory(job[lpf_job_listings_data.job_categories_slug]) || counter > this.props.limit || ! hasCategoryId;

            if (!exclude) {
              counter++;

              return (
                <li key={`lpf-jobs-list-item-${ job.id }`} className="lpf-jobs-list-item">
                  {this.jobHeader(job)}
                  {this.props.showDesc && this.jobDescription(job, this.props.descType)}
                  {this.props.showDetails && this.jobListingMeta(job)}
                  {this.props.showApplicationButton && job.application != false && this.jobListingAppButton(job)}
                </li>
              );
            }
          })
        }
      </ul>
    );
  }

  /**
   * Render the job's title.
   *
   * @param object job The job object.
   */
  jobHeader( job ) {
    return (
      <h4 key={ `job-listings-title-${ job.id }` }>
        <a key={ `job-listings-link-${ job.id }` } href={ job.link }>{ job.title.rendered }</a>
      </h4>
    );
  }

  /**
   * Render the job's description.
   *
   * @param object job The job object.
   * @param string descType Whether to show full description or description excerpt.
   */
  jobDescription( job, descType ) {
    return (
      <div key={ `job-listings-description-${ job.id }` } className="lpf-job-listings-description-container">
        <RawHTML>{ descType === 'full' ? job.content.rendered : job.excerpt.rendered }</RawHTML>
      </div>
    );
  }

  /**
   * Render the job listing meta.
   */
  jobListingMeta( job ) {
    return (
      <div key={ `job-listings-description-${ job.id }` } className="lpf-job-listing-meta-container">
        { this.jobMetaHeading() }
        { job.job_type !== '' && this.jobType( job ) }
        { this.jobLocation( job ) }
      </div>
    );
  }

  /**
   * Render the job listing meta title.
   */
  jobMetaHeading() {
    return <h4 className="lpf-job-listing-meta-header">{ this.props.detailsText }</h4>
  }

  /**
   * Render the job type.
   */
  jobType( job ) {
    return (
      <div className="lpf-job-listing-type">
        <span key="lpf-job-listing-meta-label" className="lpf-job-listing-meta-label lpf-job-listing-type-label">{ this.props.jobTypeText + ' ' }</span>
        <span key="lpf-job-listing-meta-content" className="lpf-job-listing-meta-content lpf-job-listing-type">{ job.job_type }</span>
      </div>
    );
  }

  /**
   * Render the job's location.
   */
  jobLocation( job ) {
    return (
      <div className="lpf-job-listing-location-container">
        <span className="lpf-job-listing-meta-label lpf-job-listing-location-label">{ this.props.locationText + ' ' }</span>
        { job.location === 'remote' ? this.remoteLocation() : this.jobAddress( job ) }
      </div>
    );
  }

  /**
   * Render the remote location.
   */
  remoteLocation() {
    return <span className="lpf-job-listing-location-remote">{ this.props.remoteLocationText }</span>
  }

  /**
   * Render the job address.
   */
  jobAddress( job ) {
    return (
      <address className="lpf-job-listing-location-address">
        <div className="lpf-address1">{ job.address['address-1'] }</div>
        <div className="lpf-address2">{ job.address['address-2'] }</div>
        <span className="lpf-city">{ job.address['city'] }</span>
        &nbsp;<span className="lpf-state">{ job.address['state'] }</span>
        { ( job.address['state'] || job.address['city'] ) && ( job.address['zip'] || job.address['country'] ) ? <span className="lpf-city-state-comma">,</span> : '' }
        <div className="lpf-country">{ job.address['country'] }</div>
        <div className="lpf-zip">{ job.address['zip'] }</div>
      </address>
    );
  }

  /**
   * Render the job's application button.
   *
   * @param object job The job object.
   */
  jobListingAppButton( job ) {
    return (
      <div key={ `job-listings-app-${ job.id }` } className="lpf-jobs-list-application-link">
        <a key={ `job-listings-app-link-${ job.id }` } href="">
          <button key={ `job-listings-app-button-${ job.id }` } type="button" className="lpf-jobs-list-application-button">{ this.props.buttonText }</button>
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
          <span>{ __( 'Loading...', 'yikes-level-playing-field' ) }</span>
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
