// http://eslint.org/docs/user-guide/configuring

module.exports = {
  root: true,
  parser: 'babel-eslint',
  parserOptions: {
    sourceType: 'module'
  },
  env: {
    browser: true,
  },
  extends: 'airbnb-base',
  // required to lint *.vue files
  plugins: [
    'html'
  ],
  // add your custom rules here
  'rules': {
    // don't require .vue extension when importing
    'import/extensions': ['error', 'always', {
      'js': 'never',
      'vue': 'never'
    }],
    // allow optionalDependencies
    'import/no-extraneous-dependencies': ['error', {
      'optionalDependencies': ['test/unit/index.js']
    }],
    'no-console': 0,
    // allow debugger during development
    'no-debugger': process.env.NODE_ENV === 'production' ? 2 : 0,
    'func-names': ['error', 'as-needed'],
    'object-shorthand': ['error', 'properties'],
    'no-plusplus': ['error', { 'allowForLoopAfterthoughts': true }],
    'no-param-reassign': ["error", { "props": false }],
  },
  settings: {
    'import/resolver': {
      'webpack': {
        'config': './node_modules/laravel-mix/setup/webpack.config.js',
      },
    },
  },
}
