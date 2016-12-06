const webpack      = require('webpack');
const elixir       = require('laravel-elixir');
const livereload   = require('gulp-livereload');
const gulp         = require('gulp');
const inProduction = elixir.config.production;

require('laravel-elixir-vue-2');

gulp.on('task_start', function (e) {
    if (e.task === 'watch') {
        livereload.listen();
    }
});

gulp.task('watch-lr-css', function () {
    livereload.changed('app.css');
});

elixir(mix => {
    mix.sass('app.scss', 'public/css')
    .webpack(
        'app.js',					// entry point
        './public/js',				// output directory
        './resources/assets/js/', 	// base directory
        {							// custom config
            plugins: [
                // fix moment.js locale import
                new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
            ],
            module: {
                loaders: [{
                    test: /\.json$/,
                    loader: 'json'
                }]
            }
        }
    );

    if (inProduction) {
        mix.copy('node_modules/mdi/fonts', 'public/fonts')
        .copy('resources/assets/images', 'public/images');
    }
});
