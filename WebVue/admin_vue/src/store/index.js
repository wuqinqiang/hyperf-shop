import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

import app from './app'
import activity from './activity'
import system from './system'
import commodity from './commodity'
import order from './order'
import user from './user'

const store = new Vuex.Store({
  state: {},
  mutations: {},
  actions: {},
  modules: { app, activity, system, commodity, order, user }
})

export default store
