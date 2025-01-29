const path = require("path");

module.exports = {
  entry: "./src/js/main.js", // Entry file for your theme's scripts
  output: {
    filename: "main.js", // Output file
    path: path.resolve(__dirname, "dist/js"), // Output folder
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"], // For ES6+ compatibility
          },
        },
      },
      {
        test: /\.s[ac]ss$/i,
        use: [
          "style-loader", // Injects styles into the DOM
          "css-loader", // Resolves CSS imports
          "postcss-loader", // Processes Tailwind CSS with PostCSS
          "sass-loader", // Compiles Sass to CSS
        ],
      },
      {
        test: /\.css$/,
        use: [
          "style-loader",
          "css-loader",
          "postcss-loader",
        ],
      },
    ],
  },
  resolve: {
    extensions: [".js", ".scss", ".css"],
  },
  mode: "development", // Use 'development' for debugging
};
