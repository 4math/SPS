module.exports = {
    runtimeCompiler: true,
    css: {
        loaderOptions: {
            scss: {
                // prependData: `@import "~@/scss/_variables.scss";`
            }
        }
    },
    devServer: {
        overlay: {
            warnings: true,
            errors: true
        }
    }
}