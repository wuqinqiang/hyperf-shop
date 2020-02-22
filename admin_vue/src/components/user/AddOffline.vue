
<template>
  <div>
    <Modal
      :value="showModal"
      title="增加后台用户"
      @on-cancel="$emit('offlineclose')"
    >
      <div class="flex-row">
        <Input
          class="w100"
          type="text"
          v-model="name"
          placeholder="用户昵称"
        />

        <Input
          class="w100"
          type="text"
          v-model="password"
          placeholder="用户密码"
        />
      </div>
      <div class="mt10">
        <Table
          :columns="columns"
          :data="userList"
          :loading="loading"
          border
          @on-selection-change="changebox"
        >
          <template slot="id" slot-scope="{ row, index }">
            <p>
              {{ computPage(usermete.current_page, usermete.per_page, index) }}
            </p>
          </template>
          <template slot="gender" slot-scope="{ row }">
            <p v-if="row.gender === 1">男</p>
            <p v-if="row.gender === 2">女</p>
          </template>
        </Table>
        <div class="user-footer clearfix mt20">
          <div class="pull-left">
            <p>总共{{ usermete.total ? usermete.total : '0' }}条数据</p>
          </div>
          <div class="pull-right">
            <Page
              :current="usermete.current_page || 1"
              :total="usermete.total || 10"
              :page-size="usermete.per_page || 10"
              @on-change="changeSize"
            />
          </div>
        </div>
      </div>
      <div slot="footer">
        <Button type="default" @click="$emit('offlineclose')">取消</Button>
        <Button
          type="primary"
          :disabled="userList.length === 0"
          @click="success"
        >确定
        </Button
        >
      </div>
    </Modal>
  </div>
</template>

<script>
  import {Modal, Button, Input, Table, Page} from 'iview'
  import http from '@/http'
  import {computPage} from '@/util/common.js'

  export default {
    components: {
      Modal,
      Button,
      Input,
      Table,
      Page
    },
    props: {
      showModal: {
        type: Boolean,
        default: false
      },
      row: {
        type: Object,
        default: null
      }
    },
    data() {
      const columns = [
        {
          type: 'selection',
          width: 60,
          align: 'center'
        },
        {
          title: '用户ID',
          key: 'id'
        },
        {
          title: '昵称',
          key: 'name'
        },
        {
          title: '详情',
          key: 'description'
        },
        {
          title: 'guard',
          key: 'guard_name'
        }
      ]
      return {
        loading: false,
        name: null,
        password: null,
        userid: null,
        page: 1,
        phone: null,
        pid: null, // 父id
        cid: [],
        columns,
        userList: [],
        usermete: {}
      }
    },
    watch: {
      showModal(val) {
        if (val) {
          this.$nextTick(() => {
            this.getTopUser()
          })
        } else {
          this.username = null
          this.userid = null
          this.phone = null
          this.page = 1
        }
      },
      row(val) {
        this.pid = val.id
      }
    },
    methods: {
      computPage,
      // 获取没有上级用户的列表
      getTopUser() {
        let params = {
          name: this.name,
          id: this.userid,
          page: this.page,
          user_id: this.pid,
          phone: this.phone
        }
        this.loading = true
        http.get('/role', {params}).then(res => {
          this.userList = res.data.data
          this.usermete = {"current_page": res.current_page, 'per_page': res.per_page, "total": res.total}
          this.loading = false
        })
      },
      success() {
        if (this.cid.length === 0) {
          this.$Message.error('请选择用户角色')
          return
        }
        http.post('user', {name: this.name, password: this.password, cid: this.cid}).then(res => {
          this.$Message.success('添加成功')
          this.$emit('offlineclose')
          this.cid = []
        })
      },
      changebox(selection) {
        this.cid = []
        selection.forEach(item => {
          this.cid.push(item.id)
        })
      },
      changeSize(page) {
        this.page = page
        this.getTopUser()
      },
      search() {
        this.page = 1
        this.getTopUser()
      }
    }
  }
</script>

<style scoped lang="less">
  .w100 {
    width: 100px;
    margin-right: 10px;
  }
</style>
