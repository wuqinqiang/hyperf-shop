<template>
  <div class="help">
    <div style="margin-bottom: 20px;">
      <Button type="primary" icon="md-add" @click="visibleAdd = true">添加权限</Button>
    </div>
    <Table :data="permissionList" :loading="loading" :columns="columns" border>
      <template slot="action" slot-scope="{row}">
        <Button size="small" type="primary" style="margin-right: 20px;" @click="handleEdit(row)">修改</Button>
        <Button size="small" type="error" @click="handleDelete(row.id)">删除</Button>
      </template>
    </Table>
    <AddHelp :visible="visibleAdd" @close="visibleAdd = false" @refresh="getData"></AddHelp>
    <EditHelp
      :visible="visibleEdit"
      @close="visibleEdit = false"
      :defaultData="editData"
      @refresh="getData"
    ></EditHelp>

<!--    <Page-->
<!--      :current="dataMeta.current_page || 1"-->
<!--      :total="dataMeta.total || 0"-->
<!--      :page-size="dataMeta.per_page || 20"-->
<!--      @on-change="handlePageChange"-->
<!--    />-->
  </div>
</template>

<script>
  import { Button, Table } from 'iview'
  import AddHelp from '@/components/system/AddPermission.vue'
  import EditHelp from '@/components/system/EditPermission.vue'
  import { mapState, mapActions } from 'vuex'
  import http from '../../../http'
  export default {
    components: {
      Button,
      AddHelp,
      Table,
      EditHelp
    },
    data() {
      this.columns = [
        {
          title: '权限名称',
          key: 'display_name'
        },
        {
          title: '父权限',
          key: 'parent_id'
        },
        {
          title: '路由地址',
          key: 'name'
        },
        {
          title: '类型',
          key: 'guard_name'
        },
        {
          title: '排序',
          key: 'sort'
        },
        {
          title: '创建时间',
          key: 'created_at'
        },
        {
          title: '操作',
          slot: 'action'
        }
      ]
      return {
        visibleAdd: false,
        loading: false,
        editData: undefined,
        visibleEdit: false
      }
    },
    computed: {
      ...mapState({
        permissionList: state => state.system.permissionList,
        dataMeta: state => state.system.permissionMeta
      })
    },
    methods: {
      ...mapActions(['getPermissionList']),
      getData() {
        this.loading = true
        this.getPermissionList().finally(() => {
          this.loading = false
        })
      },
      handleEdit(row) {
        this.visibleEdit = true
        this.editData = row
      },
      handleDelete(id) {
        this.$Modal.confirm({
          title: '确定要删除吗？',
          onOk: () => {
            this.$Message.success('删除中')
            http.delete(`/permission/${id}`).then(res => {
              this.$Message.success('删除成功')
              this.getData()
            })
          }
        })
      }
    },
    created() {
      !this.helpList && this.getData()
    }
  }
</script>

<style>
</style>
