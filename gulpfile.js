// Require our dependencies
const browserSync = require( 'browser-sync' );
const del         = require( 'del' );
const eslint      = require( 'gulp-eslint' );
const gutil       = require( 'gulp-util' );
const glob        = require( 'glob' );
const minimist    = require( 'minimist' );
const notify      = require( 'gulp-notify' );
const path        = require( 'path' );
const plumber     = require( 'gulp-plumber' );
const rename      = require( 'gulp-rename' );
const sort        = require( 'gulp-sort' );
const sourcemaps  = require( 'gulp-sourcemaps' );
const webpack     = require( 'webpack-stream' );
const {
	series, parallel, task, src, dest, watch
} = require( 'gulp' );

// Set assets paths.
const paths = {
	css: [ 'assets/css/*.css', '!assets/css/*.min.css' ],
	icons: 'assets/images/svg-icons/*.svg',
	images: [ 'assets/images/*', '!assets/images/*.svg' ],
	php: [ './*.php', './src/**/*.php', './views/**/*.php' ],
	sass: 'assets/css/sass/*.scss',
	scripts: [ 'assets/js/*.js', 'assets/js/blocks/**/*.js', '!assets/js/**/*.min.js' ],
	devscripts: [ 'assets/js/dev/*.js' ],
	blocks: [ 'blocks/*/index.js' ],
	sprites: 'assets/images/sprites/*.png',
	build: [
		// JS files, except for the development versions.
		'assets/js/**/*.js',
		'!assets/js/dev/*.js',

		// Images, except those meant for SVN.
		'assets/images/**',
		'!assets/images/banner-*',
		'!assets/images/icon-*',
		'!assets/images/screenshot-*',

		// Everything else.
		'assets/css/*.css',
		'assets/vendor/**',
		'languages/*',
		'src/**/*.php',
		'vendor/awesome-yikes-framework/**',
		'views/**/*.php',
		'*.php',
		'LICENSE.txt',
		'readme.*',
	],
	svnAssets: [
		'assets/images/banner-*',
		'assets/images/icon-*',
		'assets/images/screnshot-*',
	]
};

// Command line options
const options = minimist( process.argv.slice( 2 ), {
	string: [ 'version', 'svn-dir' ],
	boolean: [ 'svn-tag', 'existing-build' ],
	default: {
		// A custom version value.
		version: '',

		// Whether to add files to build/svn/tags/ directory.
		'svn-tag': false,

		// Directory to the SVN repo.
		'svn-dir': './build/svn',

		// Use existing files in the build/ directory instead of running the build task again.
		'existing-build': false,
	}
} );

// Load the package.json data. This will be cached per request.
const packageJSON = require( './package.json' );
let currentVersion = packageJSON.version;

/**
 * Handle errors and alert the user.
 */
function handleErrors() {
	const args = Array.prototype.slice.call( arguments );

	notify.onError( {
		'title': 'Task Failed [<%= error.message %>',
		'message': 'See console.',
		'sound': 'Sosumi' // See:
	                      // https://github.com/mikaelbr/node-notifier#all-notification-options-with-their-defaults
	} ).apply( this, args );

	gutil.beep(); // Beep 'sosumi' again.

	// Prevent the 'watch' task from stopping.
	this.emit( 'end' );
}

/**
 * Output error messages to the command prompt.
 * @param error
 */
function outputErrors( error ) {
	gutil.log( gutil.colors.red( '[Error]' ), error.toString() );
	gutil.beep();
}

/**
 * Find all of the main block files.
 */
function findBlocks() {
	const globs = glob.sync( './blocks/**/index.js' );
	let blocks = {};
	globs.map( ( glob ) => {
		const name = path.basename( path.dirname( glob ) );
		blocks[ name ] = glob;
	} );

	return blocks;
}

/**
 * Map paths based on a glob pattern for Webpack.
 */
function findScripts() {
	const globs = glob.sync( './assets/js/dev/*.js' );
	let names = {};
	globs.map( ( glob ) => {
		const name = path.basename( glob, '.js' );
		names[ name ] = glob;
	} );

	return names;
}

/**
 * Delete *.css and *.min.css before we minify and optimize
 */
function cleanStyles() {
	return del( [ 'assets/css/*.css' ] );
}

/**
 * Compile Sass and run stylesheet through PostCSS.
 *
 * https://www.npmjs.com/package/gulp-sass
 * https://www.npmjs.com/package/gulp-postcss
 * https://www.npmjs.com/package/gulp-autoprefixer
 * https://www.npmjs.com/package/css-mqpacker
 */
