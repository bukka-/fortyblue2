module.exports = function (grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON("package.json"),

		// configured a task

		concat:{
			js:{
				src: ['app/app.js','app/controllers/**/*.js','app/directives/**/*.js'],
				dest: 'dev/app.js'
			}
		},

		watch: {
			js: {
				files: ['<%= concat.js.src %>'],
				tasks: ['concat:js']
			}
		}
	});

	// loaded a task from npm
	grunt.loadNpmTasks("grunt-contrib-concat");
	grunt.loadNpmTasks("grunt-contrib-watch");

	//set up workflow
	grunt.registerTask("default", ["concat", "watch"]);
};