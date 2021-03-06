// Imports
// -- Dependencies
import Vue from 'vue'
import lodash from 'lodash'
import Axios from 'axios'
import store from './store'
import { mapActions, mapGetters } from 'vuex'

// -- File imports
import './components'

// Let's instantiate Axios
window.axios = Axios
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// Let's configure axios
const token = document.head.querySelector('meta[name="csrf-token"]')

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
} else {
  console.error(
    'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
  )
}

// Bind lodash to window
window.lodash = lodash

// Bind Vue to window
window.Vue = Vue
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('access', require('./components/Access.vue'))
Vue.component('logo', require('./components/Logo.vue'))
Vue.component('keys-view', require('./components/views/Keys-view.vue'))

// Icons
Vue.component('user-icon', require('./components/icons/User-icon.vue'))
Vue.component('lesson-icon', require('./components/icons/Lesson-icon.vue'))
Vue.component('key-icon', require('./components/icons/Key-icon.vue'))
Vue.component('file-icon', require('./components/icons/File-icon.vue'))
Vue.component('science-icon', require('./components/icons/Science-icon.vue'))

// Elements
Vue.component(
  'approve-user-tile',
  require('./components/elements/Approve-user-tile.vue')
)
Vue.component(
  'approval-list',
  require('./components/elements/Approval-list.vue')
)
Vue.component('lesson-tile', require('./components/elements/Lesson-tile.vue'))
Vue.component(
  'toast-message',
  require('./components/elements/Toast-message.vue')
)

// Views
Vue.component('editor-view', require('./components/views/Editor-view.vue'))
Vue.component('planner-view', require('./components/views/Planner-view.vue'))

const app = new Vue({
  el: '#rosa-app',
  store,
  computed: {
    ...mapGetters({
      editor: 'editor',
      lessons: 'lessons',
    }),
  },
  methods: {
    ...mapActions({
      toggleEditor: 'toggleEditor',
      editLesson: 'editLesson',
      getLessons: 'getLessons',
    }),
    toggleView(view) {
      this.views[view] = !this.views[view]
    },
    homeClick() {
      if (this.editor.active) {
        return this.toggleEditor(false)
      }

      return (window.location = `${Rosa.url}/home`)
    },
  },
  mounted() {
    this.getLessons()
  },
})