function compileSass() {
	const autoprefixer = require( 'autoprefixer' );
	const bourbon = require( 'bourbon' ).includePaths;
	const neat = require( 'bourbon-neat' ).includePaths;
	const mqpacker = require( 'css-mqpacker' );
	const postcss = require( 'gulp-postcss' );
	const sass = require( 'gulp-sass' );

	return src( paths.sass, paths.css )
		// Deal with errors.
		.pipe( plumber( { 'errorHandler': handleErrors } ) )

		// Wrap tasks in a sourcemap.
		.pipe( sourcemaps.init() )

		// Compile Sass using LibSass.
		.pipe( sass( {
			'includePaths': [].concat( bourbon, neat ),
			'errLogToConsole': true,
			'outputStyle': 'expanded' // Options: nested, expanded, compact, compressed
		} ) )

		// Parse with PostCSS plugins.
		.pipe( postcss( [
			autoprefixer( {
				'overrideBrowserslist': [ 'last 2 version' ]
			} ), mqpacker( {
				'sort': true
			} )
		] ) )

		// Create sourcemap.
		.pipe( sourcemaps.write() )

		// Create .css files.
		.pipe( dest( 'assets/css' ) )
		.pipe( browserSync.stream() );
}

/**
 * Minify and optimize style.css.
 *
 * https://www.npmjs.com/package/gulp-cssnano
 */
function minifyCss() {
	const cssnano = require( 'cssnano' );
	const postcss = require( 'gulp-postcss' );

	return src( paths.css )
		.pipe( plumber( { 'errorHandler': handleErrors } ) )
		.pipe( postcss( [ cssnano( {
			// Use safe optimizations.
			'safe': true
		} ) ] ) )
		.pipe( rename( {
			// Ensure .min.css extension.
			'extname': '.min.css'
		} ) )
		.pipe( dest( 'assets/css' ) )
		.pipe( browserSync.stream() );
}

/**
 * Delete the svg-icons.svg before we minify, concat.
 */
function cleanIcons() {
	return del( [ 'assets/images/svg-icons.svg' ] );
}

/**
 * Minify, concatenate, and clean SVG icons.
 *
 * https://www.npmjs.com/package/gulp-svgmin
 * https://www.npmjs.com/package/gulp-svgstore
 * https://www.npmjs.com/package/gulp-cheerio
 */
function compileSvg() {
	const cheerio = require( 'gulp-cheerio' );
	const svgmin = require( 'gulp-svgmin' );
	const svgstore = require( 'gulp-svgstore' );

	return src( paths.icons )

	// Deal with errors.
		.pipe( plumber( { 'errorHandler': handleErrors } ) )

		// Minify SVGs.
		.pipe( svgmin() )

		// Add a prefix to SVG IDs.
		.pipe( rename( { 'prefix': 'icon-' } ) )

		// Combine all SVGs into a single <symbol>
		.pipe( svgstore( { 'inlineSvg': true } ) )

		// Clean up the <symbol> by removing the following cruft...
		.pipe( cheerio( {
			'run': function( $, file ) {
				$( 'svg' ).attr( 'style', 'display:none' );
				$( '[fill]' ).removeAttr( 'fill' );
				$( 'path' ).removeAttr( 'class' );
			},
			'parserOptions': { 'xmlMode': true }
		} ) )

		// Save svg-icons.svg.
		.pipe( dest( 'assets/images/' ) )
		.pipe( browserSync.stream() );
}

/**
 * Optimize images.
 *
 * https://www.npmjs.com/package/gulp-imagemin
 */
task( 'imagemin', () => {
	const imagemin = require( 'gulp-imagemin' );
	return src( paths.images )
		.pipe( plumber( { 'errorHandler': handleErrors } ) )
		.pipe( imagemin( {
			'optimizationLevel': 5,
			'progressive': true,
			'interlaced': true
		} ) )
		.pipe( dest( 'assets/images' ) );
} );

/**
 * Delete the scripts before rebuilding them.
 */
function cleanScripts() {
	return del( [ ...paths.scripts, 'assets/js/**/*.min.js' ] );
}

/**
 * Compile javascript files from the dev directory.
 * @returns {*}
 */
function compileScripts() {
	return src( paths.devscripts )
		.pipe( plumber( { errorHandler: outputErrors } ) )
		.pipe( sourcemaps.init() )
		.pipe( webpack( {
			entry: findScripts(),
			output: {
				filename: '[name].js'
			},
			devtool: 'inline-cheap-module-source-map',
			mode: 'none',
			module: {
				rules: [
					{
						test: /\.js$/,
						exclude: /(node_modules|bower_components)/,
						use: {
							loader: 'babel-loader'
						}
					}
				]
			}
		} ) )
		.pipe( sourcemaps.write() )
		.pipe( dest( 'assets/js' ) );
}

/**
 * Minimize scripts using uglify.
 * @returns {*}
 */
