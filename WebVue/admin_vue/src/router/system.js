import System from '../views/system/System.vue'

export default {
	path: 'system',
	component: System,
	meta: {
		requireAuth: true
	},
	children: [
    {
      path: 'permission',
      component: () => import('../views/system/menuagent/permission.vue')
    },
    {
      path: 'role',
      component: () => import('../views/system/menuagent/Role.vue')
    }
	]
}
