import axios from 'axios'
import {Message} from 'iview'
import router from './router'
import store from './store'
import {getCookie} from './util/cookie'
import {server} from './config/server'

const http = axios.create({
  baseURL: server,
  timeout: 5000
})

http.interceptors.request.use(
  config => {
    const token = getCookie('access_token')
    if (token) {
      config.headers['Authorization'] = 'Bearer ' + token
    }
    return config
  },
  error => {
    return Promise.reject(error.response)
  }
)

http.interceptors.response.use(
  response => {
    Message.destroy()
    if (response.data.code === 400) {
      Message.error(response.data.message)
      return Promise.reject(response.data.message)
    }
    return response.data
  },
  error => {
    Message.destroy()
    console.log(error.response)
    if (error.response) {
      const err = error.response
      switch (err.status) {
        case 401:
          if (err.data === 'Client authentication failed') {
            Message.error('用户名或密码错误')
          } else {
            store.dispatch('deleteLoginStatus')
            Message.error('登录信息失效，请重新登录')
            router.replace({
              path: '/login',
              query: {
                redirect: router.currentRoute.fullPath
              }
            })
          }
          break
        case 403:
          Message.error('权限不足')
          break
        default:
          if (err.data.error) {
            Message.error(err.data.error)
          } else if (err.data.errors) {
            const errorMessages = Object.values(err.data.errors)
            errorMessages.forEach(item => {
              item.forEach(subItem => {
                Message.error(subItem)
              })
            })
          } else {
            Message.error(err.data.message)
          }
          break
      }
      return Promise.reject(err)
    } else {
      Message.error('无法连接到服务器，请检查您的网络')
      return Promise.reject(error)
    }
  }
)

export default http
