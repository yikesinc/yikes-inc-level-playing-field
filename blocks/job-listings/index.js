import JobListings from './class-job-listings.js';

// Get just the __() localization function from wp.i18n.
const { __ } = wp.i18n;

// Get registerBlockType and other methods from wp.blocks.
const { registerBlockType } = wp.blocks;

const editJobListingBlock = function( props ) {

  /**
   * Update a checkbox prop.
   */
  const toggleFormControl = ( event, prop ) => {
    const properties = {};
    properties[ prop ] = !! event.target.checked
    props.setAttributes( properties );
  }

  /**
   * Update a prop when we have the value.
   */
  const handleValueControl = ( value, prop ) => {
    const properties = {};
    properties[ prop ] = value;
    props.setAttributes( properties );
  }

  return (
    <JobListings
      className={ props.className }
      focus={ !! props.isSelected }
      toggleFormControl={ toggleFormControl }
      handleValueControl={ handleValueControl }
      limit={ props.attributes.limit }
      showApplicationButton={ props.attributes.show_application_button }
      buttonText={ props.attributes.button_text }
      order={ props.attributes.order }
      orderby={ props.attributes.orderby }
      exclude={ props.attributes.exclude }      
    />
  );

}

/**
 * Server side rendering means no need to save props.
 */
const saveJobListingBlock = function( props ) {
  return null;
}

// Block settings.
const settings = {
  title     : __( 'Job Listings' ),
  category  : 'widgets',
  icon      : 'list-view',
  keywords  : [ 'yikes level playing field', 'job listings', 'jobs' ],
  attributes: lpf_job_listings_data.attributes,
  edit: editJobListingBlock,
  save: saveJobListingBlock,
}

// Register our block.
const jobListingsBlock = registerBlockType( lpf_job_listings_data.block_name, settings );