import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

// 导出默认组件
import Default from '../components/layout/Default'
import Home from '../views/home/Home.vue'
import User from './user'
import Order from './order'
import Commodity from './commodity'
import Activity from './activity'
import System from './system'
import Login from '../views/login/Login.vue'

import store from '../store'
import { getCookie } from '../util/cookie'
import { SET_ACTIVE_PATH } from '../store/mutation-types'

const routes = [
	{
		path: '/',
		component: Default,
		children: [
			{
				path: '/home',
				meta: {
					requireAuth: true
				},
				component: Home
			},
			User,
			Order,
			Commodity,
			Activity,
			System
		],
		redirect: '/home'
	},
	{
		path: '/login',
		component: Login
	}
]

const router = new VueRouter({
	mode: 'history',
	base: process.env.BASE_URL,
	routes,
	scrollBehavior: (to, form, savedPosition) => savedPosition || { x: 0, y: 0 }
})

router.beforeEach((to, from, next) => {
	if (to.meta.requireAuth) {
		if (getCookie('access_token')) {
			next()
		} else {
			next({
				path: '/login',
				query: { redirect: to.fullPath }
			})
		}
	} else {
		next()
	}
})

router.beforeEach((to, from, next) => {
	store.commit(SET_ACTIVE_PATH, to.path)
	next()
})

export default router
