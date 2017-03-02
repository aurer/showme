var server = require('browser-sync');
var gulp = require('gulp');
var less = require('gulp-less');
var plumber = require('gulp-plumber');
var del = require('del');
var fs = require('fs');

var src = './assets/src';
var dist = './assets/dist';

// Compile less
gulp.task('less', function() {
	gulp.src(['docs/src/less/docs.less'])
		.pipe(plumber())
		.pipe(less())
		.pipe(gulp.dest('docs/dist/css'))
		.pipe(server.stream());

	gulp.src([`${src}/less/*.less`])
		.pipe(plumber())
		.pipe(less())
		.pipe(gulp.dest(`${dist}/css`))
		.pipe(server.stream());
});

// Watch for changes
gulp.task('watch', function() {
	gulp.watch(`${src}/less/**/*.less`, ['less']);
});

// Setup local server with injection
gulp.task('serve', function() {
	server.init({
		proxy: 'localhost:8000',
		notify: false
	});
});

// Clean the build folder
gulp.task('clean', function() {
	return del(`${dist}/*`);
});

gulp.task('default', ['clean', 'less']);

gulp.task('dev', ['default', 'watch', 'serve']);