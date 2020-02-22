<template>
  <div class="commodity mt10">
    <div class="commodity-header mb20">
      <div class="insert-btn">
        <Button type="primary" icon="md-add" @click="$router.push('/commodity/create')">新增商品</Button>
      </div>
    </div>
    <div class="commodity-body">
      <!-- tab栏 -->
      <Tabs value="onsale" :animated="false" @on-click="handleTypeChange">
        <TabPane label="在线商品" name="onsale"></TabPane>
        <TabPane label="库存商品" name="inventory"></TabPane>
      </Tabs>
      <!-- 搜索栏 -->
      <div class="clearfix">
        <div class="pull-left mr20 mb20 mt10">
          <DatePicker
            v-model="query.created_at"
            type="date"
            placeholder="创建时间"
            style="width: 180px"
          ></DatePicker>
        </div>
        <div class="pull-left mr20 mb20 mt10">
          <Select style="width: 180px" placeholder="请选择商品分类" v-model="parentCategoryId">
            <Option value>全部</Option>
            <Option
              v-for="item in flatCategoryList"
              :key="item.id"
              :value="item.id"
              :label="item.name"
            >
              <span>{{item.name}}</span>
            </Option>
          </Select>
        </div>
        <div
          class="pull-left mr20 mb20 mt10"
          v-if="childCategoryList && childCategoryList.length > 0"
        >
          <Select style="width: 180px" placeholder="商品二级分类" v-model="childCategoryId">
            <Option value>全部</Option>
            <Option
              v-for="item in childCategoryList"
              :key="item.id"
              :value="item.id"
              :label="item.name"
            >
              <span>{{item.name}}</span>
            </Option>
          </Select>
        </div>
        <div class="pull-left mr20 mb20 mt10">
          <Input v-model="query.name" placeholder="商品名称" style="width: 180px" />
        </div>
        <div class="pull-left mr20 mb20 mt10">
          <Select style="width: 180px" placeholder="销量" v-model="query.sold_sort">
            <Option value>默认排序</Option>
            <Option :value="1">销量从高到低</Option>
            <Option :value="2">销量从低到高</Option>
          </Select>
        </div>
        <div class="pull-left mr20 mb20 mt10">
          <Button type="primary" icon="ios-search" @click="handleSearch">搜索</Button>
        </div>
      </div>
      <!-- 表格 -->
      <div class="mt10">
        <Table :columns="columns" :data="dataList" :loading="loading" border>
          <!-- <template slot="category_id" slot-scope="{row}">{{getCategoryName(row.category_id)}}</template> -->
          <template slot-scope="{row}" slot="status">
            <Tag v-if="row.on_sale === 0" color="default">未上架</Tag>
            <Tag v-if="row.on_sale === 1" color="success">上架</Tag>
            <Tag v-if="row.on_sale === 2" color="primary">定时上架</Tag>
          </template>
          <template slot="operation" slot-scope="{row}">
            <Button
              type="info"
              size="small"
              class="mr10"
              v-if="query.type === 0"
              @click="handleShelves(row.id)"
            >上架</Button>
            <Button
              type="error"
              size="small"
              class="mr10"
              v-if="query.type === 1"
              @click="handleDownShelves(row.id)"
            >下架</Button>
            <!-- <Button type="info" size="small" class="mr10" >详情</Button> -->
            <Button
              type="primary"
              size="small"
              class="mr10"
              @click="$router.push(`/commodity/edit/${row.id}`)"
            >修改</Button>
            <Button
              type="error"
              size="small"
              class="mr10"
              v-if="query.type === 0"
              @click="handleDelete(row.id)"
            >删除</Button>
            <Button
              size="small"
              class="mr10"
              @click="$router.push(`/commodity/detail/${row.id}`)"
            >详情</Button>
          </template>
        </Table>
      </div>
    </div>
    <div class="commodity-footer mt10 clearfix">
      <div class="pull-right">
        <Page
          :current="dataMeta.current_page || 1"
          :total="dataMeta.total || 0"
          :page-size="dataMeta.per_page || 1"
          @on-change="handlePageChange"
        />
      </div>
    </div>
  </div>
