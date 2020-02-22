// 设置cookie
export const setCookie = (name, value, seconds) => {
  let expires = ''
  if (!seconds) {
    seconds = 30 * 24 * 60 * 60 // 默认30天
  }
  const d = new Date()
  d.setTime(d.getTime() + seconds * 1000)
  expires = 'expires=' + d.toUTCString() + ';'
  document.cookie = name + '=' + value + ';' + expires + 'path=/'
}

// 获取cookie
export const getCookie = name => {
  const searchStr = name + '='
  let res = ''
  if (document.cookie.length > 0) {
    let sd = document.cookie.indexOf(searchStr)
    if (sd !== -1) {
      sd += searchStr.length
      let end = document.cookie.indexOf(';', sd)
      if (end === -1) end = document.cookie.length
      res = unescape(document.cookie.substring(sd, end))
    }
  }
  return res
}

// 删除cookie
export const delCookie = name => {
  let exp = new Date()
  exp.setTime(exp.getTime() - 1)
  let val = getCookie(name)
  if (val != null) {
    document.cookie = name + '=' + val + ';expires=' + exp.toUTCString()
  }
}
