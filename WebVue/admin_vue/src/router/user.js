import User from '../views/user/User.vue'
export default {
	path: 'user',
	component: User,
	meta: {
		requireAuth: true
	},
	children: [
    {
      path: 'adminUser',
      component: () => import('../views/user/menuuser/AdminUser.vue')
    },
	],
	redirect: '/user/common'
}
