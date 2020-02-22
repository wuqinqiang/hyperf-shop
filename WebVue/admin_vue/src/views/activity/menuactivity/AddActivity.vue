<template>
  <Modal
    :value="visible"
    :mask-closable="false"
    title="新增首页商品"
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
          type="datetimerange"
          format="yyyy-MM-dd HH:mm:ss"
          style="width: 288px"
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
      <FormItem prop="weight" label="排序">
        <Input v-model="formData.weight" placeholder="首页商品的排序值，排序越小越靠前" />
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
    Select,
    Option
  },
  props: {
    visible: {
      type: Boolean,
      default: false
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
        start_at: undefined,
        end_at: undefined,
        image: '',
        weight: '',
        product_id: '',
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
        weight: [
          {
            required: true,
            message: '排序不能为空'
          }
        ],
        // image: [
        // 	{
        // 		required: true,
        // 		message: '封面不能为空'
        // 	}
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
    }
  },
  watch: {
    visible(val) {
      if (!val) {
        this.$refs['addActivityForm'].resetFields()
      }
    }
  },
  methods: {
    handleTimeChange(timeRange) {
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
          this.$Message.loading('添加中')
          http.post('/front_activity', { ...this.formData }).then(
            res => {
              this.$Message.success('添加成功')
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
  &:hover {
    border-color: #c5c5c5;
  }
  img {
    width: 54px;
    height: 54px;
    margin: 1px;
    border-radius: 5px;
  }
}
</style>
