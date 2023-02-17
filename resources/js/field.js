import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-permission-select', IndexField)
  app.component('detail-permission-select', DetailField)
  app.component('form-permission-select', FormField)
})
