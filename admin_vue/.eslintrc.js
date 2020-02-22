module.exports = {
  root: true,
  env: {
    node: true,
    es6: true
  },
  extends: ['plugin:vue/essential', '@vue/standard'],
  rules: {
    'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    'no-tabs': 'off',
    indent: [0, 'tabs'],
    'import/first': 0,
    'import/order': 0,
    'space-before-function-paren': 'off',
    'camelcase': 'off',
    'vue/no-parsing-error': 'off'
  },
  parserOptions: {
    parser: 'babel-eslint'
  }
}
