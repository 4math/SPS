const path = require("path");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

module.exports = {
  entry: {
    app: "./src/app/app.ts",
    client: "./src/app/client.ts",
  },
  output: {
    filename: "[name].bundle.js",
    path: path.resolve(__dirname, "dist"),
  },
  target: "node",
  mode: "production",
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: "ts-loader",
        exclude: /node_modules/,
      },
    ],
  },
  plugins: [new CleanWebpackPlugin({ verbose: true })],
  optimization: {
    minimize: true,
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
  stats: {
      warnings: false
  }
};
