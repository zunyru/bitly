const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

const tailwindcss = require('tailwindcss')
// Default
mix.js('resources/js/app.js' , 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/tailwind.scss', 'public/css')
    .sass('resources/sass/backend-custom.scss' , 'public/css')
    .options({
        processCssUrls: false ,
        postCss: [tailwindcss('./tailwind.config.js')] ,
    });

mix.copyDirectory('resources/assets' , 'public/assets');
mix.copyDirectory('resources/plugin' , 'public/plugin');

mix.autoload({
    jquery: ['$', 'window.jQuery']
});

if (mix.inProduction()) {
    mix.version();
}
