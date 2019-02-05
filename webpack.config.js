let path = require('path');

module.exports = {
    entry: './js/src/index',
    output: {
        filename: 'build.js',
        path: path.resolve(__dirname, 'js/dist')
    },

    watch: true,

    watchOptions: {
        aggregateTimeout: 100
    },

    module: {
        rules: [
            {
                test: /\.js?$/,
                loader: 'babel-loader',
                exclude: /node_modules/,
                query: {
                    presets: ['react','es2015'],
                    plugins: ["transform-class-properties"]
                }
            }
        ]
    },
    // resolve: {
    //     modules: ['node_modules'],
    //     extensions: [".js", ".json", ".jsx", ".css"]
    // }
};