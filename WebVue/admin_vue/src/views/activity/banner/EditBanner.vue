<template>
  <Modal
    :value="visible"
    :mask-closable="false"
    title="编辑轮播图"
    @on-cancel="$emit('close')"
    width="400"
  >
    <Form :model="formData" :rules="rules" ref="editBannerForm" :label-width="80">
      <FormItem prop="image" label="图片">
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
        >
          <div class="uploader" style="width: 58px;height:58px;line-height: 58px;">
            <img v-if="formData.image" :src="formData.image" alt />
            <Icon v-else type="ios-camera" size="20"></Icon>
          </div>
        </Upload>
        <p>图片尺寸建议：750*300</p>
      </FormItem>
      <FormItem prop="url" label="跳转URL或商品ID">
        <Input v-model="formData.url" placeholder="跳转的链接或商品ID" />
      </FormItem>
      <FormItem prop="weight" label="排序">
        <Input v-model="formData.weight" placeholder="轮播图的排序值，排序越小越靠前" />
      </FormItem>
      <FormItem prop="status" label="活动状态">
        <i-switch v-model="formData.status" :true-value="1" :false-value="2">
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
  Switch,
  Button,
  Upload,
  Icon
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
    'i-switch': Switch,
    Button,
    Upload,
    Icon
  },
  props: {
    visible: {
      type: Boolean,
      default: false
    },
    defaultData: {
      type: Object,
      default: undefined
    }
  },
  data() {
    return {
      formData: {
        image: '',
        weight: '',
        url: '',
        status: ''
      },
      rules: {
        image: [
          {
            required: true,
            message: '图片不能为空'
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
    defaultData(val) {
      const { image, url, weight, status } = val
      this.formData = {
        image,
        url,
        weight,
        status
      }
    }
  },
  methods: {
    submit() {
      this.$refs['editBannerForm'].validate(valid => {
        if (valid) {
          this.$Message.loading('修改中')
          http
            .patch(`/front_image/${this.defaultData.id}`, { ...this.formData })
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
