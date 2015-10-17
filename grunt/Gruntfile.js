/**
 * Load Grunt
 */

module.exports = function (grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: { // Begin Sass Plugin
      dist: {
        options: {
          sourcemap: 'none'
        },
        files: {
          'src/css/style.css': 'src/scss/*.scss'
        }
      }
    },
    postcss: { // Begin Post CSS Plugin
      options: {
        map: false,
        processors: [
      require('autoprefixer')({
            browsers: ['last 2 versions']
          })
    ]
      },
      dist: {
        src: 'src/css/*.css'
      }
    },
    cssmin: { // Begin CSS Minify Plugin
      target: {
        files: [{
          expand: true,
          cwd: 'src/css',
          src: ['*.css', '!*.min.css'],
          dest: '../wp-content/themes/oblate/css',
          ext: '.min.css'
    }]
      }
    },
    uglify: { // Begin JS Minify Plugin
      build: {
        src: ['src/js/*.js'],
        dest: '../wp-content/themes/oblate/js/script.min.js'
      }
    },
    watch: { // Compile everything into one task with Watch Plugin
      css: {
        files: '**/*.scss',
        tasks: ['sass', 'postcss', 'cssmin']
      },
      js: {
        files: '**/*.js',
        tasks: ['uglify']
      }
    }
  });

  // Loading Grunt Plugins
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.registerTask('default', ['watch']);
  grunt.registerTask('dev', []);
};
