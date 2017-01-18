process.env.DISABLE_NOTIFIER = true;

const webpack      = require('webpack');
const elixir       = require('laravel-elixir');
const inProduction = elixir.config.production;

require('laravel-elixir-vue-2');

elixir(mix => {
    mix.sass('app.scss', 'public/css')
    .sass('admin/admin.scss', 'public/css')
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
    } else {
        mix.browserSync({
            proxy: {
                target: 'https://soapbox.localhost'
            }
        });
    }
});
