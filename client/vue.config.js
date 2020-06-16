module.exports = {
  runtimeCompiler: true,
  css: {
    loaderOptions: {
      scss: {
        // prependData: `@import "~@/scss/_variables.scss";`
      },
    },
  },
  devServer: {
    overlay: {
      warnings: true,
      errors: true,
    },
  },
  configureWebpack: {
    devtool: "eval-source-map",
  },
};
