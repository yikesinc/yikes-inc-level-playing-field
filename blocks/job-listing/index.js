import JobListing from './class-job-listing.js';
import './style.scss'

// Get just the __() localization function from wp.i18n
const { __ } = wp.i18n;

// Get registerBlockType and other methods from wp.blocks
const { registerBlockType } = wp.blocks;

const editJobListingBlock = function( props ) {

  const onChangeJob = ( job_id ) => {
    props.setAttributes( { job_id: job_id } );
  };

  const toggleFormControl = ( event, prop ) => {
    const properties = {};
    properties[ prop ] = !! event.target.checked
    props.setAttributes( properties );
  }

  const handleTextControl = ( value, prop ) => {
    const properties = {};
    properties[ prop ] = value;
    props.setAttributes( properties );
  }

  return (
    <JobListing
      className={ props.className }
      focus={ !! props.isSelected }
      jobID={ props.attributes.job_id }
      onChangeJob={ onChangeJob }
      toggleFormControl={ toggleFormControl }
      handleTextControl={ handleTextControl }
      showTitle={ props.attributes.show_title }
      showDescription={ props.attributes.show_description }
      showJobType={ props.attributes.show_job_type }
      jobTypeText={ props.attributes.job_type_text }
      showLocation={ props.attributes.show_location }
      locationText={ props.attributes.location_text }
      remoteLocationText={ props.attributes.remote_location_text }
      showApplicationButton={ props.attributes.show_application_button }
      buttonText={ props.attributes.button_text }
    />
  );

}

/**
 * Server side rendering means no need to save props.
 */
const saveJobListingBlock = function( props ) {
  return null;
}

const settings = {
  title     : __( 'Job Listing' ),
  category  : 'widgets',
  icon      : 'welcome-widgets-menus',
  keywords  : [ 'yikes level playing field', 'job listing', 'jobs' ],
  attributes: lpf_job_listing_data.attributes,
  edit: editJobListingBlock,
  save: saveJobListingBlock,
}

const EasyFormsBlock = registerBlockType( lpf_job_listing_data.block_name, settings );