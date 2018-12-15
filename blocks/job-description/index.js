/**
 * Block dependencies
 */

import './style.scss';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { RichText } = wp.editor;

/**
 * Register block
 */
export default registerBlockType( 'ylpf/job-description', {
	title: __( 'Job Description', 'yikes-level-playing-field' ),
	description: __( 'General overview of the Job and its requirements.', 'yikes-level-playing-field' ),
	category: 'common',
	icon: 'forms',
	attributes: {
		message: {
			type: 'array',
			source: 'children',
			selector: '.message-body'
		}
	},
	edit: props => {
		const { attributes: { message }, className, setAttributes } = props;
		const onChangeMessage = message => {
			setAttributes( { message } );
		};
		return (
			<div className={className}>
				<h2>{__( 'Call to Action', 'yikes-level-playing-field' )}</h2>
				<RichText
					tagName="div"
					multiline="p"
					placeholder={__( 'Describe your job to your applicants.', 'yikes-level-playing-field' )}
					onChange={onChangeMessage}
					value={message}
				/>
			</div>
		);
	},
	save: props => {
		const { attributes: { message } } = props;
		return (
			<div>
				<h2>{__( 'Call to Action', 'yikes-level-playing-field' )}</h2>
				<div className="message-body">
					{message}
				</div>
			</div>
		);
	}
} );
