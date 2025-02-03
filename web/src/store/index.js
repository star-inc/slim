import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    loaded: false,
    ready: false,
    user: null
  },
  mutations: {
    setLoaded(state, status) {
      state.loaded = status
    },
    setReady(state, status) {
      state.ready = status
    },
    setUser(state, user) {
      state.user = user
    }
  },
})
