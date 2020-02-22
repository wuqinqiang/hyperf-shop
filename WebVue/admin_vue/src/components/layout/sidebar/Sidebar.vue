
<template>
  <div>
    <Sider hide-trigger class="sidebar">
      <Menu
        :open-names="[
					'/user',
					'/order',
					'/commodity',
					'/activity',
					'/market',
					'/system'
				]"
        class="w200"
        theme="dark"
      >
          <title>都是一场梦</title>
        <MenuItem name="/home" to="/">
          <Icon type="ios-home" size="18"/>
          首页
        </MenuItem>
        <Submenu class="w200" name="/user">
          <template slot="title">
            <Icon type="ios-people" size="20"/>
            用户管理
          </template>
          <MenuItem name="/user/adminUser" to="/user/adminUser">后台用户</MenuItem>
        </Submenu>
        <Submenu class="w200" name="/order">
          <template slot="title">
            <Icon type="ios-paper" size="18"/>
            订单管理
          </template>
          <MenuItem name="/order/commodity" to="/order/commodity"
          >商品订单
          </MenuItem
          >
        </Submenu>
        <Submenu class="w200" name="/commodity">
          <template slot="title">
            <Icon type="ios-cart" size="20"/>
            商品管理
          </template>
          <MenuItem name="/commodity/manage" to="/commodity/manage"
          >商品管理
          </MenuItem
          >
          <MenuItem name="/commodity/category" to="/commodity/category"
          >分类管理
          </MenuItem
          >
        </Submenu>

        <Submenu class="w200" name="/activity">
          <template slot="title">
            <Icon type="ios-photos" size="18"/>
            首页管理
          </template>
          <MenuItem name="/activity/menuactive" to="/activity/menuactive"
          >首页商品管理
          </MenuItem
          >
          <MenuItem name="/activity/menu" to="/activity/menu"
          >首页类型管理
          </MenuItem
          >
          <MenuItem name="/activity/banner" to="/activity/banner"
          >轮播图管理
          </MenuItem
          >
        </Submenu>

        <Submenu class="w200" name="/system">
          <template slot="title">
            <Icon type="ios-settings" size="20"/>
            系统管理
          </template>

          <MenuItem name="/system/persmission" to="/system/permission">权限列表</MenuItem>
          <MenuItem name="/system/role" to="/system/role">角色列表</MenuItem>
        </Submenu>
      </Menu>
    </Sider>
  </div>
</template>

<script>
  import {Sider, Menu, MenuItem, Icon, Submenu} from 'iview'

  export default {
    components: {
      Sider,
      Menu,
      MenuItem,
      Icon,
      Submenu
    }
  }
</script>

<style></style>
