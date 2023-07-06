const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const CompressionPlugin = require("compression-webpack-plugin");

module.exports = {
    entry : {
        main: path.join(__dirname, "src/index.js"),
        inscription : path.join(__dirname, "src/inscription/inscription.js"),
        login : path.join(__dirname, "src/login/login.js"),
        todo : path.join(__dirname, "src/todo/todo.js")
    },
    output: {
        path: path.join(__dirname, "dist"),
        filename: "[name].bundle.js"
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
       /* new CleanWebpackPlugin(),
        new CompressionPlugin({
            filename: "[path].br[query]",
            algorithm: "brotliCompress",
            test: /\.(js|css|html|svg)$/,
            compressionOptions: { level: 11 },
            threshold: 10240,
            minRatio: 0.8,
            deleteOriginalAssets: false
        }),*/
        new HtmlWebpackPlugin({
            filename : "index.html",
            template: path.join(__dirname, "./src/index.html"),
            chunks : ["main"]
            //hash: true
        }),
        new HtmlWebpackPlugin({
            filename : "inscription.html",
            template: path.join(__dirname, "./src/inscription/inscription.html"),
            chunks : ["inscription"]
            //hash: true
        }),
        new HtmlWebpackPlugin({
            filename : "login.html",
            template: path.join(__dirname, "./src/login/login.html"),
            chunks : ["login"]
            //hash: true
        }),
        new HtmlWebpackPlugin({
            filename : "todo.html",
            template: path.join(__dirname, "./src/todo/todo.html"),
            chunks : ["todo"]
            //hash: true
        })
    ],
    stats: "minimal",
    devtool : "source-map",
    mode: "development",
    devServer: {
        //static: path.resolve(__dirname, "./dist"),
        open: true,
        static : path.resolve(__dirname,"./dist"),
        port: 4000
    }
};