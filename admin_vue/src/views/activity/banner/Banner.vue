<template>
  <div class="banner">
    <div class="clearfix">
      <div class="pull-left">
        <Button type="primary" @click="visibleAdd = true">添加轮播图</Button>
      </div>
    </div>
    <div class="banner-list">
      <Table :data="bannerList" :loading="loading" :columns="columns" border>
        <template slot="image" slot-scope="{row}">
          <img
            class="banner-image"
            :src="row.image"
            alt
            @click="handlePreview(row.image)"
            style="cursor: pointer;"
          />
        </template>
        <template slot="status" slot-scope="{row}">
          <Tag v-if="row.status === 1" color="success">开启</Tag>
          <Tag v-if="row.status === 2" color="default">关闭</Tag>
        </template>
        <template slot="action" slot-scope="{row}">
          <Button size="small" type="info" style="margin-right: 20px;" @click="handleEdit(row)">编辑</Button>
          <Button
            size="small"
            type="error"
            style="margin-right: 20px;"
            @click="handleDelete(row.id)"
          >删除</Button>
        </template>
      </Table>
    </div>
    <AddBanner :visible="visibleAdd" @close="visibleAdd = false" @refresh="getData"></AddBanner>
    <EditBanner
      :visible="visibleEdit"
      :defaultData="editData"
      @close="visibleEdit = false"
      @refresh="getData"
    ></EditBanner>
    <Modal title="查看封面图片" v-model="visiblePreview" footer-hide>
      <img :src="currentImage" style="width: 100%" />
    </Modal>
  </div>
</template>

<script>
import { Button, Table, Modal, Tag } from 'iview'
import { mapState, mapActions } from 'vuex'
import AddBanner from './AddBanner'
import EditBanner from './EditBanner'
import http from '@/http'
export default {
  components: {
    Button,
    Table,
    AddBanner,
    EditBanner,
    Modal,
    Tag
  },
  data() {
    return {
      visibleAdd: false,
      visibleEdit: false,
      editData: undefined,
      visiblePreview: false,
      currentImage: undefined,
      columns: [
        {
          title: 'ID',
          key: 'id'
        },
        {
          title: '排序',
          key: 'weight'
        },
        {
          title: '图片',
          slot: 'image'
        },
        {
          title: '跳转URL或商品ID',
          key: 'url'
        },
        {
          title: '创建时间',
          key: 'created_at'
        },
        {
          title: '状态',
          slot: 'status'
        },
        {
          title: '操作',
          slot: 'action'
        }
      ],
      loading: false
    }
  },
  computed: {
    ...mapState({
      bannerList: state => state.activity.bannerList
    })
  },
  methods: {
    ...mapActions(['getBannerList']),
    getData() {
      this.loading = true
      this.getBannerList().finally(() => {
        this.loading = false
      })
    },
    handlePreview(url) {
      this.currentImage = url
      this.visiblePreview = true
    },
    handleEdit(row) {
      // debugger
      this.visibleEdit = true
      this.editData = row
    },
    handleDelete(id) {
      this.$Modal.confirm({
        title: '确定要删除吗？',
        onOk: () => {
          this.$Message.loading('删除中')
          http.delete(`/front_image/${id}`).then(
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
    !this.bannerList && this.getData()
  }
}
</script>

<style>
</style>
