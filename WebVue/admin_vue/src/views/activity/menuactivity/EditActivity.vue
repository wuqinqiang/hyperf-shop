<!--
 * @Author: your name
 * @Date: 2019-08-14 18:18:55
 * @LastEditTime: 2019-10-29 15:23:46
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \tcy-system-manage\src\views\activity\menuactivity\EditActivity.vue
 -->
<template>
  <Modal
    :value="visible"
    :mask-closable="false"
    title="修改首页商品"
    @on-cancel="$emit('close')"
    width="400"
  >
    <Form :model="formData" :rules="rules" ref="addActivityForm" :label-width="80">
      <FormItem prop="name" label="名称">
        <Input v-model="formData.name" />
      </FormItem>
      <FormItem prop="type" label="类型">
        <Select v-model="formData.type" placeholder="类型">
          <Option :value="item.id" :label="item.name" v-for="item in menuList" :key="item.id"></Option>
        </Select>
      </FormItem>
      <FormItem prop="start_at" label="时间">
        <DatePicker
          :value="defaultTimeRange"
          type="datetimerange"
          style="width: 288px"
          format="yyyy-MM-dd HH:mm:ss"
          @on-change="handleTimeChange"
        ></DatePicker>
      </FormItem>
      <FormItem prop="product_id" label="关联商品">
        <Select v-model="formData.product_id">
          <Option v-for="item in commodityList" :key="item.id" :value="item.id" :label="item.name">
            <div class="product-item clearfix">
              <img class="pull-left" :src="item.front_image" alt />
              <span class="pull-left" :title="item.name">{{item.name}}</span>
            </div>
          </Option>
        </Select>
      </FormItem>
      <FormItem prop="image" label="封面图">
        <div
          class="uploader"
          style="width: 58px;height:58px;line-height: 58px;"
          v-if="formData.image"
        >
          <img :src="formData.image" alt />
          <div class="delete-mask" v-if="formData.image" @click="formData.image = ''">
            <Icon type="ios-trash" size="20"></Icon>
          </div>
        </div>
        <Upload
          ref="upload"
          :show-upload-list="false"
          :on-success="handleSuccess"
          accept="image/*"
          :max-size="1024"
          :on-exceeded-size="handleMaxSize"
          :action="`${server}/upload_file/image`"
          name="file"
          :headers="headers"
          style="display: inline-block;width:58px;"
          v-else
        >
          <div class="uploader" style="width: 58px;height:58px;line-height: 58px;">
            <Icon type="ios-camera" size="20"></Icon>
          </div>
        </Upload>
        <p>封面图只能上传1张，图片尺寸建议: {{returnSize()}}</p>
      </FormItem>
      <FormItem prop="sort" label="排序">
        <Input v-model="formData.sort" placeholder="首页商品的排序值，排序越小越靠前" />
      </FormItem>

      <FormItem prop="status" label="状态">
        <i-switch v-model="formData.status" :true-value="1" :false-value="0">
          <span slot="open">开</span>
          <span slot="close">关</span>
        </i-switch>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button @click="$emit('close')">取消</Button>
      <Button type="primary" @click="submit">确定</Button>
    </div>
  </Modal>
</template>

<script>
import {
  Modal,
  Form,
  FormItem,
  Input,
  DatePicker,
  Button,
  Upload,
  Icon,
  Switch,
  Select,
  Option
} from 'iview'

import http from '../../../http'
import { server } from '../../../config/server'
import { getCookie } from '../../../util/cookie'

export default {
  components: {
    Modal,
    Form,
    FormItem,
    Input,
    DatePicker,
    Button,
    Upload,
    Icon,
    'i-switch': Switch,
    Select,
    Option
  },
  props: {
    visible: {
      type: Boolean,
      default: false
    },
    defaultData: {
      type: Object,
      default: undefined
    },
    menuList: {
      type: Array,
      default: undefined
    },
    commodityList: {
      type: Array,
      default: undefined
    }
  },
  data() {
    return {
      formData: {
        name: '',
        start_at: '',
        end_at: '',
        image: '',
        sort: '',
        product_id: '',
        status: 1,
        type: undefined
      },
      rules: {
        name: [
          {
            required: true,
            message: '名称不能为空'
          }
        ],
        type: [
          {
            required: true,
            message: '类型不能为空'
          }
        ],
        sort: [
          {
            required: true,
            message: '排序不能为空'
          }
        ],
        // image: [
        //   {
        //     required: true,
        //     message: '封面不能为空'
        //   }
        // ],
        product_id: [
          {
            required: true,
            message: '关联商品不能为空'
          }
        ]
      }
    }
  },
  computed: {
    server() {
      return server
    },
    headers() {
      return {
        Authorization: 'Bearer ' + getCookie('access_token')
      }
    },
    defaultTimeRange() {
      return [this.formData.start_at, this.formData.end_at]
    }
  },
  watch: {
    defaultData(val) {
      if (val) {
        const { name, image, sort, status } = val

        this.formData = {
          name,
          start_at: val.start_at,
          end_at: val.end_at,
          image,
          sort,
          product_id: val.product_id,
          status,
          type: val.type
        }
      }
    }
  },
  methods: {
    handleTimeChange(timeRange, date) {
      if (timeRange && Array.isArray(timeRange)) {
        this.formData.start_at = timeRange[0]
        this.formData.end_at = timeRange[1]
      } else {
        this.formData.start_at = undefined
        this.formData.end_at = undefined
      }
    },
    submit() {
      this.$refs['addActivityForm'].validate(valid => {
        if (valid) {
          this.$Message.loading('修改中')
          http
            .patch(`/front_activity/${this.defaultData.id}`, { ...this.formData })
            .then(
              res => {
                this.$Message.success('修改成功')
                this.$emit('refresh')
                this.$emit('close')
              },
              err => {
                console.log(err)
              }
            )
        }
      })
    },
    handleSuccess(res) {
      this.formData.image = res.path
    },
    handleMaxSize() {
      this.$Message.error('所选图片尺寸过大')
    },
    returnSize() {
      if (this.formData.type && this.menuList) {
        for (let item of this.menuList) {
          if (item.id === this.formData.type) {
            return item.size || '暂无'
          }
        }
      } else {
        return '暂无'
      }
    }
  }
}
</script>

<style lang="less">
.uploader {
  height: 60px;
  width: 60px;
  line-height: 60px;
  text-align: center;
  border-radius: 5px;
  border: 1px dashed #e5e5e5;
  cursor: pointer;
  position: relative;
  &:hover {
    border-color: #c5c5c5;
    .delete-mask {
      display: block;
    }
  }
  img {
    width: 54px;
    height: 54px;
    margin: 1px;
    border-radius: 5px;
  }
  .delete-mask {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.2);
    line-height: 60px;
    text-align: center;
    color: #eee;
    display: none;
  }
}
</style>
