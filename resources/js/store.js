import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    loader: true,
    submitting: false
  },
  mutations: {
    showLoader(state) {
      state.loader = true
    },
    hideLoader(state) {
      state.loader = false
    },
    showSubmitting(state) {
      state.submitting = true
    },
    hideSubmitting(state) {
      state.submitting = false
    }
  },
  getters: {
    loader: state => {
      return state.loader
    },
    submitting: state => {
      return state.submitting
    }
  }
})
