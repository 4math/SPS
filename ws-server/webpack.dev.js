const path = require("path");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

module.exports = {
  entry: {
    app: "./src/app/app.ts",
    client: "./src/app/client.ts",
  },
  output: {
    filename: "[name].bundle.js",
    path: path.resolve(__dirname, "dist/dev"),
  },
  devtool: "inline-source-map",
  target: "node",
  mode: "development",
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: "ts-loader",
        exclude: /node_modules/,
      },
    ],
  },
  plugins: [new CleanWebpackPlugin()],
  optimization: {
    minimize: false,
  },
  externals: {
    uws: "uws",
  },
  resolve: {
    extensions: [".tsx", ".ts", ".js"],
  },
  node: {
    fs: "empty",
  },
  stats: "errors-only",
};
