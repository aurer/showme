var server = require('browser-sync');
var gulp = require('gulp');
var less = require('gulp-less');
var uglify = require('gulp-uglify');
var insert = require('gulp-insert');
var plumber = require('gulp-plumber');
var del = require('del');
var fs = require('fs');

var src = './assets/src';
var dist = './assets/dist';

// Compile less
gulp.task('less', function() {
	gulp.src([`${src}/less/*.less`])
		.pipe(plumber())
		.pipe(less())
		.pipe(gulp.dest(`${dist}/css`))
		.pipe(server.stream());
});

gulp.task('bookmarklets', function() {
	gulp.src([`${src}/bookmarklets/*`])
		.pipe(uglify())
		.pipe(insert.prepend('javascript:(function(){'))
		.pipe(insert.append('})();'))
		.pipe(gulp.dest(`${dist}/bookmarklets`));
});

// Watch for changes
gulp.task('watch', function() {
	gulp.watch(`${src}/less/**/*.less`, ['less']);
	gulp.watch(`${src}/bookmarklets/*.js`, ['bookmarklets']);
});

// Setup local server with injection
gulp.task('serve', function() {
	server.init({
		proxy: 'localhost:4000',
		notify: false
	});
});

// Clean the build folder
gulp.task('clean', function() {
	return del(`${dist}/*`);
});

gulp.task('default', ['clean', 'less', 'bookmarklets']);

gulp.task('dev', ['default', 'watch', 'serve']);
