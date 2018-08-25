// Require our dependencies
const babel = require( 'gulp-babel' );
const bourbon = require( 'bourbon' ).includePaths;
const browserSync = require( 'browser-sync' );
const cheerio = require( 'gulp-cheerio' );
const concat = require( 'gulp-concat' );
const cssnano = require( 'gulp-cssnano' );
const del = require( 'del' );
const eslint = require( 'gulp-eslint' );
const gulp = require( 'gulp' );
const gutil = require( 'gulp-util' );
const imagemin = require( 'gulp-imagemin' );
const mqpacker = require( 'css-mqpacker' );
const neat = require( 'bourbon-neat' ).includePaths;
const notify = require( 'gulp-notify' );
const plumber = require( 'gulp-plumber' );
const postcss = require( 'gulp-postcss' );
const rename = require( 'gulp-rename' );
const sassLint = require( 'gulp-sass-lint' );
const sort = require( 'gulp-sort' );
const sourcemaps = require( 'gulp-sourcemaps' );
const spritesmith = require( 'gulp.spritesmith' );
const svgmin = require( 'gulp-svgmin' );
const svgstore = require( 'gulp-svgstore' );
const uglify = require( 'gulp-uglify' );
const print = require( 'gulp-print' );

// Environment variables.
const gitKey = process.env.gitKey;

// Set assets paths.
const paths = {
	'css': [ 'assets/css/*.css', '!assets/css/*.min.css' ],
	'icons': 'assets/images/svg-icons/*.svg',
	'images': [ 'assets/images/*', '!assets/images/*.svg' ],
	'php': [ './*.php', './src/**/*.php', './views/**/*.php' ],
	'sass': 'assets/css/sass/*.scss',
	'concat_scripts': 'assets/js/concat/*.js',
	'scripts': [ 'assets/js/*.js', '!assets/js/*.min.js' ],
	'sprites': 'assets/images/sprites/*.png',
	'build': [
		'assets/css/*.css',
		'assets/js/*.js',
		'assets/images/**',
		'languages/*',
		'src/**/*.php',
		'vendor/awesome-yikes-framework/**',
		'vendor/htmlburger/**',
		'views/**/*.php',
		'*.php',
		'LICENSE.txt',
		'readme.*'
	]
};

// Load the package.json data
const packageJSON = require( './package.json' );

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
 * Delete style.css and style.min.css before we minify and optimize
 */
gulp.task( 'clean:styles', () => del( [
	'assets/css/yikes-level-playing-field.css', 'assets/css/yikes-level-playing-field.min.css'
] ) );

/**
 * Compile Sass and run stylesheet through PostCSS.
 *
 * https://www.npmjs.com/package/gulp-sass
 * https://www.npmjs.com/package/gulp-postcss
 * https://www.npmjs.com/package/gulp-autoprefixer
 * https://www.npmjs.com/package/css-mqpacker
 */
gulp.task( 'postcss', [ 'clean:styles' ], () => {
	const sass = require( 'gulp-sass' ), autoprefixer = require( 'autoprefixer' );
	return gulp.src( paths.sass, paths.css )

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
		.pipe( gulp.dest( 'assets/css' ) )
		.pipe( browserSync.stream() );
} );

/**
 * Minify and optimize style.css.
 *
 * https://www.npmjs.com/package/gulp-cssnano
 */
gulp.task( 'cssnano', [ 'postcss' ], () => gulp.src( paths.css )
	.pipe( plumber( { 'errorHandler': handleErrors } ) )
	.pipe( cssnano( {
		// Use safe optimizations.
		'safe': true
	} ) )
	.pipe( rename( {
		// Ensure .min.css extension.
		'extname': '.min.css'
	} ) )
	.pipe( gulp.dest( 'assets/css' ) )
	.pipe( browserSync.stream() ) );

/**
 * Delete the svg-icons.svg before we minify, concat.
 */
gulp.task( 'clean:icons', () => del( [ 'assets/images/svg-icons.svg' ] ) );

/**
 * Minify, concatenate, and clean SVG icons.
 *
 * https://www.npmjs.com/package/gulp-svgmin
 * https://www.npmjs.com/package/gulp-svgstore
 * https://www.npmjs.com/package/gulp-cheerio
 */
gulp.task( 'svg', [ 'clean:icons' ], () => gulp.src( paths.icons )

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
	.pipe( gulp.dest( 'assets/images/' ) )
	.pipe( browserSync.stream() ) );

/**
 * Optimize images.
 *
 * https://www.npmjs.com/package/gulp-imagemin
 */
gulp.task( 'imagemin', () => gulp.src( paths.images )
	.pipe( plumber( { 'errorHandler': handleErrors } ) )
	.pipe( imagemin( {
		'optimizationLevel': 5,
		'progressive': true,
		'interlaced': true
	} ) )
	.pipe( gulp.dest( 'assets/images' ) ) );

/**
 * Delete the sprites.png before rebuilding sprite.
 */
gulp.task( 'clean:sprites', () => {
	del( [ 'assets/images/sprites.png' ] );
} );

/**
 * Concatenate images into a single PNG sprite.
 *
 * https://www.npmjs.com/package/gulp.spritesmith
 */
