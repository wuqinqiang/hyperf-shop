<template>
  <div class="activity mt10">
    <div class="activity-header">
      <div class="insert-active">
        <Button type="primary" icon="md-add" @click="visibleAdd = true">新增首页商品</Button>
      </div>
      <div class="search-bar clearfix">
        <div class="search-item">
          <DatePicker
            @on-change="handleTimeChange"
            type="datetimerange"
            placeholder="创建时间"
            style="width: 300px"
          ></DatePicker>
        </div>
        <div class="search-item">
          <Input v-model="params.name" placeholder="请输入活动名称" />
        </div>
        <div class="search-item">
          <Button type="primary" icon="ios-search" @click="handleSearch">搜索</Button>
        </div>
      </div>
      <div>
        <Tabs :value="params.type" @on-click="handleMenuChange">
          <TabPane
            :label="item.name"
            :name="item.id.toString()"
            v-for="item in menuList"
            :key="item.id"
          ></TabPane>
        </Tabs>
      </div>
    </div>
    <div class="activity-body">
      <Table :data="dataList" :loading="loading" :columns="columns" border>
        <template slot="position" slot-scope="{row}">
          <div v-if="row.position === 1">banner</div>
        </template>
        <template slot="time" slot-scope="{row}">
          <div
            v-if="row.start_at || row.end_at"
          >{{`${row.start_at || '现在'} 至 ${row.end_at || '现在'}`}}</div>
        </template>
        <template slot-scope="{row}" slot="progress">
          <Tag color="success" v-if="row.status === 1">进行中</Tag>
          <Tag color="default" v-if="row.status === 0">已结束</Tag>
        </template>
        <template slot-scope="{row}" slot="product">
          <div class="product-item clearfix">
            <img class="pull-left" :src="row.product ? row.product.front_image : ''" alt />
            <span style="max-width: 260px;" class="pull-left" :title="row.product ? row.product.name : ''">{{row.product ? row.product.name : ''}}</span>
          </div>
        </template>
        <template slot-scope="{row}" slot="type">
          <span>{{returnMenu(row.type)}}</span>
        </template>

        <template slot="image" slot-scope="{row}">
          <img class="cover-image" :src="row.image" alt @click="handlePreview(row.image)" />
        </template>
        <template slot="action" slot-scope="{row}">
          <Button
            size="small"
            type="info"
            @click="handleStatusChange(row)"
            style="margin-right: 20px;"
            v-if="row.status === 1"
          >关闭</Button>
          <Button
            size="small"
            type="info"
            @click="handleStatusChange(row)"
            style="margin-right: 20px;"
            v-if="row.status === 0"
          >开启</Button>
          <Button
            size="small"
            type="primary"
            @click="handleEdit(row)"
            style="margin-right: 20px;"
          >修改</Button>
          <Button size="small" type="error" @click="handleDelete(row.id)">删除</Button>
        </template>
      </Table>
    </div>
    <AddActivity
      :visible="visibleAdd"
      @close="visibleAdd = false"
      @refresh="getData"
      :menuList="menuList"
      :commodityList="commodityList"
    />
    <EditActivity
      :visible="visibleEdit"
      :defaultData="editData"
      :menuList="menuList"
      :commodityList="commodityList"
      @close="visibleEdit = false"
      @refresh="getData"
    />
    <Page
      :current="dataMeta.current_page || 1"
      :total="dataMeta.total || 0"
      :page-size="dataMeta.per_page || 20"
      @on-change="handlePageChange"
    />
    <Modal title="查看封面图片" v-model="visiblePreview" footer-hide>
      <img :src="currentImage" style="width: 100%" />
    </Modal>
  </div>
</template>

<script>
import {
  Button,
  DatePicker,
  Input,
  Table,
  Page,
  Modal,
  Tag,
  Tabs,
  TabPane
} from 'iview'
import { mapState, mapActions } from 'vuex'
import AddActivity from './AddActivity'
import EditActivity from './EditActivity'
import http from '../../../http'
export default {
  components: {
    Button,
    DatePicker,
    Input,
    Table,
    AddActivity,
    EditActivity,
    Page,
    Modal,
    Tag,
    Tabs,
    TabPane
  },
  data() {
    return {
      loading: false,
      visibleAdd: false,
      visibleEdit: false,
      editData: undefined,
      visiblePreview: false,
      currentImage: undefined,
      menuList: undefined,
      commodityList: undefined,
      params: {
        page: 1,
        start_time: undefined,
        end_time: undefined,
        name: undefined,
        type: ''
      },
      columns: [
        {
          title: '排序',
          key: 'weight',
          width: 60
        },
        {
          title: '名称',
          key: 'name',
          width: 140
        },
        {
          title: '时间',
          slot: 'time',
          width: 300
        },
        {
          title: '关联商品',
          slot: 'product'
        },
        {
          title: '封面图',
          slot: 'image',
          width: 100
        },
        {
          title: '状态',
          slot: 'progress',
          width: 100
        },

        {
          title: '操作',
          slot: 'action',
          width: 240
        }
      ]
    }
  },
  computed: {
    ...mapState({
      dataList: state => state.activity.activityList.data,
      dataMeta: state => state.activity.activityList.meta
    })
  },
  watch: {
    menuList(val) {
      this.$forceUpdate()
    }
  },
  methods: {
    ...mapActions(['getActivityList']),
    getData() {
      this.loading = true
      this.getActivityList(this.params).finally(() => {
        this.loading = false
      })
    },
    getMenuList() {
      http.get('/front_category/front_category_list').then(res => {
        this.menuList = res.data
        this.params.type = res.data[0].id.toString()
        this.getData()
      })
    },
    getCommodityList() {
      http.get('/product/product_list').then(res => {
        this.commodityList = res.data
      })
    },
    returnMenu(menuId) {
      if (this.menuList) {
        for (let item of this.menuList) {
          if (item.id === menuId) {
            return item.name
          }
        }
      } else {
        return ''
      }
    },
    handleEdit(row) {
      this.editData = row
      this.visibleEdit = true
    },
    handlePageChange(page) {
      this.params.page = page
      this.getData()
    },
    handleTimeChange(time) {
      if (time && Array.isArray(time)) {
        this.params.start_time = time[0]
        this.params.end_time = time[1]
      } else {
        this.params.start_time = undefined
        this.params.end_time = undefined
      }
    },
    handleSearch() {
      this.params.page = 1
      this.getData()
    },
    handleDelete(id) {
      this.$Modal.confirm({
        title: '确定要删除吗？',
        onOk: () => {
          this.$Message.loading('删除中')
          http.delete(`/front_activity/${id}`).then(
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
    },
    handlePreview(image) {
      if (image) {
        this.visiblePreview = true
        this.currentImage = image
      }
    },
    handleStatusChange(row) {
      let newStatus = 1
      if (row.status === 1) {
        newStatus = 0
      }
      this.$Message.loading('操作处理中')
      http
        .patch(`/front_activity/${row.id}`, {
          status: newStatus
        })
        .then(
          res => {
            this.$Message.success('操作成功')
            this.getData()
          },
          err => {
            console.log(err)
          }
        )
    },
    handleMenuChange(name) {
      this.params.type = name
      this.getData()
    }
  },
  created() {
    // !this.dataList && this.getData()
    this.getMenuList()
    this.getCommodityList()
  }
}
</script>

<style lang="less">
@import '~@/styles/activity.less';
.cover-image {
  width: 40px;
  height: auto;
  cursor: pointer;
}
.ivu-table {
  td,
  th {
    text-align: center;
  }
}
.activity-body {
  margin-bottom: 20px;
}
</style>
