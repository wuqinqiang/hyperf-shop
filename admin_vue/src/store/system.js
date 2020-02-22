import * as types from './mutation-types'
import http from '@/http'

export default {
  state: {
    permissionList: undefined,
    permissionMeta: {},
    roleList: undefined
  },
  mutations: {
    [types.GET_PERMISSION_LIST](state, {data, meta}) {
      state.permissionList = data.data
      state.permissionMeta = {"current_page": data.current_page, 'per_page': data.per_page, "total": data.total}
    },
    [types.GET_ROLE_LIST](state, {data, meta}) {
      state.roleList = data.data
      //state.permissionMeta = {"current_page":data.current_page,'per_page':data.per_page,"total":data.total}
    },
  },
  actions: {
    getPermissionList({commit}) {
      return http.get('/permission').then(res => {
        commit(types.GET_PERMISSION_LIST, res)
      })
    },
    getRoleList({commit}) {
      return http.get('/role').then(res => {
        commit(types.GET_ROLE_LIST, res)
      })
    },
  }
}
