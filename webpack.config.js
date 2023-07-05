const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const CompressionPlugin = require("compression-webpack-plugin");

module.exports = {
    entry: path.resolve(__dirname, "src/index.js"),
    output: {
        path: path.resolve(__dirname, "dist"),
        filename: "test.bundle.js"
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                use: {
                    loader: "babel-loader"
                }
            },
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    {
                        loader: "style-loader"
                    },
                    {
                        loader: "css-loader"
                    },
                    {
                        loader: "postcss-loader"
                    },
                    {
                        loader: "sass-loader"
                    }
                ]
            },
            {
                test: /\.(png|jpe?g|gif|svg)$/,
                use: [
                    {
                        loader: "file-loader"
                    }
                ]
            }
        ]
    },
    plugins: [
        new CleanWebpackPlugin(),
        new CompressionPlugin({
            filename: "[path].br[query]",
            algorithm: "brotliCompress",
            test: /\.(js|css|html|svg)$/,
            compressionOptions: { level: 11 },
            threshold: 10240,
            minRatio: 0.8,
            deleteOriginalAssets: false
        }),
        new HtmlWebpackPlugin({
            template: "./src/index.html",
            hash: true
        })
    ],
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                test: /\.js$/i,
                terserOptions: {
                    mangle: true
                }
            })
        ]
    },
    mode: "development",
    devServer: {
        static: path.resolve(__dirname, "./dist"),
        open: true,
        watchFiles: ['./src/**'],
        hot: true,
        port: 4000
    }
};