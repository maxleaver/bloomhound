let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js/')
  .extract(['axios', 'buefy', 'bulma', 'moment', 'vue'], `public/js/vendor.js`)
  .sourceMaps()
  .sass('resources/assets/sass/app.scss', 'public/css/');

if (mix.config.inProduction) {
  mix.version();
}

if (process.env.NODE_ENV === 'development') {
  mix.webpackConfig({
    module: {
      rules: [
        {
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          enforce: 'pre',
          exclude: /node_modules/,
          options: {
            formatter: require('eslint-friendly-formatter')
          }
        },
      ],
    },
  });
}
