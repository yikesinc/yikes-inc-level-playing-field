// Require our dependencies
const bourbon     = require( 'bourbon' ).includePaths;
const browserSync = require( 'browser-sync' );
const cheerio     = require( 'gulp-cheerio' );
const del         = require( 'del' );
const eslint      = require( 'gulp-eslint' );
const gutil       = require( 'gulp-util' );
const imagemin    = require( 'gulp-imagemin' );
const merge       = require( 'gulp-merge' );
const minimist    = require( 'minimist' );
const mqpacker    = require( 'css-mqpacker' );
const neat        = require( 'bourbon-neat' ).includePaths;
const notify      = require( 'gulp-notify' );
const plumber     = require( 'gulp-plumber' );
const postcss     = require( 'gulp-postcss' );
const rename      = require( 'gulp-rename' );
const sassLint    = require( 'gulp-sass-lint' );
const sort        = require( 'gulp-sort' );
const sourcemaps  = require( 'gulp-sourcemaps' );
const svgmin      = require( 'gulp-svgmin' );
const svgstore    = require( 'gulp-svgstore' );
const debug       = require( 'gulp-debug' );
const webpack     = require( 'webpack-stream' );
const uglifyWpack = require( 'uglifyjs-webpack-plugin' );
const {
	series, parallel, task, src, dest, watch
} = require( 'gulp' );

// Environment variables.
const gitKey = process.env.gitKey;

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
		'assets/css/*.css',
		'assets/js/**/*.js',
		'assets/images/**',
		'assets/vendor/**',
		'languages/*',
		'src/**/*.php',
		'vendor/awesome-yikes-framework/**',
		'views/**/*.php',
		'*.php',
		'LICENSE.txt',
		'readme.*',
		'!assets/js/dev/*.js'
	]
};

// Command line options
const options = minimist( process.argv.slice( 2 ), {
	string: [ 'version', 'release', 'preid' ],
	default: {
		// A custom version value.
		version: '',

		// This is the type of release to create when running the generic release task.
		release: 'minor',

		// For pre-releases, use this parameter. "beta", "rc", etc.
		preid: undefined,
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
	const sass = require( 'gulp-sass' ), autoprefixer = require( 'autoprefixer' );
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
				'browsers': [ 'last 2 version' ]
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
	return src( paths.css )
		.pipe( plumber( { 'errorHandler': handleErrors } ) )
		.pipe( cssnano( {
			// Use safe optimizations.
			'safe': true
		} ) )
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
task( 'imagemin', () => src( paths.images )
	.pipe( plumber( { 'errorHandler': handleErrors } ) )
	.pipe( imagemin( {
		'optimizationLevel': 5,
		'progressive': true,
		'interlaced': true
	} ) )
	.pipe( dest( 'assets/images' ) ) );

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
	const babel = require( 'gulp-babel' );
	return src( paths.devscripts )
		.pipe( plumber( { errorHandler: outputErrors } ) )
		.pipe( sourcemaps.init() )
		.pipe( babel( {
			"presets": [ [ "env", {
				"targets": {
					"browsers": [ "last 2 versions" ]
				}
			} ] ]
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
	const findBlocks = () => {
		const glob = require( 'glob' );
		const path = require( 'path' );
		const globs = glob.sync( './blocks/**/index.js' );
		let blocks = {};
		globs.map( ( glob ) => {
			const name = path.basename( path.dirname( glob ) );
			blocks[ name ] = glob;
		} );

		return blocks;
	};

	return src( paths.blocks )
		.pipe( named() )
		.pipe( webpack( {
			entry: findBlocks(),
			output: {
				filename: '[name]/index.js'
			},
			devtool: 'cheap-eval-source-map',
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
	return del( [ 'languages/yikes-level-playing-field.pot' ] );
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
			'writeFile': false,
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
		'!assets/js/yikes-level-playing-field.js',
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
 * Task for replacing the version in all PHP files.
 */
task( 'replace:version', () => {
	return replaceVersion();
} );

/**
 * Bump the version in various files.
 *
 * @returns {*}
 */
function bumpVersion() {
	const bump = require( 'gulp-bump' );

	return src( [ './yikes-level-playing-field.php', './package.json' ] )
		.pipe( plumber( { 'errorHandler': outputErrors } ) )
		.pipe( bump( { version: currentVersion } ) )
		.pipe( dest( './' ) );
}

/**
 * Tasks for updating versions in preparation for a new release.
 */
task( 'release:patch', () => {
	throw new Error('This task is not yet ready for use.');
	const semver = require( 'semver' );
	currentVersion = semver.inc( packageJSON.version, 'patch' );

	return merge( replaceVersion(), bumpVersion() );
} );
task( 'release:minor', () => {
	throw new Error( 'This task is not yet ready for use.' );
	const semver = require( 'semver' );
	currentVersion = semver.inc( packageJSON.version, 'minor' );
	bumpVersion();

	return replaceVersion();
} );
task( 'release:major', () => {
	throw new Error( 'This task is not yet ready for use.' );
	return merge( bumpVersion( 'major' ), gulp.run( 'replace:version' ) );
} );

/**
 * General release task. Use this when creating pre-release versions, making
 * use of the --release and --preid CLI flags.
 *
 * --release refers to a SemVer release type. Use "preminor" or "premajor".
 * --preid refers to the type of pre-release. Use "beta" or "rc".
 */
task( 'release', () => {
	throw new Error( 'This task is not yet ready for use.' );
	return merge( bumpVersion( options.release, options.preid ), gulp.run( 'replace:version' ) );
} );

/**
 * Create individual tasks.
 */
// task( 'markup', browserSync.reload );
// task( 'i18n', [ 'wp-pot' ] );
// task( 'icons', [ 'svg' ] );
// task( 'scripts', [ 'webpack' ] );
// task( 'lint', [ 'sass:lint', 'js:lint' ] );
// task( 'docs', [ 'sassdoc' ] );
// task( 'assets', [ 'styles', 'scripts' ] );
// task( 'default', [ 'i18n', 'assets' ] );

exports['check-textdomain'] = checkTextDomain;
exports.i18n = series( parallel( cleanPot, exports['check-textdomain'] ), makePot );
exports.icons = series( cleanIcons, compileSvg );
exports.styles = series( cleanStyles, compileSass, minifyCss );
exports.scripts = series( cleanScripts, parallel( compileBlocks, compileScripts ), minimizeScripts );
exports.assets = parallel( exports.styles, exports.scripts );
exports.default = parallel( exports.i18n, exports.assets );
exports.build = series( exports.default, build );