function minimizeScripts() {
	const uglify = require( 'gulp-uglify' );
	return src( paths.scripts, { base: 'assets/js' } )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( uglify( { mangle: false } ) )
		.pipe( dest( 'assets/js' ) );
}

/**
 * Compile blocks using webpack.
 */
function compileBlocks() {
	const named = require( 'vinyl-named' );
	return src( paths.blocks )
		.pipe( named() )
		.pipe( webpack( {
			entry: findBlocks(),
			output: {
				filename: '[name]/index.js'
			},
			// devtool: 'cheap-eval-source-map',
			mode: 'none',
			module: {
				rules: [
					{
						test: /\.js$/,
						exclude: /(node_modules|bower_components)/,
						use: {
							loader: 'babel-loader'
						}
					},
					{
						test: /\.s?css$/,
						use: [ {
							// creates style nodes from JS strings
							loader: "style-loader"
						}, {
							// translates CSS into CommonJS
							loader: "css-loader"
						}, {
							// compiles Sass to CSS
							loader: "sass-loader"
						} ]
					}
				]
			}
		} ) )
		.pipe( dest( 'assets/js/blocks' ) );
}

/**
 * Delete the theme's .pot before we create a new one.
 */
function cleanPot() {
	return del( [ 'languages/*.pot' ] );
}

/**
 * Check that the text domain is present in all of our PHP files.
 */
function checkTextDomain() {
	const wpCheckTextDomain = require( 'gulp-checktextdomain' );
	return src( paths.php )
		.pipe( plumber( { 'errorHandler': outputErrors } ) )
		.pipe( wpCheckTextDomain( {
			text_domain: packageJSON.name,
			correct_domain: true,
			keywords: [
				'__:1,2d',
				'esc_attr__:1,2d',
				'esc_html__:1,2d',
				'_e:1,2d',
				'esc_attr_e:1,2d',
				'esc_html_e:1,2d',
				'_x:1,2c,3d',
				'_ex:1,2c,3d',
				'esc_attr_x:1,2c,3d',
				'esc_html_x:1,2c,3d',
				'_n:1,2,4d',
				'_nx:1,2,4c,5d',
				'_n_noop:1,2,3d',
				'_nx_noop:1,2,3c,4d'
			]
		} ) );
}

/**
 * Scan the theme and create a POT file.
 */
function makePot() {
	const wpPot = require( 'gulp-wp-pot' );
	return src( paths.php )
		.pipe( plumber( { 'errorHandler': handleErrors } ) )
		.pipe( sort() )
		.pipe( wpPot( {
			'domain': packageJSON.name,
			'package': packageJSON.title,
			'headers': {
				'Language': 'en_US'
			}
		} ) )
		.pipe( dest( `languages/${packageJSON.name}.pot` ) );
}

/**
 * Sass linting.
 *
 * https://www.npmjs.com/package/sass-lint
 */
task( 'sass:lint', () => {
	const sassLint = require( 'gulp-sass-lint' );
	return src( [
		'assets/css/sass/**/*.scss',
		'!assets/css/sass/base/_normalize.scss',
		'!assets/css/sass/base/_sprites.scss',
		'!node_modules/**'
	] )
		.pipe( sassLint() )
		.pipe( sassLint.format() )
		.pipe( sassLint.failOnError() );
} );

/**
 * JavaScript linting.
 *
 * https://www.npmjs.com/package/gulp-eslint
 */
task( 'js:lint', () => {
	return src( [
		'assets/js/concat/*.js',
		'assets/js/*.js',
		'!assets/js/*.min.js',
		'!Gruntfile.js',
		'!Gulpfile.js',
		'!node_modules/**'
	] )
		.pipe( eslint() )
		.pipe( eslint.format() )
		.pipe( eslint.failAfterError() );
} );

/**
 * Generate Sass docs.
 */
task( 'sassdoc', () => {
	const sassdoc = require( 'sassdoc' );
	return src( 'assets/css/sass/**/*.scss' )
		.pipe( sassdoc( { dest: 'docs' } ) );
} );

/**
 * Process tasks and reload browsers on file changes.
 *
 * https://www.npmjs.com/package/browser-sync
 */
task( 'watch', () => {

	// Kick off BrowserSync.
	browserSync( {
		'open': false,             // Open project in a new tab?
		'injectChanges': true,     // Auto inject changes instead of full reload.
		'proxy': '_s.dev',         // Use http://_s.dev:3000 to use BrowserSync.
		'watchOptions': {
			'debounceDelay': 1000  // Wait 1 second before injecting.
		}
	} );

	// Run tasks when files change.
	// watch( paths.icons, [ 'icons' ]);
	watch( paths.sass, [ 'styles' ] );
	watch( paths.scripts, [ 'scripts' ] );
	watch( paths.concat_scripts, [ 'scripts' ] );
	// watch( paths.sprites, [ 'sprites' ]);
	watch( paths.php, [ 'markup' ] );
} );

