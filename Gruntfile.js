module.exports = function (grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON("package.json"),

		// configured a task

		concat:{
			js:{
				src: ['js/vendor/jquery.min.js','js/vendor/bootstrap.min.js','js/vendor/underscore-min.js','js/vendor/en-GB.js','js/vendor/*.js','js/vendor/**/*.js', 'js/**/*.js'],
				dest: 'dev/main.js'
			},
			css:{
				src: ['css/vendor/**/*.css', 'css/*.css'],
				dest: 'dev/main.css'
			}
		},


		watch: {
			js: {
				files: ['<%= concat.js.src %>'],
				tasks: ['concat:js']
			},
			css: {
				files: ['<%= concat.css.src %>'],
				tasks: ['concat:css']
			}
		}
	});

	// loaded a task from npm
	grunt.loadNpmTasks("grunt-contrib-concat");
	grunt.loadNpmTasks("grunt-contrib-watch");

	//set up workflow
	grunt.registerTask("default", ["concat", "watch"]);
};