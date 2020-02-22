<template>
  <Modal
    :value="visible"
    @on-cancel="$emit('close')"
    :mask-closable=" false"
    title="编辑首页类型"
    width="400px"
  >
    <Form
      :model="formData"
      :rules="rules"
      :label-width="100"
      ref="editMenuForm"
      style="padding-right: 20px;"
    >
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
    },
    defaultData: {
      type: Object,
      default: undefined
    }
  },
  data() {
    return {
      formData: {
        name: '',
        detail: '',
        weight: '',
        size: ''
      },
      rules: {
        name: [
          {
            required: true,
            message: '类型名称不能为空'
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
    defaultData(val) {
      const { name, detail, weight, size } = val
      this.formData = {
        name,
        detail,
        weight,
        size
      }
    }
  },
  methods: {
    submit() {
      this.$refs['editMenuForm'].validate(valid => {
        if (valid) {
          this.$Message.loading('编辑中')
          http.patch(`/front_category/${this.defaultData.id}`, this.formData).then(res => {
            this.$Message.success('编辑成功')
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
