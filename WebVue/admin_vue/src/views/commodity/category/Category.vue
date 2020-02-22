<template>
  <div class="commodity mt10">
    <div class="commodity-header mb20">
      <div class="insert-btn">
        <Button type="primary" icon="md-add" @click="visibleAdd = true">新增类目</Button>
      </div>

      <div class="category-tree">
        <div v-for="item in categoryList" :key="item.id" class="item-group">
          <div class="primary-item">
            <span class="primary-item-name" style="margin-right: 40px;">{{item.name}}</span>
            <div class="action-area">
              <Button
                size="small"
                type="primary"
                style="margin-right: 20px;"
                @click="handleEdit(item)"
              >编辑</Button>
              <Button
                size="small"
                type="error"
                v-if="!item.children || item.children.length === 0"
                @click="handleDelete(item.id)"
              >删除</Button>
            </div>
          </div>
          <ul class="item-children">
            <li v-for="subItem in item.children" :key="subItem.id">
              <div class="secondary-item">
                <span class="secondary-item-name" style="margin-right: 40px;">{{subItem.name}}</span>
                <div class="action-area">
                  <Button
                    size="small"
                    type="primary"
                    style="margin-right: 20px;"
                    @click="handleEdit(subItem)"
                  >编辑</Button>
                  <Button size="small" type="error" @click="handleDelete(subItem.id)">删除</Button>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <AddCategory :visible="visibleAdd" @close="visibleAdd = false" @refresh="getCategoryList" />
    <EditCategory
      :visible="visibleEdit"
      @close="visibleEdit = false"
      :defaultData="editData"
      @refresh="getCategoryList"
    />
  </div>
</template>

<script>
import { Button } from 'iview'

import { mapState, mapActions } from 'vuex'

import AddCategory from './AddCategory'
import EditCategory from './EditCategory'
import http from '@/http'

export default {
  components: {
    Button,
    AddCategory,
    EditCategory
  },
  data() {
    return {
      visibleAdd: false,
      visibleEdit: false,
      editData: undefined
    }
  },
  computed: {
    ...mapState({
      categoryList: state => state.commodity.categoryList
    })
  },
  created() {
    !this.categoryList && this.getCategoryList()
  },
  methods: {
    ...mapActions(['getCategoryList']),
    handleEdit(data) {
      this.editData = data
      this.visibleEdit = true
    },
    handleDelete(id) {
      this.$Modal.confirm({
        title: '确认要删除吗？',
        onOk: () => {
          this.$Message.loading('删除中')
          http.delete(`/category/${id}`).then(
            res => {
              this.$Message.success('删除成功')
              this.getCategoryList()
            },
            err => {
              console.log(err)
            }
          )
        }
      })
    }
  }
}
</script>

<style lang="less">
.category-tree {
  padding: 20px;
  line-height: 30px;
  ul {
    padding-left: 20px;
    list-style: none;
  }
  .item-group {
    border-bottom: 1px solid #e5e5e5;
    padding: 20px 0;
  }
  .primary-item-name {
    font-size: 18px;
    line-height: 30px;
    border-left: 2px solid #60aaf4;
    padding-left: 10px;
    // font-weight: 700;
  }
  .item-children {
    padding-left: 20px;
  }
  .secondary-item-name {
    font-size: 14px;
  }
  .action-area {
    display: none;
  }
  .primary-item {
    display: flex;
    padding: 10px 0;
    &:hover {
      background-color: #f5f5f5;
      .action-area {
        display: block;
      }
    }
  }
  .secondary-item {
    display: flex;
    padding: 10px;
    &:hover {
      background-color: #f5f5f5;
      .action-area {
        display: block;
      }
    }
  }
}
</style>
