// enable vue in eslint

module.exports = {
  parser: "vue-eslint-parser",
  parserOptions: {
    parser: "babel-eslint",
  },
  plugins: ["vue"],
  extends: [
    "eslint:recommended",
    "plugin:vue/recommended",
    // "plugin:prettier/recommended",
    // "prettier/vue"
  ],
  rules: {
    "vue/max-attributes-per-line": [
      2,
      {
        singleline: 20,
        multiline: {
          max: 1,
          allowFirstLine: false,
        },
      },
    ],
  },
  env: {
    browser: true,
    amd: true,
    node: true,
  },
};
