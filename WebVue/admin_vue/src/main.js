import Vue from 'vue'
import App from './App.vue'
import 'iview/dist/styles/iview.css'
import './styles/base.less'
import 'v-charts/lib/style.css'
import { Message, Modal } from 'iview'
// 导入store
import store from './store/index'
// 导入路由
import router from './router/index'
import VeLine from 'v-charts/lib/line.common'
import VePie from 'v-charts/lib/pie.common'

Vue.component(VeLine.name, VeLine)
Vue.component(VePie.name, VePie)

Vue.prototype.$Message = Message
Vue.prototype.$Modal = Modal

Vue.config.productionTip = false

new Vue({
  store,
  router,
  render: h => h(App)
}).$mount('#app')
