require('./bootstrap')
window.Vue = require('vue')

import VueBootstrapTypeahead from 'vue-bootstrap-typeahead'
import 'bootstrap/scss/bootstrap.scss'
import VueSelect from 'vue-cool-select'

Vue.prototype.convertCurrency = (val, useRate = false) => {
  val = useRate ? val / 1 : val
  return new Intl.NumberFormat('en-US', {maximumFractionDigits: 0}).format(val)
}

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
Vue.component('homestay', require('./components/Homestay.vue').default)
Vue.component('card-homestay', require('./components/cardHomestay').default)
Vue.component('pagination', require('laravel-vue-pagination'))
Vue.component('star-rating', require('vue-star-rating').default)
Vue.component('FormSearch', require('./components/FormSearch').default)
Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead)

Vue.use(VueSelect, {
  theme: 'bootstrap' // or 'material-design'
})
const app = new Vue({
  el: '#app'
})

$(document).ready(function(){
  $('.pop-trigger').click(function(e){
    let target = $(this).data('pop')
    $(target).show()
  })
  $(document).mouseup(function(e){
    let container = $('.pop')
    if(!container.is(e.target) && container.has(e.target).length === 0){
      container.hide()
    }
  })
})