import Order from '../views/order/Order.vue'

export default {
  path: 'order',
  component: Order,
  meta: {
    requireAuth: true
  },
  children: [
    {
      path: 'commodity',
      component: () => import('../views/order/menuorder/Commodity.vue')
    },
    {
      path: 'commodity/:id',
      component: () => import('../views/order/menuorder/CommodityDetail.vue')
    },
  ],
  redirect: '/order/commodity'
}