/**
 * Build a zip file appropriate for distribution.
 */
function build() {
	const zip = require( 'gulp-zip' );
	return src( paths.build, { base: process.cwd() } )
		.pipe( rename( function( path ) {
			// Prefix the paths with the directory name before zipping.
			path.dirname = `${packageJSON.name}/${path.dirname}`;
		} ) )

		// Pipe each file to the build directory and the Zip file
		.pipe( dest( 'build' ) )
		.pipe( zip( `${packageJSON.name}-${currentVersion}.zip` ) )

		// Pipe the zip file to the build directory
		.pipe( dest( 'build' ) );
}

/**
 * Replace %VERSION% with the current version string.
 *
 * The version from package.json will be used, or the --version
 * flag can be passed via CLI to explicitly set the version.
 */
function replaceVersion() {
	const replace = require( 'gulp-replace' );
	const version = options.version || currentVersion;
	return src( paths.php, { base: process.cwd() } )
		.pipe( plumber( { 'errorHandler': handleErrors } ) )
		.pipe( replace( '%VERSION%', version ) )
		.pipe( dest( './' ) );
}

/**
 * Bump the version in various files.
 *
 * @returns {*}
 */
function bumpVersion( version ) {
	const bump = require( 'gulp-bump' );
	return src( [ './level-playing-field.php', './package.json', './readme.txt' ] )
		.pipe( plumber( { 'errorHandler': outputErrors } ) )
		.pipe( bump( {
			version: version,
			keys: [
				'version',
				'stable tag'
			]
		} ) )
		.pipe( dest( './' ) );
}

/**
 * Get a function to bump the version by given type.
 *
 * We use the semver library to determine the version based on what is currently
 * listed in the package.json file. This is to ensure the same version is applied
 * everywhere, instead of simply incrementing whatever version is found in
 * each file, in case they weren't in sync.
 *
 * @param type
 * @returns {function(): *}
 */
function getVersionBump( type ) {
	return function() {
		const semver = require( 'semver' );
		const validTypes = [ 'patch', 'minor', 'major' ];

		if ( !validTypes.includes( type ) ) {
			throw new Error( `"${type} is not a valid option.` );
		}

		currentVersion = semver.inc( packageJSON.version, type );

		return bumpVersion( currentVersion );
	};
}

/**
 * Copy plugin repo assets from images into the SVN assets directory.
 * @returns {*}
 */
function svnAssets() {
	return src( paths.svnAssets )
		.pipe( dest( `${options['svn-dir']}/assets` ) );
}

/**
 * Copy files from the build directory into SVN Trunk.
 */
function svnTrunk() {
	return src( [ `./build/${packageJSON.name}/**/*` ] )
		.pipe( dest( `${options['svn-dir']}/trunk` ) );
}

/**
 * Copy files from teh build directory into the SVN tags directory.
 *
 * Uses the current tag as defined in package.json.
 */
function svnTag( done ) {
	const fs = require( 'fs' );
	const tagDir = `${options['svn-dir']}/tags/${currentVersion}`;

	if ( fs.existsSync( tagDir ) ) {
		const promptMessage = `Tag ${currentVersion} already exists. Overwrite directory contents?`;
		const readLine = require( 'readline-sync' );
		const response = readLine.keyInYN( promptMessage );
		if ( !response ) {
			console.warn( 'Skipping because tag already exists.' );
			done();
			return;
		}
	}

	return src( [ `./build/${packageJSON.name}/**/*` ] )
		.pipe( dest( tagDir ) );
}

// Create individual tasks.
exports['check-textdomain'] = checkTextDomain;
exports['replace:version'] = replaceVersion;
exports['release:patch'] = series( getVersionBump('patch'), replaceVersion );
exports['release:minor'] = series( getVersionBump('minor'), replaceVersion );
exports['release:major'] = series( getVersionBump('major'), replaceVersion );
exports.i18n = series( parallel( cleanPot, exports['check-textdomain'] ), makePot );
exports.icons = series( cleanIcons, compileSvg );
exports.styles = series( cleanStyles, compileSass, minifyCss );
exports.scripts = series( cleanScripts, parallel( compileBlocks, compileScripts ), minimizeScripts );
exports.assets = parallel( exports.styles, exports.scripts );
exports.default = parallel( exports.i18n, exports.assets );
exports.build = series( exports.default, build );

// Set up conditional tasks.
const svnTask = options['svn-tag'] ? parallel( svnTrunk, svnTag ) : svnTrunk;
const buildTask = options['existing-build'] ? svnTask : series( this.build, svnTask );
exports.svn = parallel( svnAssets, buildTask );
