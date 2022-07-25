const mix = require('laravel-mix');
mix.options({
    processCssUrls: false,
});

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

// Tailwind
mix.postCss("resources/assets/css/tailwind.css", "public/assets/css", [
    require("tailwindcss"),
]).version();
// Bootstrap
mix.sass('node_modules/bootstrap/scss/bootstrap.scss', 'public/assets/plugins/bootstrap').version();
mix.js('node_modules/bootstrap/dist/js/bootstrap.bundle.js', 'public/assets/plugins/bootstrap').version();

/**
 * Plugins
 */
// Moment
mix.js('resources/assets/plugins/moment-js/moment.js', 'public/assets/plugins/moment').version();

/**
 * Fonts
 */
// Fontawesome
mix.copy('node_modules/@fortawesome/fontawesome-free/css', 'public/assets/fonts/fontawesome');
mix.copy('node_modules/@fortawesome/fontawesome-free/js', 'public/assets/fonts/fontawesome');
mix.copy('node_modules/@fortawesome/fontawesome-free/svgs', 'public/assets/fonts/fontawesome');
mix.copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'public/assets/fonts/fontawesome');
mix.copy('node_modules/@fortawesome/fontawesome-free/attribution.js', 'public/assets/fonts/fontawesome');
mix.copy('node_modules/@fortawesome/fontawesome-free/LICENSE.txt', 'public/assets/fonts/fontawesome');

/**
 * DashboardKit
 */
// Images
mix.copy('resources/assets/dashboard-kit/images', 'public/assets/img/dashboard-kit');
// Style
mix.copy('resources/assets/dashboard-kit/css/style.css', 'public/assets/css/dashboard-kit').version();
mix.sass('resources/assets/dashboard-kit/css/siaji.scss', 'public/assets/css/dashboard-kit').version();
mix.copy('resources/assets/dashboard-kit/css/landing.css', 'public/assets/css/dashboard-kit').version();
mix.copy('resources/assets/dashboard-kit/css/plugins', 'public/assets/css/dashboard-kit/plugins').version();
// Fonts
mix.copy('resources/assets/dashboard-kit/fonts/cryptofont.css', 'public/assets/fonts/dashboard-kit');
mix.copy('resources/assets/dashboard-kit/fonts/feather.css', 'public/assets/fonts/dashboard-kit');
mix.copy('resources/assets/dashboard-kit/fonts/fontawesome.css', 'public/assets/fonts/dashboard-kit');
mix.copy('resources/assets/dashboard-kit/fonts/material.css', 'public/assets/fonts/dashboard-kit');
mix.copy('resources/assets/dashboard-kit/fonts/cryptocoins', 'public/assets/fonts/dashboard-kit/scryptocoins');
mix.copy('resources/assets/dashboard-kit/fonts/feather', 'public/assets/fonts/dashboard-kit/feather');
mix.copy('resources/assets/dashboard-kit/fonts/fontawesome', 'public/assets/fonts/dashboard-kit/fontawesome');
mix.copy('resources/assets/dashboard-kit/fonts/inter', 'public/assets/css/fonts/inter');
mix.copy('resources/assets/dashboard-kit/fonts/material', 'public/assets/fonts/dashboard-kit/material');
// Script
mix.copy('resources/assets/dashboard-kit/js/pcoded.js', 'public/assets/js/dashboard-kit').version();
mix.copy('resources/assets/dashboard-kit/js/script.js', 'public/assets/js/dashboard-kit').version();
mix.copy('resources/assets/dashboard-kit/js/siaji.js', 'public/assets/js/dashboard-kit').version();
mix.copy('resources/assets/dashboard-kit/js/vendor-all.min.js', 'public/assets/js/dashboard-kit').version();
mix.copy('resources/assets/dashboard-kit/js/plugins', 'public/assets/js/dashboard-kit/plugins').version();