import * as types from './mutation-types'
import http from '@/http'

export default {
  state: {
    activityList: {
      data: undefined,
      meta: {}
    },
    bannerList: undefined
  },
  mutations: {
    [types.GET_ACTICITY_LIST](state, { data, meta }) {
      state.activityList.data = data.data
      state.activityList.meta = {"current_page":data.current_page,'per_page':data.per_page,"total":data.total}
    },
    [types.GET_BANNER_LIST](state, payload) {
      state.bannerList = payload.data
    }
  },
  actions: {
    async getActivityList({ commit }, params) {
      const res = await http.get('/front_activity', { params })
      res && commit(types.GET_ACTICITY_LIST, res)
    },
    async getBannerList({ commit }) {
      const res = await http.get('/front_image')
      res && commit(types.GET_BANNER_LIST, res.data)
    }
  }
}
