const path = require( 'path' );
const webpack = require( 'webpack' );
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );

// Set different CSS extraction for editor only and common block styles
const blocksCSSPlugin = new ExtractTextPlugin( {
	filename: './assets/css/blocks-style.css'
} );
const editBlocksCSSPlugin = new ExtractTextPlugin( {
	filename: './assets/css/blocks-editor.css'
} );

// Configuration for the ExtractTextPlugin.
const extractConfig = {
	use: [
		{ loader: 'raw-loader' }, {
			loader: 'postcss-loader',
			options: {
				plugins: [ require( 'autoprefixer' ) ]
			}
		}, {
			loader: 'sass-loader',
			query: {
				outputStyle: 'production' === process.env.NODE_ENV ? 'compressed' : 'nested'
			}
		}
	]
};


module.exports = {
	entry: {
		// 'job-description': './blocks/job-description/index.js',
		// 'job-type': './blocks/job-type/index.js',
		'job-listing': './blocks/job-listing/index.js',
		'job-listings': './blocks/job-listings/index.js',
	},
	output: {
		path: path.resolve( __dirname ),
		filename: 'assets/js/blocks/[name]/index.js'
	},
	watch: 'production' !== process.env.NODE_ENV,
	devtool: 'cheap-eval-source-map',
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: 'babel-loader'
				}
			}, {
				test: /style\.s?css$/,
				use: blocksCSSPlugin.extract( extractConfig )
			}, {
				test: /editor\.s?css$/,
				use: editBlocksCSSPlugin.extract( extractConfig )
			}
		]
	},
	plugins: [
		blocksCSSPlugin, editBlocksCSSPlugin
	]
};
