'use strict';
module.exports = function(grunt) {

  grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		// js minification
    uglify: {
      dist: {
        files: {
          // admin scripts
          'admin/js/min/yikes-inc-level-playing-field-admin.min.js': [ // all other admin scripts
            'admin/js/yikes-inc-level-playing-field-admin.js',
          ],
					// admin metabox script
          'admin/js/min/yikes-inc-level-playing-field-metabox-scripts.min.js': [ // all other admin scripts
            'admin/js/yikes-inc-level-playing-field-metabox-scripts.js',
          ],
					// public scripts
          'public/js/min/yikes-inc-level-playing-field-public.min.js': [ // all other admin scripts
            'public/js/yikes-inc-level-playing-field-public.js',
          ],
					// footable script
          'public/js/min/footable.min.js': [
            'public/js/footable.js',
						'public/js/footable-init.js',
          ],
					// lity lightbox script
          'public/js/min/lity.min.js': [
            'public/js/lity.js',
          ],
        }
      }
    },

    // Autoprefixer for our CSS files
    postcss: {
      options: {
        map: true,
        processors: [
          require('autoprefixer-core') ({
            browsers: ['last 2 versions']
          })
        ]
      },
      dist: {
        src: ['admin/css/*.css', 'public/css/*.css']
      }
    },
    auto_install: {
      local: {}
    },

    // css minify all contents of our directory and add .min.css extension
    cssmin: {
      target: {
        files: [
          // admin css files
          {
						'admin/css/min/yikes-inc-level-playing-field-admin.min.css':
						[
							'admin/css/yikes-inc-level-playing-field-admin.css',
						],
          },
					// admin metabox styles
          {
						'admin/css/min/yikes-inc-level-playing-field-metabox-styles.min.css':
						[
							'admin/css/yikes-inc-level-playing-field-metabox-styles.css',
						],
          },
					// public css files
          {
						'public/css/min/yikes-inc-level-playing-field-public.min.css':
						[
							'public/css/yikes-inc-level-playing-field-public.css',
							'public/css/yikes-inc-level-playing-field-applicant-messenger.css',
						],
          },
					// FooTable bootstrap css
          {
						'public/css/min/footable.bootstrap.min.css':
						[
							'public/css/footable.bootstrap.css',
						],
          },
					// FooTable standalone css
          {
						'public/css/min/footable.standalone.min.css':
						[
							'public/css/footable.standalone.css',
						],
          },
					// lity css
          {
						'public/css/min/lity.min.css':
						[
							'public/css/lity.css',
						],
          }
        ]
      }
    },

		// Generate a nice banner for our css/js files
		usebanner: {
	    taskName: {
	      options: {
	        position: 'top',
					replace: true,
	        banner: '/*\n'+
						' * @Plugin <%= pkg.title %>\n' +
						' * @Author <%= pkg.author %>\n'+
						' * @Site <%= pkg.site %>\n'+
						' * @Version <%= pkg.version %>\n' +
		        ' * @Build <%= grunt.template.today("mm-dd-yyyy") %>\n'+
						' */',
	        linebreak: true
	      },
	      files: {
	        src: [
						'admin/css/min/yikes-inc-level-playing-field-admin.min.css',
						'admin/js/min/yikes-inc-level-playing-field-admin.min.js',
						'public/css/min/yikes-inc-level-playing-field-public.min.css',
						'public/js/min/yikes-inc-level-playing-field-public.min.js',
						'public/css/min/FooTable.min.css',
						'public/js/min/FooTable-init.min.js',
						'public/css/min/lity.min.css',
						'public/js/min/lity.min.js',
					]
	      }
	    }
	  },

		// Copy our template files to the root /template/ directory.
    copy: {
      main: {
        files: [
          // copy over template files into the root of the plugin
          {
            expand: true,
            flatten: true,
            src: ['public/partials/templates/*.php'],
            dest: 'templates/',
            filter: 'isFile'
          },
					// single job templates
					{
            expand: true,
            flatten: true,
            src: ['public/partials/templates/single-job/*.php'],
            dest: 'templates/single-job',
            filter: 'isFile'
          },
					// global templates
					{
            expand: true,
            flatten: true,
            src: ['public/partials/templates/global/*.php'],
            dest: 'templates/global',
            filter: 'isFile'
          },
        ],
      },
    },

    // watch our project for changes
    watch: {
      public_css: { // public css
        files: 'public/css/*.css',
        tasks: ['cssmin', 'usebanner', 'copy'],
        options: {
          spawn: false,
          event: ['all']
        },
      },
			admin_css: { // admin css
        files: 'admin/css/*.css',
        tasks: ['cssmin', 'usebanner', 'copy'],
        options: {
          spawn: false,
          event: ['all']
        },
      },
			public_js: { // public js
			 files: 'public/js/*.js',
			 tasks: ['uglify', 'usebanner', 'copy'],
			 options: {
				 spawn: false,
				 event: ['all']
			 },
		 },
		 admin_js: { // admin js
			files: 'admin/js/*.js',
			tasks: ['uglify', 'usebanner', 'copy'],
			options: {
				spawn: false,
				event: ['all']
			},
		 },
    },

  });

  // load tasks
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-banner');
	grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-postcss'); // CSS autoprefixer plugin (cross-browser auto pre-fixes)
  grunt.loadNpmTasks('grunt-auto-install'); // autoload all of ourd ependencies (ideally, you install this one package, and run grunt auto_install to install our dependencies automagically)

  // register task
  grunt.registerTask('default', [
		'uglify',
    'postcss',
    'cssmin',
		'usebanner',
		'copy',
  ]);

};