</template>

<script>
import {
  Button,
  Tabs,
  TabPane,
  DatePicker,
  Input,
  Select,
  Option,
  Table,
  Page,
  Tag
} from 'iview'
import { mapState, mapActions } from 'vuex'
import http from '@/http'
export default {
  components: {
    Button,
    Tabs,
    TabPane,
    DatePicker,
    Input,
    Select,
    Option,
    Table,
    Page,
    Tag
  },
  data() {
    return {
      loading: false,
      query: {
        sold_sort: '',
        name: undefined,
        category_id: '',
        created_at: '',
        page: 1,
        type: 1
      },
      parentCategoryId: '',
      childCategoryId: '',
      columns: [
        {
          title: '商品ID',
          key: 'id',
          width: 80
        },
        {
          title: '商品名称',
          key: 'name',
          width: 300
        },
        {
          title: '分类',
          key: 'category_name'
        },
        {
          title: '单价（元）',
          key: 'price'
        },
        {
          title: '销量',
          key: 'sold_count'
        },
        {
          title: '当前状态',
          slot: 'status'
        },
        {
          title: '当前排序',
          key: 'sort'
        },
        {
          title: '创建时间',
          key: 'created_at',
          width: 160
        },
        {
          title: '操作',
          slot: 'operation',
          width: 240
        }
      ]
    }
  },
  watch: {
    parentCategoryId(id) {
      this.query.category_id = id
      this.childCategoryId = ''
    },
    childCategoryId(id) {
      if (id) {
        this.query.category_id = id
      } else {
        this.query.category_id = this.parentCategoryId
      }
    }
  },
  computed: {
    ...mapState({
      dataList: state => state.commodity.productList.data,
      dataMeta: state => state.commodity.productList.meta,
      categoryList: state => state.commodity.categoryList
    }),
    flatCategoryList() {
      const result = []
      if (this.categoryList) {
        this.categoryList.forEach(item => {
          result.push({
            id: item.id,
            name: item.name,
            pid: 0
          })
        })
      }
      return result
    },
    childCategoryList() {
      let result
      if (this.categoryList) {
        this.categoryList.some(item => {
          if (item.id === this.parentCategoryId) {
            result = item.children
          }
        })
      }
      return result
    }
  },
  methods: {
    ...mapActions(['getProductList', 'getCategoryList']),
    handlePageChange(page) {
      this.query.page = page
      this.getData()
    },
    getData() {
      this.loading = true
      this.getProductList(this.query).finally(() => {
        this.loading = false
      })
    },
    handleTypeChange(type) {
      if (type === 'onsale') {
        this.query.type = 1
      } else {
        this.query.type = 2
      }
      this.query.page = 1
      this.getData()
    },
    handleShelves(id) {
      this.$Message.loading('操作处理中')
      http.post(`/product/sale/${id}`).then(
        res => {
          this.$Message.success('操作成功')
          this.getData()
        },
        err => {
          console.log(err)
        }
      )
    },
    handleDownShelves(id) {
      this.$Modal.confirm({
        title: '确定要下架吗？',
        onOk: () => {
          this.handleShelves(id)
        }
      })
    },
    handleSearch() {
      this.query.page = 1
      this.getData()
    },
    handleDelete(id) {
      this.$Modal.confirm({
        title: '确定要删除吗？',
        onOk: () => {
          this.$Message.loading('删除中')
          http.delete(`/product/${id}`).then(res => {
            this.$Message.success('删除成功')
            this.getData()
          })
        }
      })
    }
    // getCategoryName(id) {
    //   let result = ''
    //   if (this.categoryList) {
    //     for (let item of this.categoryList) {
    // 			if (item.children && item.children.length > 0) {
    //         debugger
    // 				for (let subItem of item.children) {
    // 					if (subItem.id === id) {
    // 						result = item.name
    // 						break
    // 					}
    // 				}
    // 			}
    // 		}
    //   }
    //   return result
    // }
  },
  created() {
    this.getData()
    !this.categoryList && this.getCategoryList()
  }
}
</script>

<style scoped lang="less">
@import '~@/styles/commodity.less';
</style>
