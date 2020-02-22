import * as types from './mutation-types'
import http from '@/http'

export default {
  state: {
    categoryList: undefined,
    productList: {
      data: undefined,
      meta: {}
    }
  },
  mutations: {
    [types.GET_CATEGORY_LIST](state, payload) {
      state.categoryList = payload
    },
    [types.GET_PRODUCT_LIST](state, { data, meta }) {
      state.productList.data = data.data
      state.productList.meta = {"current_page":data.current_page,'per_page':data.per_page,"total":data.total}
    }
  },
  actions: {
    async getCategoryList({ commit }) {
      const res = await http.get('/category')
      res && commit(types.GET_CATEGORY_LIST, res.data)
    },
    async getProductList({ commit }, params) {
      const res = await http.get('/product', { params })
      res && commit(types.GET_PRODUCT_LIST, res)
    }
  }
}
