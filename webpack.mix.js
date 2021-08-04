require('dotenv').config();
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.disableNotifications();
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles(['resources/css/dashboard.css',
            'resources/css/date_picker.css',
            'resources/css/dist.css',
            'resources/css/hamburgers.min.css',
            'resources/css/style.css',
            'resources/css/tables.css',
            'resources/css/app.css',
            'resources/css/vendors.css',
            'resources/css/page.css'],'public/css/all.css' )
            .browserSync({proxy:process.env.APP_URL, open:false});

console.log("Testing watch");
