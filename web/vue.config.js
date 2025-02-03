module.exports = {
  pages: {
    index: {
      title: 'Slim',
      entry: 'src/main.js',
      template: 'public/index.html',
      filename: 'index.html',
    }
  },
  transpileDependencies: [
    'vuetify'
  ]
}
