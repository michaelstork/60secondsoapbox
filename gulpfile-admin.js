process.env.DISABLE_NOTIFIER = true;

const elixir = require('laravel-elixir');

require('laravel-elixir-vue-2');

elixir(mix => {
    mix.sass('admin/admin.scss', 'public/css')
    .webpack(
        'admin/login.js',			// entry point
        './public/js/admin',    	// output directory
        './resources/assets/js/' 	// base directory
    )
    .webpack(
        'admin/dashboard.js',      // entry point
        './public/js/admin',              // output directory
        './resources/assets/js/'    // base directory
    );

    mix.browserSync({
        proxy: {
            target: 'https://soapbox.localhost/dashboard'
        }
    });
});
