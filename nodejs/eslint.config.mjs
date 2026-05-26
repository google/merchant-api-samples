import gts from 'gts';
import globals from 'globals';

export default [
  ...gts,
  {
    ignores: [
      'node_modules/**',
      '.prettierrc.js',
      'eslint.config.mjs',
      'tests/build.sh'
    ]
  },
  {
    languageOptions: {
      ecmaVersion: 2022,
      sourceType: 'commonjs',
      globals: {
        ...globals.node
      }
    }
  }
];
