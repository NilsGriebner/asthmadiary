const path = require('path');
const webpack = require('webpack');
const {VueLoaderPlugin} = require('vue-loader');
const MomentLocalesPlugin = require('moment-locales-webpack-plugin');
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = {
	entry: path.join(__dirname, 'src', 'main.js'),
	output: {
		path: path.resolve(__dirname, './js'),
		publicPath: '/js/',
		filename: 'asthmadiary.js',
		chunkFilename: 'chunks/[name].js'
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: "babel-loader"
				}
			},
			{
				test: /\.vue$/,
				loader: 'vue-loader'
			},
			{
				test: /\.css$/,
				loader: ['vue-style-loader', 'css-loader']
			}
		]
	},
	plugins: [
		new VueLoaderPlugin(),
		new MomentLocalesPlugin({
			localesToKeep: ['de'],
		}),
		new BundleAnalyzerPlugin(),
	],
	resolve: {
		alias: {
			Components: path.resolve(__dirname, 'src/components/'),
			Models: path.resolve(__dirname, 'src/Models/'),
			Services: path.resolve(__dirname, 'src/services/'),
			Store: path.resolve(__dirname, 'src/store/'),
			Views: path.resolve(__dirname, 'src/views/'),
		},
		extensions: ['*', '.js', '.vue', '.json']
	},
};