module.exports = {
  root: true,
  env: {
    browser: true,
  },
  extends: ['standard', 'plugin:prettier/recommended'],
  plugins: ['prettier'],
  rules: {
    'no-console': 'off',
    'no-var': 'error',
    'no-unused-vars': 'off',
    camelcase: 'off',
    quotes: [2, 'single'],
    curly: [2, 'all'],
    'prettier/prettier': [
      'error',
      {
        singleQuote: true,  // Prettier enforces single quotes
        trailingComma: 'none',
      },
    ],
  },
  ignorePatterns: [
    'node_modules/',
    'vendor/',
    '*.min.js',
    'lib/**',
    '*jquery*',
    'common/css/',
    'common/dialog/',
    'common/font/',
    'common/image/',
    'common/plugin/',
    'common/js/lightbox/',
    'common/js/nav/',
    'common/js/slick/',
    'csv/',
    'debug/',
    'excel/',
    'image/',
    'migration/',
    'mock/',
    'nbproject/',
    'pdf',
    'tool/',
    'upload/',
  ],
};