gulp.task( 'spritesmith', () => {
	return gulp.src( paths.sprites )
		.pipe( plumber( { 'errorHandler': handleErrors } ) )
		.pipe( spritesmith( {
			'imgName': 'sprites.png',
			'cssName': '../../assets/sass/base/_sprites.scss',
			'imgPath': 'assets/images/sprites.png',
			'algorithm': 'binary-tree'
		} ) )
		.pipe( gulp.dest( 'assets/images/' ) )
		.pipe( browserSync.stream() );
} );

/**
 * Delete the scripts before rebuilding them.
 */
gulp.task( 'clean:scripts', () => del( [ 'assets/js/yikes-level-playing-field*.js' ] ) );

/**
 * Concatenate and transform JavaScript.
 *
 * https://www.npmjs.com/package/gulp-concat
 * https://github.com/babel/gulp-babel
 * https://www.npmjs.com/package/gulp-sourcemaps
 */
gulp.task( 'concat', [ 'clean:scripts' ], () => {
	return gulp.src( paths.concat_scripts )
		.pipe( plumber( { 'errorHandler': handleErrors } ) )

		// Start a sourcemap.
		.pipe( sourcemaps.init() )

		// Convert ES6+ to ES2015.
		.pipe( babel( { presets: [ 'latest' ] } ) )

		// Concatenate partials into a single script.
		.pipe( concat( 'yikes-level-playing-field.js' ) )

		// Append the sourcemap to project.js.
		.pipe( sourcemaps.write() )

		// Save project.js
		.pipe( gulp.dest( 'assets/js' ) )
		.pipe( browserSync.stream() );
} );

/**
 * Minify compiled JavaScript.
 *
 * https://www.npmjs.com/package/gulp-uglify
 */
gulp.task( 'uglify', [ 'concat' ], () => {
	gulp.src( paths.scripts )
		.pipe( plumber( { 'errorHandler': outputErrors } ) )
		.pipe( rename( { 'extname': '.min.js' } ) )
		.pipe( babel( { presets: [ 'latest' ] } ) )
		.pipe( uglify( { 'mangle': false } ) )
		.pipe( gulp.dest( 'assets/js' ) );
} );

/**
 * Delete the theme's .pot before we create a new one.
 */
gulp.task( 'clean:pot', () => del( [ 'languages/yikes-level-playing-field.pot' ] ) );

/**
 * Check that the text domain is present in all of our PHP files.
 */
gulp.task( 'wp-check-textdomain', () => {
	const wpCheckTextDomain = require( 'gulp-checktextdomain' );
	return gulp.src( paths.php )
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
} );

/**
 * Scan the theme and create a POT file.
 *f
 */
gulp.task( 'wp-pot', [ 'wp-check-textdomain', 'clean:pot' ], () => {
	const wpPot = require( 'gulp-wp-pot' );
	return gulp.src( paths.php )
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
		.pipe( gulp.dest( `languages/${packageJSON.name}.pot` ) );
} );

/**
 * Sass linting.
 *
 * https://www.npmjs.com/package/sass-lint
 */
gulp.task( 'sass:lint', () => {
	return gulp.src( [
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
gulp.task( 'js:lint', () => {
	return gulp.src( [
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
gulp.task( 'sassdoc', () => {
	const sassdoc = require( 'sassdoc' );
	return gulp.src( 'assets/css/sass/**/*.scss' )
		.pipe( sassdoc( { dest: 'docs' } ) );
} );

/**
 * Process tasks and reload browsers on file changes.
 *
 * https://www.npmjs.com/package/browser-sync
 */
gulp.task( 'watch', () => {

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
	// gulp.watch( paths.icons, [ 'icons' ]);
	gulp.watch( paths.sass, [ 'styles' ] );
	gulp.watch( paths.scripts, [ 'scripts' ] );
	gulp.watch( paths.concat_scripts, [ 'scripts' ] );
	// gulp.watch( paths.sprites, [ 'sprites' ]);
	gulp.watch( paths.php, [ 'markup' ] );
} );

/**
 * Build a zip file appropriate for distribution.
 */
gulp.task( 'build', [ 'default' ], function() {
	const zip = require( 'gulp-zip' );
	return gulp.src( paths.build, { base: process.cwd() } )
		.pipe( rename( function( path ) {
			// Prefix the paths with the directory name before zipping.
			path.dirname = `${packageJSON.name}/${path.dirname}`;
		} ) )

		// Pipe each file to the build directory and the Zip file
		.pipe( gulp.dest( 'build' ) )
		.pipe( zip( `${packageJSON.name}-${packageJSON.version}.zip` ) )

		// Pipe the zip file to the build directory
		.pipe( gulp.dest( 'build' ) );
} );

/**
 * Create individual tasks.
 */
gulp.task( 'markup', browserSync.reload );
gulp.task( 'i18n', [ 'wp-pot' ] );
gulp.task( 'icons', [ 'svg' ] );
gulp.task( 'scripts', [ 'uglify' ] );
gulp.task( 'styles', [ 'cssnano' ] );
gulp.task( 'sprites', [ 'spritesmith' ] );
gulp.task( 'lint', [ 'sass:lint', 'js:lint' ] );
gulp.task( 'docs', [ 'sassdoc' ] );
// gulp.task( 'default', [ 'sprites', 'i18n', 'icons', 'styles', 'scripts', 'imagemin'] );
gulp.task( 'default', [ 'i18n', 'styles', 'scripts' ] );
