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
  .extract(['axios', 'buefy', 'bulma', 'moment', 'moment-timezone', 'vue', 'vuex'])
  .sass('resources/assets/sass/app.scss', 'public/css/')
  .sourceMaps();

if (mix.config.inProduction) {
  mix.version();
  mix.disableNotifications();
}

var config = {
  resolve: {
    alias:{
      components: path.resolve(__dirname, 'resources/assets/js/components/'),
      helpers: path.resolve(__dirname, 'resources/assets/js/helpers/'),
      mixins: path.resolve(__dirname, 'resources/assets/js/mixins/'),
    },
    extensions: ['*', '.js', '.vue']
  }
};

if (process.env.NODE_ENV === 'development') {
  Object.assign(config, {
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

mix.webpackConfig(config);
