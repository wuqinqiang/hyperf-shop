import * as types from './mutation-types'
import { setCookie } from '../util/cookie'
import http from '@/http'

export default {
	state: {
		activePath: '/',
		loginStatus: false,
		userInfo: localStorage.getItem('userInfo')
			? JSON.parse(localStorage.getItem('userInfo'))
			: undefined,
		statsData: undefined
	},
	mutations: {
		[types.SET_ACTIVE_PATH](state, payload) {
			state.activePath = payload
		},
		[types.SET_LOGIN_STATUS](state, payload) {
			state.loginStatus = payload
		},
		// [types.SET_USER_INFO](state, payload) {
		// 	state.userInfo = payload
		// 	console.log(state.userInfo)
		// }
		[types.GET_STATS_DATA](state, payload) {
			state.statsData = payload
		}
	},
	actions: {
		login({ commit }, { token, user }) {
			commit(types.SET_LOGIN_STATUS, true)
		//	setCookie('access_token', token.access_token, token.expires_in)
			setCookie('access_token', token.token)
			// commit(types.SET_USER_INFO, user)
		},
		async getStatsData({ commit }) {
			const res = await http.get('/home', { params: { yesterday: 1 } })
			res && commit(types.GET_STATS_DATA, res.data)
		}
	}
}
