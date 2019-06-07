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
      show_exclude_toggle    : props.exclude.length > 0,
      exclude                : props.exclude,
      show_exclude_cat_toggle: props.cat_exclude_ids.length > 0,
      catExclude             : props.cat_exclude_ids
    };

    this.jobsCategoryEndpoint = `/wp/v2/${lpf_job_listings_data.job_categories_slug}`;
    this.baseJobsEndpoint     = `/wp/v2/${lpf_job_listings_data.jobs_slug}`;
    this.setJobsEndpoint( this.props.order, this.props.orderby );
  }

  /**
   * Run our API calls after the component has mounted. You can't use setState before a component is mounted.
   */
  componentDidMount() {
    this.fetchAllJobs();
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
    this.jobsEndpoint = `${this.baseJobsEndpoint}/?order=${order}&orderby=${orderby}`
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
   * Render the jobs list.
   */
  jobs() {
    return Object.keys( this.state.jobs ).length > 0 ? (
      <ul className="lpf-jobs-list">
        { this.jobListing() }
      </ul>
    ) : `<em>${ __( 'No jobs found...', 'yikes-level-playing-field' ) }</em>`;
  }

  /**
   * Render each job.
   */
  jobListing() {
    let counter = 1;
    return (
      Object.keys( this.state.jobs ).map( ( job_index ) => {

        const job     = this.state.jobs[ job_index ];
        const exclude = this.state.exclude.includes( job.id ) || this.jobHasExcludedCategory( job[ lpf_job_listings_data.job_categories_slug ] ) || counter > this.props.limit;

        if ( ! exclude ) {
          counter++;

          return (
            <li key={ `lpf-jobs-list-item-${ job.id }` } className="lpf-jobs-list-item">
              { this.jobHeader( job ) }
              { this.props.showDesc && this.jobDescription( job, this.props.descType ) }
              { this.props.showApplicationButton && job.application && this.jobListingAppButton( job ) }
            </li>
          );
        }
      })
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
   */
  jobDescription( job, descType ) {
    return (
      <div key={ `job-listings-description-${ job.id }` } className="lpf-job-listings-description-container">
        <RawHTML>{ descType === 'full' ? job.content.rendered : job.excerpt.rendered }</RawHTML>
      </div>
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
