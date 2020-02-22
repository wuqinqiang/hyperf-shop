import * as types from './mutation-types'
import http from '@/http'

export default {
	state: {
		// 用户列表
		user: {
			list: [],
			meta: {}
		},
	},
	mutations: {
		[types.GET_USER_LIST](state, payload) {
			state.user.list = payload.data.data
			state.user.meta = {"current_page":payload.current_page,'per_page':payload.per_page,"total":payload.total}
		},
	},
	actions: {
		async getUserList({ commit }, params) {
			const result = await http.get('/user', { params })
			result && commit(types.GET_USER_LIST, result)
		},
	}
}
