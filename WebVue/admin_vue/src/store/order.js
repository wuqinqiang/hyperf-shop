import * as types from './mutation-types'
import http from '@/http'

export default {
  state: {
    // 订单
    order: {
      list: [],
      meta: {}
    },
    // 提现订单
    withdraw: {
      list: [],
      meta: {}
    }
  },
  mutations: {
    [types.GET_ORDER_LIST](state, payload) {
      state.order.list = payload.data
      state.order.meta = {"current_page":payload.current_page,'per_page':payload.per_page,"total":payload.total}
    },
    [types.GET_WITHDRAW](state, payload) {
      state.withdraw.list = payload.data
      state.withdraw.meta = payload.meta
    }
  },
  actions: {
    getOrderList({ commit }, params) {
      return http.get('/order', { params }).then(res => {
        commit(types.GET_ORDER_LIST, res.data)
      })
    },
    getWithdraw({ commit }, params) {
      return http.get('/withdraw', { params }).then(res => {
        commit(types.GET_WITHDRAW, res)
      })
    }
  }
}
