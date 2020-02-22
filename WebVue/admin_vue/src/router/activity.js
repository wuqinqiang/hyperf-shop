import Activity from '../views/activity/Activity.vue'

export default {
  path: 'activity',
  component: Activity,
  meta: {
    requireAuth: true
  },
  children: [
    {
      path: 'menuactive',
      component: () => import('../views/activity/menuactivity/MenuActivity.vue')
    },
    {
      path: 'banner',
      component: () => import('../views/activity/banner/Banner.vue')
    },
    {
      path: 'menu',
      component: () => import('../views/activity/menu/Menu.vue')
    }
  ],
  redirect: '/activity/menuactive'
}
