import Commodity from '../views/commodity/Commodity.vue'

export default {
  path: 'commodity',
  component: Commodity,
  meta: {
    requireAuth: true
  },
  children: [
    {
      path: 'manage',
      component: () => import('../views/commodity/manage/CommodityManage.vue')
    },
    {
      path: 'category',
      component: () => import('../views/commodity/category/Category.vue')
    },
    {
      path: 'create',
      component: () => import('../views/commodity/manage/AddCommodity.vue')
    },
    {
      path: 'edit/:id',
      component: () => import('../views/commodity/manage/EditCommodity.vue'),
      props: true
    },
    {
      path: 'detail/:id',
      component: () => import('../views/commodity/manage/CommodityDetail.vue'),
      props: true
    }
  ],
  redirect: '/commodity/manage'
}
