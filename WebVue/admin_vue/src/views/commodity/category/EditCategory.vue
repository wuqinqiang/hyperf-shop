<template>
  <Modal
    :value="visible"
    :mask-closable="false"
    title="编辑类目"
    @on-cancel="$emit('close')"
    width="400"
  >
    <Form :model="formData" ref="addCategoryForm" :label-width="80">
      <FormItem prop="name" label="分类姓名">
        <Input v-model="formData.name" />
      </FormItem>
      <FormItem prop="sort" label="排序">
        <Input v-model="formData.sort" placeholder="排序越小越靠前" />
      </FormItem>
      <FormItem prop="pid" label="上级类目" v-if="defaultData && defaultData.pid !== 0">
        <Select v-model="formData.pid">
          <Option v-for="item in categoryList" :key="item.id" :value="item.id">{{item.name}}</Option>
        </Select>
      </FormItem>
    </Form>
    <div slot="footer">
      <Button @click="$emit('close')">取消</Button>
      <Button type="primary" @click="submit">确定</Button>
    </div>
  </Modal>
</template>

<script>
import { Modal, Form, FormItem, Input, Button, Select, Option } from 'iview'
import { mapState } from 'vuex'
import http from '../../../http'

export default {
  components: {
    Modal,
    Form,
    FormItem,
    Input,
    Button,
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
    }
  },
  data() {
    return {
      formData: {
        name: '',
        pid: undefined,
        sort: 99
      }
    }
  },
  computed: {
    ...mapState({
      categoryList: state => state.commodity.categoryList
    })
  },
  watch: {
    defaultData(val) {
      const { name, pid, sort } = val
      this.formData = { name, pid, sort }
    }
  },
  methods: {
    submit() {
      if (!this.formData.name) {
        this.$Message.error('请输入类目名称')
        return
      }

      if (this.formData.sort !== 0 && this.formData.sort !== '0') {
        if (
          !this.formData.sort ||
          !/^[+]{0,1}(\d+)$/.test(this.formData.sort)
        ) {
          this.$Message.error('请输入有效的排序值')
          return
        }
      }

      this.$Message.loading('编辑中')
      http.patch(`/category/${this.defaultData.id}`, { ...this.formData }).then(
        res => {
          this.$Message.success('编辑成功')
          this.$emit('close')
          this.$emit('refresh')
        },
        err => {
          console.log(err)
        }
      )
    }
  }
}
</script>

<style>
</style>
