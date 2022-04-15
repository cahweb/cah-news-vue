const { defineConfig } = require('@vue/cli-service')
module.exports = defineConfig({
  transpileDependencies: true,
  productionSourceMap: false,
  publicPath: 'localhost/wordpress/',
  outputDir: './dist',
  configureWebpack: {
    devServer: {
      contentBase: '/wp-content/plugins/cah-news-vue/dist/',
      allowedHosts: ['localhost/wordpress'],
      headers: {
        'Access-Control-Allow-Origin': '*',
      },
    },
    output: {
      filename: 'js/cah-news-vue-[name].js',
      chunkFilename: 'js/cah-news-vue-chunk-[name].js',
    }
  },
  css: {
    extract: {
      filename: 'css/cah-news-vue.css',
      chunkFilename: 'css/chunk-cah-news-vue.css',
    },
    loaderOptions: {
      sass: {
        implementation: require('sass'),
      }
    }
  }
})
