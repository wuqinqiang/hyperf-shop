<template>
  <div class="menu">
    <div class="view-header">
      <Button type="primary" icon="md-add" @click="visibleAdd = true">添加首页类型</Button>
    </div>
    <div class="search-bar"></div>
    <div class="view-body">
      <Table :data="data" :columns="columns" border>
        <template slot-scope="{row}" slot="action">
          <Button size="small" type="primary" style="margin-right: 10px;" @click="handleEdit(row)">编辑</Button>
          <Button size="small" type="error" @click="handleDelete(row.id)">删除</Button>
        </template>
      </Table>
    </div>
    <Page
      :current="meta.current_page || 1"
      :total="meta.total || 0"
      :page-size="meta.per_page || 20"
      @on-change="handlePageChange"
    />
    <AddMenu :visible="visibleAdd" @close="visibleAdd = false" @refresh="getData"></AddMenu>
    <EditMenu :visible="visibleEdit" @close="visibleEdit = false" @refresh="getData" :defaultData="editData"></EditMenu>
  </div>
</template>

<script>
import { Button, Table, Page } from 'iview'
import AddMenu from './AddMenu'
import EditMenu from './EditMenu'
import http from '../../../http'
export default {
  components: {
    Button,
    Table,
    Page,
    AddMenu,
    EditMenu
  },
  data() {
    return {
      visibleAdd: false,
      visibleEdit: false,
      editData: undefined,
      loading: false,
      data: undefined,
      meta: {},
      params: {
        name: '',
        detail: '',
        page: 1
      },
      columns: [
        {
          title: 'ID',
          key: 'id'
        },
        {
          title: '名称',
          key: 'name'
        },
        {
          title: '描述',
          key: 'detail'
        },
        {
          title: '创建时间',
          key: 'created_at'
        },
        {
          title: '排序',
          key: 'weight'
        },
        {
          title: '操作',
          slot: 'action',
          width: 140
        }
      ]
    }
  },
  methods: {
    getData() {
      this.loading = true
      http
        .get('/front_category', { params: this.params })
        .then(res => {
          this.data = res.data.data
          this.meta = {'current_page':res.data.current_page,'per_page':res.data.per_page,'total':res.data.total}
        })
        .finally(() => {
          this.loading = false
        })
    },
    handlePageChange(page) {
      this.params.page = page
      this.getData()
    },
    handleSearch() {
      this.params.page = 1
      this.getData()
    },
    handleEdit(row) {
      this.editData = row
      this.visibleEdit = true
    },
    handleDelete(id) {
      this.$Modal.confirm({
        title: '确定要删除吗？',
        onOk: () => {
          this.$Message.loading('删除中')
          http.delete(`/front_category/${id}`).then(
            res => {
              this.$Message.success('删除成功')
              this.getData()
            },
            err => {
              console.log(err)
            }
          )
        }
      })
    }
  },
  created() {
    this.getData()
  }
}
</script>

<style>
</style>
