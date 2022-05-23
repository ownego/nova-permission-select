Nova.booting((Vue, router, store) => {
  Vue.component('index-permission-select', require('./components/IndexField'))
  Vue.component('detail-permission-select', require('./components/DetailField'))
  Vue.component('form-permission-select', require('./components/FormField'))
})
