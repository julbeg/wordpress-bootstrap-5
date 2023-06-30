const terser = require('@rollup/plugin-terser');

const rollupConfig = {
  input: './assets/src/js/main.js',
  output: {
    format: 'iife',
    file: './assets/js/theme.js'
  },
  plugins: [terser()]
};

module.exports = rollupConfig;