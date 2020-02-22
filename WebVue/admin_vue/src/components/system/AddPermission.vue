<template>
  <Modal
    :value="visible"
    :mask-closable="false"
    title="新增权限"
    @on-cancel="$emit('close')"
    width="400"
  >
    <Form :model="formData" ref="addCategoryForm" :label-width="80">
      <FormItem prop="display_name" label="权限名称">
        <Input v-model="formData.display_name"/>
      </FormItem>
      <FormItem prop="name" label="路由地址">
        <Input v-model="formData.name"/>
      </FormItem>
      <FormItem prop="sort" label="排序">
        <Input v-model="formData.sort" placeholder="排序越小越靠前"/>
      </FormItem>
      <FormItem prop="parent_id" label="上级权限">
        <Select v-model="formData.parent_id">
          <Option :value="0">顶级权限</Option>
          <Option v-for="item in permissionList" :key="item.id" :value="item.id">{{item.display_name}}</Option>
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
  import {Modal, Form, FormItem, Select, Option, Input, Button} from 'iview'
  import {mapState} from 'vuex'
  import http from '../../http'

  export default {
    components: {
      Modal,
      Form,
      FormItem,
      Select,
      Option,
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
          display_name: '',
          name: '',
          parent_id: 0,
          sort: 99
        }
      }
    },
    computed: {
      ...mapState({
        permissionList: state => state.system.permissionList,
      })
    },
    watch: {
      visible(val) {
        if (!val) {
          this.formData = {name: '', pid: 0, sort: 99}
        }
      }
    },
    create() {
      this.permissionList = this.getPermissionList()
    },
    methods: {
      submit() {
        if (!this.formData.name) {
          this.$Message.error('请输入权限名称')
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
        this.$Message.loading('添加中')
        http.post('/permission', {...this.formData}).then(
          res => {
            this.$Message.success('添加成功')
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
