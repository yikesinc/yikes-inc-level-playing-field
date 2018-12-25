/**
 * Block dependencies
 */

import './style.scss';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { RadioControl } = wp.components;
const { withState } = wp.compose;

/**
 * Register block
 */
export default registerBlockType( 'ylpf/job-type', {
	title: __( 'Job Type', 'yikes-level-playing-field' ),
	description: __( 'The type of job being offered.', 'yikes-level-playing-field' ),
	category: 'ylpf-job',
	icon: 'forms',
	attributes: {

	},
	edit: props => {},
	save: props => {}
} );
