<template>
  <Modal
    :value="visible"
    @on-cancel="$emit('close')"
    :mask-closable=" false"
    title="添加首页类型"
    width="400px"
  >
    <Form :model="formData" :rules="rules" :label-width="100" ref="addMenuForm" style="padding-right: 20px;">
      <FormItem prop="name" label="名称">
        <Input v-model="formData.name" />
      </FormItem>
      <FormItem prop="detail" label="描述">
        <Input v-model="formData.detail" />
      </FormItem>
      <FormItem prop="weight" label="排序">
        <Input v-model="formData.weight" placeholder="请输入一个正整数，排序越小越靠前" />
      </FormItem>

    </Form>
    <div slot="footer">
      <Button @click="$emit('close')">取消</Button>
      <Button type="primary" @click="submit">确定</Button>
    </div>
  </Modal>
</template>

<script>
import { Modal, Form, FormItem, Input, Button } from 'iview'
import http from '@/http'
export default {
  components: {
    Modal,
    Form,
    FormItem,
    Input,
    Button
  },
  props: {
    visible: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      formData: {
        name: '',
        detail: '',
        weight: '',
      },
      rules: {
        name: [
          {
            required: true,
            message: '首页类型名称不能为空'
          }
        ],
        detail: [
          {
            required: true,
            message: '描述不能为空'
          }
        ],
        weight: [
          {
            required: true,
            message: '排序不能为空'
          }
        ]
      }
    }
  },
  watch: {
    visible(val) {
      if (val) {
        this.$refs['addMenuForm'].resetFields()
      }
    }
  },
  methods: {
    submit() {
      this.$refs['addMenuForm'].validate(valid => {
        if (valid) {
          this.$Message.loading('添加中')
          http.post('/front_category', this.formData).then(res => {
            this.$Message.success('添加成功')
            this.$emit('close')
            this.$emit('refresh')
          })
        }
      })
    }
  }
}
</script>

<style>
</style>
